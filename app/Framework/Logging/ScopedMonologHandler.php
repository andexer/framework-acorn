<?php

namespace App\Framework\Logging;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\LogRecord;

final class ScopedMonologHandler extends AbstractProcessingHandler
{
	private static array $addonPaths = [];

	private readonly string $corePath;

	private ?StreamHandler $delegate = null;

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

	public static function registerAddonPath(string $absolutePath): void
	{
		$key = rtrim(wp_normalize_path($absolutePath), '/');
		self::$addonPaths[$key] = true;
	}

	public static function registeredPaths(): array
	{
		return array_keys(self::$addonPaths);
	}

	protected function write(LogRecord $record): void
	{
		if (! $this->originatesFromScope($record)) {
			return;
		}

		if ($this->delegate === null) {
			$this->delegate = new StreamHandler($this->logPath, $this->getLevel(), $this->getBubble());
			$this->delegate->setFormatter($this->getFormatter());
		}

		$this->delegate->handle($record);
	}

	private function originatesFromScope(LogRecord $record): bool
	{
		$exception = $record->context['exception'] ?? null;

		if ($exception instanceof \Throwable) {
			return $this->fileIsInScope($exception->getFile());
		}

		return $this->callstackIsInScope();
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

			$normalized = rtrim(wp_normalize_path($frame['file']), '/');

			if (str_starts_with($normalized, $this->corePath)) {
				return true;
			}

			foreach (array_keys(self::$addonPaths) as $addonPath) {
				if (str_starts_with($normalized, $addonPath)) {
					return true;
				}
			}
		}

		return false;
	}
}
