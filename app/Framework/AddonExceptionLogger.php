<?php

namespace App\Framework;

use App\Framework\Logging\AddonLogResolver;

final class AddonExceptionLogger
{
	public static function report(\Throwable $exception): bool
	{
		$slug = AddonLogResolver::resolveSlug($exception);

		if ($slug === null) {
			return false;
		}

		if ($slug === AddonLogResolver::CORE_SLUG) {
			return self::writeToPath(AddonLogResolver::coreLogPath(), $exception);
		}

		if (! in_array($slug, AddonBootstrapper::registeredAddonSlugs(), true)) {
			return false;
		}

		return self::writeToPath(AddonLogResolver::addonLogPath($slug), $exception);
	}

	private static function writeToPath(string $logPath, \Throwable $exception): bool
	{
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
}
