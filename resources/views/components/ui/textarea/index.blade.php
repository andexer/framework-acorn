@props ([
	'disabled' => false,
	'resize' => 'vertical',
	'name' => $attributes->whereStartsWith('wire:model')->first() ?? $attributes->whereStartsWith('x-model')->first(),
	'rows' => null,
	'invalid' => null,
])
@php
	$rows ??= 3;

	$initialHeight = $rows * 1.5 + 0.75;

	$classes = [
		'inline-block p-2 w-full text-base sm:text-sm text-neutral-800 disabled:text-neutral-500 placeholder-neutral-400 disabled:placeholder-neutral-400/70 ',

		'bg-white ',

		'disabled:cursor-not-allowed transition-colors duration-200',

		'shadow-sm disabled:shadow-none border rounded-box',

		'focus:ring-2 focus:ring-offset-0 focus:outline-none',

		'border-black/10 focus:border-black/15 focus:ring-neutral-900/15 ' => !$invalid,

		'border-red-500 focus:border-red-500 focus:ring-red-500/25 ' => $invalid,

		match ($resize) {
			'none' => 'resize-none',
			'both' => 'resize',
			'horizontal' => 'resize-x',
			'vertical' => 'resize-y',
		},
	];
@endphp

<textarea
	x-data="{
	initialHeight: @js($initialHeight) + 'rem',
	height: @js($initialHeight) + 'rem',
	name: @js($name),
	state: '',
	resize() {
		if (!this.$el) return;
		this.$el.style.height = 'auto';
		let newHeight = this.$el.scrollHeight + 'px';

		if (this.$el.scrollHeight < parseFloat(this.initialHeight)) {
			this.$el.style.height = this.initialHeight;
		} else {
			this.$el.style.height = newHeight;
		}
	}
}"
	x-init="
		$nextTick(() => {
			this.state = this.$root?._x_model?.get();
		});

		$watch('state', (value) => {
			this.$root?._x_model?.set(value);

			let wireModel = this?.$root
				.getAttributeNames()
				.find((n) => n.startsWith('wire:model'));

			if (this.$wire && wireModel) {
				let prop = this.$root.getAttribute(wireModel);
				this.$wire.set(prop, value, wireModel?.includes('.live'));
			}
		});

		if (!this.$el) return;

		this.$el.style.height = this.initialHeight;

		const observer = new ResizeObserver(() => {
			this.resize();
		});

		observer.observe(this.$el);
	"
	{{ $attributes->class(Arr::toCssClasses($classes)) }}
	@disabled ($disabled)
	@if ($invalid) aria-invalid="true" data-slot="invalid" @endif
	data-slot="textarea"
	x-intersect.once="resize()"
	rows={{ $rows }}
	x-on:input.stop="resize()"
	x-on:resize.window="resize()"
	x-on:keydown="resize()"
></textarea>
