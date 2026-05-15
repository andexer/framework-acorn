<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeAddonCommand extends Command
{
	private const STUBS_BASE = __DIR__ . '/../../Framework/template/addon';

	private const STATIC_STUBS = [
		'plugin.php.stub'                        => '{{slug}}.php',
		'composer.json.stub'                     => 'composer.json',
		'routes/web.php.stub'                    => 'routes/web.php',
		'config/config.php.stub'                 => 'config/{{slug}}.php',
		'resources/views/welcome.blade.php.stub' => 'resources/views/welcome.blade.php',
		'app/Http/Controllers/Home/HomeController.php.stub' => 'app/Http/Controllers/Home/HomeController.php',
		'app/Providers/AddonServiceProvider.php.stub'            => 'app/Providers/AddonServiceProvider.php',
		'.gitignore.stub'                        => '.gitignore',
		'.env.example.stub'                      => '.env.example',
	];

	private const HOOK_STUBS = [
		'app/Http/Controllers/Hook/Activate.php.stub'   => 'app/Http/Controllers/Hook/Activate.php',
		'app/Http/Controllers/Hook/Deactivate.php.stub' => 'app/Http/Controllers/Hook/Deactivate.php',
	];

	private const ASSET_STUBS = [
		'resources/css/app.css.stub' => 'resources/css/app.css',
		'resources/js/app.js.stub'   => 'resources/js/app.js',
		'vite.config.js.stub'        => 'vite.config.js',
		'package.json.stub'          => 'package.json',
	];

	private const LANG_STUBS = [
		'lang/en.php.stub' => 'lang/en/messages.php',
		'lang/es.php.stub' => 'lang/es/messages.php',
	];

	protected $signature = 'make:addon {name? : Nombre del plugin addon} {--namespace= : Namespace PSR-4} {--description= : Descripción del addon} {--f|force : Sobreescribir si existe}';

	protected $description = 'Genera un nuevo plugin addon que depende de este framework Core';

	private array $features = [];

	public function handle(): int
	{
		$name      = $this->argument('name') ?? $this->ask('¿Nombre del addon?');

		if (empty($name)) {
			$this->error('El nombre del addon no puede estar vacío.');
			return self::FAILURE;
		}

		$slug      = Str::slug($name);
		$namespace = $this->option('namespace') ?: Str::studly($name);

		$description = $this->option('description');
		if (! $description) {
			$description = $this->ask('¿Descripción del addon?', "{$name} Addon que depende del Framework Core (plugin).");
		}

		$pluginsDir = dirname(base_path());
		$addonPath  = $pluginsDir . '/' . $slug;

		if (File::exists($addonPath) && ! $this->option('force')) {
			$this->error("El addon [{$slug}] ya existe en: {$addonPath}");
			$this->info('Usa --force para sobreescribir.');
			return self::FAILURE;
		}

		$this->newLine();
		$this->line('<fg=cyan>┌─────────────────────────────────────────┐</>');
		$this->line('<fg=cyan>│   Framework Core — Generador de Addon   │</>');
		$this->line('<fg=cyan>└─────────────────────────────────────────┘</>');
		$this->newLine();
		$this->line("  Addon:     <comment>{$name}</comment>");
		$this->line("  Slug:      <comment>{$slug}</comment>");
		$this->line("  Namespace: <comment>{$namespace}</comment>");
		$this->newLine();
		$this->line('<fg=yellow>Selecciona las características (Enter para Sí):</>');
		$this->newLine();

		$this->features = [
			'livewire'   => $this->confirm('  ¿Tendrá componentes Livewire?', true),
			'migrations' => $this->confirm('  ¿Necesita migraciones de base de datos?', true),
			'hooks'      => $this->confirm('  ¿Necesita hooks de activación/desactivación?', true),
			'assets'     => $this->confirm('  ¿Tendrá assets propios (CSS/JS con Vite)?', true),
			'lang'       => $this->confirm('  ¿Necesita soporte multi-idioma (en/es)?', true),
			'ai_skill'   => $this->confirm('  ¿Incluir Skill de IA para diseño (documentación)?', true),
		];

		$this->newLine();

		if (! $this->confirm("  ¿Generar addon <comment>{$name}</comment> con estas opciones?", true)) {
			$this->info('Operación cancelada.');
			return self::SUCCESS;
		}

		$this->newLine();
		$this->info("Generando addon <comment>{$name}</comment> ...");
		$this->newLine();

		$vars = [
			'name'        => $name,
			'slug'        => $slug,
			'slug_snake'  => str_replace('-', '_', $slug),
			'slug_snake_upper' => strtoupper(str_replace('-', '_', $slug)),
			'namespace'   => $namespace,
			'timestamp'   => now()->format('Y_m_d_His'),
			'description' => $description,
			'core_logo_url' => 'https://raw.githubusercontent.com/andexer/framework-acorn/refs/heads/main/public/img/logo.png',
		];

		if ($this->option('force') && File::exists($addonPath)) {
			File::deleteDirectory($addonPath);
		}

		$this->createDirectories($addonPath);
		$this->publishStaticStubs($addonPath, $vars);
		$this->publishConditionalStubs($addonPath, $vars);
		$this->writeBinary($addonPath, $vars);

		$this->newLine();
		$this->line("<fg=green>✅ Addon <comment>{$name}</comment> creado con éxito.</>");
		$this->line("   Ruta: {$addonPath}");
		$this->newLine();
		$this->line('<fg=yellow>Siguientes pasos:</>');
		$step = 1;
		$this->line("  {$step}. <comment>cd {$addonPath} && composer install</comment>");
		$step++;
		if ($this->features['assets']) {
			$this->line("  {$step}. <comment>npm install && npm run dev</comment>");
			$step++;
		}
		$this->line("  {$step}. Activa el plugin en WordPress.");
		$step++;
		$this->line("  {$step}. Usa <comment>acorn-addon {$slug} list</comment> o <comment>wp acorn addon {$slug} list</comment>.");

		return self::SUCCESS;
	}

	private function createDirectories(string $basePath): void
	{
		$dirs = [
			'app/Http/Controllers',
			'app/Http/Controllers/Home',
			'app/Http/Requests',
			'app/Helpers',
			'app/Models',
			'app/Providers',
			'config',
			'resources/views/components',
			'resources/views/layouts',
			'routes',
		];

		if ($this->features['hooks']) {
			$dirs[] = 'app/Http/Controllers/Hook';
		}

		if ($this->features['migrations']) {
			$dirs[] = 'database/migrations';
		}

		if ($this->features['assets']) {
			$dirs[] = 'resources/css';
			$dirs[] = 'resources/js';
			$dirs[] = 'public/build';
		}

		if ($this->features['lang']) {
			$dirs[] = 'lang/en';
			$dirs[] = 'lang/es';
		}

		foreach ($dirs as $dir) {
			File::makeDirectory("{$basePath}/{$dir}", 0755, true);
		}

		foreach (['resources/views/components', 'resources/views/layouts', 'app/Http/Requests'] as $dir) {
			File::put("{$basePath}/{$dir}/.gitkeep", '');
		}
	}

	private function publishStaticStubs(string $basePath, array $vars): void
	{
		$this->publishStubMap(self::STATIC_STUBS, $basePath, $vars);
	}

	private function publishConditionalStubs(string $basePath, array $vars): void
	{
		if ($this->features['hooks']) {
			$this->publishStubMap(self::HOOK_STUBS, $basePath, $vars);
		}

		if ($this->features['livewire']) {
			$this->publishStub(self::STUBS_BASE, 'resources/views/components/⚡saludo.blade.php.stub', $basePath, 'resources/views/components/⚡saludo.blade.php', $vars);
		}

		if ($this->features['migrations']) {
			$target = "database/migrations/{$vars['timestamp']}_create_{$vars['slug_snake']}_tables.php";
			$this->publishStub(self::STUBS_BASE, 'database/migrations/create_tables.php.stub', $basePath, $target, $vars);
		}

		if ($this->features['assets']) {
			$this->publishStubMap(self::ASSET_STUBS, $basePath, $vars);
		}

		if ($this->features['lang']) {
			$this->publishStubMap(self::LANG_STUBS, $basePath, $vars);
		}

		if ($this->features['ai_skill']) {
			$this->publishAiSkill($basePath);
		}
	}

	private function publishAiSkill(string $basePath): void
	{
		$skillsSource = base_path('.agents/skills');
		$agentsSource = base_path('AGENTS.md');

		$targetDir = "{$basePath}/.agents/skills";

		if (File::exists($skillsSource)) {
			File::copyDirectory($skillsSource, $targetDir);
			$this->line('  <fg=green>created</> .agents/skills <fg=gray>(AI Design Skill)</>');
		}

		if (File::exists($agentsSource)) {
			File::copy($agentsSource, "{$basePath}/AGENTS.md");
			$this->line('  <fg=green>created</> AGENTS.md <fg=gray>(AI Context)</>');
		}
	}

	private function writeBinary(string $basePath, array $vars): void
	{
		$stubFile = self::STUBS_BASE . '/bin.sh.stub';
		$content  = $this->replacePlaceholders(File::get($stubFile), $vars);
		$binary   = "{$basePath}/{$vars['slug']}";

		File::put($binary, $content);
		chmod($binary, 0755);

		$this->line("  <fg=green>created</> {$vars['slug']} <fg=gray>(binary +x)</>");
	}


	private function publishStub(string $stubsPath, string $stub, string $basePath, string $target, array $vars): void
	{
		$stubFile = "{$stubsPath}/{$stub}";

		if (! File::exists($stubFile)) {
			$this->warn("  Stub no encontrado: {$stub}");
			return;
		}

		$content    = $this->replacePlaceholders(File::get($stubFile), $vars);
		$targetFile = "{$basePath}/{$target}";

		File::ensureDirectoryExists(dirname($targetFile));
		File::put($targetFile, $content);

		$this->line("  <fg=green>created</> {$target}");
	}

	private function publishStubMap(array $map, string $basePath, array $vars): void
	{
		foreach ($map as $stub => $target) {
			$target = $this->replacePlaceholders($target, $vars);
			$this->publishStub(self::STUBS_BASE, $stub, $basePath, $target, $vars);
		}
	}

	private function replacePlaceholders(string $content, array $vars): string
	{
		return str_replace(
			['{{name}}', '{{slug}}', '{{slug_snake}}', '{{slug_snake_upper}}', '{{namespace}}', '{{timestamp}}', '{{description}}', '{{core_logo_url}}'],
			[$vars['name'], $vars['slug'], $vars['slug_snake'], $vars['slug_snake_upper'], $vars['namespace'], $vars['timestamp'], $vars['description'], $vars['core_logo_url']],
			$content
		);
	}
}
