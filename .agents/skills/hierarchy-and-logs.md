# **Skill: Hierarchy & Logging System**

This document explains the relationship between the Parent Framework and its child Addons, and how logging is handled across the ecosystem.

## **1. The Hierarchy: Core vs. Addons**

The project is structured as a centralized Core that provides services to isolated Addons.

*   **Parent Framework (Core)**: 
    *   **Slug**: `framework`
    *   **Role**: Orchestrator. Bootstraps Acorn, registers global UI components, and provides shared utilities.
    *   **Location**: `wp-content/plugins/framework/`
*   **Child Addons**:
    *   **Slug**: Custom (e.g., `mi-test`, `otro-addon`).
    *   **Role**: Feature-specific extensions. They inherit the Core's UI components and services but maintain isolated Livewire namespaces and business logic.
    *   **Location**: `wp-content/plugins/{slug}/`

## **2. Logging System (Scoped Logs)**

To ensure easy debugging and isolation, logs are not centralized in a single file. Instead, they are scoped by component.

*   **Log Location**: All logs are stored in the Core's storage: `wp-content/plugins/framework/storage/logs/`
*   **Naming Convention**: `{scope}.log`
    *   **Framework Logs**: `framework.log`
    *   **Addon Logs**: `{addon-slug}.log` (e.g., `mi-test.log`)

### **Instruction for the Agent**
When debugging an error:
1.  Identify the **Scope** (Is it a core service or an addon feature?).
2.  Check the corresponding log file in the `storage/logs/` directory.
3.  **NEVER** assume all errors go to `laravel.log` or `debug.log`.
