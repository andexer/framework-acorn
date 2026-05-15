<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SyncSkillsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'skills:sync
                            {slug? : Slug del addon específico}
                            {--all : Sincronizar todos los addons detectados}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza las skills de IA y AGENTS.md del Core hacia los addons';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $slug = $this->argument('slug');
        $all = $this->option('all');

        $corePath = base_path();
        $pluginsDir = dirname($corePath);
        $skillsSource = $corePath . '/.agents/skills';
        $agentsSource = $corePath . '/AGENTS.md';

        if (!File::exists($skillsSource)) {
            $this->error("No se encontró el directorio de skills en el Core: {$skillsSource}");
            return self::FAILURE;
        }

        if (!File::exists($agentsSource)) {
            $this->warn("No se encontró AGENTS.md en el Core: {$agentsSource}");
        }

        // Detectar addons disponibles
        $availableAddons = [];
        $directories = File::directories($pluginsDir);
        foreach ($directories as $dir) {
            if (basename($dir) === 'framework-acorn') {
                continue;
            }
            if (File::exists($dir . '/app/Providers/AddonServiceProvider.php')) {
                $availableAddons[basename($dir)] = $dir;
            }
        }

        // Menú interactivo si no hay argumentos
        if (!$slug && !$all) {
            if (empty($availableAddons)) {
                $this->error('No se detectaron addons en el directorio de plugins.');
                return self::FAILURE;
            }

            $options = array_merge(['[Sincronizar TODOS]'], array_keys($availableAddons));
            $selection = $this->choice('¿Qué addon deseas sincronizar?', $options, 0);

            if ($selection === '[Sincronizar TODOS]') {
                $all = true;
            } else {
                $slug = $selection;
            }
        }

        $targets = [];

        if ($all) {
            $this->info('Sincronizando todos los addons detectados...');
            $targets = array_values($availableAddons);
        } else {
            $addonPath = $pluginsDir . '/' . $slug;
            if (!File::exists($addonPath)) {
                $this->error("No se encontró el addon en: {$addonPath}");
                return self::FAILURE;
            }
            $targets[] = $addonPath;
        }

        if (empty($targets)) {
            $this->warn('No se encontraron addons para sincronizar.');
            return self::SUCCESS;
        }

        $this->newLine();
        $this->info('Sincronizando ' . count($targets) . ' addon(s)...');
        $this->newLine();

        foreach ($targets as $targetPath) {
            $slugName = basename($targetPath);
            $this->line("<fg=cyan>Sincronizando: {$slugName}</>");

            // Sincronizar .agents/skills
            $targetSkills = $targetPath . '/.agents/skills';
            if (File::exists($targetSkills)) {
                File::deleteDirectory($targetSkills);
            }
            File::ensureDirectoryExists(dirname($targetSkills));
            File::copyDirectory($skillsSource, $targetSkills);
            $this->line("  <fg=green>✓</> .agents/skills");

            // Sincronizar AGENTS.md
            if (File::exists($agentsSource)) {
                File::copy($agentsSource, $targetPath . '/AGENTS.md');
                $this->line("  <fg=green>✓</> AGENTS.md");
            }

            $this->newLine();
        }

        $this->info('Sincronización completada con éxito.');

        return self::SUCCESS;
    }
}
