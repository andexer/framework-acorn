---
name: 'description'
---

# Description Component

## Introduction

The `description` component provides contextual help text for form fields. It's designed to work exclusively within forms to give users additional information about what to enter, formatting requirements, or field constraints. **This component is form-specific and should only be used with form fields.**

## Installation


```bash
```


## Basic Usage

@blade
<x-demo class="flex justify-center">
    <x-ui.field>
        <x-ui.label>Password</x-ui.label>
        <x-ui.description>
            Must be at least 8 characters long and include numbers
        </x-ui.description>
        <x-ui.input 
            wire:model="password" 
            type="password"
            placeholder="••••••••"
        />
    </x-ui.field>
</x-demo>
@endblade

```html
<x-ui.field>
    <x-ui.label>Password</x-ui.label>
    <x-ui.description>
        Must be at least 8 characters long and include numbers
    </x-ui.description>
    <x-ui.input 
        wire:model="password" 
        type="password"
        placeholder="••••••••"
    />
    <x-ui.error name="password" />
</x-ui.field>
```

## Usage Patterns

### Helpful Instructions

@blade
<x-demo class="flex justify-center">
    <x-ui.field>
        <x-ui.label>Website URL</x-ui.label>
        <x-ui.description>
            Enter your full website URL including https://
        </x-ui.description>
        <x-ui.input 
            wire:model="website" 
            type="url"
            placeholder="https://example.com"
        />
    </x-ui.field>
</x-demo>
@endblade

```html
<x-ui.field>
    <x-ui.label>Website URL</x-ui.label>
    <x-ui.description>
        Enter your full website URL including https://
    </x-ui.description>
    <x-ui.input 
        wire:model="website" 
        type="url"
        placeholder="https://example.com"
    />
    <x-ui.error name="website" />
</x-ui.field>
```

### Format Requirements

@blade
<x-demo class="flex justify-center">
    <x-ui.field required>
        <x-ui.label>Phone Number</x-ui.label>
        <x-ui.description>
            Format: (555) 123-4567 or +1-555-123-4567
        </x-ui.description>
        <x-ui.input 
            wire:model="phone" 
            type="tel"
            placeholder="(555) 123-4567"
        />
    </x-ui.field>
</x-demo>
@endblade

```html
<x-ui.field required>
    <x-ui.label>Phone Number</x-ui.label>
    <x-ui.description>
        Format: (555) 123-4567 or +1-555-123-4567
    </x-ui.description>
    <x-ui.input 
        wire:model="phone" 
        type="tel"
        placeholder="(555) 123-4567"
    />
    <x-ui.error name="phone" />
</x-ui.field>
```

### Character Limits

@blade
<x-demo class="flex justify-center">
    <x-ui.field>
        <x-ui.label>Bio</x-ui.label>
        <x-ui.description>
            Tell us about yourself (maximum 500 characters)
        </x-ui.description>
        <x-ui.textarea 
            wire:model="bio" 
            rows="4"
            placeholder="I'm a passionate developer..."
        />
    </x-ui.field>
</x-demo>
@endblade

```html
<x-ui.field>
    <x-ui.label>Bio</x-ui.label>
    <x-ui.description>
        Tell us about yourself (maximum 500 characters)
    </x-ui.description>
    <x-ui.textarea 
        wire:model="bio" 
        rows="4"
        placeholder="I'm a passionate developer..."
    />
    <x-ui.error name="bio" />
</x-ui.field>
```

### Privacy Information

@blade
<x-demo class="flex justify-center">
    <x-ui.field>
        <x-ui.label>Email Address</x-ui.label>
        <x-ui.description>
            We'll never share your email with anyone else
        </x-ui.description>
        <x-ui.input 
            wire:model="email" 
            type="email"
            placeholder="john@example.com"
        />
    </x-ui.field>
</x-demo>
@endblade

```html
<x-ui.field>
    <x-ui.label>Email Address</x-ui.label>
    <x-ui.description>
        We'll never share your email with anyone else
    </x-ui.description>
    <x-ui.input 
        wire:model="email" 
        type="email"
        placeholder="john@example.com"
    />
    <x-ui.error name="email" />
</x-ui.field>
```

## Automatic Spacing

The description component automatically adjusts its spacing based on context:

- **After labels**: Tight spacing (no top margin) for visual cohesion
- **After other elements**: Normal spacing (8px top margin)
- **Bottom margin**: Always 8px to separate from inputs

## Component Props

| Prop Name | Type | Default | Required | Description |
|-----------|------|---------|----------|-------------|
| `class` | string | `''` | No | Additional CSS classes |

