<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class AddonsDashboard extends Component
{
	public function render()
	{
		return view('framework::livewire.admin.addons-dashboard', [
			'addons' => $this->getFrameworkAddons(),
		]);
	}

	private function getFrameworkAddons(): array
	{
		if (! function_exists('get_plugins') || ! function_exists('is_plugin_active')) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$addons = [];
		$plugins = get_plugins();

		foreach ($plugins as $pluginFile => $data) {
			$pluginPath = WP_PLUGIN_DIR . '/' . $pluginFile;
			$headers = get_file_data($pluginPath, [
				'FrameworkAddon' => 'Framework Addon',
				'RequiresPlugins' => 'Requires Plugins',
			]);

			$isFrameworkAddon = strtolower(trim((string) ($headers['FrameworkAddon'] ?? ''))) === 'true';
			$requiresFramework = str_contains(strtolower((string) ($headers['RequiresPlugins'] ?? '')), 'framework');

			if (! $isFrameworkAddon || ! $requiresFramework) {
				continue;
			}

			$addons[] = [
				'name' => (string) ($data['Name'] ?? $pluginFile),
				'description' => (string) ($data['Description'] ?? ''),
				'author' => trim(wp_strip_all_tags((string) ($data['Author'] ?? ''))),
				'version' => (string) ($data['Version'] ?? ''),
				'active' => is_plugin_active($pluginFile),
			];
		}

		usort($addons, fn(array $a, array $b): int => strcmp($a['name'], $b['name']));

		return $addons;
	}
}
