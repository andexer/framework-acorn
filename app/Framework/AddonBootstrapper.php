<?php

namespace App\Framework;

use Illuminate\Support\ServiceProvider;
use Livewire\Finder\Finder as LivewireFinder;

final class AddonBootstrapper
{
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
