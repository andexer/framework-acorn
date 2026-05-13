---
name: 'text'
---

# Text Component

## Introduction

The `text` component provides a simple and consistent way to display text content with proper styling and theme support, and serves as a foundational text element for consistent typography across your application.

## Installation


```bash
```

## Basic Usage

@blade
<x-demo class="flex justify-center">
    <x-ui.text>
        This is a basic text component with default styling.
    </x-ui.text>
</x-demo>
@endblade

```html
<x-ui.text>
    This is a basic text component with default styling.
</x-ui.text>
```

## Text Variations

### Default Text

@blade
<x-demo class="flex justify-center">
    <div class="space-y-2">
        <x-ui.text>
            Standard text with default small size and neutral colors.
        </x-ui.text>
        <x-ui.text>
            Perfect for body text, descriptions, and general content.
        </x-ui.text>
    </div>
</x-demo>
@endblade

```html
<x-ui.text>
    Standard text with default small size and neutral colors.
</x-ui.text>
```

### Custom Sized Text

@blade
<x-demo class="flex justify-center">
    <div class="space-y-3">
        <x-ui.text class="text-xs">
            Extra small text content
        </x-ui.text>
        <x-ui.text>
            Default small text content
        </x-ui.text>
        <x-ui.text class="text-base">
            Base sized text content
        </x-ui.text>
        <x-ui.text class="text-lg">
            Large sized text content
        </x-ui.text>
        <x-ui.text class="text-xl">
            Extra large text content
        </x-ui.text>
    </div>
</x-demo>
@endblade

```html
<x-ui.text class="text-xs">Extra small text content</x-ui.text>
<x-ui.text>Default small text content</x-ui.text>
<x-ui.text class="text-base">Base sized text content</x-ui.text>
<x-ui.text class="text-lg">Large text content</x-ui.text>
<x-ui.text class="text-xl">Extra large text content</x-ui.text>
```


## Styling Variations

### Muted Text

@blade
<x-demo class="flex justify-center">
    <div class="space-y-2">
        <x-ui.text>
            Regular text content
        </x-ui.text>
        <x-ui.text class="opacity-75">
            Slightly muted text content
        </x-ui.text>
        <x-ui.text class="opacity-50">
            More muted text content
        </x-ui.text>
    </div>
</x-demo>
@endblade

```html
<x-ui.text>Regular text content</x-ui.text>
<x-ui.text class="opacity-75">Slightly muted text content</x-ui.text>
<x-ui.text class="opacity-50">More muted text content</x-ui.text>
```

### Emphasized Text

@blade
<x-demo class="flex justify-center">
    <div class="space-y-2">
        <x-ui.text class="font-medium">
            Medium weight text
        </x-ui.text>
        <x-ui.text class="font-semibold">
            Semibold text
        </x-ui.text>
        <x-ui.text class="italic">
            Italic text
        </x-ui.text>
        <x-ui.text class="uppercase tracking-wide">
            Uppercase text
        </x-ui.text>
    </div>
</x-demo>
@endblade

```html
<x-ui.text class="font-medium">Medium weight text</x-ui.text>
<x-ui.text class="font-semibold">Semibold text</x-ui.text>
<x-ui.text class="italic">Italic text</x-ui.text>
<x-ui.text class="uppercase tracking-wide">Uppercase text</x-ui.text>
```

## Component Props

| Prop Name | Type | Default | Required | Description |
|-----------|------|---------|----------|-------------|
| `class` | string | `''` | No | Additional CSS classes |

## Default Styles

The text component comes with these default styles:

- **Color**: `text-neutral-950` (dark) / `text-neutral-50` (light mode)
- **Size**: `text-sm` (small text)

