---
name: hierarchy-and-logs
description: Define la relación jerárquica entre el Core y los Addons, y el sistema de logs segmentado por componente.
---

# **Skill: Hierarchy & Logging System**

Define la relación entre el Framework Padre y sus Addons hijos, y cómo se maneja el registro de logs.

## **1. Jerarquía: Core vs. Addons**

- **Parent Framework (Core)**: Orquestador. Inicia Acorn, registra componentes UI globales y provee utilidades compartidas (`slug: framework`).
- **Child Addons**: Extensiones de funcionalidades. Heredan servicios del Core pero mantienen namespaces aislados para Livewire y lógica de negocio.

## **2. Sistema de Logs (Logs Segmentados)**

Los logs se almacenan segmentados por componente para facilitar la depuración.

- **Ubicación**: `wp-content/plugins/framework/storage/logs/`
- **Convención de Nombres**: `{scope}.log`
    - **Logs del Framework**: `framework.log`
    - **Logs de Addons**: `{addon-slug}.log` (ej: `mi-test.log`)

### **Instrucción para el Agente**
Al depurar:
1. Identifica el **Scope** (¿es un servicio core o una funcionalidad de addon?).
2. Revisa el archivo de log correspondiente en `storage/logs/`.
3. **NUNCA** asumas que todos los errores van a `laravel.log` o `debug.log`.
