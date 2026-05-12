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
