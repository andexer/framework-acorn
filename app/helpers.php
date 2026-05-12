<?php

/*
 * Framework Helpers
 */
if (! function_exists('base_url')) {
	/**
	 * Obtiene la URL base del framework.
	 *
	 * @param  string  $path
	 * @return string
	 */
	function base_url($path = '')
	{
		// dirname nos saca de 'app/' para quedar en la raíz del plugin
		$baseUrl = untrailingslashit(dirname(plugin_dir_url(__FILE__)));
		return $baseUrl . ($path ? '/' . ltrim($path, '/') : '');
	}
}

if (! function_exists('plugin_asset')) {
	/**
	 * Obtiene un activo del framework desde el manifiesto.
	 *
	 * @param  string  $asset
	 * @return \Roots\Acorn\Assets\Contracts\Asset
	 */
	function plugin_asset(string $asset): \Roots\Acorn\Assets\Contracts\Asset
	{
		return asset($asset, 'plugin');
	}
}

if (! function_exists('get_plugin_manifest')) {
	/**
	 * Obtiene el contenido del manifiesto de activos.
	 *
	 * @return array
	 */
	function get_plugin_manifest(): array
	{
		static $manifest = null;
		if ($manifest === null) {
			$path = get_plugin_path('public/build/manifest.json');
			$manifest = file_exists($path) ? json_decode(file_get_contents($path), true) : [];
		}
		return $manifest;
	}
}

if (! function_exists('get_manifest_entry_css_files')) {
	function get_manifest_entry_css_files(array $manifest, string $entry): array
	{
		if (! isset($manifest[$entry])) {
			return [];
		}

		$pending = [$entry];
		$visited = [];
		$css = [];

		while ($pending !== []) {
			$key = array_pop($pending);
			if (! is_string($key) || isset($visited[$key]) || ! isset($manifest[$key])) {
				continue;
			}

			$visited[$key] = true;
			$chunk = $manifest[$key];

			foreach (($chunk['css'] ?? []) as $cssFile) {
				if (is_string($cssFile) && $cssFile !== '') {
					$css[$cssFile] = true;
				}
			}

			foreach (($chunk['imports'] ?? []) as $import) {
				if (is_string($import) && $import !== '') {
					$pending[] = $import;
				}
			}
		}

		return array_keys($css);
	}
}

if (! function_exists('get_plugin_path')) {
	/**
	 * Obtiene la ruta física del framework.
	 *
	 * @param  string  $path
	 * @return string
	 */
	function get_plugin_path(string $path = ''): string
	{
		return untrailingslashit(dirname(__DIR__)) . ($path ? '/' . ltrim($path, '/') : '');
	}
}

if (! function_exists('get_plugin_uri')) {
	/**
	 * Obtiene la URI del framework.
	 *
	 * @param  string  $path
	 * @return string
	 */
	function get_plugin_uri(string $path = ''): string
	{
		return base_url($path);
	}
}
