# **My Components UI**

**My Components UI** is a modern component library and design system built specifically for the **TALL stack**: Tailwind CSS, Alpine.js, Laravel, and Livewire, and even raw **Blade** with alpinejs reactivity.

Available Components:
==================================================

`resources/views/components/ui/`

### Design Philosophy

- Components are fully customizable and strictly follow the design system.
- Everything is plain Blade, Alpine, and Tailwind, nothing more.
- No build step. No lock-in. No surprises.

## [#] Architecture

### [#] Component Anatomy

Each component includes:

- **Blade templates** for markup and structure
- **Alpine logic** for client-side behavior
- **Design tokens and variants** for theme customization
- **Clear docs** for usage, props, slots, and integration

Example:

this how may typically your codebase will look likes:

resources/views/components/ui/
├── button/index.blade.php
├── card/index.blade.php
└── dropdown/
    ├── index.blade.php
    └── item.blade.php

### [#] Design System

My Components UI includes a fully themeable system with:

- Light/Dark mode support via system preference
- Custom color palettes, spacing, and typography
- Utility-first design with consistency baked in
  
  

```php
<x-ui.button variant="primary" size="lg">
    Click me
</x-ui.button>

<x-ui.card>
    <x-slot name="header">Card Title</x-slot>
    Card content goes here.
</x-ui.card>

```



✦ icon  
 A versatile icon component supporting multiple icon libraries with consistent stylin  
g and sizing options.  

✦ button  
 A flexible button component with multiple variants, sizes, and states including prim  
ary, secondary, destructive, and ghost styles.  

✦ card  
 A container component with header, content, and footer sections for organizing relat  
ed information in a clean layout.  

✦ heading  
 Semantic heading component with consistent typography scaling from H1 to H6 with pro  
per accessibility attributes.  

✦ textarea  
 A multi-line text input component with auto-resize capabilities and form validation  
support.  

✦ dropdown  
 An accessible dropdown menu component with keyboard navigation and customizable posi  
tioning options.  

✦ tags-input  
 An interactive input component for adding and removing tags with keyboard shortcuts  
and validation.  

✦ radio  
 A radio button group component with proper form integration and accessible label ass  
ociations.  

✦ select  
 A styled select dropdown component with search functionality and multi-select capabi  
lities.  

✦ separator  
 A visual separator component for dividing content sections with horizontal or vertic  
al orientation.  

✦ tabs  
 A tabbed interface component for organizing content into multiple panels with keyboa  
rd navigation support.  

✦ otp  
 A one-time password input component with automatic focus management and paste functi  
onality.  

✦ autocomplete  
 An intelligent input component with real-time suggestions and keyboard navigation fo  
r enhanced user experience.  

✦ key-value  
 A component for displaying key-value data pairs in a clean, organized format with op  
tional editing capabilities.  

✦ theme-switcher  
 A toggle component for switching between light and dark themes with smooth transitio  
ns and persistence.  

✦ tooltip  
 An accessible tooltip component with customizable positioning and trigger options fo  
r providing contextual information.  

✦ accordion  
 A collapsible content component for organizing information in expandable sections wi  
th smooth animations.  

✦ toast  
 A non-intrusive notification component for displaying temporary messages with differ  
ent severity levels.  

✦ modal  
 An overlay dialog component for displaying content above the main interface with foc  
us management and escape handling.  

✦ alerts  
 An alert component for displaying important messages with different variants like su  
ccess, warning, error, and info.  

✦ fieldset  
 A form fieldset component for grouping related form controls with proper semantic st  
ructure and styling.  

✦ input  
 A versatile input component supporting various types including text, email, password  
with validation states.  

✦ error  
 A component for displaying form validation errors and error states with proper acces  
sibility attributes.  

✦ link  
 A styled link component with hover states and external link indicators for consisten  
t navigation styling.  

✦ text  
 A text component with consistent typography scaling and color variants for body text  
and captions.  

✦ description  
 A component for displaying descriptive text with proper semantic meaning and muted s  
tyling.  

✦ label  
 A form label component with proper association to form controls and required field i  
ndicators.  

✦ field  
 A complete form field component that combines label, input, and error message in a c  
ohesive unit.  

✦ breadcrumbs  
 A navigation component showing the user's current location within the application hi  
erarchy.  

✦ badge  
 A small status indicator component with different variants for displaying counts, st  
atuses, or categories.  

✦ brand  
 A branding component for displaying logos and brand identity elements with consisten  
t styling.  

✦ switch  
 A toggle switch component for binary choices with smooth animations and accessibilit  
y support.  

✦ avatar  
 A user representation component that displays profile pictures, auto-generated initi  
als, or icons with customizable colors, sizes, and status badges.  

✦ popover  
 component provides a flexible overlay system for displaying contextual information o  
r interactive content.    

✦ checkbox  
 The checkbox component provides a powerful and flexible foundation for checkbox inpu  
t fields.  

✦ layout  
 The Layout component is the backbone of My Components UI’s layout system. It manages viewpor  
t states, sidebar collapse behavior, and coordinates all child components to ensure a  
cohesive, responsive structure across devices.  

✦ navlist  
 The Navlist component powers sidebar navigation with collapsible groups, icons, and  
tooltips. It’s built for flexibility, clarity, and consistency across all sidebar vari  
ants  

✦ navbar  
 The Navbar component offers a horizontal navigation system for headers or standalone  
use. It supports icons, badges, and active states to make top-level navigation intuit  
ive and visually balanced.  

✦ sidebar  
 The Sidebar component is a responsive, collapsible navigation panel that adapts flui  
dly to screen sizes. It supports branding, navlists, and interactive transitions to de  
liver a refined navigation experience.  

✦ slider  
 The slider component provides a powerful and intuitive way to select numeric values  
or ranges through an interactive draggable interface. Built on top of the robust noUiS  
lider library, it features support for single and multiple handles, tooltips, visual t  
rack fills, pips (value markers), custom formatting, non-linear scales, and full acces  
sibility support. Perfect for price ranges, ratings, measurements, or any numeric inpu  
t scenario.  

✦ kbd  
 A styled <kbd> element that renders keyboard shortcuts and key combinations with a m  
onospace, bordered appearance to visually distinguish them from surrounding text.  

✦ combobox  
 A combobox is a composite UI component combining a single-line text input field with  
a dropdown list (listbox or menu). It allows users to select a predefined option, or  
optionally type a custom value, filtering the list as they type.  

✦ empty  
 The Empty component provides beautiful empty state placeholders for when there's no  
data to display.  

✦ skeleton  
 The Skeleton component provides loading placeholders that mimic the structure of you  
r content while data is being fetched. It offers multiple animation styles and a flexi  
ble API for creating custom loading states that match your UI perfectly.
