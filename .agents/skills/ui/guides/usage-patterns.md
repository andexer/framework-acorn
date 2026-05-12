---
name: usage patterns
---

## About State Binding In livewire and Blade with Alpinejs

We built My Components UI to work out of the box with typical Laravel apps no big JavaScript frameworks required. Just Blade, Alpine, and optionally Livewire.

But to make the most of it, we follow a small pattern that lets our components work smoothly in both Livewire and plain Blade. Once you get the idea, it’s super flexible.



### Using with Livewire

All dynamic components in My Components UI work with Livewire the same way you'd expect. Just bind your property using `wire:model`, like you normally do:

```html
<x-ui.key-value 
    wire:model="configurations" 
/>
```

That’s it. Livewire will pick up the value, and it’ll react like any other input field.



### Using with Raw Blade

Now if you're just using Blade + AlpineJS (no Livewire), we’ve still got you covered.

The pattern we suggest is binding the state using `x-model`, then syncing it through a hidden input like this:

```html
<x-ui.key-value 
    x-model="configurations" 
/>

<input type="hidden" name="configurations" x-model="configurations" />
<!-- Now you’ll get the value in your controller as usual -->
```

So whether you’re submitting with a form or capturing it via JS, you’re good.



### A Bit of Theory

Under the hood, My Components UI components expose a `state` a global value that needs to be either synced with Livewire or sent through a request. That’s where `x-model` and `wire:model` come into play.

You can use either of them depending on your setup, and things will just work. But how?

Well, shoutout to Caleb Porzio who introduced [`x-modelable`](https://alpinejs.dev/directives/modelable) an underrated gem in Alpine. It makes it super easy to sync component state with an outside model.

Here’s a quick refresher:

```html
<div x-data="{ number: 5 }">
    <!-- `count` is exposed and bound to `number` -->
    <div x-data="{ count: 0 }" x-modelable="count" x-model="number">
        <button @click="count++">Increment</button>
    </div>

    Number: <span x-text="number"></span>
</div>
```

But sometimes that’s just not enough especially when you want more control over the shape of the state or need two-way syncing with extra logic. So we go a step further and bind the state manually inside the component.



### Fun Fact About Livewire

Dig into the Livewire source code and you’ll see this:  
`wire:model` is basically just a powered-up `x-model`. Under the hood, it uses Alpine’s `bind()` utility.

> You can see that in action [here](https://github.com/livewire/livewire/blob/main/js/directives/wire-model.js#L54).

So yeah, next time you use `wire:model`, know that it’s just `x-model` with some Laravel seasoning.

And guess what? When Alpine sees an element with `x-model`, it adds a `_x_model` property to the element, which you can access and modify directly even if it’s not a form input [read this](https://alpinejs.dev/directives/model#programmatic%20access).

Here's how:

- Get the value: `$el._x_model.get()`
- Set the value: `$el._x_model.set(newValue)`

Neat, right?



### Real Example

Let’s take our [Key Value](/docs/key-value) component as a real-world example. Here's how we wire the state inside it:

```js
<div
    x-data="{
        state: [],
        ...
        init() {
            this.$nextTick(() => {
                this.state = this.$root?._x_model?.get() ?? []
                ...
            })

           this.$watch('state', (value) => {
                // Sync with Alpine state
                this.$root?._x_model?.set(value);

                // Sync with Livewire state
                if(this.$wire){
                    let wireModel = this?.$root.getAttributeNames().find(n => n.startsWith('wire:model'))
                    let prop = this.$root.getAttribute(wireModel)
                    this.$wire.set(prop, value, wireModel.includes('.live'));
                }
            });
        },
        ...
    }"
>
```

So what’s going on?

- We check if there’s an existing external state to pull in
- We assign it to our internal `state`
- Then anytime `state` changes, we push it back to the external model (`x-model` or `wire:model`) using `set()`

This pattern gives us full control without breaking Livewire or Blade compatibility. It’s how we make components that are truly reusable, reactive, and enjoyable to work with no matter your setup.

## Design Patterns

As a team working on this amazing project, we’ve defined a few essential rules to guide how we design and manage component styles, especially when it comes to handling **variants**.

### Rule #1: Use `data-slot` for Core Elements

Every essential element inside a component gets a `data-slot="..."` attribute. For example:

* `data-slot="tabs-group"`
* `data-slot="tabs-item"`
* `data-slot="tabs-panel"`

This pattern was inspired by [Adam Wathan’s talk at Laracon](https://www.youtube.com/watch?v=MrzrSFbxW7M), where he broke down how to build scalable UI libraries.

In our case, this convention has been a game changer especially for managing *multiple variants* of the same component in a clean, CSS-first way.

For instance, take our [Tabs](/docs/tabs) component. When the tabs group is left- or right-aligned, we want to dynamically remove panel rounding based on whether the active tab is the **first** or **last** item.

Now here’s the catch: we don’t use any JS, no custom props, no extra logic just pure CSS selectors made possible by the `data-slot` attributes.

Here’s a real example for the `outlined` variant:

```php
$classes = match($variant){
    'outlined' => [
        'dark:text-gray-200 text-gray-800 rounded-3xl',

        // If tabs are left-aligned and the first tab is active, remove top-left rounding from the panels
        '[&:has(:first-child[data-slot=tabs-group].justify-start_>_:first-child[data-active=true])_[data-slot=tabs-panel]]:rounded-tl-none',

        // Same, but on :focus or :hover
        '[&:has(:first-child[data-slot=tabs-group].justify-start_>_:first-child:is(:focus,:hover))_[data-slot=tabs-panel]]:rounded-tl-none',

        // If tabs are right-aligned and the last tab is active, remove top-right rounding from the panels
        '[&:has(:first-child[data-slot=tabs-group].justify-end_>_:last-child[data-active=true])_[data-slot=tabs-panel]]:rounded-tr-none',

        // Same, but on :focus or :hover
        '[&:has(:first-child[data-slot=tabs-group].justify-end_>_:last-child:is(:focus,:hover))_[data-slot=tabs-panel]]:rounded-tr-none',
    ],
    ...
};
```

that's all driven by css selectors in specific cretarias 

### Rule #2: Hover States

<!-- @todo -->
soon

### Rule #3:
 <!--@todo  -->
soon.