<?php

namespace App\Http\Controllers\Hook;

use Illuminate\Support\Facades\Artisan;

class Activate
{
	public function __invoke(): void
	{
		$this->ensureEnvFile();
		$this->generateAppKey();

		try {
			Artisan::call('migrate', ['--force' => true]);
			if (\Illuminate\Support\Facades\DB::table('plugin_test_data')->count() === 0) {
				Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\TestDataSeeder', '--force' => true]);
			}
		} catch (\Exception $e) {
		}
	}

	private function ensureEnvFile(): void
	{
		$envFile = base_path('.env');
		if (! file_exists($envFile)) {
			$template = "APP_KEY=";
			file_put_contents($envFile, $template);
		}
	}

	private function generateAppKey(): void
	{
		if (empty(env('APP_KEY'))) {
			Artisan::call('key:generate', ['--force' => true]);
		}
	}
}
