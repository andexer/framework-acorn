@aware ([
	'placement' => 'top',
	'offset' => '3',
])

@php
	$classes = [
		'absolute whitespace-nowrap min-h-8 z-50 px-2 py-1 text-sm [:where(&)]:bg-white [:where(&)]: [:where(&)]:text-black [:where(&)]: rounded shadow-lg pointer-events-none',
	];
@endphp

<div
	x-show="show"
	x-on:click.away="hideTooltip()"
	x-transition.duration.300
	x-anchor.{{ $placement }}.offset.{{ $offset }}="$refs.tooltipTrigger"
	style="display: none"
	{{ $attributes->class($classes) }}
>
	{{ $slot }}
</div>
