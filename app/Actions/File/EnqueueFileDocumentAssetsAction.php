<?php

namespace App\Actions\File;

use Illuminate\Support\Facades\Log;

class EnqueueFileDocumentAssetsAction
{
    public function __invoke(): void
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
                wp_enqueue_style('framework-file-document-'.md5($cssFile), $baseUrl.$cssFile, [], null);
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
                Log::error('Framework File Document: Error encolando assets - '.$e->getMessage());
            }
        }
    }
}
