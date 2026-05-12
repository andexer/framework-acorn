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

		Log::build([
			'driver' => 'single',
			'path' => storage_path("logs/{$plugin}.log"),
			'level' => env('LOG_LEVEL', 'debug'),
			'replace_placeholders' => true,
		])->error($exception->getMessage(), [
			'exception' => $exception,
		]);

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
