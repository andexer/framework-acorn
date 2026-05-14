<?php

namespace App\Framework;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

final class AddonResourceServiceProvider extends ServiceProvider
{
	public function register(): void {}

	public function boot(): void
	{
		$frameworkPath = get_plugin_path();

		$this->loadViewsFrom("{$frameworkPath}/resources/views", 'framework');

		if (! $this->app->bound('blade.compiler')) {
			return;
		}

		Blade::anonymousComponentPath("{$frameworkPath}/resources/views/components/ui", 'ui');
		Blade::anonymousComponentPath("{$frameworkPath}/resources/views/layouts", 'layouts');
	}
}
