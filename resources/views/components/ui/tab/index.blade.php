@aware (['variant' => 'outlined', 'size' => 'default'])

@props ([
	'label' => null,
	'name' => null,
	'iconAfter' => null,
	'icon' => null,
	'iconVariant' => null,
	'iconClasses' => null,
])

@php
	$classes = match ($variant) {
		'outlined' => [
			'p-3 border-neutral-950/10 rounded-box rounded-b-none justify-center focus:outline-none focus:bg-white/5 hover:bg-white/5',
			'data-[active=true]:bg-white ',
			'data-[active=true]:border-b-0',
			'data-[active=true]:border',
		],
		'non-contained' => [
			'data-[active=true]:bg-neutral-900/10 text-neutral-900 ',
			'hover:bg-white/10 focus:bg-white/10',
			'rounded-[calc(var(--noncontained-variant-radius)-var(--noncontained-variant-padding))]',
		],
		'pills' => [
			'rounded-full h-8 whitespace-nowrap rounded-full text-sm font-medium',
			'data-[active=true]:bg-(--color-primary) data-[active=true]:text-(--color-primary-fg)',
		],
		default => [],
	};

	if (filled($name)) {
		$attributes['data-name'] = $name;
	}
@endphp

<x-ui.button
	:attributes="$attributes->class(Arr::toCssClasses($classes))"
	x-on:click="handleTabClick($el)"
	data-slot="tab"
	tabindex="0"
	variant="none"
>
	@if ($slot->isNotEmpty())
		<span class="flex-1">{{ $slot }}</span>
	@else
		{{ $label }}
	@endif
</x-ui.button>
