---
name: switch
---

## Introduction

The `Switch` component is a flexible toggle control that provides an intuitive way for users to switch between two states (on/off, enabled/disabled, etc.). It's designed with accessibility in mind and supports various sizes, alignments, and styling options. Perfect for settings panels, forms, and any interface where binary choices need to be made.

## Installation


```bash
```

> Once installed, you can use the <x-ui.switch /> component in any Blade view.

## Usage

### Basic Switch

A simple switch with a label, perfect for most use cases.

@blade
<x-demo>
    <div class="flex justify-center">
        <x-components::ui.switch 
            label="Enable notifications"
            name="notifications"
        />
    </div>
</x-demo>
@endblade

```html
<x-ui.switch 
    label="Enable notifications"
    name="notifications"
/>
```

### Switch with Description

Add additional context with a description to help users understand the toggle's purpose.

@blade
<x-demo>
    <div class="flex justify-center">
        <x-components::ui.switch 
            label="Dark mode"
            description="Switch between light and dark themes"
            name="dark_mode"
            :checked="true"
        />
    </div>
</x-demo>
@endblade

```html
<x-ui.switch 
    label="Dark mode"
    description="Switch between light and dark themes"
    name="dark_mode"
    :checked="true"
/>
```

### Different Sizes

The switch component supports three sizes: small, medium (default), and large.

@blade
<x-demo>
    <div class="flex flex-col items-center gap-4">
        <x-components::ui.switch 
            label="Small switch"
            size="sm"
            name="small_switch"
        />
        <x-components::ui.switch 
            label="Medium switch"
            size="md"
            name="medium_switch"
        />
        <x-components::ui.switch 
            label="Large switch"
            size="lg"
            name="large_switch"
        />
    </div>
</x-demo>
@endblade

```html
<x-ui.switch 
    label="Small switch"
    size="sm"
    name="small_switch"
/>

<x-ui.switch 
    label="Medium switch"
    size="md"
    name="medium_switch"
/>

<x-ui.switch 
    label="Large switch"
    size="lg"
    name="large_switch"
/>
```

### Left-Aligned Switch

Position the switch on the left side of the label for different layouts.

@blade
<x-demo>
    <div class="flex justify-center">
        <x-components::ui.switch 
            label="Push notifications"
            description="Receive notifications on your device"
            align="left"
            name="push_notifications"
            maxWidth="max-w-sm"
        />
    </div>
</x-demo>
@endblade

```html
<x-ui.switch 
    label="Push notifications"
    description="Receive notifications on your device"
    align="left"
    name="push_notifications"
    maxWidth="max-w-sm"
/>
```

### Disabled Switch

Show switches in a disabled state when they cannot be interacted with.

@blade
<x-demo>
    <div class="space-y-4 max-w-sm mx-auto">
        <div class="">
            <x-components::ui.switch 
                label="Disabled (Off)"
                description="This setting cannot be changed"
                :disabled="true"
                name="disabled_off"
            />
        </div>
        <div class="">
            <x-components::ui.switch 
                label="Disabled (On)"
                description="This setting is locked on"
                :disabled="true"
                :checked="true"
                name="disabled_on"
            />
        </div>
    </div>
</x-demo>
@endblade

```html
<x-ui.switch 
    label="Disabled setting"
    description="This setting cannot be changed"
    :disabled="true"
    name="disabled_setting"
/>

<x-ui.switch 
    label="Locked setting"
    description="This setting is permanently enabled"
    :disabled="true"
    :checked="true"
    name="locked_setting"
/>
```

### Switch Without Label

Use switches without labels when the context is clear or when building custom layouts.

@blade
<x-demo>
    <div class="flex justify-center">
        <div class="flex items-center gap-3">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Auto-save</span>
            <x-components::ui.switch 
                name="autosave"
                :checked="true"
            />
        </div>
    </div>
</x-demo>
@endblade

```html
<div class="flex items-center gap-3">
    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Auto-save</span>
    <x-ui.switch 
        name="autosave"
        :checked="true"
    />
</div>
```


### Switch with Icons

Add visual feedback to your switches by displaying different icons for on and off states. This enhances user experience by providing clear visual indicators of the current state.

@blade
<x-demo>
    <div class="flex flex-col items-center gap-4">
        <x-components::ui.switch 
            label="Sound effects"
            description="Play sound effects in the application"
            name="sound_effects"
            iconOff="speaker-x-mark"
            iconOn="speaker-wave"
        />
    </div>
</x-demo>
@endblade

```html
<x-ui.switch 
    label="Sound effects"
    description="Play sound effects in the application"
    name="sound_effects"
    iconOff="speaker-x-mark"
    iconOn="speaker-wave"
/>
```

### Different Icon Examples

Various icon combinations for different use cases and contexts.

@blade
<x-demo>
    <div class="flex flex-col items-center gap-6">
        <x-components::ui.switch 
            label="Dark mode"
            description="Switch between light and dark themes"
            name="dark_mode_icon"
            iconOff="sun"
            iconOn="moon"
            size="lg"
        />        
        <x-components::ui.switch 
            label="Auto-save"
            description="Automatically save your work"
            name="autosave_icon"
            iconOff="bookmark-slash"
            iconOn="bookmark"
            size="sm"
        />
    </div>
</x-demo>
@endblade

```html
<x-ui.switch 
    label="Dark mode"
    name="dark_mode"
    iconOff="sun"
    iconOn="moon"
    size="lg"
/>


<x-ui.switch 
    label="Auto-save"
    name="autosave"
    iconOff="bookmark-slash"
    iconOn="bookmark"
    size="sm"
/>
```

### Custom Colors

Customize the switch and thumb colors using Tailwind CSS classes for different states. Perfect for matching your brand colors or creating themed switches.

@blade
<x-demo>
    <div class="flex flex-col items-center gap-6">
        <x-components::ui.switch 
            label="Success theme"
            description="Green themed switch for positive actions"
            name="success_switch"
            onClass="bg-green-500"
            offClass="bg-red-300 dark:bg-red-600"
            thumbOnClass="bg-white"
            thumbOffClass="bg-white"
            :checked="true"
        />
        
        <x-components::ui.switch 
            label="Warning theme"
            description="Orange themed switch for caution actions"
            name="warning_switch"
            onClass="bg-orange-500"
            offClass="bg-gray-300 dark:bg-gray-600"
            thumbOnClass="bg-white"
            thumbOffClass="bg-gray-100"
            :checked="true"
        />
        
        <x-components::ui.switch 
            label="Brand theme"
            description="Purple themed switch matching brand colors"
            name="brand_switch"
            onClass="bg-purple-600"
            offClass="bg-gray-200 dark:bg-gray-700"
            thumbOnClass="bg-white shadow-lg"
            thumbOffClass="bg-gray-50"
            :checked="true"
        />
        
        <x-components::ui.switch 
            label="Danger theme"
            description="Red themed switch for critical actions"
            name="danger_switch"
            onClass="bg-red-500"
            offClass="bg-gray-300"
            thumbOnClass="bg-white"
            thumbOffClass="bg-red-100"
            iconOff="x-mark"
            iconOn="check"
        />
    </div>
</x-demo>
@endblade

```html
<x-ui.switch 
    label="Success theme"
    description="Green themed switch for positive actions"
    name="success_switch"
    onClass="bg-green-500"
    offClass="bg-red-300 dark:bg-red-600"
    thumbOnClass="bg-white"
    thumbOffClass="bg-white"
    :checked="true"
/>

<x-ui.switch 
    label="Warning theme"
    description="Orange themed switch for caution actions"
    name="warning_switch"
    onClass="bg-orange-500"
    offClass="bg-gray-300 dark:bg-gray-600"
    thumbOnClass="bg-white"
    thumbOffClass="bg-gray-100"
/>

<x-ui.switch 
    label="Brand theme"
    description="Purple themed switch matching brand colors"
    name="brand_switch"
    onClass="bg-purple-600"
    offClass="bg-gray-200 dark:bg-gray-700"
    thumbOnClass="bg-white shadow-lg"
    thumbOffClass="bg-gray-50"
    :checked="true"
/>

<x-ui.switch 
    label="Danger theme"
    description="Red themed switch for critical actions"
    name="danger_switch"
    onClass="bg-red-500"
    offClass="bg-gray-300"
    thumbOnClass="bg-white"
    thumbOffClass="bg-red-100"
    iconOff="x-mark"
    iconOn="check"
/>
```


## Component Props Reference

### Switch Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `align` | `string` | `'right'` | Position of the switch relative to label: `'left'`, `'right'` |
| `label` | `string` | `''` | Label text displayed next to the switch |
| `name` | `string` | `null` | Form input name attribute |
| `description` | `string\|null` | `null` | Optional description text shown below the label |
| `disabled` | `boolean` | `false` | Whether the switch is disabled and cannot be toggled |
| `maxWidth` | `string` | `'max-w-md'` | Maximum width constraint for the component container |
| `checked` | `boolean` | `false` | Initial checked state of the switch |
| `size` | `string` | `'md'` | Size variant: `'sm'`, `'md'`, `'lg'` |
| `switchClass` | `string` | `''` | Custom Tailwind classes for switch background (applied to both states) |
| `thumbClass` | `string` | `''` | Custom Tailwind classes for thumb element (applied to both states) |
| `iconOff` | `string\|null` | `null` | Lucide icon name to display in thumb when switch is off |
| `iconOn` | `string\|null` | `null` | Lucide icon name to display in thumb when switch is on |
| `onClass` | `string` | `''` | Custom Tailwind classes for switch background when on |
| `offClass` | `string` | `''` | Custom Tailwind classes for switch background when off |
| `thumbOnClass` | `string` | `''` | Custom Tailwind classes for thumb element when on |
| `thumbOffClass` | `string` | `''` | Custom Tailwind classes for thumb element when off |



For advanced customization, you can override the component's CSS classes or extend the component file directly.