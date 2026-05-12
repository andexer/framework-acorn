@props ([
	'label' => '',
	'required' => false,
	'error' => '',
	'direction' => 'vertical',
	'disabled' => false,
	'variant' => 'default',
	'labelClass' => '',
	'indicator' => true,
	'name' => $attributes->whereStartsWith('wire:model')->first() ?? $attributes->whereStartsWith('x-model')->first(),
])

@php
	$componentId = $id ?? 'radio-group-' . uniqid();

	$labelClasses = ['text-gray-800 font-semibold mb-4 inline-block', $labelClass];

	$variantClass = [
		'space-y-2' => $direction === 'vertical',
		'flex gap-1 items-stretch' => $direction === 'horizontal',
		'bg-neutral-200 rounded-box w-fit p-1' => $variant === 'segmented',
		'p-1' => $variant === 'cards',
	];

	$modelAttrs = collect($attributes->getAttributes())->keys()->first(fn($key) => str_starts_with($key, 'wire:model'));

	$model = $modelAttrs ? $attributes->get($modelAttrs) : null;

	$isLive = $modelAttrs && str_contains($modelAttrs, '.live');
@endphp

<div
	x-data="function() {
	const $entangle = (prop, live) => {

		const binding = $wire.$entangle(prop);

		return live ? binding.live : binding;
	};

	const $initState = (prop, live, multiple) => {

		if (!prop) return null;

		return $entangle(prop, live);
	};

	return {
		_state: $initState(@js($model), @js($isLive)),

		init() {
			this.$nextTick(() => {
				this._state = this.$root?._x_model?.get();
			});

			this.$watch('_state', (value) => {

				this.$root?._x_model?.set(value);
			});
		},
	}
}"
	{{ $attributes->merge(['class' => 'w-full text-start']) }}
>
	@if ($label)
		<label id="{{ $componentId }}-label" @class ($labelClasses)>
			{{ $label }}
		</label>
	@endif

	<div
		role="radiogroup"
		@class (Arr::toCssClasses($variantClass))
		@if ($label) aria-labelledby="{{ $componentId }}-label" @endif
	>
		{{ $slot }}
	</div>

	@if ($error && filled($error))
		<p
			class="text-gray-200 bg-red-500 relative w-fit after:content-[''] after:w-1 after:h-full after:bg-white after:absolute after:left-0 after:top-0 text-sm mt-4 px-4 py-0.5"
		>
			{{ $error }}
		</p>
	@endif
</div>
