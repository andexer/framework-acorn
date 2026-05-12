# Theming System

My Components UI is designed to be minimal and sharp. The theming system lets you align every component with your brand using CSS variables.

## Core Idea

Themes are powered by **two main color families** and **two radius controls**.

### Color Roles

| Variable                  | Purpose                                                 |
| ------------------------- | ------------------------------------------------------- |
| `--color-primary`         | Your main brand color (buttons, links, active states).  |
| `--color-primary-content` | Slightly darker/lighter variant for better readability. |
| `--color-primary-fg`      | Text color that sits on top of primary backgrounds.     |

**Base color** covers neutral backgrounds, borders, and text.
**Primary color** handles interactive elements and brand accents.

## Setting Your Primary Colors

We use CSS variables so you can change the theme without touching component code.

```css
/* resources/css/app.css */
@theme {
    --color-primary: var(--color-brand-600);
    --color-primary-content: var(--color-brand-600);
    --color-primary-fg: var(--color-white);

    --radius-field: 0.25rem;
    --radius-box: 0.5rem;
}
```

## Controlling Roundness

Two variables control all corner radii:

| Variable         | Affects                         |
| ---------------- | ------------------------------- |
| `--radius-field` | Inputs and small elements       |
| `--radius-box`   | Cards, modals, large containers |

Examples:

```css
/* Soft and friendly */
@theme {
    --radius-field: 0.5rem;
    --radius-box: 1rem;
}

/* Sharp and minimal */
@theme {
    --radius-field: 0rem;
    --radius-box: 0rem;
}
```
