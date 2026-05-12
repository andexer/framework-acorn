---
name: avatar
---

## Introduction

The `Avatar` component is a versatile user representation component designed to display profile pictures, initials, or icons in a consistent and visually appealing format. It provides multiple display modes, automatic initials generation, color theming, and badge support for status indicators.

## Installation


```bash
```

> Once installed, you can use the <x-ui.avatar /> component in any Blade view.

## Usage

### Basic Avatar Types

The avatar component automatically chooses the best display mode based on the provided props.

@blade
<x-demo>
    <div class="flex flex-wrap items-center justify-center gap-4">
        <x-ui.avatar src="/youssef.jpeg" alt="Profile Picture" />
        <x-ui.avatar name="John Doe" />
        <x-ui.avatar icon="user" />
        <x-ui.avatar />
    </div>
</x-demo>
@endblade

```html
<!-- Image avatar -->
<x-ui.avatar src="/youssef.jpeg" alt="Profile Picture" />

<!-- Initials avatar (auto-generated from name) -->
<x-ui.avatar name="John Doe" />

<!-- Icon avatar -->
<x-ui.avatar icon="user" />

<!-- Fallback avatar (default user icon) -->
<x-ui.avatar />
```

### Avatar Sizes

Choose from five different sizes to match your design context and hierarchy.

@blade
<x-demo>
    <div class="flex flex-wrap items-center justify-center gap-4">
        <x-ui.avatar name="XS" size="xs" color="blue" />
        <x-ui.avatar name="SM" size="sm" color="green" />
        <x-ui.avatar name="MD" size="md" color="purple" />
        <x-ui.avatar name="LG" size="lg" color="orange" />
        <x-ui.avatar name="XL" size="xl" color="red" />
    </div>
</x-demo>
@endblade

```html
<!-- Different avatar sizes -->
<x-ui.avatar name="XS" size="xs" color="blue" />
<x-ui.avatar name="SM" size="sm" color="green" />
<x-ui.avatar name="MD" size="md" color="purple" />
<x-ui.avatar name="LG" size="lg" color="orange" />
<x-ui.avatar name="XL" size="xl" color="red" />
```

### Circular vs Rounded Avatars

Control the shape of avatars using the `circle` prop.

@blade
<x-demo>
    <div class="flex flex-wrap items-center justify-center gap-4">
        <x-ui.avatar name="Round" color="blue" />
        <x-ui.avatar name="Circle" color="green" circle />
        <x-ui.avatar icon="star" color="purple" />
        <x-ui.avatar icon="heart" color="red" circle />
    </div>
</x-demo>
@endblade

```html
<!-- Rounded rectangle avatars (default) -->
<x-ui.avatar name="Round" color="blue" />
<x-ui.avatar icon="star" color="purple" />

<!-- Circular avatars -->
<x-ui.avatar name="Circle" color="green" circle />
<x-ui.avatar icon="heart" color="red" circle />
```

### Color Variants

The avatar component supports 17 beautiful color options for initials and icon avatars.

@blade
<x-demo>
    <div class="flex flex-wrap items-center justify-center gap-2">
        <x-ui.avatar icon="user" color="red" />
        <x-ui.avatar icon="user" color="orange" />
        <x-ui.avatar icon="user" color="amber" />
        <x-ui.avatar icon="user" color="yellow" />
        <x-ui.avatar icon="user" color="lime" />
        <x-ui.avatar icon="user" color="green" />
        <x-ui.avatar icon="user" color="emerald" />
        <x-ui.avatar icon="user" color="teal" />
        <x-ui.avatar icon="user" color="cyan" />
        <x-ui.avatar icon="user" color="sky" />
        <x-ui.avatar icon="user" color="blue" />
        <x-ui.avatar icon="user" color="indigo" />
        <x-ui.avatar icon="user" color="violet" />
        <x-ui.avatar icon="user" color="purple" />
        <x-ui.avatar icon="user" color="fuchsia" />
        <x-ui.avatar icon="user" color="pink" />
        <x-ui.avatar icon="user" color="rose" />
    </div>
</x-demo>
@endblade

```html
<!-- Colored avatars -->
<x-ui.avatar icon="user" color="red" />
<x-ui.avatar icon="user" color="green" />
<x-ui.avatar icon="user" color="blue" />
<x-ui.avatar icon="user" color="purple" />
```

### Automatic Color Assignment

Use `color="auto"` to automatically assign colors based on content, perfect for user lists.

@blade
<x-demo>
    <div class="flex flex-wrap items-center justify-center gap-2">
        <x-ui.avatar name="Alice Johnson" color="auto" />
        <x-ui.avatar name="Bob Smith" color="auto" />
        <x-ui.avatar name="Charlie Brown" color="auto" />
        <x-ui.avatar name="Diana Prince" color="auto" />
        <x-ui.avatar name="Eve Wilson" color="auto" />
        <x-ui.avatar name="Frank Miller" color="auto" />
    </div>
</x-demo>
@endblade

```html
<!-- Automatic color assignment based on name -->
<x-ui.avatar name="Alice Johnson" color="auto" />
<x-ui.avatar name="Bob Smith" color="auto" />
<x-ui.avatar name="Charlie Brown" color="auto" />

<!-- Custom color seed for consistent coloring -->
<x-ui.avatar name="User" color="auto" color:seed="unique-identifier" />
```

### Initials Generation

The component automatically generates initials from names with flexible formatting options.

@blade
<x-demo>
    <div class="flex flex-wrap items-center justify-center gap-4">
        <x-ui.avatar name="John Doe" color="blue" />
        <x-ui.avatar name="Jane Smith Wilson" color="green" />
        <x-ui.avatar name="Bob" color="purple" />
        <x-ui.avatar name="Alice Johnson" initials:single color="orange" />
        <x-ui.avatar initials="XY" color="red" />
    </div>
</x-demo>
@endblade

```html
<!-- Automatic initials (first letter of first two words) -->
<x-ui.avatar name="John Doe" color="blue" />

<!-- Multiple names (first letter of first two words) -->
<x-ui.avatar name="Jane Smith Wilson" color="green" />

<!-- Single name (first letter + second letter) -->
<x-ui.avatar name="Bob" color="purple" />

<!-- Single initial only -->
<x-ui.avatar name="Alice Johnson" initials:single color="orange" />

<!-- Custom initials -->
<x-ui.avatar initials="XY" color="red" />
```

### Interactive Avatars

Make avatars clickable by adding the `href` prop for navigation.

@blade
<x-demo>
    <div class="flex flex-wrap items-center justify-center gap-4">
        <x-ui.avatar name="Profile" color="blue" href="/profile" />
        <x-ui.avatar icon="cog-8-tooth" color="gray" href="/settings" />
        <x-ui.avatar name="Admin" color="red" href="/admin" circle />
    </div>
</x-demo>
@endblade

```html
<!-- Clickable avatars -->
<x-ui.avatar name="Profile" color="blue" href="/profile" />
<x-ui.avatar icon="cog-8-tooth" color="gray" href="/settings" />
<x-ui.avatar name="Admin" color="red" href="/admin" circle />
```

### Avatars with Badges

Add status indicators or notification badges to avatars with flexible positioning and styling.

@blade
<x-demo>
    <div class="flex flex-wrap items-center justify-center gap-6">
        <x-ui.avatar name="Online" color="blue" size="lg" badge badge:color="green" />
        <x-ui.avatar name="Away" color="amber" size="lg" badge badge:color="yellow" badge:position="top right" />
        <x-ui.avatar name="Busy" color="red" size="lg" badge="5" badge:color="red" />
        <x-ui.avatar name="VIP" color="purple" size="lg" badge="👑" badge:circle />
    </div>
</x-demo>
@endblade

```html
<!-- Status badge (dot) -->
<x-ui.avatar name="Online" color="blue" badge badge:color="green" />

<!-- Positioned status badge -->
<x-ui.avatar name="Away" color="amber" badge badge:color="yellow" badge:position="top right" />

<!-- Notification count badge -->
<x-ui.avatar name="User" color="red" badge="5" badge:color="red" />

<!-- Emoji or text badge -->
<x-ui.avatar name="VIP" color="purple" badge="👑" badge:circle />
```

### Badge Positioning and Variants

Control badge placement and appearance with detailed customization options.

@blade
<x-demo>
    <div class="space-y-4">
        <div class="flex flex-wrap items-center justify-center gap-6">
            <div class="text-center">
                <x-ui.avatar name="TL" color="blue" size="lg" badge badge:color="green" badge:position="top left" />
                <p class="text-xs mt-1">Top Left</p>
            </div>
            <div class="text-center">
                <x-ui.avatar name="TR" color="green" size="lg" badge badge:color="red" badge:position="top right" />
                <p class="text-xs mt-1">Top Right</p>
            </div>
            <div class="text-center">
                <x-ui.avatar name="BL" color="purple" size="lg" badge badge:color="blue" badge:position="bottom left" />
                <p class="text-xs mt-1">Bottom Left</p>
            </div>
            <div class="text-center">
                <x-ui.avatar name="BR" color="orange" size="lg" badge badge:color="purple" badge:position="bottom right" />
                <p class="text-xs mt-1">Bottom Right (Default)</p>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center justify-center gap-6">
            <div class="text-center">
                <x-ui.avatar name="Solid" color="blue" size="lg" badge="3" badge:color="red" />
                <p class="text-xs mt-1">Solid Badge</p>
            </div>
            <div class="text-center">
                <x-ui.avatar name="Outline" color="green" size="lg" badge="5" badge:color="blue" badge:variant="outline" />
                <p class="text-xs mt-1">Outline Badge</p>
            </div>
            <div class="text-center">
                <x-ui.avatar name="Circle" color="purple" size="lg" badge="99+" badge:color="red" badge:circle />
                <p class="text-xs mt-1">Circular Badge</p>
            </div>
        </div>
    </div>
</x-demo>
@endblade

```html
<!-- Badge positioning -->
<x-ui.avatar name="User" badge badge:position="top left" />
<x-ui.avatar name="User" badge badge:position="top right" />
<x-ui.avatar name="User" badge badge:position="bottom left" />
<x-ui.avatar name="User" badge badge:position="bottom right" />

<!-- Badge variants -->
<x-ui.avatar name="User" badge="3" badge:color="red" />
<x-ui.avatar name="User" badge="5" badge:variant="outline" />
<x-ui.avatar name="User" badge="99+" badge:circle />
```

### Icon Variants and Customization

Customize icon avatars with different icon variants and styling.

@blade
<x-demo>
    <div class="flex flex-wrap items-center justify-center gap-4">
        <x-ui.avatar icon="user" iconVariant="outline" color="blue" />
        <x-ui.avatar icon="star" iconVariant="outline" color="yellow" />
        <x-ui.avatar icon="heart" iconVariant="micro" color="red" />
        <x-ui.avatar icon="home" iconVariant="micro" color="green" />
    </div>
</x-demo>
@endblade

```html
<!-- Outline icon variant -->
<x-ui.avatar icon="user" iconVariant="outline" color="blue" />
<x-ui.avatar icon="star" iconVariant="outline" color="yellow" />

<!-- Micro icon variant (default) -->
<x-ui.avatar icon="heart" iconVariant="micro" color="red" />
<x-ui.avatar icon="home" iconVariant="micro" color="green" />
```

### Avatar Groups and Lists

Create user lists and avatar groups for team displays or user collections.
@blade
<x-demo>
    <div class="space-y-6 flex flex-col items-center">
        <x-ui.heading level="3">Team Members</x-ui.heading>
        <x-ui.avatar.group >
            <x-ui.avatar name="Alice Johnson" color="auto" circle href="/user/alice" />
            <x-ui.avatar name="Bob Smith" color="auto" circle href="/user/bob" />
            <x-ui.avatar name="Charlie Brown" color="auto" circle href="/user/charlie" />
            <x-ui.avatar name="Diana Prince" color="auto" circle href="/user/diana" />
            <x-ui.avatar circle>+5</x-ui.avatar>
        </x-ui.avatar.group>
    </div>
</x-demo>
@endblade

```html
<x-ui.heading level="3">Team Members</x-ui.heading>
<x-ui.avatar.group >
    <x-ui.avatar name="Alice Johnson" color="auto" circle href="/user/alice" />
    <x-ui.avatar name="Bob Smith" color="auto" circle href="/user/bob" />
    <x-ui.avatar name="Charlie Brown" color="auto" circle href="/user/charlie" />
    <x-ui.avatar name="Diana Prince" color="auto" circle href="/user/diana" />
    <x-ui.avatar circle>+5</x-ui.avatar>
</x-ui.avatar.group>
```

## Component Props Reference

### Avatar Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `src` | `string` | `null` | Image source URL for photo avatars |
| `alt` | `string` | `null` | Alt text for images (falls back to `name`) |
| `name` | `string` | `null` | Full name for automatic initials generation |
| `initials` | `string` | `null` | Custom initials to display |
| `icon` | `string` | `null` | Icon name for icon avatars |
| `iconVariant` | `string` | `'outline'` | Icon style: `'outline'`, `'micro'` |
| `size` | `string` | `'md'` | Avatar size: `'xs'`, `'sm'`, `'md'`, `'lg'`, `'xl'` |
| `color` | `string` | `null` | Color theme or `'auto'` for automatic assignment |
| `circle` | `boolean` | `false` | Whether to make avatar circular |
| `href` | `string` | `null` | URL to make avatar clickable |
| `as` | `string` | `'div'` | HTML element type |
| `badge` | `string\|boolean` | `null` | Badge content or `true` for dot badge |

### Special Attributes

| Attribute | Description | Example |
|-----------|-------------|---------|
| `initials:single` | Generate single initial from name | `<x-ui.avatar name="John" initials:single />` |
| `color:seed` | Custom seed for auto color generation | `<x-ui.avatar color="auto" color:seed="unique-id" />` |

### Badge Props

Badge customization uses prefixed attributes:

| Badge Prop | Type | Default | Description |
|------------|------|---------|-------------|
| `badge:color` | `string` | `'white'` | Badge background color |
| `badge:position` | `string` | `'bottom right'` | Badge position |
| `badge:variant` | `string` | `null` | Badge style: `'outline'` or `null` |
| `badge:circle` | `boolean` | `false` | Make badge circular |

### Size Reference

| Size | Avatar Dimensions | Text Size | Icon Size | Use Case |
|------|------------------|-----------|-----------|----------|
| `'xs'` | 24px (1.5rem) | `text-xs` | 16px | Compact lists, inline mentions |
| `'sm'` | 32px (2rem) | `text-sm` | 20px | User lists, comments |
| `'md'` | 40px (2.5rem) | `text-sm` | 24px | Standard usage, navigation |
| `'lg'` | 48px (3rem) | `text-base` | 32px | Profile cards, headers |
| `'xl'` | 64px (4rem) | `text-base` | 32px | Profile pages, showcases |

### Color Palette

| Color | Background | Text | Use Case |
|-------|------------|------|----------|
| `red` | Light red | Dark red | Error states, admin users |
| `orange` | Light orange | Dark orange | Warnings, moderators |
| `amber` | Light amber | Dark amber | Pending states |
| `yellow` | Light yellow | Dark yellow | Highlights, VIP users |
| `lime` | Light lime | Dark lime | Success variations |
| `green` | Light green | Dark green | Success, online status |
| `emerald` | Light emerald | Dark emerald | Nature, eco themes |
| `teal` | Light teal | Dark teal | Medical, professional |
| `cyan` | Light cyan | Dark cyan | Technology, innovation |
| `sky` | Light sky | Dark sky | Communication, social |
| `blue` | Light blue | Dark blue | Primary, trust |
| `indigo` | Light indigo | Dark indigo | Premium, enterprise |
| `violet` | Light violet | Dark violet | Creative, artistic |
| `purple` | Light purple | Dark purple | Luxury, premium |
| `fuchsia` | Light fuchsia | Dark fuchsia | Fashion, lifestyle |
| `pink` | Light pink | Dark pink | Personal, friendly |
| `rose` | Light rose | Dark rose | Romance, personal |

### Badge Colors

| Color | Light Mode | Dark Mode | Common Usage |
|-------|------------|-----------|--------------|
| `red` | `bg-red-500` | `bg-red-400` | Notifications, errors |
| `green` | `bg-green-500` | `bg-green-400` | Online status, success |
| `yellow` | `bg-yellow-500` | `bg-yellow-400` | Away status, warnings |
| `blue` | `bg-blue-500` | `bg-blue-400` | Information, messages |
| `purple` | `bg-purple-500` | `bg-purple-400` | Special status, premium |
| `white` | `bg-white` | `bg-neutral-900` | Default, neutral status |

### Badge Positions

| Position | CSS Classes | Visual Location |
|----------|-------------|-----------------|
| `'top left'` | `top-0 left-0` | Upper left corner |
| `'top right'` | `top-0 right-0` | Upper right corner |
| `'bottom left'` | `bottom-0 left-0` | Lower left corner |
| `'bottom right'` | `bottom-0 right-0` | Lower right corner (default) |
