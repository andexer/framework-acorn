---
name: 'label'
---

# Label Component

## Introduction

The `label` component provides consistent styling and accessibility for form field labels. It automatically handles required field indicators and integrates seamlessly with the field component for proper spacing and layout. Designed to work perfectly with all form input components.

## Installation


```bash
```

## Basic Usage

@blade
<x-demo class="flex justify-center w-full max-w-md">
    <x-ui.label>Email Address</x-ui.label>
</x-demo>
@endblade

```html
<x-ui.label>Email Address</x-ui.label>
```

### Using Text Prop

You can pass the label text as a prop instead of using the slot:

@blade
<x-demo class="flex justify-center w-full max-w-md">
    <x-ui.label text="Full Name" />
</x-demo>
@endblade

```html
<x-ui.label text="Full Name" />
```

## Required Field Indicator

### Required Label

@blade
<x-demo class="flex justify-center w-full max-w-md">
    <x-ui.label required>Email Address</x-ui.label>
</x-demo>
@endblade

```html
<x-ui.label required>Email Address</x-ui.label>
```

### With Field Component (Automatic)

When used within a field component with `required`, the label automatically inherits the required state:

@blade
<x-demo class="flex justify-center w-full max-w-md">
    <x-ui.field required>
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
<x-ui.field required>
    <x-ui.label>Email Address</x-ui.label>
    <x-ui.input 
        wire:model="email" 
        type="email"
        placeholder="john@example.com"
    />
    <x-ui.error name="email" />
</x-ui.field>
```

## Usage with Form Components

### With Input

@blade
<x-demo class="flex justify-center w-full max-w-md">
    <div class="space-y-4 w-full">
            <x-ui.label>Full Name</x-ui.label>
            <x-ui.input 
                wire:model="name" 
                placeholder="John Doe"
                class="mt-2"
            />
    </div>
</x-demo>
@endblade

```html
<x-ui.label>Full Name</x-ui.label>
<x-ui.input 
    wire:model="name" 
    placeholder="John Doe"
/>
```

### With Select

@blade
<x-demo class="flex justify-center w-full max-w-md">
    <div class="space-y-4 w-full">
        <div>
            <x-ui.label required>Country</x-ui.label>
            <x-ui.select wire:model="country" class="mt-2">
                <x-ui.select.option value="us">United States</x-ui.select.option>
                <x-ui.select.option value="ca">Canada</x-ui.select.option>
                <x-ui.select.option value="uk">United Kingdom</x-ui.select.option>
            </x-ui.select>
        </div>
    </div>
</x-demo>
@endblade

```html
<x-ui.label required>Country</x-ui.label>
<x-ui.select wire:model="country">
    <x-ui.select.option value="us">United States</x-ui.select.option>
    <x-ui.select.option value="ca">Canada</x-ui.select.option>
    <x-ui.select.option value="uk">United Kingdom</x-ui.select.option>
</x-ui.select>
```

### With Textarea

@blade
<x-demo class="flex justify-center w-full max-w-md">
    <div class="space-y-4 w-full">
        <div>
            <x-ui.label>Message</x-ui.label>
            <x-ui.textarea 
                wire:model="message" 
                placeholder="Enter your message..."
                rows="4"
                class="mt-2"
            />
        </div>
    </div>
</x-demo>
@endblade

```html
<x-ui.label>Message</x-ui.label>
<x-ui.textarea 
    wire:model="message" 
    placeholder="Enter your message..."
    rows="4"
/>
```

### With Checkbox

<!-- @blade
<x-demo class="flex justify-center w-full max-w-md">
    <div class="space-y-4 w-full">
        <x-ui.checkbox wire:model="newsletter">
            <x-ui.label>Subscribe to newsletter</x-ui.label>
        </x-ui.checkbox>
    </div>
</x-demo>
@endblade
 -->

```html
<x-ui.checkbox wire:model="newsletter">
    <x-ui.label>Subscribe to newsletter</x-ui.label>
</x-ui.checkbox>
``` 

## Complete Form Examples

### Registration Form

@blade
<x-demo class="flex justify-center w-full max-w-md">
    <form class="space-y-6 w-full">
        <x-ui.field required>
            <x-ui.label>Full Name</x-ui.label>
            <x-ui.input 
                wire:model="name"
                placeholder="John Doe"
            />
        </x-ui.field>
        <!--  -->
        <x-ui.field required>
            <x-ui.label>Email Address</x-ui.label>
            <x-ui.input 
                wire:model="email"
                type="email"
                placeholder="john@example.com"
            />
        </x-ui.field>
        <!--  -->
        <x-ui.field>
            <x-ui.label>Company</x-ui.label>
            <x-ui.input 
                wire:model="company"
                placeholder="Acme Inc."
            />
        </x-ui.field>
    </form>
</x-demo>
@endblade

```html
<form class="space-y-6">
    <x-ui.field required>
        <x-ui.label>Full Name</x-ui.label>
        <x-ui.input 
            wire:model="name"
            placeholder="John Doe"
        />
        <x-ui.error name="name" />
    </x-ui.field>
    
    <x-ui.field required>
        <x-ui.label>Email Address</x-ui.label>
        <x-ui.input 
            wire:model="email"
            type="email"
            placeholder="john@example.com"
        />
        <x-ui.error name="email" />
    </x-ui.field>
    
    <x-ui.field>
        <x-ui.label>Company</x-ui.label>
        <x-ui.input 
            wire:model="company"
            placeholder="Acme Inc."
        />
        <x-ui.error name="company" />
    </x-ui.field>
</form>
```

## Styling and Customization

### Custom Classes

@blade
<x-demo class="flex justify-center w-full max-w-md">
    <div class="space-y-4 w-full">
        <x-ui.label class="text-lg font-bold text-blue-600">
            Custom Styled Label
        </x-ui.label>
        
        <x-ui.label class="text-xs uppercase tracking-wide text-gray-500">
            Small Label
        </x-ui.label>
    </div>
</x-demo>
@endblade

```html
<x-ui.label class="text-lg font-bold text-blue-600">
    Custom Styled Label
</x-ui.label>

<x-ui.label class="text-xs uppercase tracking-wide text-gray-500">
    Small Label
</x-ui.label>
```

## Accessibility

The label component follows accessibility best practices:

- Uses semantic HTML structure
- Provides clear visual hierarchy
- Required indicators are properly marked with `aria-hidden="true"`
- Works seamlessly with screen readers
- Maintains proper color contrast

## Component Props

| Prop Name | Type | Default | Required | Description |
|-----------|------|---------|----------|-------------|
| `text` | string | - | No | Label text (alternative to slot content) |
| `required` | boolean | `false` | No | Whether to show required indicator (*) |
| `class` | string | `''` | No | Additional CSS classes |

## Integration with Other Components

The label component is designed to work seamlessly with:

- [Field Component](/components/field) - Provides automatic spacing and required state inheritance
- [Input Component](/components/input) - Text inputs, email, password, etc.
- [Select Component](/components/select) - Dropdown selections
- [Textarea Component](/components/textarea) - Multi-line text input
- [Checkbox Component](/components/checkbox) - Boolean selections
- [Radio Component](/components/radio) - Single choice selections
