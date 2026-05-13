---
name: breadcrumbs
---

## Introduction

The `Breadcrumbs` component is a navigation aid that shows users their current location within a website's hierarchy. It provides a clear path back to previous pages and helps users understand the site structure, making it easier to navigate complex applications and websites.

## Installation


```bash
```

> Once installed, you can use the <x-ui.breadcrumbs /> and <x-ui.breadcrumbs.item /> components in any Blade view.

## Usage

### Basic Breadcrumbs

@blade
<x-demo>
    <div class="flex justify-center w-full">
        <x-components::ui.breadcrumbs>
            <x-components::ui.breadcrumbs.item href="#">Home</x-components::ui.breadcrumbs.item>
            <x-components::ui.breadcrumbs.item href="#">Products</x-components::ui.breadcrumbs.item>
            <x-components::ui.breadcrumbs.item href="#">Electronics</x-components::ui.breadcrumbs.item>
            <x-components::ui.breadcrumbs.item>Smartphones</x-components::ui.breadcrumbs.item>
        </x-components::ui.breadcrumbs>
    </div>
</x-demo>
@endblade

```html
<x-ui.breadcrumbs>
    <x-ui.breadcrumbs.item href="#">Home</x-ui.breadcrumbs.item>
    <x-ui.breadcrumbs.item href="#">Products</x-ui.breadcrumbs.item>
    <x-ui.breadcrumbs.item href="#">Electronics</x-ui.breadcrumbs.item>
    <x-ui.breadcrumbs.item>Smartphones</x-ui.breadcrumbs.item>
</x-ui.breadcrumbs>
```

### Slash Separators

Use slash separators for a more compact, file-path-like appearance.

@blade
<x-demo>
    <div class="flex justify-center w-full">
        <x-components::ui.breadcrumbs>
            <x-components::ui.breadcrumbs.item href="#" separator="slash">Dashboard</x-components::ui.breadcrumbs.item>
            <x-components::ui.breadcrumbs.item href="#" separator="slash">Settings</x-components::ui.breadcrumbs.item>
            <x-components::ui.breadcrumbs.item href="#" separator="slash">User Management</x-components::ui.breadcrumbs.item>
            <x-components::ui.breadcrumbs.item>Edit Profile</x-components::ui.breadcrumbs.item>
        </x-components::ui.breadcrumbs>
    </div>
</x-demo>
@endblade

```html
<x-ui.breadcrumbs>
    <x-ui.breadcrumbs.item href="#" separator="slash">Dashboard</x-ui.breadcrumbs.item>
    <x-ui.breadcrumbs.item href="#" separator="slash">Settings</x-ui.breadcrumbs.item>
    <x-ui.breadcrumbs.item href="#" separator="slash">User Management</x-ui.breadcrumbs.item>
    <x-ui.breadcrumbs.item>Edit Profile</x-ui.breadcrumbs.item>
</x-ui.breadcrumbs>
```

### With Icons

Enhance breadcrumbs with icons to provide visual context and improve recognition.

@blade
<x-demo>
    <div class="flex justify-center w-full">
        <x-components::ui.breadcrumbs>
            <x-components::ui.breadcrumbs.item href="#" icon="home" separator="slash">Home</x-components::ui.breadcrumbs.item>
            <x-components::ui.breadcrumbs.item href="#" icon="folder" separator="slash">Documents</x-components::ui.breadcrumbs.item>
            <x-components::ui.breadcrumbs.item href="#" icon="document-text" separator="slash">Reports</x-components::ui.breadcrumbs.item>
        </x-components::ui.breadcrumbs>
    </div>
</x-demo>
@endblade

```html
<x-ui.breadcrumbs>
    <x-ui.breadcrumbs.item href="#" icon="home" separator="slash">Home</x-ui.breadcrumbs.item>
    <x-ui.breadcrumbs.item href="#" icon="folder" separator="slash">Documents</x-ui.breadcrumbs.item>
    <x-ui.breadcrumbs.item href="#" icon="document-text" separator="slash">Reports</x-ui.breadcrumbs.item>
</x-ui.breadcrumbs>
```

### Icon-Only Breadcrumbs

Create compact breadcrumbs using only icons, perfect for mobile interfaces or when space is limited.

@blade
<x-demo>
    <div class="flex justify-center w-full">
        <x-components::ui.breadcrumbs>
            <x-components::ui.breadcrumbs.item href="#" icon="home" separator="slash" />
            <x-components::ui.breadcrumbs.item href="#" icon="building-office" separator="slash" />
            <x-components::ui.breadcrumbs.item href="#" icon="users" separator="slash" />
            <x-components::ui.breadcrumbs.item icon="user-circle" />
        </x-components::ui.breadcrumbs>
    </div>
</x-demo>
@endblade

```html
<x-ui.breadcrumbs>
    <x-ui.breadcrumbs.item href="#" icon="home" separator="slash" />
    <x-ui.breadcrumbs.item href="#" icon="building-office" separator="slash" />
    <x-ui.breadcrumbs.item href="#" icon="users" separator="slash" />
    <x-ui.breadcrumbs.item icon="user-circle" />
</x-ui.breadcrumbs>
```

### Collapsed Navigation with Dropdown

Use dropdowns to handle long breadcrumb paths by collapsing intermediate items.

@blade
<x-demo>
    <div class="flex justify-center w-full">
        <x-components::ui.breadcrumbs>
            <x-components::ui.breadcrumbs.item href="#" icon="home" separator="slash" />
            
            <x-components::ui.breadcrumbs.item separator="slash">
                <x-components::ui.dropdown>
                    <x-slot:button class="justify-center dark:hover:bg-white/10 hover:bg-gray-100 rounded p-1 cursor-pointer">
                        <x-components::ui.icon name="ellipsis-horizontal" variant="mini" />
                    </x-slot:button>
                    <x-slot:menu>
                        <x-components::ui.dropdown.item href="#">Projects</x-components::ui.dropdown.item>
                        <x-components::ui.dropdown.item href="#">Templates</x-components::ui.dropdown.item>
                        <x-components::ui.dropdown.item href="#">Archive</x-components::ui.dropdown.item>
                    </x-slot:menu>
                </x-components::ui.dropdown>
            </x-components::ui.breadcrumbs.item>
            
            <x-components::ui.breadcrumbs.item href="#" separator="slash">Website Redesign</x-components::ui.breadcrumbs.item>
            <x-components::ui.breadcrumbs.item>Design Assets</x-components::ui.breadcrumbs.item>
        </x-components::ui.breadcrumbs>
    </div>
</x-demo>
@endblade

```html
<x-ui.breadcrumbs>
    <x-ui.breadcrumbs.item href="#" icon="home" separator="slash" />
    
    <x-ui.breadcrumbs.item separator="slash">
        <x-ui.dropdown>
            <x-slot:button class="justify-center dark:hover:bg-white/10 hover:bg-gray-100 rounded p-1 cursor-pointer">
                <x-ui.icon name="ellipsis-horizontal" variant="mini" />
            </x-slot:button>
            <x-slot:menu>
                <x-ui.dropdown.item href="#">Projects</x-ui.dropdown.item>
                <x-ui.dropdown.item href="#">Templates</x-ui.dropdown.item>
                <x-ui.dropdown.item href="#">Archive</x-ui.dropdown.item>
            </x-slot:menu>
        </x-ui.dropdown>
    </x-ui.breadcrumbs.item>
    
    <x-ui.breadcrumbs.item href="#" separator="slash">Website Redesign</x-ui.breadcrumbs.item>
    <x-ui.breadcrumbs.item>Design Assets</x-ui.breadcrumbs.item>
</x-ui.breadcrumbs>
```

### Custom Separators

Use custom icons as separators to match your design system or brand aesthetic.

@blade
<x-demo>
    <div class="flex justify-center w-full space-y-4">
        <div>
            <x-ui.heading level="2" class="mb-2">With arrow separators:</x-ui.heading>
            <x-components::ui.breadcrumbs>
                <x-components::ui.breadcrumbs.item href="#" separator="arrow-right">E-commerce</x-components::ui.breadcrumbs.item>
                <x-components::ui.breadcrumbs.item href="#" separator="arrow-right">Categories</x-components::ui.breadcrumbs.item>
                <x-components::ui.breadcrumbs.item href="#" separator="arrow-right">Clothing</x-components::ui.breadcrumbs.item>
            </x-components::ui.breadcrumbs>
        </div>

    </div>
</x-demo>
@endblade

```html
<!-- Arrow separators -->
<x-ui.breadcrumbs>
    <x-ui.breadcrumbs.item href="#" separator="arrow-right">E-commerce</x-ui.breadcrumbs.item>
    <x-ui.breadcrumbs.item href="#" separator="arrow-right">Categories</x-ui.breadcrumbs.item>
    <x-ui.breadcrumbs.item href="#" separator="arrow-right">Clothing</x-ui.breadcrumbs.item>
</x-ui.breadcrumbs>

```

### E-commerce Breadcrumbs

A practical example showing breadcrumbs in an e-commerce context with mixed content types.

@blade
<x-demo>
    <div class="flex justify-center w-full">
        <x-components::ui.breadcrumbs>
            <x-components::ui.breadcrumbs.item href="#" icon="home" separator="slash" />
            <x-components::ui.breadcrumbs.item href="#" separator="slash">Electronics</x-components::ui.breadcrumbs.item>
            <x-components::ui.breadcrumbs.item href="#" separator="slash">Laptop</x-components::ui.breadcrumbs.item>
            <x-components::ui.breadcrumbs.item>MacBook Pro 16"</x-components::ui.breadcrumbs.item>
        </x-components::ui.breadcrumbs>
    </div>
</x-demo>
@endblade

```html
<x-ui.breadcrumbs>
    <x-ui.breadcrumbs.item href="#" icon="home" separator="slash" />
    <x-ui.breadcrumbs.item href="#" separator="slash">Electronics</x-ui.breadcrumbs.item>
    <x-ui.breadcrumbs.item href="#" separator="slash">Laptop</x-ui.breadcrumbs.item>
    <x-ui.breadcrumbs.item>MacBook Pro 16"</x-ui.breadcrumbs.item>
</x-ui.breadcrumbs>
```

## Component Props Reference

### Breadcrumbs Props

The main breadcrumbs container accepts standard HTML attributes and merges them with default styling classes.

### Breadcrumbs Item Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `separator` | `string\|null` | `null` | Separator type: `null` (chevron), `'slash'`, or custom icon name |
| `iconVariant` | `string` | `'mini'` | Icon variant for both item icons and separators |
| `icon` | `string\|null` | `null` | Icon name to display before the text |
| `href` | `string\|null` | `null` | URL for clickable breadcrumb items (makes item a link) |

## Separator Options

The breadcrumbs component supports multiple separator styles:

| Separator Value | Appearance | Description |
|----------------|------------|-------------|
| `null` (default) | `>` | Chevron right (automatically reverses for RTL) |
| `'slash'` | `/` | Forward slash (rotates for RTL) |
| Custom icon name | Varies | Any Heroicon name (e.g., `'arrow-right'`, `'stop'`) |
