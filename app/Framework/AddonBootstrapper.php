<?php

namespace App\Framework;

use App\Framework\Contracts\AddonContract;
use App\Framework\Logging\ScopedMonologHandler;
use Illuminate\Support\ServiceProvider;
use Livewire\Finder\Finder as LivewireFinder;

final class AddonBootstrapper
{
	private static array $registeredProviders = [];

	public static function register(string $providerClass): void
	{
		if (! function_exists('app') || ! app()->has('events')) {
			return;
		}

		if (! is_a($providerClass, ServiceProvider::class, true)) {
			return;
		}

		if (app()->getProvider($providerClass)) {
			return;
		}

		app()->register($providerClass);

		$provider = app()->getProvider($providerClass);

		if ($provider instanceof AddonContract) {
			ScopedMonologHandler::registerAddonPath($provider->addonPath(), $provider->addonSlug());
			self::$registeredProviders[$provider->addonSlug()] = $provider->addonPath();
		}

		self::registerLivewireLocations($providerClass);
	}

	public static function isReady(): bool
	{
		return function_exists('app') && app()->has('events');
	}

	public static function version(): string
	{
		return app()->version();
	}

	public static function registeredAddonSlugs(): array
	{
		return array_keys(self::$registeredProviders);
	}

	public static function registeredAddonPaths(): array
	{
		return self::$registeredProviders;
	}

	private static function registerLivewireLocations(string $providerClass): void
	{
		if (! app()->bound('livewire.finder')) {
			return;
		}

		$finder = app('livewire.finder');

		if (! $finder instanceof LivewireFinder) {
			return;
		}

		$providerFile = (new \ReflectionClass($providerClass))->getFileName();

		if (! is_string($providerFile) || $providerFile === '') {
			return;
		}

		$addonRoot = dirname($providerFile, 3);
		$resources = $addonRoot . '/resources/views';
		$components = $resources . '/components';
		$livewireViews = $resources . '/livewire';
		$layouts = $resources . '/layouts';
		$pages = $resources . '/pages';
		$slug = basename($addonRoot);

		if (is_dir($components)) {
			$finder->addLocation(viewPath: $components);
			$finder->addNamespace($slug, viewPath: $components);
		}

		if (is_dir($livewireViews)) {
			$finder->addLocation(viewPath: $livewireViews);
			$finder->addNamespace($slug, viewPath: $livewireViews);
		}

		if (is_dir($layouts)) {
			$finder->addNamespace('layouts', viewPath: $layouts);
		}

		if (is_dir($pages)) {
			$finder->addNamespace('pages', viewPath: $pages);
		}
	}
}
