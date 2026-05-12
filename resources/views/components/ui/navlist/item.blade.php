@aware ([
	'collapsible' => true,
])
@props ([
	'icon' => null,
	'badge' => null,
	'label' => null,
	'href' => '#',
	'active' => null,
])

@php

	$classes = [
		'isolate',
		'flex items-center [:where(&)]:justify-start',

		'[:has([data-collapsed]_&)_&]:justify-center',

		'data-active-link:bg-primary/5 
 data-active-link:!text-primary 
 data-active-link:[&_[data-slot=icon]]:!text-primary',

		'[&:not([data-active-link])]:hover:bg-primary/5
 [&:not([data-active-link])]:hover:!text-primary
 [&:not([data-active-link])]:hover:[&_[data-slot=icon]]:!text-primary',

		' text-neutral-600',

		'[&_[data-slot=icon]]:
 [&_[data-slot=icon]]:text-neutral-600 
 data-[active-link]:text-[var(--color-primary)]',

		'gap-x-2 pl-3 pr-1 py-1 rounded-box',

		'[:has([data-collapsed]_&)_&]:p-2',
	];

	$iconAttributes = new \Illuminate\View\ComponentAttributeBag();
	$badgeAttributes = new \Illuminate\View\ComponentAttributeBag();

	foreach ($attributes->getAttributes() as $key => $value) {
		if (str_starts_with($key, 'icon:')) {
			$iconAttributes[substr($key, 5)] = $value;
		} elseif (str_starts_with($key, 'badge:')) {
			$badgeAttributes[substr($key, 6)] = $value;
		}
	}

	$active = $active ?? url($href) === url()->current();

@endphp
<a
	href="{{ $href }}"
	wire:navigate
	@if ($active) data-active-link @endif
	data-slot="navlist-item"
	{{ $attributes->class($classes) }}
>
	@if ($icon)
		<x-ui.navlist.has-tooltip :tooltip="$label" :condition="$collapsible">
			<x-ui.icon
				:attributes="$iconAttributes->class('[:where(&)]:size-5')"
				:name="$icon"
			/>
		</x-ui.navlist.has-tooltip>
	@endif

	<span class="text-base [:has([data-collapsed]_&)_&]:hidden">
		{{ $label }}
	</span>

	@if ($badge)
		<x-ui.badge
			:attributes="$badgeAttributes->merge([
			'size' => 'sm',
		])"
			class="[:has([data-collapsed]_&)_&]:hidden ml-auto"
		>
			{{ $badge }}</x-ui.badge
		>
	@endif
</a>
