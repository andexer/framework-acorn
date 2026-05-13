---
name: ui-components
description: Usage guide and reference for the framework's UI component library (Blade + Alpine + Tailwind).
---

# **Skill: UI Components (Framework Core)**

This skill defines the design system and the component library available in the framework.

## **Design Philosophy**

- Components are fully customizable and strictly follow the framework's design system.
- Everything is pure Blade, Alpine.js, and Tailwind CSS v4.
- No complex build steps required for using components.

## **Using Components**

All UI components are located in `resources/views/components/ui/` and are invoked using the `<x-ui.*>` prefix.

### **Basic Example**

```html
<x-ui.button variant="primary" size="lg">
    Click me
</x-ui.button>

<x-ui.card>
    <x-slot name="header">Card Title</x-slot>
    The content goes here.
</x-ui.card>
```

## **Available Components**

| Component | Description |
| :--- | :--- |
| `icon` | Supports multiple icon libraries with consistent styling. |
| `button` | Variants: primary, secondary, destructive, ghost. |
| `card` | Sections: header, content, footer. |
| `heading` | Typographic scale H1 to H6. |
| `input` / `textarea` | Support for validation and auto-resize. |
| `dropdown` | Accessible with keyboard navigation. |
| `modal` / `popover` | Overlay systems. |
| `tabs` / `accordion` | Collapsible content organization. |
| `alert` / `toast` | Notifications and status messages. |
| `field` | Complete unit: Label + Input + Error. |
| `avatar` | User representation with initials or image. |
| `skeleton` | Loading placeholders. |

*For more details about a specific component, check `.agents/skills/ui-components/ui/components/{name}/{name}.md`.*
