---
name: 'link'
---

# Link Component

## Introduction

The `link` component provides a flexible and accessible way to create styled links.

## Installation


```bash
```

## Basic Usage

@blade
<x-demo class="flex justify-center">
    <x-ui.link href="https://example.com">
        Visit our website
    </x-ui.link>
</x-demo>
@endblade

```html
<x-ui.link href="https://example.com">
    Visit our website
</x-ui.link>
```

### Internal Links

@blade
<x-demo class="flex justify-center">
    <x-ui.link href="/dashboard">
        Go to Dashboard
    </x-ui.link>
</x-demo>
@endblade

```html
<x-ui.link href="/dashboard">
    Go to Dashboard
</x-ui.link>
```

### External Links

@blade
<x-demo class="flex justify-center">
    <x-ui.link href="https://github.com" openInNewTab>
        View on GitHub
    </x-ui.link>
</x-demo>
@endblade

```html
<x-ui.link href="https://github.com" openInNewTab>
    View on GitHub
</x-ui.link>
```

## Link Variants

### Default Variant

The default variant shows an underlined link with primary colors.

@blade
<x-demo class="flex justify-center">
    <x-ui.text>This is a paragraph with a <x-ui.link href="#">default link</x-ui.link> inside it.</x-ui.text>
</x-demo>
@endblade

```html
<x-ui.text>This is a paragraph with a <x-ui.link href="#">default link</x-ui.link> inside it.</x-ui.text>
```

### Ghost Variant

Ghost links have no underline by default but show underline on hover.

@blade
<x-demo class="flex justify-center">
    <x-ui.text>This is a paragraph with a <x-ui.link href="#" variant="ghost">ghost link</x-ui.link> that underlines on hover.</x-ui.text>
</x-demo>
@endblade

```html
<x-ui.text>This is a paragraph with a <x-ui.link href="#" variant="ghost">ghost link</x-ui.link> that underlines on hover.</x-ui.text>
```

### Soft Variant

Soft links have a muted appearance with no underline and Soft hover effects.

@blade
<x-demo class="flex justify-center">
    <x-ui.text>This is a paragraph with a <x-ui.link href="#" variant="soft">soft link</x-ui.link> that has Soft styling.</x-ui.text>
</x-demo>
@endblade

```html
<x-ui.text>This is a paragraph with a <x-ui.link href="#" variant="soft">soft link</x-ui.link> that has Soft styling.</x-ui.text>
```

## primary Colors

### With primary (Default)

Links with primary use the primary color scheme (our primary is white).

@blade
<x-demo class="flex justify-center">
    <div class="space-y-2 ">
        <x-ui.text><x-ui.link href="#" :primary="true">primary link (default)</x-ui.link></x-ui.text>
        <x-ui.text><x-ui.link href="#" :primary="true" variant="ghost">primary ghost link</x-ui.link></x-ui.text>
        <x-ui.text><x-ui.link href="#" :primary="true" variant="soft">primary soft link</x-ui.link></x-ui.text>
    </div>
</x-demo>
@endblade

```html
<x-ui.link href="#" :primary="true">primary link (default)</x-ui.link>
<x-ui.link href="#" :primary="true" variant="ghost">primary ghost link</x-ui.link>
<x-ui.link href="#" :primary="true" variant="soft">primary soft link</x-ui.link>
```

### Without primary

Links without primary use neutral colors.

@blade
<x-demo class="flex justify-center">
    <div class="space-y-2">
        <x-ui.text><x-ui.link href="#" :primary="false">Neutral link</x-ui.link></x-ui.text>
        <x-ui.text><x-ui.link href="#" :primary="false" variant="ghost">Neutral ghost link</x-ui.link></x-ui.text>
        <x-ui.text><x-ui.link href="#" :primary="false" variant="soft">Neutral soft link</x-ui.link></x-ui.text>
    </div>
</x-demo>
@endblade

```html
<x-ui.link href="#" :primary="false">Neutral link</x-ui.link>
<x-ui.link href="#" :primary="false" variant="ghost">Neutral ghost link</x-ui.link>
<x-ui.link href="#" :primary="false" variant="soft">Neutral soft link</x-ui.link>
```



## Accessibility Features

### ARIA Labels

@blade
<x-demo class="flex justify-center">
    <div class="space-y-2 ">
        <x-ui.text>
            <x-ui.link 
                href="https://example.com" 
                openInNewTab 
                aria-label="Visit our main website (opens in new tab)"
            >
                Visit Website
            </x-ui.link>
        </x-ui.text>
        <x-ui.text>
            <x-ui.link 
                href="/download" 
                aria-describedby="download-info"
            >
                Download App
            </x-ui.link>
            <span id="download-info" class="sr-only">Downloads a 50MB installer file</span>
        </x-ui.text>
    </div>
</x-demo>
@endblade

```html
<x-ui.link 
    href="https://example.com" 
    openInNewTab 
    aria-label="Visit our main website (opens in new tab)"
>
    Visit Website
</x-ui.link>

<x-ui.link 
    href="/download" 
    aria-describedby="download-info"
>
    Download App
</x-ui.link>
<span id="download-info" class="sr-only">Downloads a 50MB installer file</span>
```

## Component Props

| Prop Name | Type | Default | Required | Description |
|-----------|------|---------|----------|-------------|
| `href` | string | - | Yes | Link destination URL |
| `variant` | string | `null` | No | Link style: `default`, `ghost`, `soft` |
| `primary` | boolean | `true` | No | Whether to use primary colors |
| `openInNewTab` | boolean | `false` | No | Whether link opens in new tab |
| `download` | boolean/string | `false` | No | Whether link triggers download |
| `class` | string | `''` | No | Additional CSS classes |

## Variant Styles

| Variant | Underline | Hover Behavior | Use Case |
|---------|-----------|----------------|----------|
| `default` | Always visible | Color change | Primary content links |
| `ghost` | Only on hover | Underline appears | Navigation, secondary links |
| `soft` | Never | Soft color change | Footer links, muted references |
