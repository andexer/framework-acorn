---
name: modular-architecture
description: Define el sistema de namespaces y scoping para componentes Livewire y vistas entre el Core y los Addons.
---

# **Skill: Modular Architecture & Scoping**

Este repositorio utiliza una arquitectura modular donde un "Framework Core" gestiona múltiples "Addons" (plugins de WordPress).

## **1. Blade Component Scoping (Global)**

Los componentes de UI se comparten globalmente para mantener la consistencia del diseño.

*   **Sintaxis**: `<x-ui.component-name />`
*   **Ubicación**: `resources/views/components/ui/` en el Core.
*   **Uso**: Todos los addons "heredan" estos componentes y pueden usarlos sin prefijos.

## **2. Livewire Component Scoping (Modular)**

Para evitar colisiones de nombres, los componentes de Livewire están **namespaced** usando el slug del addon.

### **Sintaxis e Interoperabilidad**

| Objetivo | Sintaxis | Descripción |
| :--- | :--- | :--- |
| **Componente Core** | `<livewire:name />` | Componentes globales del Framework Core. |
| **Addon Actual** | `<livewire:current-slug::name />` | Componentes del addon en el que estás trabajando. |
| **Cross-Addon** | `<livewire:other-slug::name />` | Componentes de otro addon (clave para la interoperabilidad). |

## **3. View Scoping**

Al renderizar vistas desde un addon, usa siempre el namespace del addon:
*   **PHP**: `view('addon-slug::path.to.view')`
*   **Blade**: `@include('addon-slug::partials.header')`
