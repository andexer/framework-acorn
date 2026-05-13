<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Map\MapConfig;

class MapSettingsController extends AdminPanelController
{
    protected string $pageSlug = 'framework-map-settings';

    protected string $sectionId = 'framework_map_section';

    protected string $settingsGroup = 'framework_map_group';

    protected array $countries = [
        'VE' => '🇻🇪 Venezuela (por defecto)',
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

    public function register(): void
    {
        add_action('admin_menu', [$this, 'addMenuPage']);
        add_action('admin_init', [$this, 'registerSettings']);
    }

    public function addMenuPage(): void
    {
        $this->registerSubmenuPage(
            __('Configuración del Mapa', 'framework'),
            __('Mapa Interactivo', 'framework'),
            $this->pageSlug,
            [$this, 'renderPage'],
            20,
        );
    }

    public function registerSettings(): void
    {
        register_setting(
            $this->settingsGroup,
            MapConfig::OPTION_KEY,
            [
                'type' => 'string',
                'sanitize_callback' => [$this, 'sanitizeCountry'],
                'default' => MapConfig::DEFAULT_CODE,
            ],
        );

        add_settings_section(
            $this->sectionId,
            __('País del Mapa', 'framework'),
            function (): void {
                echo '<p class="description">'
                    .esc_html__('Selecciona el país que se mostrará por defecto en el mapa del shortcode [framework_map].', 'framework')
                    .'</p>';
            },
            $this->pageSlug,
        );

        add_settings_field(
            'framework_map_country_field',
            __('País activo', 'framework'),
            [$this, 'renderField'],
            $this->pageSlug,
            $this->sectionId,
        );
    }

    public function renderField(): void
    {
        $current = (string) get_option(MapConfig::OPTION_KEY, MapConfig::DEFAULT_CODE);
        echo '<select id="framework_map_country_field" name="'.esc_attr(MapConfig::OPTION_KEY).'" class="regular-text">';
        foreach ($this->countries as $code => $label) {
            printf(
                '<option value="%s"%s>%s</option>',
                esc_attr($code),
                selected($current, $code, false),
                esc_html($label),
            );
        }
        echo '</select>';
        echo '<p class="description">'.esc_html__('El mapa se centrará en este país cuando se use el shortcode sin parámetros.', 'framework').'</p>';
    }

    public function renderPage(): void
    {
        if (! current_user_can('manage_options')) {
            wp_die(esc_html__('No tienes permisos para acceder a esta página.', 'framework'));
        }

        if (isset($_GET['settings-updated'])) {
            add_settings_error(
                'framework_map_messages',
                'framework_map_message',
                __('✅ Configuración del mapa guardada correctamente.', 'framework'),
                'updated',
            );
        }

        settings_errors('framework_map_messages');

        $current = (string) get_option(MapConfig::OPTION_KEY, MapConfig::DEFAULT_CODE);
        $countryName = $this->countries[$current] ?? $current;

        echo app('view')->make('framework::admin.map-settings', [
            'pageSlug' => $this->pageSlug,
            'settingsGroup' => $this->settingsGroup,
            'currentCode' => $current,
            'currentName' => $countryName,
        ])->render();
    }

    public function sanitizeCountry(mixed $value): string
    {
        $code = strtoupper(sanitize_text_field((string) $value));

        return array_key_exists($code, $this->countries) ? $code : MapConfig::DEFAULT_CODE;
    }
}
