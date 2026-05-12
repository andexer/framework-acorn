@aware (['required' => false])

@props ([
	'text' => null,
])

@php
	$classes = ['block text-sm [:where(&)]:text-start font-medium select-none', '[:where(&)]:text-neutral-800'];
@endphp

<label {{ $attributes->class($classes) }} data-slot="label">
	@if ($slot->isNotEmpty())
		{{ $slot }}
	@else
		{{ $text }}
	@endif

	@if (isset($required) && $required)
		<span class="text-red-500 text-xs px-1 py-1" aria-hidden="true">
			*
		</span>
	@endif
</label>
