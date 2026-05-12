---
name: brand
---

## Introduction

The `Brand` component is a flexible branding element designed to display your company logo and name in a consistent, clickable format. It's perfect for headers, navigation bars, footers, and anywhere you need to showcase your brand identity with proper link functionality.

## Installation


```bash
```

> Once installed, you can use the <x-ui.brand /> component in any Blade view.

## Usage

### Basic Brand with Logo URL

Display your brand using a logo image URL and company name.

@blade
<x-demo>
    <div class="flex justify-center">
        <x-ui.brand 
            href="#" 
            name="BluePeak" 
            alt="BluePeak" 
            logo="/logo-demo.jpg" 
            logoClass="rounded-full size-12!"
        />
    </div>
</x-demo>
@endblade

```html
<x-ui.brand 
    href="/" 
    logo="/logo-demo.jpg" 
    name="BluePeak" 
    alt="BluePeak" 
    logoClass="rounded-full size-12"
/>
```

### Logo Only

Create a minimal brand display using only the logo without text.

@blade
<x-demo>
    <div class="flex justify-center">
        <x-ui.brand 
            href="#" 
            logo="/logo-demo.jpg"  
            logoClass="rounded-full size-12!"
            alt="My Components UI" 
        />
    </div>
</x-demo>
@endblade

```html
<x-ui.brand 
    href="/" 
    logo="/logo-demo.jpg"  
    logoClass="rounded-full size-12" 
    alt="My Components UI" 
/>
```

### Text Only Brand

Display your brand using only text, perfect for text-based logos or minimalist designs.

@blade
<x-demo>
    <div class="flex justify-center">
        <x-ui.brand 
            href="#" 
            name="TechCorp" 
        />
    </div>
</x-demo>
@endblade

```html
<x-ui.brand 
    href="/" 
    name="TechCorp" 
/>
```

### Custom Logo Content

Use the logo slot to include custom SVG content or more complex logo structures.

@blade
<x-demo>
    <div class="flex justify-center">
        <x-ui.brand href="#" name="InnovateLab">
            <x-slot:logo>
                <div class="h-8 w-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                </div>
            </x-slot:logo>
        </x-ui.brand>
    </div>
</x-demo>
@endblade

```html
<x-ui.brand href="/" name="Your Company">
    <x-slot:logo>
        <div class="h-8 w-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
        </div>
    </x-slot:logo>
</x-ui.brand>
```

### External Link

Configure the brand to open in a new tab when linking to external sites.

@blade
<x-demo class="flex justify-center">
    <x-ui.brand 
        href="https://example.com"
        logo="/logo-demo.jpg" 
        logoClass="rounded-full size-12!"
        target="_blank"
        name="External Site" 
    />
</x-demo>
@endblade

```html
<x-ui.brand 
    href="https://external-site.com" 
    target="_blank"
    logo="https://external-site.com/logo.svg" 
    name="External Site" 
    alt="External Site" 
/>
```

## Component Props Reference

### Brand Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `href` | `string` | `'#'` | The URL the brand link should navigate to |
| `logo` | `string\|null` | `null` | Image URL for the logo (use slot for custom content) |
| `name` | `string` | `''` | Brand name text to display alongside the logo |
| `logoClass` | `string` | `''` | Logo wrapper styles |
| `alt` | `string` | `''` | Alt text for the logo image (accessibility) |
| `target` | `string` | `'_self'` | Link target: `'_self'`, `'_blank'`, `'_parent'`, `'_top'` |

### Slot Reference

| Slot | Required | Description |
|------|----------|-------------|
| `logo` | No | Custom logo content (overrides the `logo` prop) |
