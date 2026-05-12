@props ([
	'iconVariant' => 'micro',
	'iconAfter' => null,
	'variant' => 'solid',
	'pill' => false,
	'color' => null,
	'size' => null,
	'icon' => null,
])
@php
	$colorClasses = match ($variant) {
		'outline' => match ($color) {
			'brand' => '!text-brand-500 !bg-transparent !border-brand-500 !border-2',
			'red' => 'text-red-700 bg-red-500/5 border-red-400 border',
			default => 'text-neutral-900 bg-neutral-900/5 border-neutral-900 border',
		},
		default => match ($color) {
			'brand' => 'text-white bg-brand-600 border-brand-500',
			default => 'text-white bg-neutral-900 border-black/5',
		},
	};

	$classes = [
		'inline-flex items-center justify-center font-black whitespace-nowrap gap-x-1 uppercase tracking-tighter leading-none',
		$colorClasses,
		match ($size) {
			'sm' => 'text-[9px] h-5 px-2.5',
			'lg' => 'text-xs h-7 px-4',
			default => 'text-[10px] h-6 px-3',
		},
		match ($pill) {
			true => 'rounded-full',
			default => 'rounded-md',
		},
	];

@endphp

<div {{ $attributes->class(Arr::toCssClasses($classes)) }} data-slot="badge">
	@if (is_string($icon) && $icon !== '')
		<x-ui.icon
			:name="$icon"
			:variant="$iconVariant"
			data-slot="badge-icon"
		/>
	@else
		{{ $icon }}
	@endif

	<span>{{ $slot }}</span>

	@if ($iconAfter)
		@if (is_string($iconAfter))
			<x-ui.icon
				:name="$iconAfter"
				:variant="$iconVariant"
				data-slot="badge-icon:after"
			/>
		@else
			{{ $iconAfter }}
		@endif
	@endif
</div>
