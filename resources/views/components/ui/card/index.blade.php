@props (['size' => 'md'])

@php
	$variantClasses = match ($size) {
		'xs' => 'max-w-xs',
		'sm' => 'max-w-sm',
		'md' => 'max-w-md',
		'lg' => 'max-w-lg',
		'xl' => 'max-w-xl',
		'2xl' => 'max-w-2xl',
	};

	$classes = [
		'bg-white border-none ring-1 ring-zinc-200/60 shadow-xl overflow-hidden',
		'[:where(&)]:p-4 [:where(&)]:rounded-[2.5rem] transition-all duration-300',
		$variantClasses,
	];

@endphp

<div {{ $attributes->class(Arr::toCssClasses($classes)) }}> {{ $slot }}</div>
