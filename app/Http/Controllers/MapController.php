<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

/**
 * MapController
 *
 * - Registra el shortcode [framework_map]
 * - Encola map.bootstrap.tsx (compilado) solo cuando el shortcode está activo en la página
 * - Lee el país desde wp_options → framework_map_country (default: VE)
 */
class MapController
{
	public const OPTION_KEY = 'framework_map_country';
	public const DEFAULT_CODE = 'VE';
	public const SHORTCODE = 'framework_map';
	public const REST_NAMESPACE = 'framework/v1';
	public const REST_REVERSE_ROUTE = '/reverse-geocode';

	/** Llamado desde el ServiceProvider. */
	public function register(): void
	{
		add_shortcode(self::SHORTCODE, [$this, 'render']);
		add_action('rest_api_init', [$this, 'registerRestRoutes']);
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
			$entry    = 'resources/js/components/react/map.bootstrap.tsx';

			if (! isset($manifest[$entry])) {
				Log::error("Framework Map: Entry point [{$entry}] not found in manifest.");
				return;
			}

			$jsFile = $manifest[$entry]['file'];
			$baseUrl = get_plugin_uri('public/build/');
			$cssFiles = get_manifest_entry_css_files($manifest, $entry);
			foreach ($cssFiles as $cssFile) {
				wp_enqueue_style(
					'framework-map-app-' . md5($cssFile),
					$baseUrl . $cssFile,
					[],
					null
				);
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

	public function registerRestRoutes(): void
	{
		register_rest_route(self::REST_NAMESPACE, self::REST_REVERSE_ROUTE, [
			'methods' => 'GET',
			'callback' => [$this, 'reverseGeocode'],
			'permission_callback' => '__return_true',
		]);
	}

	public function reverseGeocode(\WP_REST_Request $request): \WP_REST_Response
	{
		$lat = (float) $request->get_param('lat');
		$lng = (float) $request->get_param('lng');

		if (! $this->validCoordinates($lat, $lng)) {
			return new \WP_REST_Response([
				'message' => 'Invalid coordinates.',
			], 422);
		}

		$cacheKey = 'framework_rg_' . md5(number_format($lat, 4, '.', '') . '|' . number_format($lng, 4, '.', ''));
		$cached = get_transient($cacheKey);
		if (is_array($cached)) {
			return new \WP_REST_Response($cached, 200);
		}

		$url = add_query_arg([
			'format' => 'jsonv2',
			'addressdetails' => 1,
			'lat' => number_format($lat, 6, '.', ''),
			'lon' => number_format($lng, 6, '.', ''),
			'accept-language' => 'es',
		], 'https://nominatim.openstreetmap.org/reverse');

		$response = wp_remote_get($url, [
			'timeout' => 10,
			'user-agent' => 'framework-acorn/1.0 (' . home_url('/') . ')',
			'headers' => [
				'Accept' => 'application/json',
			],
		]);

		if (is_wp_error($response)) {
			return new \WP_REST_Response([
				'message' => $response->get_error_message(),
			], 502);
		}

		$code = (int) wp_remote_retrieve_response_code($response);
		if ($code < 200 || $code >= 300) {
			return new \WP_REST_Response([
				'message' => 'Reverse geocode request failed.',
			], 502);
		}

		$body = json_decode((string) wp_remote_retrieve_body($response), true);
		$payload = $this->normalizeReversePayload($body, $lat, $lng);

		set_transient($cacheKey, $payload, 5 * MINUTE_IN_SECONDS);

		return new \WP_REST_Response($payload, 200);
	}

	public static function insideVenezuelaBounds(float $lat, float $lng): bool
	{
		return (
			$lat >= 0.4 &&
			$lat <= 12.4 &&
			$lng >= -73.6 &&
			$lng <= -59.6
		);
	}

	private function validCoordinates(float $lat, float $lng): bool
	{
		return is_finite($lat) && is_finite($lng) && $lat >= -90 && $lat <= 90 && $lng >= -180 && $lng <= 180;
	}

	private function normalizeReversePayload(array $body, float $lat, float $lng): array
	{
		$address = is_array($body['address'] ?? null) ? $body['address'] : [];

		$estado = $this->addressValue($address, ['state']);
		$ciudad = $this->addressValue($address, ['city', 'town', 'village', 'hamlet']);
		$municipio = $this->addressValue($address, ['municipality', 'county']);
		$parroquia = $this->addressValue($address, ['suburb', 'city_district', 'quarter', 'neighbourhood']);
		$codigoPostal = $this->addressValue($address, ['postcode']);

		return [
			'estado' => $estado,
			'ciudad' => $ciudad,
			'municipio' => $municipio,
			'parroquia' => $parroquia,
			'codigo_postal' => $codigoPostal,
			'latitud' => round($lat, 6),
			'longitud' => round($lng, 6),
			'direccion_completa' => (string) ($body['display_name'] ?? ''),
			'fuera_de_venezuela' => ! self::insideVenezuelaBounds($lat, $lng),
		];
	}

	private function addressValue(array $address, array $keys): string
	{
		foreach ($keys as $key) {
			$value = trim((string) ($address[$key] ?? ''));
			if ($value !== '') {
				return $value;
			}
		}

		return '';
	}
}
