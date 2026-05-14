<?php

namespace App\Providers;

use App\Actions\Map\MapConfig;
use App\Actions\Map\RegisterMapRoutesAction;
use App\Actions\Map\RenderMapShortcodeAction;
use App\Console\Commands\AddonProxyCommand;
use App\Console\Commands\MakeAddonCommand;
use App\Framework\AddonResourceServiceProvider;
use App\Http\Controllers\Admin\AdminPanelController;
use App\Http\Controllers\Admin\MapSettingsController;
use Illuminate\Foundation\Console\RequestMakeCommand;
use Illuminate\Support\ServiceProvider;

class FrameworkServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		$this->app->register(AddonResourceServiceProvider::class);
	}

	public function boot(): void
	{
		if ($this->app->runningInConsole()) {
			$this->commands([
				RequestMakeCommand::class,
				MakeAddonCommand::class,
				AddonProxyCommand::class,
			]);
		}

		add_action('wp_enqueue_scripts', fn() => $this->enqueueFrameworkAssets(), 100);

		add_action('admin_enqueue_scripts', function (): void {
			$page = sanitize_key((string) ($_GET['page'] ?? ''));

			if ($page === '' || ! str_starts_with($page, 'framework-')) {
				return;
			}

			$this->enqueueFrameworkAssets();
		}, 100);

		add_action('admin_head', function (): void {
			$page = sanitize_key((string) ($_GET['page'] ?? ''));

			if ($page === '' || ! str_starts_with($page, 'framework-')) {
				return;
			}

			remove_all_actions('admin_notices');
			remove_all_actions('all_admin_notices');
			remove_all_actions('network_admin_notices');
		}, 999);

		add_filter('script_loader_tag', function ($tag, $handle, $src) {
			if (in_array($handle, ['framework-app', 'framework-islands-app'], true)) {
				return '<script type="module" src="' . esc_url($src) . '" id="' . esc_attr($handle) . '-js"></script>';
			}

			return $tag;
		}, 10, 3);

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

			wp_enqueue_style('framework-app', $css->uri(), [], null);
			wp_enqueue_script('framework-app', $js->uri(), [], null, true);
			wp_set_script_translations('framework-app', 'framework', lang_path());
		} catch (\Exception $e) {
			if ($this->app->bound('log')) {
				$this->app['log']->error('Framework: Error encolando activos - ' . $e->getMessage());
			}
		}
	}
}
