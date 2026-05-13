---
name: 'field'
---

# Field Component

## Introduction

The `field` component is the essential wrapper for all form fields, providing consistent spacing, layout, and state management between labels, descriptions, inputs, and error messages.

## Installation


```bash
```


## Basic Usage

@blade
<x-demo>
    <x-ui.field
        class="max-w-sm mx-auto"
    >
        <x-ui.label>Email Address</x-ui.label>
        <x-ui.input 
            wire:model="email" 
            type="email"
            placeholder="john@example.com"
        />
    </x-ui.field>
</x-demo>
@endblade

```html
<x-ui.field >
    <x-ui.label>Email Address</x-ui.label>
    <x-ui.input 
        wire:model="email" 
        type="email"
        placeholder="john@example.com"
    />
    <x-ui.error name="email" />
</x-ui.field>
```
## How It Works

The field component automatically handles spacing and layout between form elements. It detects what components you place inside it and applies the correct spacing - tighter spacing between labels and descriptions, proper separation for error messages, and automatic dimming of labels when inputs are disabled.


## Required Fields

### Required Field with Indicator

@blade
<x-demo>
    <x-ui.field  
        class="max-w-sm mx-auto"
        required
    >
        <x-ui.label>Full Name</x-ui.label>
        <x-ui.input 
            wire:model="name" 
            placeholder="John Doe"
        />
    </x-ui.field>
</x-demo>
@endblade

```html
<x-ui.field  required>
    <x-ui.label>Full Name</x-ui.label>
    <x-ui.input 
        wire:model="name" 
        placeholder="John Doe"
    />
    <x-ui.error name="name" />
</x-ui.field>
```

## Field with Description

### Label with Description

@blade
<x-demo>
    <x-ui.field 
        class="max-w-sm mx-auto"
    >
        <x-ui.label>Password</x-ui.label>
        <x-ui.description>Must be at least 8 characters long and include numbers</x-ui.description>
        <x-ui.input 
            wire:model="password" 
            type="password"
            placeholder="••••••••"
        />
    </x-ui.field>
</x-demo>
@endblade

```html
<x-ui.field >
    <x-ui.label>Password</x-ui.label>
    <x-ui.description>Must be at least 8 characters long and include numbers</x-ui.description>
    <x-ui.input 
        wire:model="password" 
        type="password"
        placeholder="••••••••"
    />
    <x-ui.error name="password" />
</x-ui.field>
```

### Required Field with Description

@blade
<x-demo>
    <x-ui.field  
        class="max-w-sm mx-auto"
        required
    >
        <x-ui.label>Website URL</x-ui.label>
        <x-ui.description>Your personal or company website (including https://)</x-ui.description>
        <x-ui.input 
            wire:model="website" 
            type="url"
            placeholder="https://example.com"
        />
    </x-ui.field>
</x-demo>
@endblade

```html
<x-ui.field  required>
    <x-ui.label>Website URL</x-ui.label>
    <x-ui.description>Your personal or company website (including https://)</x-ui.description>
    <x-ui.input 
        wire:model="website" 
        type="url"
        placeholder="https://example.com"
    />
    <x-ui.error name="website" />
</x-ui.field>
```


## Error Handling

### Field with Validation Error

@blade
<x-demo>
    <x-ui.field  
        class="max-w-sm mx-auto"
        required
    >
        <x-ui.label>Email Address</x-ui.label>
        <x-ui.input 
            wire:model="email" 
            type="email"
            placeholder="john@example.com"
            invalid
        />
        <!-- Simulate error message -->
        <div class="mt-1.5 text-sm text-red-600">
            Please enter a valid email address
        </div>
    </x-ui.field>
</x-demo>
@endblade

```html
<x-ui.field  required>
    <x-ui.label>Email Address</x-ui.label>
    <x-ui.input 
        wire:model="email" 
        type="email"
        placeholder="john@example.com"
    />
    <x-ui.error name="email" />
</x-ui.field>
```


## Component Props

| Prop Name | Type | Default | Required | Description |
|-----------|------|---------|----------|-------------|
| `required` | boolean | `false` | No | Whether the field is required (adds indicator to labels) |
| `disabled` | boolean | `false` | No | Whether the field is disabled (dims labels) |
| `class` | string | `''` | No | Additional CSS classes |

