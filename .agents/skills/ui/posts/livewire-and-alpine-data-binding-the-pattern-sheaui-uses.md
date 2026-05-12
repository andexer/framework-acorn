---
id: two-way-data-bindings
title: Building Reusable TALL Stack Components with wire:model & x-model
slug: building-reusable-tall-stack-components-with-wire-model-x-model
excerpt: Learn the exact pattern for building Blade components that work natively with both wire:model and x-model. Includes x-modelable tutorial and advanced entanglement techniques for complex Laravel components.
author: My Components UI Team
created_at: 17-11-2025
published_at: 18-11-2025  
category: advanced techniques
---

# Master Two-Way Data Binding for Universal TALL Stack Components

@blade
<x-md.callout title="Notice">
    This guide outlines the core architecture used in My Components UI. We've evolved through different patterns to make Blade components feel native with both Livewire and Alpine.js. This is our standard for robust, reactive components.
</x-md.callout>
@endblade


**What if there was a pattern that makes your components work with both frameworks automatically, feeling native to each?**

This is the universal two-way data binding system used in our components:
- Livewire's `wire:model`
- Livewire's `.live` modifier
- Alpine's `x-model`
- Pure Alpine apps (no Livewire at all)
- Hybrid setups (Livewire + Alpine)
 
The user doesn't have to change a single line of code. They just use `wire:model` or `x-model`, and it works.

## The Problem We're Solving

Let's say you're building a custom toggle component. Users want to use it like this:

```blade
<!-- In a Livewire component -->
<x-ui.toggle wire:model="isActive" />

<!-- In a pure Alpine app -->
<div x-data="{ isActive: false }">
    <x-ui.toggle x-model="isActive" />
</div>
```

**The challenge:** How do you make the same Blade component work with both frameworks? These directives (`x-model` and `wire:model`) are intended for specific HTML tags, not custom components. How do you maintain two-way reactivity without creating a mess of conditional logic?

## The Solutions 

### Alpine's Modelable API

Alpine provides a powerful directive called `x-modelable` that binds internal state from an Alpine component (including Blade components) to external parent components. This is the recommended approach for building custom form controls.

#### Basic Example

Let's say you want to create a custom textarea with additional logic beyond the native HTML element:

```blade
<!-- resources/views/components/ui/textarea.blade.php -->
@props(['name' => ''])

<div
    x-data="{ state: null }"
    {{ $attributes }}
>
    <textarea 
        x-model="state"
        name="{{ $name }}"
        class="w-full rounded border..."
    />
    <!-- Additional custom UI elements here -->
</div>
```

#### Enter x-modelable

This is where `x-modelable` solves the problem:

```blade
<!-- resources/views/components/ui/textarea.blade.php -->
@props(['name' => ''])

<div
    x-data="{ state: null }"
    x-modelable="state"
    {{ $attributes }}
>
    <textarea 
        x-model="state"
        name="{{ $name }}"
        class="w-full rounded border..."
    />
</div>
```

Now you can use your custom component exactly like a native input:

```blade
<!-- With Livewire -->
<x-ui.textarea wire:model="content" />

<!-- With Alpine -->
<div x-data="{ content: '' }">
    <x-ui.textarea x-model="content" />
</div>
```

## Toggle Component with x-modelable

### Simple Toggle Implementation

Here's our toggle using `x-modelable`:

```blade
@props(['label' => null])

<div class="flex items-center gap-3">
    @if($label)
        <label class="font-medium text-gray-700">
            {{ $label }}
        </label>
    @endif
    
    <button
        type="button"
        x-data="{ state: false }"
        x-modelable="state"
        x-on:click="state = !state"
        x-bind:class="state ? 'bg-green-600' : 'bg-neutral-200'"
        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        {{ $attributes }}
    >
        <span
            :class="state ? 'translate-x-6' : 'translate-x-1'"
            class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
        ></span>
    </button>
</div>
```
### How It Works

The magic happens with three simple Alpine directives:

1. **`x-data="{ state: false }"`** - Creates our internal state
2. **`x-modelable="state"`** - Exposes `state` to parent components via `x-model` or `wire:model`
3. **`x-on:click="state = !state"`** - Toggles the state when clicked

### Usage

Now you can use it exactly like a native input:
```blade
<!-- With Livewire -->
<x-ui.simple-toggle wire:model="isActive" label="Enable Feature" />

<!-- With Alpine -->
<div x-data="{ isActive: false }">
    <x-ui.simple-toggle x-model="isActive" label="Enable Feature" />
    <p x-show="isActive">Feature is active!</p>
</div>
```

**That's it!** For this toggle component, `x-modelable` is actually the perfect solution. Clean, simple, and it just works.

### Why We Need More: When x-modelable Hits Its Limits

The `x-modelable` approach works beautifully for simple toggles, but complex components (like sliders, autocompletes, or date pickers) require fine-grained control over syncing and third-party library integration.

**That's where custom entanglement comes in.**

## Advanced Solution: Custom Entanglement Pattern

Our solution has three layers that work together:

```
Layer 1: Blade Component (Detects wire:model or x-model)
            ↓
Layer 2: Alpine Component (Handles entanglement/x-model)
            ↓
Layer 3: Your Component Logic (Just reads/writes to _state)
```

### Step 1: Understanding Livewire Entanglement

Entanglement allows JavaScript and PHP properties to stay in sync automatically.

```javascript
init() {
    this.volume = this.$wire.$entangle('volume');
}
```

### Step 2: Building the Blade Component

We detect if the user is using `wire:model`:

```blade
@php
    $modelAttrs = collect($attributes->getAttributes())
        ->keys()
        ->first(fn($key) => str_starts_with($key, 'wire:model'));

    $model = $modelAttrs ? $attributes->get($modelAttrs) : null;
    $isLive = $modelAttrs && str_contains($modelAttrs, '.live');
    $livewireId = isset($__livewire) ? $__livewire->getId() : null;
@endphp

<div
    x-data="toggleComponent({
        model: @js($model),
        livewire: @js($livewireId) ? window.Livewire.find(@js($livewireId)) : null,
        isLive: @js($isLive),
    })"
    {{ $attributes }}
    @if(filled($model)) wire:ignore @endif
>
    <!-- Component markup here -->
</div>
```

### Step 3: The Alpine Component

```javascript
const toggleComponent = ({ livewire, model, isLive }) => {
    const $entangle = (prop, live) => {
        if (!livewire || !prop) return null;
        const binding = livewire.$entangle(prop);
        return live ? binding.live : binding;
    };

    return {
        _state: model ? $entangle(model, isLive) : null,
        
        get isOn() { return this._state ?? false; },
        set isOn(value) { this._state = value; },
        
        init() {
            this.$nextTick(() => {
                if (this._state == null) {
                    this._state = this.$root?._x_model?.get() ?? false;
                }
                this.$watch('_state', (value) => {
                    this.$root?._x_model?.set(value);
                });
            });
        },
        
        toggle() { this.isOn = !this.isOn; },
    };
};
```

## Understanding the Critical Parts

### Why $nextTick()?
Alpine's reactive system isn't fully initialized when `init()` first runs. `$nextTick()` ensures Alpine has fully set up the component before we access advanced APIs like `_x_model`.

### Why wire:ignore?
Always add `wire:ignore` when using Livewire binding to prevent Livewire from resetting the DOM element during updates.

### Why Alpine.raw()?
When passing state to third-party libraries, use `Alpine.raw(this._state)` to unwrap the reactive proxy and avoid errors.
