---
name: 'key-value'
---

# Key-Value Input Component

## Introduction

The `key-value-input` component provides a powerful and flexible way to handle key-value pairs input. It features dynamic row management, validation, drag-and-drop reordering, and full accessibility support. Perfect for environment variables, metadata, configuration settings, or any structured data input scenario.

## Installation


```bash
```


## Basic Usage

@blade
<x-demo class="flex justify-center">
    <x-ui.key-value 
        class="max-w-md"
        wire:model="configurations" 
        label="Configuration Settings"
    />
</x-demo>
@endblade

```html
<x-ui.key-value 
    wire:model="configurations" 
    label="Configuration Settings"
/>
```

### Usage with Livewire
you need to have an array of keyed arrays with `key` and `value` keys pair
@blade
<x-demo class="flex justify-center" x-data="{ 
    envVariables: [
        { key: 'theme', value: 'dark' },
        { key: 'language', value: 'English' },
        { key: 'timezone', value: 'UTC+1' },
        { key: 'notifications', value: 'enabled' },
    ]
}">
    <x-ui.key-value 
        class="max-w-lg"
        x-model="envVariables" 
        label="Environment Variables"
        key-label="Variable Name"
        value-label="Variable Value"
    />
</x-demo>
@endblade

```html
<!-- you should have sometime looks like this in your livewire component : 
 $userPreferences => [
        ['key' => 'theme', 'value' => 'dark'],
        ['key' => 'language', 'value' => 'English'],
        ['key' => 'timezone', 'value' => 'UTC+1'],
        ['key' => 'notifications', 'value' => 'enabled']
    ];
 -->
<div>
    <x-ui.key-value 
        wire:model="envVariables" 
        label="Environment Variables"
        key-label="Variable Name"
        value-label="Variable Value"
    />
</div>
```

### Usage with Raw Blade (Alpine.js)

@blade
<x-demo class="flex justify-center">
    <div class="max-w-md" x-data="{ 
        configs: [
            { key: 'database_host', value: 'localhost' },
            { key: 'cache_driver', value: 'redis' }
        ]
    }">
        <x-ui.key-value 
            x-model="configs"
            label="Server Configuration"
            key-label="Config Key"
            value-label="Config Value"
        />
    </div>
</x-demo>
@endblade

```html
<div x-data="{ 
    configs: [
        { key: 'database_host', value: 'localhost' },
        { key: 'cache_driver', value: 'redis' }
    ]
}">
    <x-ui.key-value 
        x-model="configs"
        label="Server Configuration"
        key-label="Config Key"
        value-label="Config Value"
    />
</div>
```

## Customization

### Labels and Placeholders

Customize the labels and placeholders for keys and values.

@blade
<x-demo class="flex justify-center">
    <div class="max-w-md w-full space-y-6">
        <x-ui.key-value 
            wire:model="envVars" 
            label="Environment Variables"
            key-label="Variable Name"
            value-label="Variable Value"
            key-placeholder="e.g., NODE_ENV"
            value-placeholder="e.g., production"
        />
        <x-ui.key-value 
            wire:model="metadata" 
            label="Application Metadata"
            key-label="Property"
            value-label="Value"
            key-placeholder="e.g., version"
            value-placeholder="e.g., 1.0.0"
        />
    </div>
</x-demo>
@endblade

```html
<x-ui.key-value 
    wire:model="envVars" 
    label="Environment Variables"
    key-label="Variable Name"
    value-label="Variable Value"
    key-placeholder="e.g., NODE_ENV"
    value-placeholder="e.g., production"
/>
```

### Row Constraints

Control the minimum and maximum number of rows.

@blade
<x-demo class="flex justify-center">
    <div class="max-w-md w-full space-y-6">
        <x-ui.key-value 
            wire:model="requiredSettings" 
            label="Required Settings (2-5 rows)"
            :min-rows="2"
            :max-rows="5"
            :required="true"
        />
        <x-ui.key-value 
            wire:model="optionalSettings" 
            label="Optional Settings (0-10 rows)"
            :min-rows="0"
            :max-rows="10"
        />
    </div>
</x-demo>
@endblade

```html
<x-ui.key-value 
    wire:model="requiredSettings" 
    label="Required Settings (2-5 rows)"
    :min-rows="2"
    :max-rows="5"
    :required="true"
/>
<x-ui.key-value 
    wire:model="optionalSettings" 
    label="Optional Settings (0-10 rows)"
    :min-rows="0"
    :max-rows="10"
/>
```

## Advanced Features

### Validation and Constraints

Set validation rules and constraints to ensure data quality.

@blade
<x-demo class="flex justify-center">
    <x-ui.key-value 
        class="max-w-md"
        wire:model="validatedConfig" 
        label="Validated Configuration"
        :prevent-duplicate-keys="true"
        :allow-empty-values="false"
        :required="true"
    />
</x-demo>
@endblade

```html
<x-ui.key-value 
    wire:model="validatedConfig" 
    label="Validated Configuration"
    :prevent-duplicate-keys="true"
    :allow-empty-values="false"
    :required="true"
/>
```

### Drag and Drop Reordering

Enable or disable drag-and-drop reordering of rows.

@blade
<x-demo class="flex justify-center">
    <div class="max-w-md w-full space-y-6">
        <x-ui.key-value 
            wire:model="reorderableConfig" 
            label="Reorderable Configuration"
            :reorderable="true"
        />
        <x-ui.key-value 
            wire:model="staticConfig" 
            label="Static Configuration"
            :reorderable="false"
        />
    </div>
</x-demo>
@endblade

```html
<x-ui.key-value 
    wire:model="reorderableConfig" 
    label="Reorderable Configuration"
    :reorderable="true"
/>
```

### Row Management Features

Control which row management features are available.

@blade
<x-demo class="flex justify-center">
    <x-ui.key-value 
        class="max-w-md"
        wire:model="managedRows" 
        label="Full Row Management"
        :reorderable="true"
        :show-duplicate="true"
        :show-top-bar="true"
    />
</x-demo>
@endblade

```html
<x-ui.key-value 
    wire:model="managedRows" 
    label="Full Row Management"
    :reorderable="true"
    :show-duplicate="true"
    :show-top-bar="true"
/>
```

### Simplified Interface

Create a minimal interface by hiding advanced features.

@blade
<x-demo class="flex justify-center">
    <x-ui.key-value 
        class="max-w-md"
        wire:model="simpleConfig" 
        label="Simple Configuration"
        :reorderable="false"
        :show-duplicate="false"
        :show-top-bar="false"
    />
</x-demo>
@endblade

```html
<x-ui.key-value 
    wire:model="simpleConfig" 
    label="Simple Configuration"
    :reorderable="false"
    :show-duplicate="false"
    :show-top-bar="false"
/>
```

## States 

### Disabled State

@blade
<x-demo class="flex justify-center">
    <x-ui.key-value 
        class="max-w-md"
        wire:model="disabledConfig" 
        label="Disabled Configuration"
        :disabled="true"
    />
</x-demo>
@endblade

```html
<x-ui.key-value 
    wire:model="disabledConfig" 
    label="Disabled Configuration"
    :disabled="true"
/>
```

## Component Props

| Prop Name | Type | Default | Required | Description |
|-----------|------|---------|----------|-------------|
| `wire:model` | string | - | Yes | Livewire property to bind to |
| `x-model` | string | - | Yes | Alpine `x-data` scope property to bind to |
| `label` | string | `null` | No | Label text for the component |
| `key-label` | string | `'Key'` | No | Label for the key column |
| `value-label` | string | `'Value'` | No | Label for the value column |
| `key-placeholder` | string | `'Enter key...'` | No | Placeholder for key inputs |
| `value-placeholder` | string | `'Enter value...'` | No | Placeholder for value inputs |
| `min-rows` | integer | `1` | No | Minimum number of rows |
| `max-rows` | integer | `null` | No | Maximum number of rows |
| `required` | boolean | `false` | No | Whether the component is required |
| `disabled` | boolean | `false` | No | Whether the component is disabled |
| `allow-empty-values` | boolean | `true` | No | Allow empty values |
| `prevent-duplicate-keys` | boolean | `true` | No | Prevent duplicate keys |
| `show-duplicate` | boolean | `true` | No | Show duplicate row button |
| `show-top-bar` | boolean | `true` | No | Show top toolbar |
| `add-button-text` | string | `'Add Row'` | No | Text for add button |
| `reorderable` | boolean | `true` | No | Enable drag-and-drop reordering |
| `error-class` | string | `'text-red-500 text-sm mt-1'` | No | Additional CSS classes for error messages |