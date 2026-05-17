<?php

namespace App\Framework\Logging;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\LogRecord;

final class ScopedMonologHandler extends AbstractProcessingHandler
{
	/** @var array<string, string> */
	private static array $addonPaths = [];

	/** @var array<string, StreamHandler> */
	private array $delegates = [];

	private readonly string $corePath;

	public function __construct(
		private readonly string $logPath,
		int|string|Level $level = Level::Debug,
		bool $bubble = true,
	) {
		parent::__construct($level, $bubble);

		$this->corePath = rtrim(
			wp_normalize_path(dirname(__DIR__, 3)),
			'/'
		);
	}

	public static function registerAddonPath(string $absolutePath, ?string $slug = null): void
	{
		$key = rtrim(wp_normalize_path($absolutePath), '/');
		self::$addonPaths[$key] = $slug ?? basename($key);
	}

	public static function registeredPaths(): array
	{
		return array_keys(self::$addonPaths);
	}

	public static function slugForFile(string $file): ?string
	{
		$normalized = rtrim(wp_normalize_path($file), '/');

		foreach (self::$addonPaths as $addonPath => $slug) {
			if (str_starts_with($normalized, $addonPath)) {
				return $slug;
			}
		}

		return null;
	}

	protected function write(LogRecord $record): void
	{
		if (! $this->originatesFromScope($record)) {
			return;
		}

		$targetPath = $this->resolveLogPath($record);

		$this->delegateFor($targetPath)->handle($record);
	}

	private function resolveLogPath(LogRecord $record): string
	{
		$exception = $record->context['exception'] ?? null;

		if ($exception instanceof \Throwable) {
			$slug = AddonLogResolver::resolveSlug($exception);

			return AddonLogResolver::logPathForSlug($slug);
		}

		$slug = $this->resolveSlugFromCallstack();

		return AddonLogResolver::logPathForSlug($slug);
	}

	private function delegateFor(string $path): StreamHandler
	{
		if (! isset($this->delegates[$path])) {
			$handler = new StreamHandler($path, $this->getLevel(), $this->getBubble());
			$handler->setFormatter($this->getFormatter());
			$this->delegates[$path] = $handler;
		}

		return $this->delegates[$path];
	}

	private function originatesFromScope(LogRecord $record): bool
	{
		$exception = $record->context['exception'] ?? null;

		if ($exception instanceof \Throwable) {
			return $this->throwableIsInScope($exception);
		}

		return $this->callstackIsInScope();
	}

	private const INFRASTRUCTURE_CLASSES = [
		'Roots\Acorn\Bootstrap\HandleExceptions',
		'Illuminate\Foundation\Bootstrap\HandleExceptions',
	];

	private const INFRASTRUCTURE_FUNCTIONS = [
		'trigger_error',
		'wp_trigger_error',
		'_doing_it_wrong',
		'_load_textdomain_just_in_time',
	];

	private function throwableIsInScope(\Throwable $exception): bool
	{
		$originFile = $exception->getFile();

		if ($originFile !== '' && ! $this->isInfrastructureFile($originFile) && $this->fileIsInScope($originFile)) {
			return true;
		}

		foreach ($exception->getTrace() as $frame) {
			if ($this->isInfrastructureFrame($frame)) {
				continue;
			}

			$file = $frame['file'] ?? '';

			if (is_string($file) && $file !== '' && $this->fileIsInScope($file)) {
				return true;
			}
		}

		return false;
	}

	private function isInfrastructureFrame(array $frame): bool
	{
		$class = $frame['class'] ?? '';
		if ($class !== '' && in_array($class, self::INFRASTRUCTURE_CLASSES, true)) {
			return true;
		}

		$function = $frame['function'] ?? '';
		if ($function !== '' && in_array($function, self::INFRASTRUCTURE_FUNCTIONS, true)) {
			return true;
		}

		return false;
	}

	private function isInfrastructureFile(string $file): bool
	{
		$normalized = wp_normalize_path($file);

		if (preg_match('#/wp-includes/#', $normalized) || preg_match('#/wp-admin/#', $normalized)) {
			return true;
		}

		if (str_contains($normalized, 'Bootstrap/HandleExceptions.php')) {
			return true;
		}

		return false;
	}

	private function fileIsInScope(string $file): bool
	{
		$normalized = rtrim(wp_normalize_path($file), '/');

		if (str_starts_with($normalized, $this->corePath)) {
			return true;
		}

		foreach (array_keys(self::$addonPaths) as $addonPath) {
			if (str_starts_with($normalized, $addonPath)) {
				return true;
			}
		}

		return false;
	}

	private function callstackIsInScope(): bool
	{
		$frames = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 25);

		foreach ($frames as $frame) {
			if (! isset($frame['file'])) {
				continue;
			}

			if ($this->fileIsInScope($frame['file'])) {
				return true;
			}
		}

		return false;
	}

	private function resolveSlugFromCallstack(): ?string
	{
		$frames = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 25);

		foreach ($frames as $frame) {
			$file = $frame['file'] ?? '';

			if (! is_string($file) || $file === '') {
				continue;
			}

			$slug = self::slugForFile($file);

			if ($slug !== null) {
				return $slug;
			}
		}

		return AddonLogResolver::CORE_SLUG;
	}
}
