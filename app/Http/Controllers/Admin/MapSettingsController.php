<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\MapController;

/**
 * MapSettingsController
 *
 * Registra la página de ajustes del mapa en el admin de WordPress.
 * Usa WordPress Settings API para guardar el país en wp_options.
 *
 * Ruta admin: Ajustes → Configuración del Mapa
 * Opción:     framework_map_country  (código ISO 3166-1 alpha-2)
 */
class MapSettingsController
{
	private const PAGE_SLUG    = 'framework-map-settings';
	private const SECTION_ID   = 'framework_map_section';
	private const SETTINGS_GROUP = 'framework_map_group';

	/** Lista de países disponibles (code => nombre en español) */
	private const COUNTRIES = [
		// Venezuela primero — país por defecto
		'VE' => '🇻🇪 Venezuela (por defecto)',
		// Latinoamérica
		'AR' => '🇦🇷 Argentina',
		'BO' => '🇧🇴 Bolivia',
		'BR' => '🇧🇷 Brasil',
		'CL' => '🇨🇱 Chile',
		'CO' => '🇨🇴 Colombia',
		'CR' => '🇨🇷 Costa Rica',
		'CU' => '🇨🇺 Cuba',
		'DO' => '🇩🇴 República Dominicana',
		'EC' => '🇪🇨 Ecuador',
		'SV' => '🇸🇻 El Salvador',
		'GT' => '🇬🇹 Guatemala',
		'HN' => '🇭🇳 Honduras',
		'JM' => '🇯🇲 Jamaica',
		'MX' => '🇲🇽 México',
		'NI' => '🇳🇮 Nicaragua',
		'PA' => '🇵🇦 Panamá',
		'PY' => '🇵🇾 Paraguay',
		'PE' => '🇵🇪 Perú',
		'PR' => '🇵🇷 Puerto Rico',
		'UY' => '🇺🇾 Uruguay',
		// Resto del mundo
		'US' => '🇺🇸 Estados Unidos',
		'CA' => '🇨🇦 Canadá',
		'ES' => '🇪🇸 España',
		'PT' => '🇵🇹 Portugal',
		'DE' => '🇩🇪 Alemania',
		'FR' => '🇫🇷 Francia',
		'IT' => '🇮🇹 Italia',
		'GB' => '🇬🇧 Reino Unido',
		'CN' => '🇨🇳 China',
		'JP' => '🇯🇵 Japón',
		'IN' => '🇮🇳 India',
		'AU' => '🇦🇺 Australia',
	];

	/** Llamado desde el ServiceProvider */
	public function register(): void
	{
		add_action('admin_menu',    [$this, 'addMenuPage']);
		add_action('admin_init',    [$this, 'registerSettings']);
	}

	public function addMenuPage(): void
	{
		add_options_page(
			__('Configuración del Mapa', 'framework'),
			__('Mapa Interactivo', 'framework'),
			'manage_options',
			self::PAGE_SLUG,
			[$this, 'renderPage'],
		);
	}

	public function registerSettings(): void
	{
		register_setting(
			self::SETTINGS_GROUP,
			MapController::OPTION_KEY,
			[
				'type'              => 'string',
				'sanitize_callback' => [$this, 'sanitizeCountry'],
				'default'           => MapController::DEFAULT_CODE,
			],
		);

		add_settings_section(
			self::SECTION_ID,
			__('País del Mapa', 'framework'),
			function (): void {
				echo '<p class="description">'
					. esc_html__('Selecciona el país que se mostrará por defecto en el mapa del shortcode [framework_map].', 'framework')
					. '</p>';
			},
			self::PAGE_SLUG,
		);

		add_settings_field(
			'framework_map_country_field',
			__('País activo', 'framework'),
			[$this, 'renderField'],
			self::PAGE_SLUG,
			self::SECTION_ID,
		);
	}

	public function renderField(): void
	{
		$current = (string) get_option(MapController::OPTION_KEY, MapController::DEFAULT_CODE);
		echo '<select id="framework_map_country_field" name="' . esc_attr(MapController::OPTION_KEY) . '" class="regular-text">';
		foreach (self::COUNTRIES as $code => $label) {
			printf(
				'<option value="%s"%s>%s</option>',
				esc_attr($code),
				selected($current, $code, false),
				esc_html($label),
			);
		}
		echo '</select>';
		echo '<p class="description">' . esc_html__('El mapa se centrará en este país cuando se use el shortcode sin parámetros.', 'framework') . '</p>';
	}

	public function renderPage(): void
	{
		if (!current_user_can('manage_options')) {
			wp_die(esc_html__('No tienes permisos para acceder a esta página.', 'framework'));
		}

		// Mostrar mensaje de éxito tras guardar
		if (isset($_GET['settings-updated'])) {
			add_settings_error(
				'framework_map_messages',
				'framework_map_message',
				__('✅ Configuración del mapa guardada correctamente.', 'framework'),
				'updated',
			);
		}

		settings_errors('framework_map_messages');

		$current     = (string) get_option(MapController::OPTION_KEY, MapController::DEFAULT_CODE);
		$countryName = self::COUNTRIES[$current] ?? $current;

		// Renderizar vista Blade
		echo app('view')->make('framework::admin.map-settings', [
			'pageSlug'       => self::PAGE_SLUG,
			'settingsGroup'  => self::SETTINGS_GROUP,
			'currentCode'    => $current,
			'currentName'    => $countryName,
		])->render();
	}

	public function sanitizeCountry(mixed $value): string
	{
		$code = strtoupper(sanitize_text_field((string) $value));
		return array_key_exists($code, self::COUNTRIES) ? $code : MapController::DEFAULT_CODE;
	}
}
