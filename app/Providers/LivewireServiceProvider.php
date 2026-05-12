<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Livewire\Mechanisms\FrontendAssets\FrontendAssets;

class LivewireServiceProvider extends ServiceProvider
{
	public function register(): void {}

	public function boot(): void
	{
		if (! class_exists(Livewire::class)) {
			return;
		}

		$this->register_view_composers();
		$this->register_custom_routes();
		$this->register_plugin_actions();
	}

	private function register_view_composers(): void
	{
		View::composer('*', fn($view) => $view->with('csrf_token', session()->token()));
	}

	private function register_custom_routes(): void
	{
		Livewire::setUpdateRoute(
			fn($handle) => Route::post('/plugin-wire/update', $handle)->middleware('wordpress')
		);
	}

	private function register_plugin_actions(): void
	{
		add_action('parse_request', $this->handle_livewire_requests(...), 1);
		add_action('wp_head', fn() => print(FrontendAssets::styles()));
		add_action('wp_footer', $this->inject_livewire_assets(...));
		add_action('admin_head', fn() => print(FrontendAssets::styles()));
		add_action('admin_footer', $this->inject_livewire_assets(...));
	}

	private function handle_livewire_requests(): void
	{
		$request = app('request');
		if ($request->isMethod('POST') && $request->is('plugin-wire/*')) {
			app('router')->dispatch($request)->send();
			exit;
		}
	}

	private function inject_livewire_assets(): void
	{
		$assetUrl = $this->asset_url();
		if ($assetUrl === '') {
			return;
		}

		printf(
			'<script src="%s" data-csrf="%s" data-update-uri="%s" data-navigate-once="true"></script>',
			esc_url($assetUrl),
			esc_attr(session()->token()),
			esc_url(home_url('/plugin-wire/update'))
		);
	}

	private function asset_url(): string
	{
		$debug = config('app.debug', false);
		$csp   = config('livewire.csp_safe', false);

		$file = match (true) {
			$debug && $csp => 'livewire.csp.js',
			$debug         => 'livewire.js',
			$csp           => 'livewire.csp.min.js',
			default        => 'livewire.min.js',
		};

		$publicManifest = get_plugin_path('public/vendor/livewire/manifest.json');
		$publicFile = get_plugin_path("public/vendor/livewire/{$file}");
		$vendorManifest = get_plugin_path('vendor/livewire/livewire/dist/manifest.json');
		$vendorFile = get_plugin_path("vendor/livewire/livewire/dist/{$file}");

		$url = '';
		$manifest = null;

		if (file_exists($publicFile)) {
			$url = get_plugin_uri("public/vendor/livewire/{$file}");
			$manifest = $publicManifest;
		} elseif (file_exists($vendorFile)) {
			$url = get_plugin_uri("vendor/livewire/livewire/dist/{$file}");
			$manifest = $vendorManifest;
		} else {
			return '';
		}

		if ($manifest && file_exists($manifest)) {
			$data = json_decode(file_get_contents($manifest), true) ?: [];
			$version = $data['/livewire.js'] ?? null;
			if ($version) {
				$url .= '?id=' . rawurlencode($version);
			}
		}

		return $url;
	}
}
