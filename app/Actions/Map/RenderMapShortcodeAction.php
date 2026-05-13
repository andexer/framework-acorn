<?php

namespace App\Actions\Map;

use Illuminate\Support\Facades\Log;

class RenderMapShortcodeAction
{
    public function __invoke(array|string $atts): string
    {
        $atts = shortcode_atts(
            ['country' => '', 'height' => '480'],
            $atts,
            MapConfig::SHORTCODE,
        );

        $code = ! empty($atts['country'])
            ? strtoupper(sanitize_text_field($atts['country']))
            : strtoupper((string) get_option(MapConfig::OPTION_KEY, MapConfig::DEFAULT_CODE));

        $height = max(200, (int) $atts['height']);

        $this->enqueueAssets();

        $id = 'map-root-'.esc_attr($code).'-'.wp_unique_id();

        return sprintf(
            '<div id="%s" data-country="%s" data-height="%d" class="framework-map-island"></div>',
            $id,
            esc_attr($code),
            $height,
        );
    }

    private function enqueueAssets(): void
    {
        if (wp_script_is('framework-islands-app', 'enqueued')) {
            return;
        }

        try {
            $manifest = get_plugin_manifest();
            $entry = 'resources/js/components/react/islands.bootstrap.tsx';

            if (! isset($manifest[$entry])) {
                Log::error("Framework Islands: Entry point [{$entry}] not found in manifest.");
                return;
            }

            $baseUrl = get_plugin_uri('public/build/');

            foreach (get_manifest_entry_css_files($manifest, $entry) as $cssFile) {
                wp_enqueue_style('framework-map-app-'.md5($cssFile), $baseUrl.$cssFile, [], null);
            }

            wp_enqueue_script(
                'framework-islands-app',
                $baseUrl.$manifest[$entry]['file'],
                ['framework-app'],
                null,
                ['strategy' => 'defer', 'in_footer' => true],
            );
        } catch (\Exception $e) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                Log::error('Framework Map: Error encolando assets - '.$e->getMessage());
            }
        }
    }
}
