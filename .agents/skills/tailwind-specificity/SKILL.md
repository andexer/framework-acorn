---
name: tailwind-specificity
description: Handles Tailwind v4 specificity in WordPress environments using the importance suffix and strict sizing patterns.
---

# **Skill: Tailwind v4 Specificity (Importance Suffix)**

When working in WordPress environments, active theme styles or external plugins can overwrite Tailwind CSS v4 utilities due to higher CSS specificity.

## **Instruction**

When the user requests "unbreakable styles" or when you encounter styling conflicts with the active WordPress theme, you **MUST** apply the importance suffix (`!`) at the **end** of each utility class.

### **Rules**
1.  **Suffix Placement**: Place the `!` at the end of the utility name (Tailwind v4 syntax).
    *   **Correct**: `bg-red-500!`
    *   **Incorrect**: `!bg-red-500`
2.  **Interactivity**: Apply it to pseudo-selectors and states as well.
    *   **Example**: `hover:bg-blue-600!`, `focus:ring-2!`, `md:text-lg!`
3.  **Scope**: Use it only when requested or when standard utilities are being ignored by the browser due to cascade conflicts.

## **Example Usage**

```html
<!-- Standard (May be overwritten by the theme) -->
<div class="bg-white p-4 text-gray-900">...</div>

<!-- Unbreakable (Wins the cascade) -->
<div class="bg-white! p-4! text-gray-900!">...</div>
```

## **Strict Sizing (Layout Integrity)**

In highly restrictive WordPress themes, simple sizing classes (like `w-8 h-8`) might be ignored or partially overwritten. To ensure total layout integrity, you **MUST** apply the "Strict Sizing" pattern:

**Pattern**: Use `w-{n}! h-{n}! min-w-{n}! min-h-{n}! max-w-{n}! max-h-{n}!` simultaneously.

### **Example**
```html
<!-- Strict 32px (8 units) square button -->
<button class="w-8! h-8! min-w-8! min-h-8! max-w-8! max-h-8! p-0!">...</button>
```

Use this pattern for icons, small buttons, and fixed-size avatars to prevent the theme from "stretching" or "shrinking" your UI components.
