<?php

namespace App\Framework;

use App\Framework\Contracts\AddonContract;
use App\Framework\Logging\ScopedMonologHandler;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

abstract class BaseAddonServiceProvider extends ServiceProvider implements AddonContract
{
	private string $resolvedSlug = '';

	private string $resolvedPath = '';

	public function addonSlug(): string
	{
		if ($this->resolvedSlug === '') {
			$this->resolvedSlug = basename($this->addonPath());
		}

		return $this->resolvedSlug;
	}

	public function addonPath(): string
	{
		if ($this->resolvedPath === '') {
			$this->resolvedPath = dirname(
				(new \ReflectionClass(static::class))->getFileName(),
				3
			);
		}

		return $this->resolvedPath;
	}

	public function register(): void
	{
		$this->app->instance(static::class, $this);
	}

	public function boot(): void
	{
		$path = $this->addonPath();
		$slug = $this->addonSlug();

		ScopedMonologHandler::registerAddonPath($path);

		$this->bootViews($path, $slug);
		$this->bootRoutes($path);
		$this->bootBladeComponents($path);
		$this->bootAssets($path, $slug);
		$this->bootMigrations($path);
		$this->bootTranslations($path, $slug);
	}

	final protected function addonLog(\Throwable $e): void
	{
		$slug = $this->addonSlug();
		$logPath = $this->addonPath() . "/{$slug}.log";

		$entry = sprintf(
			"[%s] %s: %s in %s:%d\nStack trace:\n%s\n\n",
			date('Y-m-d H:i:s'),
			get_class($e),
			$e->getMessage(),
			$e->getFile(),
			$e->getLine(),
			$e->getTraceAsString()
		);

		file_put_contents($logPath, $entry, FILE_APPEND);
	}

	private function bootViews(string $path, string $slug): void
	{
		$viewsPath = "{$path}/resources/views";

		if (is_dir($viewsPath)) {
			$this->loadViewsFrom($viewsPath, $slug);
		}
	}

	private function bootRoutes(string $path): void
	{
		$routesFile = "{$path}/routes/web.php";

		if (file_exists($routesFile)) {
			$this->loadRoutesFrom($routesFile);
		}
	}

	private function bootBladeComponents(string $path): void
	{
		if (! $this->app->bound('blade.compiler')) {
			return;
		}

		$uiPath = "{$path}/resources/views/components/ui";

		if (is_dir($uiPath)) {
			Blade::anonymousComponentPath($uiPath, 'ui');
		}
	}

	private function bootAssets(string $path, string $slug): void
	{
		$manifestPath = "{$path}/public/build/manifest.json";

		if (! file_exists($manifestPath)) {
			return;
		}

		$manifest = json_decode(file_get_contents($manifestPath), true) ?: [];
		$baseUrl = content_url("plugins/{$slug}/public/build/");
		$cssFile = $manifest['resources/css/app.css']['file'] ?? null;
		$jsFile = $manifest['resources/js/app.js']['file'] ?? null;

		add_action('wp_enqueue_scripts', function () use ($slug, $baseUrl, $cssFile, $jsFile): void {
			if ($cssFile) {
				wp_enqueue_style("{$slug}-style", $baseUrl . $cssFile, [], null);
			}
			if ($jsFile) {
				wp_enqueue_script("{$slug}-script", $baseUrl . $jsFile, [], null, true);
			}
		});

		add_action('admin_enqueue_scripts', function () use ($slug, $baseUrl, $cssFile, $jsFile): void {
			$page = sanitize_key((string) ($_GET['page'] ?? ''));

			if ($page === '' || ! str_starts_with($page, "{$slug}-")) {
				return;
			}

			if ($cssFile) {
				wp_enqueue_style("{$slug}-style", $baseUrl . $cssFile, [], null);
			}
			if ($jsFile) {
				wp_enqueue_script("{$slug}-script", $baseUrl . $jsFile, [], null, true);
			}
		});
	}

	private function bootMigrations(string $path): void
	{
		$migrationsPath = "{$path}/database/migrations";

		if (is_dir($migrationsPath)) {
			$this->loadMigrationsFrom($migrationsPath);
		}
	}

	private function bootTranslations(string $path, string $slug): void
	{
		$langPath = "{$path}/lang";

		if (is_dir($langPath)) {
			$this->loadTranslationsFrom($langPath, $slug);
		}
	}
}
