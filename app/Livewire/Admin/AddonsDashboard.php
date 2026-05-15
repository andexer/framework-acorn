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
				'AddonImage' => 'Addon Image',
				'AuthorURI' => 'Author URI',
			]);

			$isFrameworkAddon = strtolower(trim((string) ($headers['FrameworkAddon'] ?? ''))) === 'true';
			$requiresFramework = str_contains(strtolower((string) ($headers['RequiresPlugins'] ?? '')), 'framework');

			if (! $isFrameworkAddon || ! $requiresFramework) {
				continue;
			}

			$description = (string) ($data['Description'] ?? '');
			$imageUrl = trim((string) ($headers['AddonImage'] ?? ''));

			// Extraer imagen de la descripción: [img url]
			if (empty($imageUrl) && preg_match('/\[img\s+(https?:\/\/[^\]]+)\]/i', $description, $matches)) {
				$imageUrl = trim($matches[1]);
				$description = trim(str_replace($matches[0], '', $description));
			}

			// Intentar obtener avatar de github
			if (empty($imageUrl)) {
				$authorUri = trim((string) ($headers['AuthorURI'] ?? ''));
				if (preg_match('/github\.com\/([^\/]+)/i', $authorUri, $matches)) {
					$imageUrl = 'https://github.com/' . trim($matches[1]) . '.png';
				}
			}

			// Imagen por defecto si no hay nada
			if (empty($imageUrl)) {
				$imageUrl = get_plugin_uri('public/img/logo.png');
			}

			$addons[] = [
				'name' => (string) ($data['Name'] ?? $pluginFile),
				'description' => $description,
				'author' => trim(wp_strip_all_tags((string) ($data['Author'] ?? ''))),
				'version' => (string) ($data['Version'] ?? ''),
				'active' => is_plugin_active($pluginFile),
				'image' => $imageUrl,
			];
		}

		usort($addons, fn(array $a, array $b): int => strcmp($a['name'], $b['name']));

		return $addons;
	}
}
