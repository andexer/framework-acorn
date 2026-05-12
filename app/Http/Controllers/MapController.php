<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

/**
 * MapController
 *
 * - Registra el shortcode [framework_map]
 * - Encola map-app.tsx (compilado) solo cuando el shortcode está activo en la página
 * - Lee el país desde wp_options → framework_map_country (default: VE)
 */
class MapController
{
	public const OPTION_KEY  = 'framework_map_country';
	public const DEFAULT_CODE = 'VE';
	public const SHORTCODE    = 'framework_map';

	/** Llamado desde el ServiceProvider. */
	public function register(): void
	{
		add_shortcode(self::SHORTCODE, [$this, 'render']);
	}

	/**
	 * Callback del shortcode [framework_map height="480"].
	 *
	 * @param array<string,string>|string $atts
	 */
	public function render(array|string $atts): string
	{
		/** @var array<string,string> $atts */
		$atts = shortcode_atts(
			[
				'country' => '',      // si se pasa explícitamente en el shortcode
				'height'  => '480',
			],
			$atts,
			self::SHORTCODE,
		);

		$code   = !empty($atts['country'])
			? strtoupper(sanitize_text_field($atts['country']))
			: strtoupper((string) get_option(self::OPTION_KEY, self::DEFAULT_CODE));

		$height = max(200, (int) $atts['height']);

		// Encolar los assets del mapa solo cuando el shortcode está presente
		$this->enqueueAssets();

		// El id es único por instancia en caso de múltiples mapas en la misma página
		$id = 'map-root-' . esc_attr($code) . '-' . wp_unique_id();

		return sprintf(
			'<div id="%s" data-country="%s" data-height="%d" class="framework-map-island"></div>',
			$id,
			esc_attr($code),
			$height,
		);
	}

	private function enqueueAssets(): void
	{
		// Evitar encolar más de una vez si hay múltiples shortcodes en la página
		if (wp_script_is('framework-map-app', 'enqueued')) {
			return;
		}

		try {
			$manifest = get_plugin_manifest();
			$entry    = 'resources/js/map-app.tsx';

			if (! isset($manifest[$entry])) {
				Log::error("Framework Map: Entry point [{$entry}] not found in manifest.");
				return;
			}

			$jsFile = $manifest[$entry]['file'];
			$baseUrl = get_plugin_uri('public/build/');

			// Encolar CSS asociado si existe
			if (!empty($manifest[$entry]['css'])) {
				foreach ($manifest[$entry]['css'] as $cssFile) {
					wp_enqueue_style(
						'framework-map-app-' . md5($cssFile),
						$baseUrl . $cssFile,
						[],
						null
					);
				}
			}

			// Encolar JS
			wp_enqueue_script(
				'framework-map-app',
				$baseUrl . $jsFile,
				['framework-app'],
				null,
				['strategy' => 'defer', 'in_footer' => true]
			);
		} catch (\Exception $e) {
			if (defined('WP_DEBUG') && WP_DEBUG) {
				Log::error('Framework Map: Error encolando assets - ' . $e->getMessage());
			}
		}
	}
}
