# **Skill: Modular Architecture & Scoping**

This repo uses a modular architecture where a "Framework Core" manages multiple "Addons" (WordPress plugins).

## **1. Blade Component Scoping (Global)**

UI components are shared globally across the Core and all Addons to maintain design consistency.

*   **Syntax**: `<x-ui.component-name />`
*   **Location**: `resources/views/components/ui/` in the Core framework.
*   **Usage**: All addons "inherit" these components and can use them without prefixes.

## **2. Livewire Component Scoping (Modular)**

To prevent naming collisions between multiple addons (e.g., two addons having a "Counter" component), Livewire components are **namespaced** using the addon's slug.

### **Syntax & Interoperability**

| Target | Syntax | Description |
|--------|--------|-------------|
| **Core Component** | `<livewire:name />` | Global components defined in the Framework Core. |
| **Current Addon** | `<livewire:current-slug::name />` | Components defined in the addon you are currently working on. |
| **Cross-Addon** | `<livewire:other-slug::name />` | Components defined in a different addon. This is the key for interoperability. |

### **Example**
If you are working on `addon-1` and want to use a component from `addon-2`:
```html
<div>
    <!-- Core component -->
    <livewire:counter /> 

    <!-- Local component -->
    <livewire:addon-1::profile-card />

    <!-- Interoperability: Component from another addon -->
    <livewire:addon-2::stats-chart />
</div>
```

## **3. View Scoping**

When rendering views from an addon, always use the addon's namespace:
*   **PHP**: `view('addon-slug::path.to.view')`
*   **Blade**: `@include('addon-slug::partials.header')`
