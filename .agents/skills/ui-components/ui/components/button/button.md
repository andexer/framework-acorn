---
name: button
---

# Button Component

## Introduction

The `button` component provides a reusable UI element that saves you from creating complex button elements each time you need one. It offers multiple variants, sizes, and customization options to fit your design needs.

## Basic Usage

@blade
<x-demo>
    <x-ui.button>
        Primary
    </x-ui.button>        
    <x-ui.button variant="ghost">
        Ghost
    </x-ui.button>
    <x-ui.button variant="outline">
        Outline
    </x-ui.button>
</x-demo>
@endblade

```html
<x-ui.button>
    Button
</x-ui.button>    
<x-ui.button variant="ghost">
    Ghost
</x-ui.button>
<x-ui.button variant="outline">
    Outline
</x-ui.button>
```

## Customization

### Sizes

The button component uses `md` size as the default, but you can adjust the size to meet your needs.

@blade
<x-demo>
    <x-ui.button size="lg">
        Large
    </x-ui.button>
    <x-ui.button>  
        Medium (Default)
    </x-ui.button>    
    <x-ui.button size="sm">
        Small
    </x-ui.button>
    <x-ui.button size="xs">
        Extra Small
    </x-ui.button>
</x-demo>
@endblade

```html
<x-ui.button size="lg">
    Large
</x-ui.button>
<!-- Default size is 'md' -->
<x-ui.button>
    Medium (Default)
</x-ui.button>    
<x-ui.button size="sm">
    Small
</x-ui.button>
<x-ui.button size="xs">
    Extra Small
</x-ui.button>
```

### Colors

A wide range of colors are available for customizing button appearance.

#### Primary variant
@blade
<x-demo>
    <div class="grid grid-cols-6 gap-4">
        <x-ui.button color="slate">Slate</x-ui.button>
        <x-ui.button color="stone">Stone</x-ui.button>
        <x-ui.button color="neutral">Neutral</x-ui.button>
        <x-ui.button color="zinc">Zinc</x-ui.button>
        <x-ui.button color="red">Red</x-ui.button>
        <x-ui.button color="orange">Orange</x-ui.button>
        <x-ui.button color="amber">Amber</x-ui.button>
        <x-ui.button color="yellow">Yellow</x-ui.button>
        <x-ui.button color="lime">Lime</x-ui.button>
        <x-ui.button color="green">Green</x-ui.button>
        <x-ui.button color="emerald">Emerald</x-ui.button>
        <x-ui.button color="teal">Teal</x-ui.button>
        <x-ui.button color="cyan">Cyan</x-ui.button>
        <x-ui.button color="sky">Sky</x-ui.button>
        <x-ui.button color="blue">Blue</x-ui.button>
        <x-ui.button color="indigo">Indigo</x-ui.button>
        <x-ui.button color="violet">Violet</x-ui.button>
        <x-ui.button color="purple">Purple</x-ui.button>
        <x-ui.button color="fuchsia">Fuchsia</x-ui.button>
        <x-ui.button color="pink">Pink</x-ui.button>
        <x-ui.button color="rose">Rose</x-ui.button>
    </div>  
</x-demo>
@endblade

```html
<div class="grid grid-cols-6 gap-4">
    <x-ui.button color="slate">Slate</x-ui.button>
    <x-ui.button color="stone">Stone</x-ui.button>
    <x-ui.button color="neutral">Neutral</x-ui.button>
    <x-ui.button color="zinc">Zinc</x-ui.button>
    <x-ui.button color="red">Red</x-ui.button>
    <x-ui.button color="orange">Orange</x-ui.button>
    <x-ui.button color="amber">Amber</x-ui.button>
    <x-ui.button color="yellow">Yellow</x-ui.button>
    <x-ui.button color="lime">Lime</x-ui.button>
    <x-ui.button color="green">Green</x-ui.button>
    <x-ui.button color="emerald">Emerald</x-ui.button>
    <x-ui.button color="teal">Teal</x-ui.button>
    <x-ui.button color="cyan">Cyan</x-ui.button>
    <x-ui.button color="sky">Sky</x-ui.button>
    <x-ui.button color="blue">Blue</x-ui.button>
    <x-ui.button color="indigo">Indigo</x-ui.button>
    <x-ui.button color="violet">Violet</x-ui.button>
    <x-ui.button color="purple">Purple</x-ui.button>
    <x-ui.button color="fuchsia">Fuchsia</x-ui.button>
    <x-ui.button color="pink">Pink</x-ui.button>
    <x-ui.button color="rose">Rose</x-ui.button>
</div>  
```

#### Outline variant
@blade
<x-demo>
    <div class="grid grid-cols-6 gap-4">
    <x-ui.button variant="outline" color="slate">Slate</x-ui.button>
    <x-ui.button variant="outline" color="stone">Stone</x-ui.button>
    <x-ui.button variant="outline" color="neutral">Neutral</x-ui.button>
    <x-ui.button variant="outline" color="zinc">Zinc</x-ui.button>
    <x-ui.button variant="outline" color="red">Red</x-ui.button>
    <x-ui.button variant="outline" color="orange">Orange</x-ui.button>
    <x-ui.button variant="outline" color="amber">Amber</x-ui.button>
    <x-ui.button variant="outline" color="yellow">Yellow</x-ui.button>
    <x-ui.button variant="outline" color="lime">Lime</x-ui.button>
    <x-ui.button variant="outline" color="green">Green</x-ui.button>
    <x-ui.button variant="outline" color="emerald">Emerald</x-ui.button>
    <x-ui.button variant="outline" color="teal">Teal</x-ui.button>
    <x-ui.button variant="outline" color="cyan">Cyan</x-ui.button>
    <x-ui.button variant="outline" color="sky">Sky</x-ui.button>
    <x-ui.button variant="outline" color="blue">Blue</x-ui.button>
    <x-ui.button variant="outline" color="indigo">Indigo</x-ui.button>
    <x-ui.button variant="outline" color="violet">Violet</x-ui.button>
    <x-ui.button variant="outline" color="purple">Purple</x-ui.button>
    <x-ui.button variant="outline" color="fuchsia">Fuchsia</x-ui.button>
    <x-ui.button variant="outline" color="pink">Pink</x-ui.button>
    <x-ui.button variant="outline" color="rose">Rose</x-ui.button>
    </div>  
</x-demo>
@endblade

```html
<div class="grid grid-cols-3 lg:grid-cols-6 gap-4">
     <x-ui.button variant="outline" color="slate">Slate</x-ui.button>
    <x-ui.button variant="outline" color="stone">Stone</x-ui.button>
    <x-ui.button variant="outline" color="neutral">Neutral</x-ui.button>
    <x-ui.button variant="outline" color="zinc">Zinc</x-ui.button>
    <x-ui.button variant="outline" color="red">Red</x-ui.button>
    <x-ui.button variant="outline" color="orange">Orange</x-ui.button>
    <x-ui.button variant="outline" color="amber">Amber</x-ui.button>
    <x-ui.button variant="outline" color="yellow">Yellow</x-ui.button>
    <x-ui.button variant="outline" color="lime">Lime</x-ui.button>
    <x-ui.button variant="outline" color="green">Green</x-ui.button>
    <x-ui.button variant="outline" color="emerald">Emerald</x-ui.button>
    <x-ui.button variant="outline" color="teal">Teal</x-ui.button>
    <x-ui.button variant="outline" color="cyan">Cyan</x-ui.button>
    <x-ui.button variant="outline" color="sky">Sky</x-ui.button>
    <x-ui.button variant="outline" color="blue">Blue</x-ui.button>
    <x-ui.button variant="outline" color="indigo">Indigo</x-ui.button>
    <x-ui.button variant="outline" color="violet">Violet</x-ui.button>
    <x-ui.button variant="outline" color="purple">Purple</x-ui.button>
    <x-ui.button variant="outline" color="fuchsia">Fuchsia</x-ui.button>
    <x-ui.button variant="outline" color="pink">Pink</x-ui.button>
    <x-ui.button variant="outline" color="rose">Rose</x-ui.button>>
</div>  
```

### Variants

The component supports 7 different visual variants:

@blade
<x-demo>
    <div class="flex flex-wrap gap-3">
        <x-ui.button>
            Button
        </x-ui.button>    
        <x-ui.button variant="gradient">
            Gradient
        </x-ui.button>
        <x-ui.button variant="danger">
            Danger
        </x-ui.button>     
        <x-ui.button variant="outline">
            Outline
        </x-ui.button>
        <x-ui.button variant="solid">
            solid
        </x-ui.button>
        <x-ui.button variant="ghost">
            Ghost
        </x-ui.button>
        <x-ui.button variant="soft">
            soft
        </x-ui.button>
    </div>
</x-demo>
@endblade

```html
<x-ui.button>
    Button
</x-ui.button>    
<x-ui.button variant="gradient">
    Gradient
</x-ui.button>
<x-ui.button variant="ghost">
    Ghost
</x-ui.button>
<x-ui.button variant="outline">
    Outline
</x-ui.button>
<x-ui.button variant="solid">
    solid
</x-ui.button>
<x-ui.button variant="soft">
    soft
</x-ui.button>
<x-ui.button variant="danger">
    Danger
</x-ui.button>   
```

### Icons

The button component supports icons both before and after the text content.

@blade
<x-demo>
    <div class="flex items-center my-6 gap-2">    
        <x-ui.button size="lg" iconAfter="arrows-pointing-out">Full Screen</x-ui.button>
        <x-ui.button icon="arrow-path">Loading...</x-ui.button>
        <x-ui.button size="sm" iconAfter="arrow-trending-up"/>
        <x-ui.button size="xs" icon="ellipsis-horizontal" />
    </div>
</x-demo>
@endblade

```html
<div class="flex gap-2">    
    <x-ui.button size="lg" iconAfter="arrows-pointing-out">Full Screen</x-ui.button>
    <x-ui.button icon="arrow-path">Loading...</x-ui.button>
    <x-ui.button size="sm" iconAfter="arrow-trending-up"/>
    <x-ui.button size="xs" icon="ellipsis-horizontal" />
</div>
```
## Loading State

The button can display a loading indicator when a Livewire action is being processed or when a targeted property is updating.

### Livewire Loading

By default, the loading state is triggered by a `wire:click` or `wire:submit` action. 

@blade
<x-demo>
    <x-ui.button size="xs" loading />
    <div class="ml-2">
        <x-ui.button loading />
    </div>
    <div class="ml-4">
        <x-ui.button loading>Processing...</x-ui.button>
    </div>
</x-demo>
@endblade

```html
<x-ui.button size="xs" wire:loading />

<div class="ml-2">
    <x-ui.button wire:loading />
</div>

<div class="ml-4">
    <x-ui.button wire:loading>Processing...</x-ui.button>
</div>
```
> You can override this using `wire:target`. See [Livewire Loading Docs](https://livewire.laravel.com/docs/wire-loading#targeting-specific-actions) for more information.
### Static Loading

You can also display the loading indicator manually using the `loading` prop, regardless of Livewire actions:

@blade
<x-demo>
    <x-ui.button loading>Processing...</x-ui.button>
</x-demo>
@endblade

```html
<x-ui.button :loading="true">Processing...</x-ui.button>
```


## `div` vs `a` vs `button`

by default the button is button tag , you can change it to a link by providing `href` attribtue:

@blade
<x-demo>
    <x-ui.button href="https://convergephp.com" iconAfter="arrow-up-right" class="text-lg" target="_blank">
        convergephp
    </x-ui.button>
</x-demo>
@endblade

```html
    <x-ui.button href="https://convergephp.com" target="_blank">
        converge
    </x-ui.button>
```

## Component Props

| Prop Name | Type | Default | Required | Description |
|-----------|------|---------|----------|-------------|
| `variant` | string | `default` | No | Button visual style: `default`, `outline`, `ghost`, `filled`, `danger`, `slate` |
| `size` | string | `md` | No | Button size: `xs`, `sm`, `md`, `lg` |
| `color` | string | `blue` | No | Button color theme: `zinc`, `red`, `orange`, `amber`, `yellow`, `lime`, `green`, `emerald`, `teal`, `cyan`, `sky`, `blue`, `indigo`, `violet`, `purple`, `fuchsia`, `pink`, `rose` |
| `icon` | string | `''` | No | Icon name to display before the button text |
| `iconAfter` | string | `''` | No | Icon name to display after the button text |
| `loading` | boolean | `false` | No | Whether to show loading indicator |
| `disabled` | boolean | `false` | No | Whether the button is disabled |
| `type` | string | `button` | No | HTML button type: `button`, `submit`, `reset` |
| `class` | string | `''` | No | Additional CSS classes to apply to the button |