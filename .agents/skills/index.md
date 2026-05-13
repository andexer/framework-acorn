# **AI Design Skill: Framework Core**

This document defines the mandatory design patterns and architectural constraints for any AI Agent working on this repository.

## **🚨 Critical Constraints**

1.  **NO DARK MODE**: This project is EXCLUSIVELY Light Mode.
    *   Never use `dark:` utilities.
    *   Never generate dark-mode CSS variables.
2.  **NO PHP ARTISAN**: This is a WordPress environment.
    *   **NEVER** use `php artisan`.
    *   **ALWAYS** use the `acorn` command via WP-CLI (e.g., `wp acorn` or `ddev wp acorn`).
3.  **TAILWIND SPECIFICITY**: Use the `!` suffix (e.g., `bg-red-500!`) to override WordPress theme styles when necessary. See [Tailwind Specificity Skill](./tailwind-specificity/SKILL.md).
4.  **MODULAR SCOPING**: Livewire components must use the `slug::` prefix for isolation and interoperability. Blade UI components are global. See [Modular Architecture Skill](./modular-architecture/SKILL.md).
5.  **HIERARCHY & LOGS**: The Framework Core orchestrates Addons. Logs are scoped by name (e.g., `mi-test.log`) in the Core's storage. See [Hierarchy & Logs Skill](./hierarchy-and-logs/SKILL.md).
6.  **UI COMPONENT RECOGNITION**:
    *   Before editing any UI component, you **MUST** read its specific documentation in `./ui-components/ui/components/{name}/{name}.md`.
    *   Always use the `<x-ui.*>` prefix.

## **🛠️ TALL Stack Architecture (v4)**

*   **Blade & Alpine.js**: Use Alpine for client-side state.
*   **Livewire 4 (SFC)**: Functional components/SFCs only. No legacy Volt.
*   **Tailwind CSS 4**: Theme variables only (`--color-brand-600`). Configuration is in `resources/css/app.css`.

## **🔗 State Binding Pattern**

Every interactive component **MUST** implement the 3-layer pattern:
1.  **Blade**: Detect `wire:model`.
2.  **Alpine**: Init state via `$entangle` or `$root._x_model`.
3.  **Logic**: Handle mutations via the internal `_state` property.

## **📂 File Structure Reference**

*   **Views**: `resources/views/components/ui/`
*   **Docs**: `.agents/skills/`
*   **Styles**: `resources/css/app.css` (Tailwind 4 Entry)
*   **JS**: `resources/js/app.js`
