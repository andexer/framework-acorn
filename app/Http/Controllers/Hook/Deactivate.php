<?php

namespace App\Http\Controllers\Hook;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class Deactivate
{
	public function __invoke(): void
	{
		$this->deactivateFrameworkAddons();
		Artisan::call('cache:clear');
		Artisan::call('view:clear');
		Artisan::call('config:clear');
		$this->clearStorage();
	}

	private function deactivateFrameworkAddons(): void
	{
		if (! function_exists('get_plugins') || ! function_exists('deactivate_plugins')) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$toDeactivate = [];
		$plugins = get_plugins();

		foreach ($plugins as $pluginFile => $data) {
			if ($pluginFile === 'framework/framework.php') {
				continue;
			}

			if (! is_plugin_active($pluginFile)) {
				continue;
			}

			$pluginPath = WP_PLUGIN_DIR . '/' . $pluginFile;
			$headers = get_file_data($pluginPath, [
				'FrameworkAddon' => 'Framework Addon',
				'RequiresPlugins' => 'Requires Plugins',
			]);

			$isFrameworkAddon = strtolower(trim((string) ($headers['FrameworkAddon'] ?? ''))) === 'true';
			$requiresFramework = str_contains(strtolower((string) ($headers['RequiresPlugins'] ?? '')), 'framework');

			if ($isFrameworkAddon && $requiresFramework) {
				$toDeactivate[] = $pluginFile;
			}
		}

		if ($toDeactivate !== []) {
			deactivate_plugins($toDeactivate, true);
		}
	}

	private function clearStorage(): void
	{
		$paths = [
			storage_path('framework/cache'),
			storage_path('framework/views'),
			storage_path('framework/sessions'),
		];

		foreach ($paths as $path) {
			if (File::isDirectory($path)) {
				File::cleanDirectory($path);
			}
		}
	}
}
