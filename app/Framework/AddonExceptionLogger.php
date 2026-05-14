<?php

namespace App\Framework;

final class AddonExceptionLogger
{
	private const CORE_SLUG = 'framework-acorn';

	public static function report(\Throwable $exception): bool
	{
		$slug = self::resolvePluginSlug($exception);

		if ($slug === null) {
			return false;
		}

		if ($slug === self::CORE_SLUG) {
			return self::writeToCore($exception);
		}

		if (! in_array($slug, AddonBootstrapper::registeredAddonSlugs(), true)) {
			return false;
		}

		return self::writeToAddon($slug, $exception);
	}

	private static function writeToCore(\Throwable $exception): bool
	{
		$logPath = storage_path('logs/framework-acorn.log');

		try {
			if (function_exists('app') && app()->bound('log')) {
				app('log')->build([
					'driver' => 'single',
					'path' => $logPath,
					'level' => env('LOG_LEVEL', 'debug'),
					'replace_placeholders' => true,
				])->error($exception->getMessage(), ['exception' => $exception]);

				return true;
			}
		} catch (\Throwable) {}

		return self::writeToFile($logPath, $exception);
	}

	private static function writeToAddon(string $slug, \Throwable $exception): bool
	{
		$logPath = WP_PLUGIN_DIR . "/{$slug}/{$slug}.log";

		try {
			if (function_exists('app') && app()->bound('log')) {
				app('log')->build([
					'driver' => 'single',
					'path' => $logPath,
					'level' => env('LOG_LEVEL', 'debug'),
					'replace_placeholders' => true,
				])->error($exception->getMessage(), ['exception' => $exception]);

				return true;
			}
		} catch (\Throwable) {}

		return self::writeToFile($logPath, $exception);
	}

	private static function writeToFile(string $logPath, \Throwable $exception): bool
	{
		$logDir = dirname($logPath);

		if (! is_dir($logDir)) {
			mkdir($logDir, 0755, true);
		}

		$details = '';
		$current = $exception;

		while ($current) {
			$details .= sprintf(
				"[%s] %s: %s in %s:%d\nStack trace:\n%s\n\n",
				date('Y-m-d H:i:s'),
				get_class($current),
				$current->getMessage(),
				$current->getFile(),
				$current->getLine(),
				$current->getTraceAsString()
			);
			$current = $current->getPrevious();
		}

		file_put_contents($logPath, $details, FILE_APPEND);

		return true;
	}

	private static function resolvePluginSlug(\Throwable $exception): ?string
	{
		$current = $exception;

		while ($current !== null) {
			foreach (self::extractCandidates($current) as $candidate) {
				$slug = self::extractSlugFromText($candidate);

				if ($slug !== null) {
					return $slug;
				}
			}

			$current = $current->getPrevious();
		}

		return null;
	}

	private static function extractCandidates(\Throwable $exception): array
	{
		$candidates = [$exception->getFile(), $exception->getMessage()];

		foreach ($exception->getTrace() as $frame) {
			if (isset($frame['file']) && is_string($frame['file'])) {
				$candidates[] = $frame['file'];
			}
		}

		return $candidates;
	}

	private static function extractSlugFromText(string $value): ?string
	{
		if ($value === '') {
			return null;
		}

		$normalized = wp_normalize_path($value);

		if (! preg_match('#/wp-content/plugins/([^/]+)/#', $normalized, $matches)) {
			return null;
		}

		$slug = sanitize_key($matches[1]);

		return $slug !== '' ? $slug : null;
	}
}
