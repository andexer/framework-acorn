---
name: 'separator'
---

# Separator Component

## Introduction

The `separator` component provides a clean and accessible way to visually separate content sections. It supports both horizontal and vertical orientations, optional labels, and follows accessibility best practices. Perfect for dividing form sections, content areas, or creating visual breaks in your interface.

## Installation


```bash
```
## Basic Usage


@blade
<x-demo class="max-w-md">
    <div class="">
        <x-ui.card size="md" class="mx-auto">
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
        <x-ui.separator class="my-4" />
        <x-ui.card size="md" class="mx-auto">
            <x-ui.heading class="flex items-center justify-between mb-4" level="h3" size="sm">
                <span>Welcome to Converge.</span>
                <a href="https://convergeghp.com">
                    <x-ui.icon name="arrow-up-right" class="text-gray-800 dark:text-white size-4" />
                </a>
            </x-ui.heading>
            <p>
                Advanced documentation management framework for Laravel artisans  
            </p>
        </x-ui.card>
    </div>
</x-demo>
@endblade

```html
<div class="flex gap-4">
    <x-ui.card size="md" class="mx-auto">
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
    {+<x-ui.separator class="my-2" vertical />+}
    <x-ui.card size="md" class="mx-auto">
        <x-ui.heading class="flex items-center justify-between mb-4" level="h3" size="sm">
            <span>Welcome to Converge.</span>
            <a href="https://convergeghp.com">
                <x-ui.icon name="arrow-up-right" class="text-gray-800 dark:text-white size-4" />
            </a>
        </x-ui.heading>
        <p>
            Advanced documentation management framework for Laravel artisans  
        </p>
    </x-ui.card>
</div>
```



## Orientations

### Horizontal Separator (Default)

The default orientation creates a horizontal line to separate content vertically.

### Vertical Separator

Use vertical orientation to separate content horizontally.

@blade
<x-demo class="w-full">
    <div class="flex gap-4">
        <x-ui.card size="md" class="mx-auto flex-1">
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
        <x-ui.separator class="my-2" vertical />
        <x-ui.card size="md" class="mx-auto flex-1">
            <x-ui.heading class="flex items-center justify-between mb-4" level="h3" size="sm">
                <span>Welcome to Converge.</span>
                <a href="https://convergeghp.com">
                    <x-ui.icon name="arrow-up-right" class="text-gray-800 dark:text-white size-4" />
                </a>
            </x-ui.heading>
            <p>
                Advanced documentation management framework for Laravel artisans  
            </p>
        </x-ui.card>
    </div>
</x-demo>
@endblade

```html
<div class="flex gap-4">
        <x-ui.card size="md" class="mx-auto flex-1">
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
        {+<x-ui.separator class="my-2" vertical />+}
        <x-ui.card size="md" class="mx-auto flex-1">
            <x-ui.heading class="flex items-center justify-between mb-4" level="h3" size="sm">
                <span>Welcome to Converge.</span>
                <a href="https://convergeghp.com">
                    <x-ui.icon name="arrow-up-right" class="text-gray-800 dark:text-white size-4" />
                </a>
            </x-ui.heading>
            <p>
                Advanced documentation management framework for Laravel artisans  
            </p>
        </x-ui.card>
    </div>
```
> for spaces always use `gap` instead of `space-*`  utilities

## Labeled Separators

Add labels to provide context for the separation.

### Horizontal with Label

@blade
<x-demo class="max-w-md">
    <x-ui.fieldset>
    <div class="w-full text-center">
        <x-ui.button
            class="w-full"
            iconAfter="arrow-trending-up"
        >Log in with GitHub</x-ui.button>
    </div>
    <x-ui.separator label="Or"/>
    <div class="space-y-4">
        <x-ui.field>
            <x-ui.label>email address</x-ui.label>
            <x-ui.input
                wire:model.blur="form.email"
            />
            <x-ui.error name="form.email" />
        </x-ui.field>
        <x-ui.field>
            <x-ui.label>password</x-ui.label>
            <x-ui.input
                wire:model.blur="form.password"
                type='password'
                revealable
            />
            <x-ui.error name="form.password" />
        </x-ui.field>
    </div>
    <x-ui.button 
        class="w-full mt-4"
        type="submit"
    >
        Log in
    </x-ui.button>
    </x-ui.fieldset>
</x-demo>
@endblade

```html
<x-ui.separator label="OR" />
```

### Vertical with Label

@blade
<x-demo class="w-full">
    <div class="flex items-center justify-center gap-6 py-8">
        <div class="text-center">
            <h4 class="font-medium">Option A</h4>
            <p class="text-sm text-gray-600">First choice</p>
            <x-ui.button 
                class="w-full mt-4"
                iconAfter="arrow-trending-up"
                type="submit"
            >
                option A
            </x-ui.button>
        </div>  
        <x-ui.separator vertical label="OR" class="h-24" />
        <div class="text-center">
            <h4 class="font-medium">Option B</h4>
            <p class="text-sm text-gray-600">Second choice</p>
           <x-ui.button 
                class="w-full mt-4"
                iconAfter="arrow-trending-up"
                type="submit"
            >
                option B 
            </x-ui.button>
        </div>
    </div>
</x-demo>
@endblade

```html
<x-ui.separator vertical label="OR" />
```



## Component Props

| Prop Name | Type | Default | Required | Description |
|-----------|------|---------|----------|-------------|
| `vertical` | boolean | `false` | No | Whether to display as vertical separator |
| `variant` | string | `null` | No | Visual variant (reserved for future use) |
| `label` | string | `null` | No | Optional label text to display |
| `class` | string | `''` | No | Additional CSS classes |

## Examples

