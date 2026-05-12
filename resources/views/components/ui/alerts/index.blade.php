@props ([
	'variant' => 'info',
	'icon' => null,
	'color' => null,
	'controls' => null,
])

@php

	$color ??= match ($variant) {
		'info' => 'blue',
		'success' => 'green',
		'warning' => 'yellow',
		'error' => 'red',
		default => 'blue',
	};

	$colors = match ($color) {
		'zinc' => [
			'[--bg-color:var(--color-zinc-50)] [--border-color:var(--color-zinc-200)] [--text-color:var(--color-zinc-700)] [--heading-color:var(--color-zinc-900)] [--icon-color:var(--color-zinc-600)]',
			')] )] )] )] )]',
		],
		'red' => [
			'[--bg-color:var(--color-red-50)] [--border-color:var(--color-red-200)] [--text-color:var(--color-red-700)] [--heading-color:var(--color-red-900)] [--icon-color:var(--color-red-600)]',
			')] )] )] )] )]',
		],
		'orange' => [
			'[--bg-color:var(--color-orange-50)] [--border-color:var(--color-orange-200)] [--text-color:var(--color-orange-700)] [--heading-color:var(--color-orange-900)] [--icon-color:var(--color-orange-600)]',
			')] )] )] )] )]',
		],
		'amber' => [
			'[--bg-color:var(--color-amber-50)] [--border-color:var(--color-amber-200)] [--text-color:var(--color-amber-800)] [--heading-color:var(--color-amber-900)] [--icon-color:var(--color-amber-600)]',
			')] )] )] )] )]',
		],
		'yellow' => [
			'[--bg-color:var(--color-yellow-50)] [--border-color:var(--color-yellow-300)] [--text-color:var(--color-yellow-800)] [--heading-color:var(--color-yellow-900)] [--icon-color:var(--color-yellow-600)]',
			')] )] )] )] )]',
		],
		'lime' => [
			'[--bg-color:var(--color-lime-50)] [--border-color:var(--color-lime-200)] [--text-color:var(--color-lime-700)] [--heading-color:var(--color-lime-900)] [--icon-color:var(--color-lime-600)]',
			')] )] )] )] )]',
		],
		'green' => [
			'[--bg-color:var(--color-green-50)] [--border-color:var(--color-green-200)] [--text-color:var(--color-green-700)] [--heading-color:var(--color-green-900)] [--icon-color:var(--color-green-600)]',
			')] )] )] )] )]',
		],
		'emerald' => [
			'[--bg-color:var(--color-emerald-50)] [--border-color:var(--color-emerald-200)] [--text-color:var(--color-emerald-700)] [--heading-color:var(--color-emerald-900)] [--icon-color:var(--color-emerald-600)]',
			')] )] )] )] )]',
		],
		'teal' => [
			'[--bg-color:var(--color-teal-50)] [--border-color:var(--color-teal-200)] [--text-color:var(--color-teal-700)] [--heading-color:var(--color-teal-900)] [--icon-color:var(--color-teal-600)]',
			')] )] )] )] )]',
		],
		'cyan' => [
			'[--bg-color:var(--color-cyan-50)] [--border-color:var(--color-cyan-200)] [--text-color:var(--color-cyan-700)] [--heading-color:var(--color-cyan-900)] [--icon-color:var(--color-cyan-600)]',
			')] )] )] )] )]',
		],
		'sky' => [
			'[--bg-color:var(--color-sky-50)] [--border-color:var(--color-sky-200)] [--text-color:var(--color-sky-700)] [--heading-color:var(--color-sky-900)] [--icon-color:var(--color-sky-600)]',
			')] )] )] )] )]',
		],
		'blue' => [
			'[--bg-color:var(--color-blue-50)] [--border-color:var(--color-blue-200)] [--text-color:var(--color-blue-700)] [--heading-color:var(--color-blue-900)] [--icon-color:var(--color-blue-600)]',
			')] )] )] )] )]',
		],
		'indigo' => [
			'[--bg-color:var(--color-indigo-50)] [--border-color:var(--color-indigo-200)] [--text-color:var(--color-indigo-700)] [--heading-color:var(--color-indigo-900)] [--icon-color:var(--color-indigo-600)]',
			')] )] )] )] )]',
		],
		'violet' => [
			'[--bg-color:var(--color-violet-50)] [--border-color:var(--color-violet-200)] [--text-color:var(--color-violet-700)] [--heading-color:var(--color-violet-900)] [--icon-color:var(--color-violet-600)]',
			')] )] )] )] )]',
		],
		'purple' => [
			'[--bg-color:var(--color-purple-50)] [--border-color:var(--color-purple-200)] [--text-color:var(--color-purple-700)] [--heading-color:var(--color-purple-900)] [--icon-color:var(--color-purple-600)]',
			')] )] )] )] )]',
		],
		'fuchsia' => [
			'[--bg-color:var(--color-fuchsia-50)] [--border-color:var(--color-fuchsia-200)] [--text-color:var(--color-fuchsia-700)] [--heading-color:var(--color-fuchsia-900)] [--icon-color:var(--color-fuchsia-600)]',
			')] )] )] )] )]',
		],
		'pink' => [
			'[--bg-color:var(--color-pink-50)] [--border-color:var(--color-pink-200)] [--text-color:var(--color-pink-700)] [--heading-color:var(--color-pink-900)] [--icon-color:var(--color-pink-600)]',
			')] )] )] )] )]',
		],
		'rose' => [
			'[--bg-color:var(--color-rose-50)] [--border-color:var(--color-rose-200)] [--text-color:var(--color-rose-700)] [--heading-color:var(--color-rose-900)] [--icon-color:var(--color-rose-600)]',
			')] )] )] )] )]',
		],
		'slate' => [
			'[--bg-color:var(--color-slate-50)] [--border-color:var(--color-slate-200)] [--text-color:var(--color-slate-700)] [--heading-color:var(--color-slate-900)] [--icon-color:var(--color-slate-600)]',
			')] )] )] )] )]',
		],
		'gray' => [
			'[--bg-color:var(--color-gray-50)] [--border-color:var(--color-gray-200)] [--text-color:var(--color-gray-700)] [--heading-color:var(--color-gray-900)] [--icon-color:var(--color-gray-600)]',
			')] )] )] )] )]',
		],

		'neutral' => [
			'[--bg-color:var(--color-neutral-50)] [--border-color:var(--color-neutral-200)] [--text-color:var(--color-neutral-700)] [--heading-color:var(--color-neutral-900)] [--icon-color:var(--color-neutral-600)]',
			')] )] )] )] )]',
		],
		'stone' => [
			'[--bg-color:var(--color-stone-50)] [--border-color:var(--color-stone-200)] [--text-color:var(--color-stone-700)] [--heading-color:var(--color-stone-900)] [--icon-color:var(--color-stone-600)]',
			')] )] )] )] )]',
		],

		default => [],
	};

	$containerClass = [
		'border flex items-center rounded-xl gap-2 p-4',

		'[&:has([data-slot=alert-heading]):has([data-slot=alert-description])]:[&_[data-slot=alert-icon]]:self-start',
		'bg-[var(--bg-color)]/30 border-[var(--border-color)]/65 text-[var(--text-color)]',

		'[&_[data-slot=icon]]:text-(--icon-color)',
		...filled($color) ? $colors : [],
	];
@endphp

<div
	{{ $attributes->class(Arr::toCssClasses($containerClass)) }}
	data-slot="alert"
	role="alert"
	aria-live="polite"
>
	@if (filled($icon))
		<div
			class="shrink-0 flex items-center justify-center"
			data-slot="alert-icon"
		>
			<x-ui.icon name="{{ $icon }}" class="text-[var(--icon-color)]" />
		</div>
	@endif

	<div class="space-y-1">{{ $slot }}</div>

	@if (filled($controls))
		<div
			{{ $controls->attributes->class('h-full ml-auto') }}
			data-slot="alert-controls"
		>
			{{ $controls }}
		</div>
	@endif
</div>
