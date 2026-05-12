<?php

namespace App\Http\Controllers\Hook;

use Illuminate\Support\Facades\Artisan;

class Activate
{
	public function __invoke(): void
	{
		try {
			Artisan::call('migrate', ['--force' => true]);
			if (\Illuminate\Support\Facades\DB::table('plugin_test_data')->count() === 0) {
				Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\TestDataSeeder', '--force' => true]);
			}
		} catch (\Exception $e) {
		}
	}
}
