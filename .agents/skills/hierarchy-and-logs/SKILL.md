---
name: hierarchy-and-logs
description: Defines the hierarchical relationship between Core and Addons, and the component-scoped logging system.
---

# **Skill: Hierarchy & Logging System**

Defines the relationship between the Parent Framework (Core) and its child Addons, and how logging is handled.

## **1. Hierarchy: Core vs. Addons**

- **Parent Framework (Core)**: Orchestrator. Bootstraps Acorn, registers global UI components, and provides shared utilities (`slug: framework`).
- **Child Addons**: Feature extensions. They inherit services from the Core but maintain isolated namespaces for Livewire and business logic.

## **2. Logging System (Scoped Logs)**

Logs are stored segmented by component to facilitate debugging.

- **Location**: `wp-content/plugins/framework/storage/logs/`
- **Naming Convention**: `{scope}.log`
    - **Framework Logs**: `framework.log`
    - **Addon Logs**: `{addon-slug}.log` (e.g., `mi-test.log`)

### **Agent Instruction**
When debugging:
1. Identify the **Scope** (is it a core service or an addon feature?).
2. Check the corresponding log file in `storage/logs/`.
3. **NEVER** assume all errors go to `laravel.log` or `debug.log`.
