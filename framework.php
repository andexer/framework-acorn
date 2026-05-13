<?php

if (!file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
	wp_die('Error: Autoloader not found. Run `composer install` in the plugin directory');
}

require $composer;

use Roots\Acorn\Application;
use App\Providers\FrameworkServiceProvider;
use App\Providers\LivewireServiceProvider;
use App\Http\Controllers\Hook\Activate;
use App\Http\Controllers\Hook\Deactivate;
use App\Framework\AddonExceptionLogger;
use Roots\Acorn\Configuration\Exceptions;

if (file_exists(__DIR__ . '/.env')) {
	\Dotenv\Dotenv::createUnsafeImmutable(__DIR__)->safeLoad();
}

final class Framework
{
	protected array $files = ['bootstrap', 'functions'];

	public function run(): void
	{
		$this->boot();
		$this->lifecycle_hooks();
	}

	private function boot(): void
	{
		Application::configure(basePath: __DIR__)
			->withProviders([
				FrameworkServiceProvider::class,
				LivewireServiceProvider::class
			])
			->withRouting(wordpress: true)
			->withExceptions(function (Exceptions $exceptions) {
				$exceptions->reportable(function (\Throwable $e) {
					if (AddonExceptionLogger::report($e)) {
						return false;
					}
				});
			})
			->boot();

		add_action('plugins_loaded', fn() => $this->load_optional_files());
	}

	private function load_optional_files(): void
	{
		collect($this->files)
			->map(fn(string $file) => __DIR__ . "/app/{$file}.php")
			->filter(fn(string $path) => file_exists($path))
			->each(fn(string $path) => require_once $path);
	}

	private function lifecycle_hooks(): void
	{
		register_activation_hook(FRAMEWORK_PLUGIN_FILE, static fn() => app(Activate::class)());
		register_deactivation_hook(FRAMEWORK_PLUGIN_FILE, static fn() => app(Deactivate::class)());
	}
}

(new Framework())->run();
