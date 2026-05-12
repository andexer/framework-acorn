<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Finder\Finder as LivewireFinder;

class AddonProxyCommand extends Command
{
    protected $signature = 'addon
        {slug : Slug del addon}
        {cmd : Comando a ejecutar (ej: make:controller)}
        {args?* : Argumentos posicionales del comando}
        {--args= : Argumentos adicionales entre comillas}';

    protected $description = 'Ejecuta comandos de Acorn/Artisan dentro del scope de un addon específico';

    public function handle(): int
    {
        $slug = $this->argument('slug');
        $cmd  = $this->argument('cmd');
        $argsFromCommand = $this->argument('args');
        $argsLine = implode(' ', array_map(static fn ($value) => (string) $value, is_array($argsFromCommand) ? $argsFromCommand : []));
        $extraArgs = trim((string) $this->option('args'));
        if ($extraArgs !== '') {
            $argsLine = trim($argsLine . ' ' . $extraArgs);
        }

        $addonPath = dirname(base_path()) . '/' . $slug;

        if (! File::exists($addonPath)) {
            $this->error("Error: No se encontró el addon en [{$addonPath}]");
            return self::FAILURE;
        }

        $app = app();
        $namespace = $this->detectNamespace($addonPath);
        
        $originalNamespace = method_exists($app, 'getNamespace') ? $app->getNamespace() : 'App\\';
        $originalPaths = [
            'app'       => app_path(),
            'database'  => database_path(),
            'resources' => resource_path(),
            'config'    => config_path(),
            'lang'      => lang_path(),
        ];
        $originalLivewireConfig = [
            'component_locations' => config('livewire.component_locations'),
            'component_namespaces' => config('livewire.component_namespaces'),
            'class_namespace' => config('livewire.class_namespace'),
            'class_path' => config('livewire.class_path'),
            'view_path' => config('livewire.view_path'),
        ];
        $originalLivewireFinder = app()->bound('livewire.finder') ? app('livewire.finder') : null;

        $app->usePaths([
            'app'       => $addonPath . '/app',
            'database'  => $addonPath . '/database',
            'resources' => $addonPath . '/resources',
            'config'    => $addonPath . '/config',
            'lang'      => $addonPath . '/lang',
        ]);

        if (method_exists($app, 'useNamespace')) {
            $app->useNamespace($namespace);
        }
        $this->applyAddonLivewireScope($namespace, $addonPath);
        $this->prepareModelScope($cmd, $argsLine, $namespace, $addonPath);

        $this->info("Scope: Addon [{$slug}] (Namespace: {$namespace})");
        $this->line("Comando: {$cmd}");
        $this->newLine();

        $commandLine = trim($cmd . ' ' . $argsLine);
        $exitCode = Artisan::call($commandLine, [], $this->output);
        if ($exitCode === self::SUCCESS && $cmd === 'make:livewire') {
            $this->normalizeGeneratedLivewireComponents($addonPath);
        }

        $app->usePaths($originalPaths);
        if (method_exists($app, 'useNamespace')) {
            $app->useNamespace($originalNamespace);
        }
        config([
            'livewire.component_locations' => $originalLivewireConfig['component_locations'],
            'livewire.component_namespaces' => $originalLivewireConfig['component_namespaces'],
            'livewire.class_namespace' => $originalLivewireConfig['class_namespace'],
            'livewire.class_path' => $originalLivewireConfig['class_path'],
            'livewire.view_path' => $originalLivewireConfig['view_path'],
        ]);
        if ($originalLivewireFinder instanceof LivewireFinder) {
            app()->instance('livewire.finder', $originalLivewireFinder);
        }

        return $exitCode;
    }

    private function applyAddonLivewireScope(string $namespace, string $addonPath): void
    {
        $resourcesPath = $addonPath . '/resources';
        $componentsPath = $resourcesPath . '/views/components';
        $livewireViewsPath = $resourcesPath . '/views/livewire';

        config([
            'livewire.component_locations' => [
                $componentsPath,
                $livewireViewsPath,
            ],
            'livewire.component_namespaces' => [
                'layouts' => $resourcesPath . '/views/layouts',
                'pages' => $resourcesPath . '/views/pages',
            ],
            'livewire.class_namespace' => rtrim($namespace, '\\') . '\\Livewire',
            'livewire.class_path' => $addonPath . '/app/Livewire',
            'livewire.view_path' => $livewireViewsPath,
        ]);

        $finder = new LivewireFinder();
        $finder->addLocation(classNamespace: rtrim($namespace, '\\') . '\\Livewire');
        $finder->addLocation(viewPath: $componentsPath);
        $finder->addLocation(viewPath: $livewireViewsPath);
        $finder->addNamespace('layouts', viewPath: $resourcesPath . '/views/layouts');
        $finder->addNamespace('pages', viewPath: $resourcesPath . '/views/pages');

        app()->instance('livewire.finder', $finder);
    }

    private function prepareModelScope(string $cmd, string $argsLine, string $namespace, string $addonPath): void
    {
        if ($cmd !== 'make:model') {
            return;
        }

        File::ensureDirectoryExists($addonPath . '/app/Models');

        $modelArg = $this->extractFirstArgument($argsLine);
        if ($modelArg === null || str_starts_with($modelArg, '-')) {
            return;
        }

        $relativeModelPath = trim(str_replace('\\', '/', $modelArg), '/');
        if ($relativeModelPath === '') {
            return;
        }

        $legacyPath = $addonPath . '/app/' . $relativeModelPath . '.php';
        $targetPath = $addonPath . '/app/Models/' . $relativeModelPath . '.php';

        if (! File::exists($legacyPath) || File::exists($targetPath)) {
            return;
        }

        File::ensureDirectoryExists(dirname($targetPath));
        File::move($legacyPath, $targetPath);

        $content = File::get($targetPath);

        $segments = explode('/', $relativeModelPath);
        $className = Str::studly((string) end($segments));
        $subNamespace = implode('\\', array_map(static fn ($segment) => Str::studly($segment), array_slice($segments, 0, -1)));
        $modelNamespace = rtrim($namespace, '\\') . '\\Models' . ($subNamespace ? '\\' . $subNamespace : '');

        $content = preg_replace('/^namespace\s+[^;]+;/m', 'namespace ' . $modelNamespace . ';', $content) ?? $content;
        $content = preg_replace('/class\s+[A-Za-z_][A-Za-z0-9_]*/', 'class ' . $className, $content, 1) ?? $content;

        File::put($targetPath, $content);
    }

    private function extractFirstArgument(string $argsLine): ?string
    {
        $argsLine = trim($argsLine);
        if ($argsLine === '') {
            return null;
        }

        $parts = preg_split('/\s+/', $argsLine);
        if (! is_array($parts) || $parts === []) {
            return null;
        }

        return $parts[0] ?? null;
    }

    private function detectNamespace(string $path): string
    {
        $composer = $path . '/composer.json';
        if (File::exists($composer)) {
            $data = json_decode(File::get($composer), true);
            $psr4 = $data['autoload']['psr-4'] ?? [];
            if (! empty($psr4)) {
                return key($psr4);
            }
        }
        return Str::studly(basename($path)) . '\\';
    }

    private function normalizeGeneratedLivewireComponents(string $addonPath): void
    {
        $componentsPath = $addonPath . '/resources/views/components';

        if (! File::isDirectory($componentsPath)) {
            return;
        }

        $files = File::glob($componentsPath . '/⚡*.blade.php') ?: [];

        foreach ($files as $file) {
            $base = basename($file);
            $normalized = preg_replace('/^⚡+/u', '', $base);

            if (! is_string($normalized) || $normalized === '' || $normalized === $base) {
                continue;
            }

            $target = dirname($file) . '/' . $normalized;
            if (File::exists($target)) {
                continue;
            }

            File::move($file, $target);
        }
    }

}
