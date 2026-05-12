---
name: heading
---

## Introduction

The `Heading` component is a **flexible**, **semantic** heading component designed for consistent typography hierarchy. It provides responsive font sizing, proper semantic HTML structure, and dark mode support while maintaining accessibility standards.

Perfect for creating clear content hierarchy without the hassle of managing font sizes and semantic levels across your application.

---

## Installation

```bash
```

> Once installed, you can use the <x-ui.heading /> component in any Blade view.

## Usage

@blade
<x-demo>
    <div class="w-full flex flex-col gap-y-1 py-4">
        <x-components::ui.heading level="h1" size="xl" class="text-gray-900 dark:text-white text-2xl">
            Main Page Title
        </x-components::ui.heading>
        <x-components::ui.heading level="h2" size="lg" class="text-gray-800 dark:text-gray-100 text-xl">
            Section Heading
        </x-components::ui.heading>
        <x-components::ui.heading level="h3" size="md" class="text-gray-700 dark:text-gray-200 text-lg ">
            Subsection Title
        </x-components::ui.heading>
    </div> 
</x-demo>
@endblade

```html
    <x-ui.heading level="h1" size="xl">Main Page Title</x-ui.heading>
    <x-ui.heading level="h2" size="lg">Section Heading</x-ui.heading>
    <x-ui.heading level="h3" size="md">Subsection Title</x-ui.heading>
```

## Customization

#### Size
The `size` prop controls the visual size of the heading independent of its semantic level. Default is `md`.

@blade
<x-demo>
    <div class="w-full space-y-2">
        <x-components::ui.heading size="xs">Extra Small heading</x-components::ui.heading>
        <x-components::ui.heading size="sm">Small heading</x-components::ui.heading>
        <x-components::ui.heading size="md">Medium heading</x-components::ui.heading>
        <x-components::ui.heading size="lg">Large heading</x-components::ui.heading>
        <x-components::ui.heading size="xl">Extra Large heading</x-components::ui.heading>
    </div>
</x-demo>
@endblade

```html
<x-ui.heading size="xs">Extra Small heading</x-ui.heading>
<x-ui.heading size="sm">Small heading</x-ui.heading>
<x-ui.heading size="md">Medium heading</x-ui.heading>
<x-ui.heading size="lg">Large heading</x-ui.heading>
<x-ui.heading size="xl">Extra Large heading</x-ui.heading>
```


#### Semantic Levels
Use the `level` prop to set the appropriate HTML heading tag for proper document structure and accessibility. Default is `h2`.

@blade
<x-demo>
    <div class="w-full space-y-3">
        <x-components::ui.heading level="h1" size="lg" class="text-gray-900 dark:text-white">H1 - Page Title</x-components::ui.heading>
        <x-components::ui.heading level="h2" size="md" class="text-gray-800 dark:text-gray-100">H2 - Section Title</x-components::ui.heading>
        <x-components::ui.heading level="h3" size="sm" class="text-gray-700 dark:text-gray-200">H3 - Subsection Title</x-components::ui.heading>
        <x-components::ui.heading level="h4" size="sm" class="text-gray-600 dark:text-gray-300">H4 - Sub-subsection Title</x-components::ui.heading>
        <x-components::ui.heading level="h5" size="xs" class="text-gray-600 dark:text-gray-300">H5 - Minor Heading</x-components::ui.heading>
        <x-components::ui.heading level="h6" size="xs" class="text-gray-500 dark:text-gray-400">H6 - Smallest Heading</x-components::ui.heading>
    </div>
</x-demo>
@endblade

```html
<x-ui.heading level="h1" size="lg">H1 - Page Title</x-ui.heading>
<x-ui.heading level="h2" size="md">H2 - Section Title</x-ui.heading>
<x-ui.heading level="h3" size="sm">H3 - Subsection Title</x-ui.heading>
<x-ui.heading level="h4" size="sm">H4 - Sub-subsection Title</x-ui.heading>
<x-ui.heading level="h5" size="xs">H5 - Minor Heading</x-ui.heading>
<x-ui.heading level="h6" size="xs">H6 - Smallest Heading</x-ui.heading>
```

## Component Props

| Prop Name | Type   | Default | Required | Description                                               |
| --------- | ------ | ------- | -------- | --------------------------------------------------------- |
| `size`    | string | `sm`    | No       | Sets max-width constraint (`xs`, `sm`, `md`, `lg`, `xl`)  |
| `level`   | string | `h2`    | No       | set the heading tag (from `h2` to `h6`)                   |
| `class`   | string | `''`    | No       | Additional Tailwind classes applied to the card container |
