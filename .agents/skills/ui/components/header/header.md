---
name: header
---

## Introduction


The `Header` component is a **sticky**, **responsive** application header designed to sit at the top of your main content area. It provides a consistent navigation bar with mobile menu toggle integration and seamless integration with the sidebar layout system.

## Installation


@blade
<x-md.cta                                                            
    href="/docs/layouts/layout"                                    
    label="this component cames with the layout component"
    variant="slide"                                               
/>
@endblade


> Once installed, you can use the `<x-ui.layout.header>` component within your layout's main area.

## Usage

### Basic Header

The simplest implementation with a navbar and user actions:

```blade
<x-ui.layout.main>
    <x-ui.layout.header>
        <x-ui.navbar class="flex-1">
            <x-ui.navbar.item icon="home" label="Home" href="/" />
            <x-ui.navbar.item icon="cog-6-tooth" label="Settings" href="/settings" />
        </x-ui.navbar>
        
        <div class="flex gap-x-3 items-center">
            <x-ui.avatar size="sm" src="/avatar.png" circle />
            <x-ui.theme-switcher variant="inline" />
        </div>
    </x-ui.layout.header>

    <div class="p-6">
        <!-- Your page content -->
    </div>
</x-ui.layout.main>
```

### Header with Navigation Only

A simple header focused on navigation:

```blade
<x-ui.layout.header>
    <x-ui.navbar class="flex-1">
        <x-ui.navbar.item icon="home" label="Dashboard" href="/dashboard" />
        <x-ui.navbar.item icon="chart-bar" label="Analytics" href="/analytics" />
        <x-ui.navbar.item icon="users" label="Team" href="/team" />
        <x-ui.navbar.item icon="folder" label="Projects" href="/projects" />
    </x-ui.navbar>
</x-ui.layout.header>
```

### Header with Search and Actions

Add search functionality and action buttons:

```blade
<x-ui.layout.header>
    <x-ui.navbar class="flex-1">
        <x-ui.navbar.item icon="home" label="Home" href="/" />
        <x-ui.navbar.item icon="document-text" label="Docs" href="/docs" />
    </x-ui.navbar>

    <div class="flex items-center gap-x-4">
        <x-ui.input 
            placeholder="Search..." 
            icon="magnifying-glass"
            class="w-64"
        />
        <x-ui.button variant="solid" size="sm" icon="plus">
            New Project
        </x-ui.button>
        <x-ui.avatar src="/mohamed.png" circle size="sm" />
    </div>
</x-ui.layout.header>
```

### Mobile Menu Toggle

the toggler appears automatically next to the brand on larger screens. On mobile, add it manually inside your header.

```blade
<x-ui.layout.header>
    <x-ui.sidebar.toggle class="md:hidden" />
    <!-- navbar -->
</x-ui.layout.header>
```

Use `md:hidden` to show it only on mobile. The variant handles everything else automatically on larger screens.


## Component Props

### Header Component

| Prop Name | Type  | Default | Required | Description                      |
| --------- | ----- | ------- | -------- | -------------------------------- |
| `slot`    | mixed | `''`    | Yes      | Header content (navbar, actions) |

## Component Structure

The header component consists of:

- **Main Container**: `<x-ui.layout.header>` - The header wrapper with border and padding
- **Mobile Toggle**: Auto-included button for mobile sidebar control (internal)
- **Content Area**: Flexible space for navigation and actions

## Header Height
@blade
<x-md.cta                                                            
    href="/docs/layouts/layout#content-essential-css-variables"                                    
    label="needs to change the header height ?"
    ctaLabel="Visit Docs"
/>
@endblade
## Advanced Examples

### Using Dropdown Menus

```blade
<x-ui.layout.header>
    <x-ui.sidebar.toggle class="md:hidden"/>
    <x-ui.navbar class="flex-1 hidden lg:flex">
        <x-ui.navbar.item
            icon="home"
            label="Home" 
            href="#"
        />
        <x-ui.navbar.item 
            icon="cog-6-tooth" 
            label="Settings" 
            badge="3"
            badge:color="orange"
            badge:variant="outline"
            href="#"                    
        />
        <x-ui.dropdown>
            <x-slot:button>
                <x-ui.navbar.item 
                    icon="shopping-bag"
                    icon:variant="min" 
                    label="Store" 
                />
            </x-slot:button>
            
            <x-slot:menu>
                <x-ui.dropdown.item icon="shopping-bag" href="#">
                    Products
                </x-ui.dropdown.item>
                <x-ui.dropdown.item icon="receipt-percent" href="#">
                    Orders
                </x-ui.dropdown.item>
                <x-ui.dropdown.item icon="users" href="#">
                    Customers
                </x-ui.dropdown.item>
                <x-ui.dropdown.item icon="ticket" href="#">
                    Discounts
                </x-ui.dropdown.item>
            </x-slot:menu>
        </x-ui.dropdown>
    </x-ui.navbar>

    <div class="flex ml-auto gap-x-3 items-center">
            <x-ui.dropdown position="bottom-end">
            <x-slot:button class="justify-center">
                <x-ui.avatar size="sm" src="/iman.png" circle alt="Profile Picture" />
            </x-slot:button>

            <x-slot:menu class="w-56">
                <x-ui.dropdown.group label="signed in as">
                    <x-ui.dropdown.item>
                        iman@gmail.com
                    </x-ui.dropdown.item>
                </x-ui.dropdown.group>

                <x-ui.dropdown.separator />

                <x-ui.dropdown.item href="#" wire:navigate.live>
                    Account
                </x-ui.dropdown.item>

                <x-ui.dropdown.separator />

                <form
                    action="#"
                    method="post"
                    class="contents"
                >
                    @csrf
                    <x-ui.dropdown.item as="button" type="submit">
                        Sign Out
                    </x-ui.dropdown.item>
                </form>

            </x-slot:menu>
        </x-ui.dropdown>

        <x-ui.theme-switcher variant="inline" />
    </div>
</x-ui.layout.header>
```

### Conditional Header Content

```blade
<x-ui.layout.header>
    <x-ui.navbar class="flex-1">
        <x-ui.navbar.item icon="home" label="Home" href="/" />
        
        @auth
            <x-ui.navbar.item icon="folder" label="My Projects" href="/projects" />
            <x-ui.navbar.item icon="star" label="Favorites" href="/favorites" />
        @endauth
    </x-ui.navbar>

    <div class="flex items-center gap-x-3">
        @auth
            <x-ui.avatar src="{{ auth()->user()->avatar }}" circle size="sm" />
        @else
            <x-ui.button variant="primary" href="/login">Sign In</x-ui.button>
        @endauth
    </div>
</x-ui.layout.header>
```
