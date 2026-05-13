<?php

namespace App\Providers;

use App\Actions\Map\MapConfig;
use App\Actions\Map\RegisterMapRoutesAction;
use App\Actions\Map\RenderMapShortcodeAction;
use App\Console\Commands\AddonProxyCommand;
use App\Console\Commands\MakeAddonCommand;
use App\Http\Controllers\Admin\AdminPanelController;
use App\Http\Controllers\Admin\MapSettingsController;
use App\Http\Controllers\WooCommerce\MyAccountController;
use Illuminate\Foundation\Console\RequestMakeCommand;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class FrameworkServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $frameworkViewsPath = function_exists('get_framework_path')
            ? get_framework_path('resources/views')
            : dirname(__DIR__, 2).'/resources/views';

        $frameworkUiPath = function_exists('get_framework_path')
            ? get_framework_path('resources/views/components/ui')
            : dirname(__DIR__, 2).'/resources/views/components/ui';
        $frameworkLayoutsPath = function_exists('get_framework_path')
            ? get_framework_path('resources/views/layouts')
            : dirname(__DIR__, 2).'/resources/views/layouts';

        $this->loadViewsFrom($frameworkViewsPath, 'framework');

        // Registrar componentes UI del framework para que sean heredables
        if ($this->app->bound('blade.compiler')) {
            Blade::anonymousComponentPath(
                $frameworkUiPath,
                'ui'
            );
            Blade::anonymousComponentPath(
                $frameworkLayoutsPath,
                'layouts'
            );
        }
        // Comandos de consola
        if ($this->app->runningInConsole()) {
            $this->commands([
                RequestMakeCommand::class,
                MakeAddonCommand::class,
                AddonProxyCommand::class,
            ]);
        }
        add_action('wp_enqueue_scripts', fn () => $this->enqueueFrameworkAssets(), 100);
        add_action('admin_enqueue_scripts', function (): void {
            $page = sanitize_key((string) ($_GET['page'] ?? ''));
            if ($page === '' || ! str_starts_with($page, 'framework-')) {
                return;
            }
            $this->enqueueFrameworkAssets();
        }, 100);
        // Asegurar que los scripts de Vite se carguen como módulos
        add_filter('script_loader_tag', function ($tag, $handle, $src) {
            if (in_array($handle, ['framework-app', 'framework-islands-app'], true)) {
                return '<script type="module" src="'.esc_url($src).'" id="'.esc_attr($handle).'-js"></script>';
            }

            return $tag;
        }, 10, 3);

        add_action('woocommerce_account_dashboard', function () {
            echo app(MyAccountController::class)()->render();
        });

        add_shortcode(MapConfig::SHORTCODE, app(RenderMapShortcodeAction::class));
        add_action('rest_api_init', app(RegisterMapRoutesAction::class));
        app(AdminPanelController::class)->register();
        app(MapSettingsController::class)->register();
    }

    private function enqueueFrameworkAssets(): void
    {
        try {
            $css = plugin_asset('resources/css/app.css');
            $js = plugin_asset('resources/js/app.js');

            wp_enqueue_style(
                'framework-app',
                $css->uri(),
                [],
                null
            );
            wp_enqueue_script(
                'framework-app',
                $js->uri(),
                [],
                null,
                true
            );
            wp_set_script_translations('framework-app', 'framework', lang_path());
        } catch (\Exception $e) {
            Log::error('Framework: Error encolando activos - '.$e->getMessage());
        }
    }
}
