---
name: radio
---

## Introduction

The `Radio` component is a responsive, accessible form control component designed for single-choice selection. It provides a clean foundation for building radio button groups with consistent styling, multiple variants, and seamless dark mode support.

## Installation


```bash
```

> Once installed, you can use the <x-ui.radio.group /> and <x-ui.radio.item /> components in any Blade view.

## Usage


### Bind To Livewire

To use with Livewire you only need to use `wire:model="property"` to bind your input state:

```html
<x-ui.radio.group wire:model="role" label="Roles" name="roles">
    <x-ui.radio.item value="backend" label="Back end" checked />
    <x-ui.radio.item value="frontend" label="Front end" />
    <x-ui.radio.item value="devops" label="DevOps" />
</x-ui.radio.group>
```

### Using it within Blade & Alpine

You can use it outside Livewire with just Alpine (and Blade):

```html
<x-ui.radio.group x-model="role" label="Roles" name="roles">
    <x-ui.radio.item value="backend" label="Back end" checked />
    <x-ui.radio.item value="frontend" label="Front end" />
    <x-ui.radio.item value="devops" label="DevOps" />
</x-ui.radio.group>
```

### Basic Radio Group
@blade
<x-demo class="flex justify-center">
    <div >
        <x-ui.radio.group label="Roles" name="roles" >
            <x-ui.radio.item value="backend" label="Back end" checked />
            <x-ui.radio.item value="frontend" label="Front end" />
            <x-ui.radio.item value="devops" label="DevOps" />
        </x-ui.radio.group>
    </div>
</x-demo>
@endblade

```html
    <x-ui.radio.group name="roles" label="Roles">
        <x-ui.radio.item value="backend" label="Back end" checked />
        <x-ui.radio.item value="frontend" label="Front end" />
        <x-ui.radio.item value="devops" label="DevOps" />
    </x-ui.radio.group>
```

### With error message
@blade
<x-demo class="flex justify-center">
    <div >
        <x-ui.radio.group error="The selected role not supported" label="Roles" name="roles-1" >
            <x-ui.radio.item value="backend" label="Back end" checked />
            <x-ui.radio.item value="frontend" label="Front end" />
            <x-ui.radio.item value="devops" label="DevOps" />
        </x-ui.radio.group>
    </div>
</x-demo>
@endblade

```html
    <x-ui.radio.group  error="The selected role not supported" name="roles" label="Roles">
        <x-ui.radio.item value="backend" label="Back end" checked />
        <x-ui.radio.item value="frontend" label="Front end" />
        <x-ui.radio.item value="devops" label="DevOps" />
    </x-ui.radio.group>
```

### Radio with description

Add helpful descriptions to provide additional context for each option.

@blade
<x-demo class="flex justify-center">
    <div >
        <x-ui.radio.group name="permissions" label="Permissions">
            <x-ui.radio.item value="administrator" label="Administrator" description="Administrator users can perform any action." checked />
            <x-ui.radio.item value="editor" label="Editor" description="Editor users have the ability to read, create, and update." />
            <x-ui.radio.item value="viewer" label="Viewer" description="Viewer users only have the ability to read. Create, and update are restricted." />
        </x-ui.radio.group>
    </div>
</x-demo>
@endblade

```html
<x-ui.radio.group name="permissions" label="Permissions">
    <x-ui.radio.item 
    value="administrator" 
    label="Administrator" 
    description="Administrator users can perform any action." 
    checked />
    <x-ui.radio.item 
    value="editor" 
    label="Editor" 
    description="Editor users have the ability to read, create, and update." 
    />
    <x-ui.radio.item 
    value="viewer" 
    label="Viewer" 
    description="Viewer users only have the ability to read. Create, and update are restricted." 
    />
</x-ui.radio.group>
```


### Segmented Radio (Horizontal)
Create a more compact, button-like appearance with the segmented variant.
@blade
<x-demo class="flex justify-center">
    <div>
        <x-ui.radio.group name="view-mode" label="View Mode"  variant="segmented" direction="horizontal">
            <x-ui.radio.item
            value="list"
            label="List"
            />
            <x-ui.radio.item
            value="grid"
            label="Grid"
            />
            <x-ui.radio.item
            value="table"
            label="Table"
            checked
            />
        </x-ui.radio.group>
    </div>
</x-demo>
@endblade

```html
<x-ui.radio.group name="view-mode" label="View Mode" variant="segmented" direction="horizontal">
    <x-ui.radio.item
    value="list"
    label="List"
    />
    <x-ui.radio.item
    value="grid"
    label="Grid"
    checked
    />
    <x-ui.radio.item
    value="table"
    label="Table"
    />
</x-ui.radio.group>
```

### Segmented Radio with Icons
Enhance the segmented variant with icons for better visual communication.
@blade
<x-demo class="flex justify-center">
    <div >
        <x-ui.radio.group name="environment" :indicator="false" label="Deployment Environment" variant="segmented" direction="horizontal">
            <x-ui.radio.item
                icon="code-bracket"
                value="development"
                label="Development"
            />
            <x-ui.radio.item
                icon="beaker"
                value="staging"
                label="Staging"
                checked
            />
            <x-ui.radio.item
                icon="globe-alt"
                value="production"
                label="Production"
            />
        </x-ui.radio.group>
    </div>
</x-demo>
@endblade


```html
<x-ui.radio.group name="environment" label="Deployment Environment" :indicator="false" variant="segmented" direction="horizontal">
    <x-ui.radio.item
        icon="code-bracket"
        value="development"
        label="Development"
    />
    <x-ui.radio.item
        icon="beaker"
        value="staging"
        label="Staging"
        checked
    />
    <x-ui.radio.item
        icon="globe-alt"
        value="production"
        label="Production"
    />
</x-ui.radio.group>
```


### Selection Cards
Prominent card-style options with clear boundaries and descriptions. Ideal for important decisions, plan selections, or feature comparisons where each option needs significant visual weight.

@blade
<x-demo class="flex justify-center">
    <div class="w-full mx-auto">
        <x-ui.radio.group name="billing" label="Billing Frequency" variant="cards" direction="horizontal">
            <x-ui.radio.item
            value="monthly"
            label="Monthly"
            description="$29/month - Pay as you go"
            />
            <x-ui.radio.item
            value="yearly"
            label="Yearly"
            description="$290/year - Save 17% annually"
            checked
            />
            <x-ui.radio.item
            value="lifetime"
            label="Lifetime"
            description="$999 - One-time payment"
            />
        </x-ui.radio.group>
    </div>
</x-demo>
@endblade


```html
<x-ui.radio.group name="billing" label="Billing Frequency"  variant="cards" direction="horizontal">
    <x-ui.radio.item
        value="monthly"
        label="Monthly"
        description="$29/month - Pay as you go"
    />
    <x-ui.radio.item
        value="yearly"
        label="Yearly"
        description="$290/year - Save 17% annually"
        checked
    />
    <x-ui.radio.item
        value="lifetime"
        label="Lifetime"
        description="$999 - One-time payment"
    />
</x-ui.radio.group>
```

### Clean Selection Cards
Card-style options without traditional radio indicators for a cleaner, more modern look. Great for product selections, service tiers, or any choice where the card itself acts as the selection indicator.

@blade
<x-demo class="flex justify-center">
    <div class="w-full mx-auto">
        <x-ui.radio.group 
        :indicator="false" 
        label="Shipping Method" 
        name="shipping" 
        variant="cards" 
        direction="horizontal">
            <x-ui.radio.item
            value="standard"
            label="Standard Delivery"
            description="5-7 business days - Free"
            />
            <x-ui.radio.item
            value="express"
            label="Express Delivery"
            description="2-3 business days - $9.99"
            checked
            />
            <x-ui.radio.item
            value="overnight"
            label="Overnight"
            description="Next business day - $24.99"
            />
        </x-ui.radio.group>
    </div>
</x-demo>
@endblade


```html
<x-ui.radio.group 
    :indicator="false" 
    label="Shipping Method" 
    name="shipping" 
    variant="cards" 
    direction="horizontal"
>
        <x-ui.radio.item
            value="standard"
            label="Standard Delivery"
            description="5-7 business days - Free"
        />
        <x-ui.radio.item
            value="express"
            label="Express Delivery"
            description="2-3 business days - $9.99"
            checked
        />
        <x-ui.radio.item
            value="overnight"
            label="Overnight"
            description="Next business day - $24.99"
        />
</x-ui.radio.group>
```


### Icon Feature Cards
Card-style options enhanced with icons for better visual hierarchy. Perfect for feature selection, service categories, or any scenario where icons help communicate the option's purpose.

@blade
<x-demo class="flex justify-center">
    <div class="w-full mx-auto">
        <x-ui.radio.group name="storage" label="Storage Options" variant="cards" direction="horizontal">
            <x-ui.radio.item
            icon="cloud"
            value="cloud"
            label="Cloud Storage"
            description="Secure cloud-based storage"
            />
            <x-ui.radio.item
            icon="server-stack"
            value="server"
            label="Local Server"
            description="On-premises server storage"
            checked
            />
            <x-ui.radio.item
            icon="shield-check"
            value="encrypted"
            label="Encrypted Storage"
            description="End-to-end encrypted storage"
            />
        </x-ui.radio.group>
    </div>
</x-demo>
@endblade


```html
<x-ui.radio.group name="storage" label="Storage Options" variant="cards" direction="horizontal">
    <x-ui.radio.item
        icon="cloud"
        value="cloud"
        label="Cloud Storage"
        description="Secure cloud-based storage"
    />
    <x-ui.radio.item
        icon="server-stack"
        value="server"
        label="Local Server"
        description="On-premises server storage"
        checked
    />
    <x-ui.radio.item
        icon="shield-check"
        value="encrypted"
        label="Encrypted Storage"
        description="End-to-end encrypted storage"
    />
</x-ui.radio.group>
```


### Pill Style Selection
Compact, rounded pill-style options that work well for tags, categories, or filters. Great for space-constrained layouts or when you need multiple selection groups in close proximity.

@blade
<x-demo class="flex justify-center">
    <div >
        <x-ui.radio.group name="priority" label="Task Priority" variant="pills" direction="horizontal">
            <x-ui.radio.item
            value="low"
            label="Low"
            checked
            />
            <x-ui.radio.item
            value="medium"
            label="Medium"
            />
            <x-ui.radio.item
            value="high"
            label="High"
            />
        </x-ui.radio.group>
    </div>
</x-demo>
@endblade


```html
<x-ui.radio.group name="priority" label="Task Priority" variant="pills" direction="horizontal">
    <x-ui.radio.item
        value="low"
        label="Low"
        checked
    />
    <x-ui.radio.item
        value="medium"
        label="Medium"
    />
    <x-ui.radio.item
        value="high"
        label="High"
    />
</x-ui.radio.group>
```

## Component Props Reference

### Radio Group Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | `string` | `''` | The label text displayed above the radio group |
| `required` | `boolean` | `false` | Whether the radio group is required for form submission |
| `error` | `string` | `''` | Error message to display for validation |
| `direction` | `string` | `'vertical'` | Layout direction. Use `'horizontal'` for horizontal layout, `'vertical'` for vertical |
| `disabled` | `boolean` | `false` | Whether the entire radio group is disabled |
| `variant` | `string` | `'default'` | Visual style variant: `'default'`, `'segmented'`, `'cards'`, or `'pills'` |
| `labelClass` | `string` | `''` | Additional CSS classes for the group label |
| `indicator` | `boolean` | `true` | Whether to show radio button indicators |
| `name` | `string` | `''` | The name attribute for all radio inputs in the group |

### Radio Item Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `value` | `string` | **Required** | The value of the radio input |
| `label` | `string` | **Required** | The label text for the radio option |
| `checked` | `boolean` | `false` | Whether this radio option is selected by default |
| `description` | `string` | `''` | Additional description text below the label |
| `icon` | `string` | `''` | Icon name to display (works with segmented and cards variants) |
| `iconVariant` | `string` | `'outline'` | Icon variant style |
| `iconClass` | `string` | `''` | Additional CSS classes for the icon |

### Inherited Props (Radio Item)

These props are automatically inherited from the parent `radio.group` component:

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `variant` | `string` | `'default'` | Visual style variant inherited from group |
| `disabled` | `boolean` | `false` | Disabled state inherited from group |
| `indicator` | `boolean` | `true` | Show indicators inherited from group |
| `name` | `string` | `''` | Form name inherited from group |