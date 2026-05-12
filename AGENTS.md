# Agent Context: Framework (WordPress + Acorn)

> [!IMPORTANT]
> **MANDATORY RULE**: Before any UI/Frontend task, you **MUST** read `.agents/skills/index.md`.
> **MANDATORY COMMANDS**: This is a WordPress environment. **NEVER** use `php artisan`. **ALWAYS** use the `acorn` command through WP-CLI (e.g., `wp acorn` or `ddev wp acorn` depending on your environment).

## Environment

- This repo is a **WordPress plugin**.
- All commands must be run via WP-CLI with the `acorn` prefix.
- If using DDEV, prefix commands with `ddev wp`. Example: `ddev wp acorn cache:clear`.

## Stack & Entrypoint

- **Framework**: Roots Acorn 6 (Laravel 13.x container) — booted once as a singleton in `framework.php`.
- **PHP**: >= 8.3.
- **Frontend**: Vite + Tailwind CSS v4 + Alpine.js + Livewire v4.
- **Entry**: `framework.php` loads `vendor/autoload.php`, reads `.env`, configures `Application::configure(__DIR__)->withProviders([...])->withRouting(wordpress: true)->boot()`.
- **Service Providers**: `App\Providers\FrameworkServiceProvider`, `App\Providers\LivewireServiceProvider`.

## Daily Commands

| Task | Command |
|------|---------|
| Install PHP deps | `composer install` |
| Install JS deps | `npm install` |
| Dev server (Vite) | `npm run dev` |
| Production build | `npm run build` |
| Clear compiled Blade views | `ddev wp acorn view:clear` |
| Clear cache | `ddev wp acorn cache:clear` |
| Generate code (controllers, models, Livewire) | `ddev wp acorn make:<thing> <Name>` |
| PHP lint | `vendor/bin/pint` |
| Translation POT | `npm run translate:pot` |

## Asset Pipeline

- Vite config: `vite.config.js`.
- Base path: `/wp-content/plugins/framework/public/build/`.
- Fallback `APP_URL`: `https://wordpress.ddev.site` (set in Vite config if absent from env).
- Input entries: `resources/css/app.css`, `resources/js/app.js`.
- Vite aliases: `@scripts` → `/resources/js`, `@styles` → `/resources/css`.
- Build output goes to `public/build/`; Acorn reads `public/build/manifest.json` via `config/assets.php`.
- Enqueue assets in WordPress using the helper `framework_asset('resources/css/app.css')` / `framework_asset('resources/js/app.js')`.
- `public/vendor/livewire/` contains committed static Livewire JS assets (injected manually via `LivewireServiceProvider`). Do not delete them.

## Tailwind CSS v4 (no `tailwind.config.js`)

- Configuration lives in `resources/css/app.css` using `@theme`, `@source`, and `@layer`.
- Theme variables already defined: `--color-brand-*` and `--font-sans`.
- **Light Mode ONLY** — never use `dark:` classes.
- **Specificity Rule**: In case of conflicts with WordPress theme styles, follow the [Tailwind Specificity Skill](.agents/skills/tailwind-specificity.md) using the `!` suffix (e.g., `bg-red-500!`).

## UI Components

- Components use prefix `<x-ui.*>` and live in `resources/views/components/ui/`.
- **Modular Scoping**: Livewire components in addons MUST use the `slug::` namespace (e.g., `<livewire:my-addon::counter />`) to ensure interoperability and avoid collisions. See [Modular Architecture Skill](.agents/skills/modular-architecture.md).
- Before creating or editing any UI component, read its docs in `resources/views/components-ui/components/<name>/<name>.md`.
- Master design guide: `resources/views/components-ui/index.md`.
- Interactive components must follow the three-layer binding pattern (Blade detection → Alpine `$entangle` / `$root._x_model` → `_state` logic). Docs: `resources/views/components-ui/posts/livewire-and-alpine-data-binding-the-pattern-sheaui-uses.md`.

## Livewire

- Update endpoint is custom: `POST /plugin-wire/update`.
- Upload endpoint: `POST /plugin-wire/upload-file`.
- Assets are injected manually in `wp_head` / `wp_footer` by `LivewireServiceProvider`; do not rely on `@livewireStyles` / `@livewireScripts`.
- Component classes live in `app/Livewire/`; views in `resources/views/livewire/`.
- Config: `config/livewire.php`.

## Hierarchy & Logs

- **Core/Addon Relationship**: The Core framework acts as the parent orchestrator. Addons are isolated but inherit Core services.
- **Log Management**: Logs are scoped by name and stored in `wp-content/plugins/framework/storage/logs/`.
    - Check `framework.log` for core issues.
    - Check `{addon-slug}.log` for specific addon issues.
    - For more details, see the [Hierarchy & Logs Skill](.agents/skills/hierarchy-and-logs.md).

## Code Style

- `.editorconfig` rules:
  - Most files: 2 spaces.
  - `*.php`: 4 spaces.
  - `*.blade.php` and `resources/views/**/*.php`: **tabs**, indent size 4.
- PSR-4 autoloading: `App\` → `app/`, `Database\Factories\` → `database/factories/`, `Database\Seeders\` → `database/seeders/`.
- Helper functions in `app/helpers.php` (`framework_asset`, `base_url`, `get_framework_path`, `get_framework_uri`).

## Gotchas

- `.env` at the plugin root is loaded directly by `framework.php` via `Dotenv::createUnsafeImmutable(__DIR__)->safeLoad()`. It must contain `APP_KEY`.
- Compiled Blade views are cached in `storage/framework/views`. If templates look stale, run `ddev wp acorn view:clear`.
- Acorn is a singleton. Do **not** call `Application::configure()->boot()` from another plugin. If building addon plugins, inject service providers into this instance via `app()->register(...)` on `plugins_loaded` priority > 10.
- `framework.php` also conditionally loads `app/bootstrap.php` and `app/functions.php` on `plugins_loaded`.
