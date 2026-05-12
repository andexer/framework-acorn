<?php

namespace App\Http\Controllers\Admin;

class AdminPanelController
{
	public const MENU_SLUG = 'framework-admin';

	protected string $capability = 'manage_options';
	protected string $pageTitle = 'Framework Dashboard';
	protected string $menuTitle = 'Framework';
	protected string $icon = 'dashicons-dashboard';
	protected int $position = 2;

	public function register(): void
	{
		add_action('admin_menu', [$this, 'addMenuPage'], 9);
	}

	public function addMenuPage(): void
	{
		add_menu_page(
			__($this->pageTitle, 'framework'),
			__($this->menuTitle, 'framework'),
			$this->capability,
			self::MENU_SLUG,
			[$this, 'renderPage'],
			$this->icon,
			$this->position,
		);

		add_submenu_page(
			self::MENU_SLUG,
			__($this->pageTitle, 'framework'),
			__('Dashboard', 'framework'),
			$this->capability,
			self::MENU_SLUG,
			[$this, 'renderPage'],
			0,
		);
	}

	public function renderPage(): void
	{
		if (! current_user_can($this->capability)) {
			wp_die(esc_html__('No tienes permisos para acceder a esta página.', 'framework'));
		}

		echo app('view')->make('framework::admin.dashboard')->render();
	}

	protected function registerSubmenuPage(
		string $pageTitle,
		string $menuTitle,
		string $menuSlug,
		callable $callback,
		int $position = 10,
	): void {
		add_submenu_page(
			self::MENU_SLUG,
			$pageTitle,
			$menuTitle,
			$this->capability,
			$menuSlug,
			$callback,
			$position,
		);
	}
}
