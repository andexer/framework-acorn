# **Skill: Tailwind v4 Specificity (Importance Suffix)**

When working in WordPress environments, theme styles or external plugins may overwrite Tailwind CSS v4 utilities due to higher CSS specificity.

## **Instruction**

When the user requests "unbreakable styles" or when you encounter styling conflicts with the active WordPress theme, you **MUST** apply the importance suffix (`!`) at the **end** of every utility class.

### **Rules**
1.  **Suffix Placement**: Place the `!` at the very end of the utility name (Tailwind v4 syntax).
    *   **Correct**: `bg-red-500!`
    *   **Incorrect**: `!bg-red-500`
2.  **Interactivity**: Apply it to pseudo-selectors and states as well.
    *   **Example**: `hover:bg-blue-600!`, `focus:ring-2!`, `md:text-lg!`
3.  **Scope**: Use this only when requested or when standard utilities are being ignored by the browser due to cascade conflicts.

## **Example Usage**

```html
<!-- Standard (May be overwritten by theme) -->
<div class="bg-white p-4 text-gray-900">...</div>

<!-- Unbreakable (Wins the cascade) -->
<div class="bg-white! p-4! text-gray-900!">...</div>
```
