---
name: 'fieldset'
---

## Introduction

The `fieldset` component provides a semantic and visually appealing way to group related form fields.

> always use it with `field` component, it manage spacing properly.

## Installation


```bash
```

## Basic Usage

@blade
<x-demo>
    <x-ui.fieldset label="Personal Information">
        <x-ui.field required>
            <x-ui.label>Full Name</x-ui.label>
            <x-ui.input placeholder="John Doe" />
        </x-ui.field>
        <x-ui.field required>
            <x-ui.label>Email Address</x-ui.label>
            <x-ui.input type="email" placeholder="john@example.com" />
        </x-ui.field>
        <x-ui.field>
            <x-ui.label>Phone Number</x-ui.label>
            <x-ui.input type="tel" placeholder="+1 (555) 123-4567" />
        </x-ui.field>
    </x-ui.fieldset>
</x-demo>
@endblade

```html
<x-ui.fieldset label="Personal Information">
    <x-ui.field required>
        <x-ui.label>Full Name</x-ui.label>
        <x-ui.input placeholder="John Doe" />
    </x-ui.field>
    
    <x-ui.field required>
        <x-ui.label>Email Address</x-ui.label>
        <x-ui.input type="email" placeholder="john@example.com" />
    </x-ui.field>
    
    <x-ui.field>
        <x-ui.label>Phone Number</x-ui.label>
        <x-ui.input type="tel" placeholder="+1 (555) 123-4567" />
    </x-ui.field>
</x-ui.fieldset>
```

## Without Label

Create fieldsets without visible labels for simple grouping.

@blade
<x-demo>
    <x-ui.fieldset>
        <x-ui.field required>
            <x-ui.label>Username</x-ui.label>
            <x-ui.input placeholder="johndoe" />
        </x-ui.field>
        <!--  -->
        <x-ui.field required>
            <x-ui.label>Password</x-ui.label>
            <x-ui.input type="password" placeholder="••••••••" />
        </x-ui.field>
    </x-ui.fieldset>
</x-demo>
@endblade

```html
<x-ui.fieldset>
    <!-- no label prop pass throught -->
</x-ui.fieldset>
```

## Grid Layout Inside Fieldset

Combine fieldsets with grid layouts for responsive form organization.

@blade
<x-demo>
    <x-ui.fieldset label="Contact Information">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-ui.field required>
                <x-ui.label>First Name</x-ui.label>
                <x-ui.input placeholder="John" />
            </x-ui.field>
            <!--  -->
            <x-ui.field required>
                <x-ui.label>Last Name</x-ui.label>
                <x-ui.input placeholder="Doe" />
            </x-ui.field>
        </div>
        <!--  -->
        <x-ui.field required>
            <x-ui.label>Email Address</x-ui.label>
            <x-ui.input type="email" placeholder="john.doe@example.com" />
        </x-ui.field>
        <!--  -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-ui.field>
                <x-ui.label>Phone</x-ui.label>
                <x-ui.input type="tel" placeholder="+1 (555) 123-4567" />
            </x-ui.field>
            <!--  -->
            <x-ui.field>
                <x-ui.label>Country</x-ui.label>
                <x-ui.select>
                    <x-ui.select.option value="us">United States</x-ui.select.option>
                    <x-ui.select.option value="ca">Canada</x-ui.select.option>
                    <x-ui.select.option value="uk">United Kingdom</x-ui.select.option>
                </x-ui.select>
            </x-ui.field>
        </div>
    </x-ui.fieldset>
</x-demo>
@endblade

```html
<x-ui.fieldset label="Contact Information">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-ui.field required>
            <x-ui.label>First Name</x-ui.label>
            <x-ui.input placeholder="John" />
        </x-ui.field>
        
        <x-ui.field required>
            <x-ui.label>Last Name</x-ui.label>
            <x-ui.input placeholder="Doe" />
        </x-ui.field>
    </div>
    
    <x-ui.field required>
        <x-ui.label>Email Address</x-ui.label>
        <x-ui.input type="email" placeholder="john.doe@example.com" />
    </x-ui.field>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-ui.field>
            <x-ui.label>Phone</x-ui.label>
            <x-ui.input type="tel" placeholder="+1 (555) 123-4567" />
        </x-ui.field>
        
        <x-ui.field>
            <x-ui.label>Country</x-ui.label>
            <x-ui.select>
                <x-ui.select.option value="us">United States</x-ui.select.option>
                <x-ui.select.option value="ca">Canada</x-ui.select.option>
                <x-ui.select.option value="uk">United Kingdom</x-ui.select.option>
            </x-ui.select>
        </x-ui.field>
    </div>
</x-ui.fieldset>
```

## Component Props

### Fieldset

| Prop Name | Type | Default | Required | Description |
|-----------|------|---------|----------|-------------|
| `label` | string | `null` | No | The fieldset legend text |
| `labelHidden` | boolean | `false` | No | Whether to visually hide the label (but keep it accessible) |
| `class` | string | `''` | No | Additional CSS classes |

