---
name: modular-architecture
description: Defines the namespace and scoping system for Livewire components and views between the Core and Addons.
---

# **Skill: Modular Architecture & Scoping**

This repository utilizes a modular architecture where a "Framework Core" manages multiple "Addons" (WordPress plugins).

## **1. Blade Component Scoping (Global)**

UI components are shared globally to maintain design consistency.

*   **Syntax**: `<x-ui.component-name />`
*   **Location**: `resources/views/components/ui/` in the Core.
*   **Usage**: All addons "inherit" these components and can use them without prefixes.

## **2. Livewire Component Scoping (Modular)**

To avoid naming collisions, Livewire components are **namespaced** using the addon's slug.

### **Syntax and Interoperability**

| Target | Syntax | Description |
| :--- | :--- | :--- |
| **Core Component** | `<livewire:name />` | Global components from the Framework Core. |
| **Current Addon** | `<livewire:current-slug::name />` | Components from the addon you are currently working on. |
| **Cross-Addon** | `<livewire:other-slug::name />` | Components from another addon (key for interoperability). |

## **3. View Scoping**

When rendering views from an addon, always use the addon's namespace:
*   **PHP**: `view('addon-slug::path.to.view')`
*   **Blade**: `@include('addon-slug::partials.header')`
