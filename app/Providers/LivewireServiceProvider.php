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
		printf(
			'<script src="%s" data-csrf="%s" data-update-uri="%s" data-navigate-once="true"></script>',
			esc_url($this->asset_url()),
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

		$url      = get_plugin_uri("public/vendor/livewire/{$file}");
		$manifest = get_plugin_path('public/vendor/livewire/manifest.json');

		if (file_exists($manifest)) {
			$data = json_decode(file_get_contents($manifest), true);
			if ($v = ($data['/livewire.js'] ?? null)) {
				$url .= "?id={$v}";
			}
		}

		return $url;
	}
}
