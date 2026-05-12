# My Components UI Installation

This documentation covers the setup and architecture of the components.

## Core Requirements

- Tailwind CSS 4.0 or higher
- Alpine.js
- Livewire 3.x (Optional)

## Component Architecture

Components are located in `resources/views/components/ui/`. Each component is self-contained and uses standard Tailwind CSS classes.

### Asset Integration

Ensure your main layout file includes the compiled assets:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    @vite(['resources/css/app.css'])
</head>
<body>
    <main>
        @yield('content')
    </main>
    @vite(['resources/js/app.js'])
</body>
</html>
```

## Component Usage

To use a component, simply call it using the `x-ui` prefix:

```html
<x-ui.button>Click me</x-ui.button>
```
