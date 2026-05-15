<?php

namespace App\Http\Controllers\Admin;

class FrameworkSettingsController extends AdminPanelController
{
	protected string $pageSlug = 'framework-settings';

	public function register(): void
	{
		add_action('admin_menu', [$this, 'addMenuPage']);
	}

	public function addMenuPage(): void
	{
		$this->registerSubmenuPage(
			__('Ajustes del Framework', 'framework'),
			__('Ajustes', 'framework'),
			$this->pageSlug,
			[$this, 'renderPage'],
			30,
		);
	}

	public function renderPage(): void
	{
		if (! current_user_can('manage_options')) {
			wp_die(esc_html__('No tienes permisos para acceder a esta página.', 'framework'));
		}

		echo app('view')->make('framework::admin.settings', [
			'pageSlug' => $this->pageSlug,
		])->render();
	}
}
