<?php

namespace App\Framework\Logging;

use App\Framework\AddonBootstrapper;

final class AddonLogResolver
{
	public const CORE_SLUG = 'framework-acorn';

	public static function coreLogPath(): string
	{
		return storage_path('logs/framework-acorn.log');
	}

	public static function addonLogPath(string $slug): string
	{
		$paths = AddonBootstrapper::registeredAddonPaths();

		if (isset($paths[$slug])) {
			return rtrim($paths[$slug], '/') . "/{$slug}.log";
		}

		return WP_PLUGIN_DIR . "/{$slug}/{$slug}.log";
	}

	public static function logPathForSlug(?string $slug): string
	{
		if ($slug === null || $slug === '' || $slug === self::CORE_SLUG) {
			return self::coreLogPath();
		}

		return self::addonLogPath($slug);
	}

	public static function resolveSlug(\Throwable $exception): ?string
	{
		$registered = AddonBootstrapper::registeredAddonSlugs();

		$fromTrace = self::resolveSlugFromTrace($exception, $registered);

		if ($fromTrace !== null) {
			return $fromTrace;
		}

		$fromMessage = self::slugFromComponentReference($exception->getMessage());

		if ($fromMessage !== null && in_array($fromMessage, $registered, true)) {
			return $fromMessage;
		}

		$current = $exception;

		while ($current !== null) {
			foreach (self::prioritizeAddonSlugs(self::collectSlugsFromThrowable($current), $registered) as $slug) {
				if ($slug === self::CORE_SLUG) {
					continue;
				}

				if (in_array($slug, $registered, true)) {
					return $slug;
				}
			}

			$current = $current->getPrevious();
		}

		foreach (self::collectSlugsFromThrowable($exception) as $slug) {
			if ($slug === self::CORE_SLUG) {
				return self::CORE_SLUG;
			}
		}

		return null;
	}

	public static function slugFromPath(string $path): ?string
	{
		if ($path === '') {
			return null;
		}

		$normalized = wp_normalize_path($path);

		if (! preg_match('#/wp-content/plugins/([^/]+)/#', $normalized, $matches)) {
			return null;
		}

		$slug = sanitize_key($matches[1]);

		return $slug !== '' ? $slug : null;
	}

	public static function slugFromComponentReference(string $value): ?string
	{
		if ($value === '') {
			return null;
		}

		if (preg_match('/\[([a-z0-9-]+)::[^\]]+\]/', $value, $matches)) {
			return sanitize_key($matches[1]);
		}

		if (preg_match('/\b([a-z0-9-]+)::[a-z0-9_.-]+\b/i', $value, $matches)) {
			return sanitize_key($matches[1]);
		}

		return null;
	}

	private static function resolveSlugFromTrace(\Throwable $exception, array $registered): ?string
	{
		if ($registered === []) {
			return null;
		}

		$registeredLookup = array_flip($registered);
		$frames = $exception->getTrace();

		for ($index = count($frames) - 1; $index >= 0; $index--) {
			$file = $frames[$index]['file'] ?? '';

			if (! is_string($file) || $file === '') {
				continue;
			}

			$slug = self::slugFromPath($file);

			if ($slug !== null && isset($registeredLookup[$slug])) {
				return $slug;
			}
		}

		$file = $exception->getFile();

		if ($file !== '') {
			$slug = self::slugFromPath($file);

			if ($slug !== null && isset($registeredLookup[$slug])) {
				return $slug;
			}
		}

		return null;
	}

	private static function collectSlugsFromThrowable(\Throwable $exception): array
	{
		$slugs = [];

		foreach ([$exception->getFile(), $exception->getMessage()] as $candidate) {
			$slug = is_string($candidate) ? self::slugFromPath($candidate) : null;

			if ($slug !== null && ! in_array($slug, $slugs, true)) {
				$slugs[] = $slug;
			}
		}

		foreach ($exception->getTrace() as $frame) {
			$file = $frame['file'] ?? '';

			if (! is_string($file) || $file === '') {
				continue;
			}

			$slug = self::slugFromPath($file);

			if ($slug !== null && ! in_array($slug, $slugs, true)) {
				$slugs[] = $slug;
			}
		}

		return $slugs;
	}

	/**
	 * @param  list<string>  $slugs
	 * @param  list<string>  $registered
	 * @return list<string>
	 */
	private static function prioritizeAddonSlugs(array $slugs, array $registered): array
	{
		$addons = [];
		$core = [];

		foreach ($slugs as $slug) {
			if ($slug === self::CORE_SLUG) {
				$core[] = $slug;

				continue;
			}

			if (in_array($slug, $registered, true)) {
				$addons[] = $slug;
			}
		}

		return array_merge($addons, $core);
	}
}
