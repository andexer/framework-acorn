# Overview

**My Components UI** is a modern component library and design system built specifically for the **TALL stack**: Tailwind CSS, Alpine.js, Laravel, and Livewire, and even raw **Blade** with alpinejs reactivity.

It follows a **copy-paste** philosophy for full control over your UI.

* **Zero dependencies** (for most components)
* **Real Laravel integration**
* **Full ownership over your UI**
* **Reactive Blade components that scale**

## Philosophy

### Own Your Code

Every component lives in **your codebase**, not a vendor package.
No version conflicts, no dependency hell, no hidden behavior.
You own it. You control it. You modify it. You ship it.

### Design System Excellence

- Components come fully documented and customizable.
- Everything is plain Blade, Alpine, and Tailwind, nothing more.
- No build step. No lock-in. No surprises.

## Architecture

### Component Anatomy

Each component includes:

* **Blade templates** for markup and structure
* **Alpine logic** for client-side behavior
* **Design tokens and variants** for theme customization
* **Clear docs** for usage, props, slots, and integration

Example structure:

```
resources/views/components/ui/
├── button/index.blade.php
├── card/index.blade.php
└── dropdown/
    ├── index.blade.php
    └── item.blade.php
```

### Design System

Includes a fully themeable system with:

* Custom color palettes, spacing, and typography
* Utility-first design with consistency baked in

## Integration Patterns

* Designed to work **seamlessly with Laravel's Blade components**
* Built with **Alpine** and **Livewire compatibility**

## Usage in Blade

```blade
<x-ui.button variant="primary" size="lg">
    Click me
</x-ui.button>

<x-ui.card>
    <x-slot name="header">Card Title</x-slot>
    Card content goes here.
</x-ui.card>
```
