---
name: tooltip
---

## Introduction

The `Tooltip` component is a lightweight, accessible overlay component that provides contextual information or helpful hints when users interact with elements. It offers flexible positioning, multiple trigger variants, and seamless dark mode support.


## Installation


```bash
```

> Once installed, you can use the <x-ui.tooltip /> and <x-ui.tooltip.content /> components in any Blade view.

## Usage

### Basic Tooltip

@blade
<x-demo>
    <div class="w-full flex justify-center py-8">
        <x-ui.tooltip>
            <x-slot:trigger>
                <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Hover me
                </button>
            </x-slot:trigger>
            <x-ui.tooltip.content>
                This is a helpful tooltip message
            </x-ui.tooltip.content>
        </x-ui.tooltip>
    </div>
</x-demo>
@endblade

```html
<x-ui.tooltip>
    <x-slot:trigger>
        <button>Hover me</button>
    </x-slot:trigger>
    <x-ui.tooltip.content>
        This is a helpful tooltip message
    </x-ui.tooltip.content>
</x-ui.tooltip>
```

### Icon with Tooltip

Perfect for adding contextual help to form fields, headings, or interface elements.

@blade
<x-demo>
    <div class="w-full py-8">
        <h3 class="text-lg font-semibold flex items-center gap-x-2">
            Tax identification number
            <x-ui.tooltip placement="right" variant="button">
                <x-slot:trigger>
                    <x-ui.icon name="information-circle" class="h-5 w-5 text-gray-500" />
                </x-slot:trigger>
                <x-ui.tooltip.content>
                    Enter your federal tax ID or SSN for tax reporting purposes
                </x-ui.tooltip.content>
            </x-ui.tooltip>
        </h3>
    </div>
</x-demo>
@endblade

```html
<h3 class="text-lg font-semibold flex items-center gap-x-2">
    Tax identification number
    <x-ui.tooltip placement="right" variant="button">
        <x-slot:trigger>
            <x-ui.icon name="information-circle" class="h-5 w-5 text-gray-500" />
        </x-slot:trigger>
        <x-ui.tooltip.content>
            Enter your federal tax ID or SSN for tax reporting purposes
        </x-ui.tooltip.content>
    </x-ui.tooltip>
</h3>
```

### Different Placements

The tooltip can be positioned in four directions relative to the trigger element.

@blade
<x-demo>
    <div class="w-full py-16">
        <div class="grid grid-cols-2 gap-8 max-w-md mx-auto">
            <!-- Top -->
            <div class="flex justify-center">
                <x-ui.tooltip placement="top">
                    <x-slot:trigger>
                        <button class="p-2 bg-gray-100 rounded hover:bg-gray-200">
                            <x-ui.icon name="cog-6-tooth" class="h-5 w-5" />
                        </button>
                    </x-slot:trigger>
                    <x-ui.tooltip.content>Top placement</x-ui.tooltip.content>
                </x-ui.tooltip>
            </div>

            <!-- Right -->
            <div class="flex justify-center">
                <x-ui.tooltip placement="right">
                    <x-slot:trigger>
                        <button class="p-2 bg-gray-100 rounded hover:bg-gray-200">
                            <x-ui.icon name="cog-6-tooth" class="h-5 w-5" />
                        </button>
                    </x-slot:trigger>
                    <x-ui.tooltip.content>Right placement</x-ui.tooltip.content>
                </x-ui.tooltip>
            </div>

            <!-- Left -->
            <div class="flex justify-center">
                <x-ui.tooltip placement="left">
                    <x-slot:trigger>
                        <button class="p-2 bg-gray-100 rounded hover:bg-gray-200">
                            <x-ui.icon name="cog-6-tooth" class="h-5 w-5" />
                        </button>
                    </x-slot:trigger>
                    <x-ui.tooltip.content>Left placement</x-ui.tooltip.content>
                </x-ui.tooltip>
            </div>

            <!-- Bottom -->
            <div class="flex justify-center">
                <x-ui.tooltip placement="bottom">
                    <x-slot:trigger>
                        <button class="p-2 bg-gray-100 rounded hover:bg-gray-200">
                            <x-ui.icon name="cog-6-tooth" class="h-5 w-5" />
                        </button>
                    </x-slot:trigger>
                    <x-ui.tooltip.content>Bottom placement</x-ui.tooltip.content>
                </x-ui.tooltip>
            </div>
        </div>
    </div>
</x-demo>
@endblade

```html
<!-- Top placement -->
<x-ui.tooltip placement="top">
    <x-slot:trigger>
        <button><x-ui.icon name="cog-6-tooth" /></button>
    </x-slot:trigger>
    <x-ui.tooltip.content>Settings</x-ui.tooltip.content>
</x-ui.tooltip>

<!-- Right placement -->
<x-ui.tooltip placement="right">
    <x-slot:trigger>
        <button><x-ui.icon name="cog-6-tooth" /></button>
    </x-slot:trigger>
    <x-ui.tooltip.content>Settings</x-ui.tooltip.content>
</x-ui.tooltip>

<!-- Bottom placement -->
<x-ui.tooltip placement="bottom">
    <x-slot:trigger>
        <button><x-ui.icon name="cog-6-tooth" /></button>
    </x-slot:trigger>
    <x-ui.tooltip.content>Settings</x-ui.tooltip.content>
</x-ui.tooltip>

<!-- Left placement -->
<x-ui.tooltip placement="left">
    <x-slot:trigger>
        <button><x-ui.icon name="cog-6-tooth" /></button>
    </x-slot:trigger>
    <x-ui.tooltip.content>Settings</x-ui.tooltip.content>
</x-ui.tooltip>
```

### Button Variant

Use the button variant when you want the tooltip to appear on click instead of hover. This is particularly useful for touch devices or when you need more deliberate interaction.

@blade
<x-demo>
    <div class="w-full flex justify-center py-8">
        <x-ui.tooltip variant="button" placement="top">
            <x-slot:trigger>
                <x-ui.icon name="question-mark-circle" class="h-6 w-6 text-blue-500 cursor-pointer" />
            </x-slot:trigger>
            <x-ui.tooltip.content>
                Click the icon to see this tooltip
            </x-ui.tooltip.content>
        </x-ui.tooltip>
    </div>
</x-demo>
@endblade

```html
<x-ui.tooltip variant="button" placement="top">
    <x-slot:trigger>
        <x-ui.icon name="question-mark-circle" class="h-6 w-6 text-blue-500 cursor-pointer" />
    </x-slot:trigger>
    <x-ui.tooltip.content>
        Click the icon to see this tooltip
    </x-ui.tooltip.content>
</x-ui.tooltip>
```

### Custom Styling

You can customize the tooltip content appearance by passing additional classes to the content component.

@blade
<x-demo>
    <div class="w-full flex justify-center py-8">
        <x-ui.tooltip placement="top">
            <x-slot:trigger>
                <button class="px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600">
                    Custom tooltip
                </button>
            </x-slot:trigger>
            <x-ui.tooltip.content class="bg-purple-600 text-white border border-purple-500">
                This tooltip has custom styling
            </x-ui.tooltip.content>
        </x-ui.tooltip>
    </div>
</x-demo>
@endblade

```html
<x-ui.tooltip placement="top">
    <x-slot:trigger>
        <button>Custom tooltip</button>
    </x-slot:trigger>
    <x-ui.tooltip.content class="bg-purple-600 text-white border border-purple-500">
        This tooltip has custom styling
    </x-ui.tooltip.content>
</x-ui.tooltip>
```

## Component Props Reference

### Tooltip Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `placement` | `string` | `'top'` | Position of the tooltip relative to trigger: `'top'`, `'bottom'`, `'left'`, `'right'` |
| `variant` | `string` | `'default'` | Interaction variant: `'default'` (hover/focus) or `'button'` (click) |

### Tooltip Content Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `placement` | `string` | Inherited | Position inherited from parent tooltip component |

### Inherited Props

The tooltip content component automatically inherits the `placement` prop from its parent tooltip component, ensuring proper positioning.
