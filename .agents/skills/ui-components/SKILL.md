---
name: ui-components
description: Guía de uso y referencia de la biblioteca de componentes UI del framework (Blade + Alpine + Tailwind).
---

# **Skill: UI Components (Framework Core)**

Esta skill define el sistema de diseño y la biblioteca de componentes disponibles en el framework.

## **Filosofía de Diseño**

- Los componentes son totalmente personalizables y siguen estrictamente el sistema de diseño del framework.
- Todo es Blade puro, Alpine.js y Tailwind CSS v4.
- Sin pasos de compilación complejos para el uso de componentes.

## **Uso de Componentes**

Todos los componentes de UI se encuentran en `resources/views/components/ui/` y se invocan con el prefijo `<x-ui.*>`.

### **Ejemplo Básico**

```html
<x-ui.button variant="primary" size="lg">
    Click me
</x-ui.button>

<x-ui.card>
    <x-slot name="header">Título de la Tarjeta</x-slot>
    El contenido va aquí.
</x-ui.card>
```

## **Componentes Disponibles**

| Componente | Descripción |
| :--- | :--- |
| `icon` | Soporta múltiples librerías con estilizado consistente. |
| `button` | Variantes: primary, secondary, destructive, ghost. |
| `card` | Secciones: header, content, footer. |
| `heading` | Escala tipográfica H1 a H6. |
| `input` / `textarea` | Soporte para validación y auto-resize. |
| `dropdown` | Accesible con navegación por teclado. |
| `modal` / `popover` | Sistemas de superposición (overlays). |
| `tabs` / `accordion` | Organización de contenido colapsable. |
| `alert` / `toast` | Notificaciones y mensajes de estado. |
| `field` | Unidad completa: Label + Input + Error. |
| `avatar` | Representación de usuario con iniciales o imagen. |
| `skeleton` | Placeholders de carga. |

*Para más detalles sobre un componente específico, consulta `.agents/skills/ui/components/{nombre}/{nombre}.md`.*
