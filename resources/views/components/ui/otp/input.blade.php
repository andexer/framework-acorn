@aware (['type' => 'text', 'name' => null])

@php
	$classes = [
		'z-0 relative',
		'focus:z-10',
		'[:where(&:first-child)]:rounded-l-box overflow-visible [:where(&:last-child)]:rounded-r-box',
		'text-center text-base max-w-12 w-full h-12',
		'bg-white ',
		'text-neutral-900 placeholder:text-neutral-400 ',
		'border border-black/10 ',
		'focus:outline-none focus:ring-3 focus:ring-[color-mix(in_oklab,_var(--color-primary)_15%,_var(--color-primary-fg)_60%)]',
		'transition duration-300 ease-in-out',
		'shadow-sm',
		'disabled:pointer-events-none',

		'disabled:after:content-[""] disabled:after:absolute disabled:after:inset-0 disabled:after:cursor-text disabled:after:pointer-events-auto',
	];
@endphp

<input
	{{ $attributes->merge([
		'name' => $name,
		'type' => $type,
	])->class($classes) }}
	required
	maxlength="1"
	data-slot="otp-input"
	x-on:input="handleInput($el)"
	x-on:keydown.enter="handleInput($el)"
	x-on:paste="handlePaste($event)"
	x-on:keydown.delete.prevent="await handleBackspace($event)"
	x-on:keydown.backspace.prevent="await handleBackspace($event)"
	autocomplete="one-time-code"
	x-on:keydown.right="$focus.within($refs.inputsWrapper).next()"
	x-on:keydown.up="$focus.within($refs.inputsWrapper).next()"
	x-on:keydown.left="$focus.within($refs.inputsWrapper).prev()"
	x-on:keydown.down="$focus.within($refs.inputsWrapper).prev()"
	x-on:focus="requestAnimationFrame(() => $el.select())"
/>
