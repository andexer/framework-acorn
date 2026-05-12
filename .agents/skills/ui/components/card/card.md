---
name: card
---

## Introduction

The `Card` component is a **responsive**, **unstyled** container component designed for content encapsulation. It provides a clean foundation for building UI sections with consistent spacing, subtle borders, and seamless dark mode support.

## Installation

```bash
```

> Once installed, you can use the <x-ui.card /> component in any Blade view.




## Usage

@blade
<x-demo>
    <div class="w-full">
        <x-ui.card size="xl" class="mx-auto">
            <x-ui.heading class="flex items-center justify-between mt-0 mb-4 leading-0 " level="h3" size="sm">
                <span>Welcome to ui.</span>
                <a href="https://ui.dev">
                    <x-ui.icon name="arrow-up-right" class="text-gray-800 dark:text-white size-4" />
                </a>
            </x-ui.heading>
            <p>
                Powered by the TALL stack, our components offer speed, </br> elegance, and accessibility for modern web development. 
            </p>
        </x-ui.card>
    </div>
</x-demo>
@endblade

```html
    <x-ui.card size="xl" class="mx-auto">
        <x-ui.heading class="flex items-center justify-between mb-4" level="h3" size="sm">
            <span>Welcome to My Components UI.</span>
            <a href="https://ui.dev">
                <x-ui.icon name="arrow-up-right" class="text-gray-800 dark:text-white size-4" />
            </a>
        </x-ui.heading>
        <p>
            Powered by the TALL stack, our components offer speed, elegance,
             and accessibility for modern web development. 
        </p>
    </x-ui.card>
```

## Customization

#### Size
The `size` prop controls the max width of the card and helps enforce layout constraints in a flexible way. Default is `md`.

@blade
<x-demo>
    <div class="w-full space-y-2">
        <x-ui.card size="xs">Extra Small Card</x-ui.card>
        <x-ui.card size="sm">Small Card</x-ui.card>
        <x-ui.card size="md">Medium Card</x-ui.card>
        <x-ui.card size="lg">Large Card</x-ui.card>
        <x-ui.card size="xl">Extra Large Card</x-ui.card>
    </div>
</x-demo>
@endblade

```html
<x-ui.card size="xs">Extra Small Card</x-ui.card>
<x-ui.card size="sm">Small Card</x-ui.card>
<x-ui.card size="md">Medium Card</x-ui.card>
<x-ui.card size="lg">Large Card</x-ui.card>
<x-ui.card size="xl">Extra Large Card</x-ui.card>
```

## Component Props

| Prop Name | Type                 | Default | Required | Description                                                                                            |
| --------- | ---------------------| ------- | -------- | ------------------------------------------------------------------------------------------------------ |
| `slot`    | string or components | `''`    | No       | Content to display within the card. Can include headings, text, forms, buttons, and other components.  |
| `size`    | string               | `md`    | No       | Sets max-width constraint (`xs`, `sm`, `md`, `lg`, `xl`)                                               |
| `class`   | string               | `''`    | No       | Additional Tailwind classes applied to the card container                                              |
