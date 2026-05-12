<?php

require_once __DIR__ . '/vendor/autoload.php';

// Mock get_plugin_path and get_plugin_uri
function get_plugin_path($path = '') { return __DIR__ . ($path ? '/' . ltrim($path, '/') : ''); }
function get_plugin_uri($path = '') { return 'http://localhost/' . ltrim($path, '/'); }

$config = include __DIR__ . '/config/assets.php';
$manager = new \Roots\Acorn\Assets\Manager($config);

try {
    $manifest = $manager->manifest('plugin');
    echo "Manifest loaded.\n";
    
    $keys = [
        'resources/js/map-app.tsx',
        'map-app'
    ];
    
    foreach ($keys as $key) {
        try {
            $bundle = $manifest->bundle($key);
            echo "Bundle found for key: $key\n";
            echo "JS files:\n";
            $bundle->js(function($h, $s) { echo "  - $h: $s\n"; });
            echo "CSS files:\n";
            $bundle->css(function($h, $s) { echo "  - $h: $s\n"; });
        } catch (\Exception $e) {
            echo "Bundle NOT found for key: $key - " . $e->getMessage() . "\n";
        }
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
