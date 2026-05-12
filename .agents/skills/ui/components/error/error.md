---
name: error
---

## Overview

The Error component displays validation errors and custom error messages with a consistent design and proper accessibility features.

## Installation


```bash
```


### Basic Usage

```blade
<x-ui.error name="email" />
```


### Examples

#### Laravel Validation Errors

Display validation errors for a specific field:

```blade
<x-ui.input name="email" type="email" />
<x-ui.error name="email" />
```

#### Custom Error Messages

Display custom error messages:

```blade
<x-ui.error :messages="'Something went wrong'" />
<x-ui.error :messages="['Password too short', 'Must contain numbers']" />
```

#### Combined Sources

Combine Laravel validation errors with custom messages:

```blade
<x-ui.error name="username" :messages="['Custom validation failed']" />
```

#### Styling

Apply custom classes:

```blade
<x-ui.error name="phone" class="mb-4 p-3 bg-red-50 rounded-lg" />
```

### Integration with Forms
Works with form validation:

```blade
<form wire:submit="save">
    <x-ui.input name="title" label="Post Title" />
    <x-ui.error name="title" />
    
    <x-ui.textarea name="content" label="Content" />
    <x-ui.error name="content" />
    
    <x-ui.button type="submit">Save Post</x-ui.button>
</form>
```
### Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | `string\|null` | `null` | The field name to get validation errors from Laravel's `$errors` bag |
| `messages` | `array\|string\|null` | `[]` | Custom error messages to display alongside or instead of validation errors |

### Styling Slots

The component includes data slots for targeted styling:

- `data-slot="error"` - The main error container
- `data-slot="icon"` - The exclamation icon (automatically styled red)

```css
[data-slot="error"] {
    /* Custom error container styles */
}
```