<?php

namespace App\Framework;

use Illuminate\Support\Facades\Log;

final class AddonExceptionLogger
{
	public static function report(\Throwable $exception): bool
	{
		$plugin = self::resolvePluginSlug($exception);

		if ($plugin === null || $plugin === 'framework') {
			return false;
		}

		$logPath = storage_path("logs/{$plugin}.log");
		
		$details = "";
		$curr = $exception;
		while ($curr) {
			$details .= sprintf(
				"[%s] %s: %s in %s:%d\nStack trace:\n%s\n\n",
				date('Y-m-d H:i:s'),
				get_class($curr),
				$curr->getMessage(),
				$curr->getFile(),
				$curr->getLine(),
				$curr->getTraceAsString()
			);
			$curr = $curr->getPrevious();
		}

		$message = $details;

		// Intentar usar el logger de Laravel si está disponible
		try {
			if (function_exists('app') && app()->bound('log')) {
				app('log')->build([
					'driver' => 'single',
					'path' => $logPath,
					'level' => env('LOG_LEVEL', 'debug'),
					'replace_placeholders' => true,
				])->error($exception->getMessage(), [
					'exception' => $exception,
				]);
				return true;
			}
		} catch (\Throwable $e) {
			// Fallback to error_log if Laravel logger fails
		}

		// Fallback: escribir directamente al archivo o al log de PHP
		if (!is_dir(dirname($logPath))) {
			mkdir(dirname($logPath), 0755, true);
		}
		file_put_contents($logPath, $message . PHP_EOL, FILE_APPEND);
		error_log("Addon Error [{$plugin}]: " . $exception->getMessage());

		return true;
	}

	private static function resolvePluginSlug(\Throwable $exception): ?string
	{
		$current = $exception;

		while ($current !== null) {
			foreach (self::extractCandidates($current) as $candidate) {
				$slug = self::extractSlugFromText($candidate);

				if ($slug !== null && $slug !== 'framework') {
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
