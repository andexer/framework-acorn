@aware (['icon', 'iconClasses', 'iconVariant', 'iconAfter'])

@props ([
	'type' => 'button',
	'size' => 'md',
	'color' => null,
	'disabled' => false,
	'loading' => false,
	'variant' => 'primary',
	'icon' => null,
	'iconAfter' => null,
	'iconVariant' => null,
	'as' => 'button',
	'iconClasses' => null,
	'squared' => false,
])

@php

	$color ??= 'brand';
	$squared = $slot->isEmpty();

	$sizeClasses = match ($size) {
		'lg' => 'h-12! text-md! font-bold! rounded-2xl!' . ' ' . ($squared ? 'w-12!' : ($icon ? 'ps-4!' : 'ps-5!') . ' ' . ($iconAfter ? 'pe-4!' : 'pe-5!')),
		'md' => 'h-10! text-base! rounded-xl!' . ' ' . ($squared ? 'w-10!' : ($icon ? 'ps-3!' : 'ps-4!') . ' ' . ($iconAfter ? 'pe-3!' : 'pe-4!')),
		'sm' => 'h-9! text-sm! rounded-lg!' . ' ' . ($squared ? 'w-9!' : ($icon ? 'ps-2!' : 'ps-3!') . ' ' . ($iconAfter ? 'pe-2!' : 'pe-3!')),
		'xs' => 'h-6! text-xs! rounded-md!' . ' ' . ($squared ? 'w-6!' : ($icon ? 'ps-1!' : 'ps-2!') . ' ' . ($iconAfter ? 'pe-1!' : 'pe-2!')),
		default => 'h-10! text-sm! rounded-xl!' . ' ' . ($squared ? 'w-10!' : ($icon ? 'ps-3!' : 'ps-4!') . ' ' . ($iconAfter ? 'pe-3!' : 'pe-4!')),
	};

	$iconVariant ??= match ($size) {
		'xs' => 'micro',
		'sm' => 'mini',
		'md' => $squared ? 'mini' : 'micro',
		'lg' => $squared ? 'mini' : 'micro',
		default => 'micro',
	};

	$iconClasses = [
		$iconClasses,
		$size !== 'xs' ? 'size-5!' : 'size-4!',
		'text-[var(--color-primary-fg)]!' => in_array($variant, ['primary', 'gradient']),
		'text-[var(--color-primary)]!' => $variant === 'outline',
	];

	$iconAttributes = (new \Illuminate\View\ComponentAttributeBag())->class($iconClasses);

	$colors = match ($color) {
		'brand' => '[--color-primary:var(--color-brand-500)]! [--color-primary-stop:var(--color-brand-700)]! [--color-primary-fg:var(--color-white)]!',
		'slate' => '[--color-primary:var(--color-slate-700)]! [--color-primary-stop:var(--color-slate-900)]! [--color-primary-fg:var(--color-neutral-50)]!',
		'neutral' => '[--color-primary:var(--color-neutral-700)]! [--color-primary-stop:var(--color-neutral-900)]! [--color-primary-fg:var(--color-neutral-50)]!',
		'zinc' => '[--color-primary:var(--color-zinc-700)]! [--color-primary-stop:var(--color-zinc-900)]! [--color-primary-fg:var(--color-neutral-50)]!',
		'stone' => '[--color-primary:var(--color-stone-700)]! [--color-primary-stop:var(--color-stone-900)]! [--color-primary-fg:var(--color-neutral-50)]!',
		'red' => '[--color-primary:var(--color-red-500)]! [--color-primary-stop:var(--color-red-700)]! [--color-primary-fg:var(--color-neutral-50)]!',
		'orange' => '[--color-primary:var(--color-orange-500)]! [--color-primary-stop:var(--color-orange-700)]! [--color-primary-fg:var(--color-neutral-50)]!',
		'amber' => '[--color-primary:var(--color-amber-400)]! [--color-primary-stop:var(--color-amber-600)]! [--color-primary-fg:var(--color-amber-950)]!',
		'yellow' => '[--color-primary:var(--color-yellow-400)]! [--color-primary-stop:var(--color-yellow-600)]! [--color-primary-fg:var(--color-yellow-950)]!',
		'lime' => '[--color-primary:var(--color-lime-400)]! [--color-primary-stop:var(--color-lime-600)]! [--color-primary-fg:var(--color-lime-900)]!',
		'green' => '[--color-primary:var(--color-green-500)]! [--color-primary-stop:var(--color-green-700)]! [--color-primary-fg:var(--color-neutral-50)]!',
		'emerald' => '[--color-primary:var(--color-emerald-500)]! [--color-primary-stop:var(--color-emerald-700)]! [--color-primary-fg:var(--color-neutral-50)]!',
		'teal' => '[--color-primary:var(--color-teal-500)]! [--color-primary-stop:var(--color-teal-700)]! [--color-primary-fg:var(--color-neutral-50)]!',
		'cyan' => '[--color-primary:var(--color-cyan-500)]! [--color-primary-stop:var(--color-cyan-700)]! [--color-primary-fg:var(--color-neutral-50)]!',
		'sky' => '[--color-primary:var(--color-sky-500)]! [--color-primary-stop:var(--color-sky-700)]! [--color-primary-fg:var(--color-neutral-50)]!',
		'blue' => '[--color-primary:var(--color-blue-500)]! [--color-primary-stop:var(--color-blue-700)]! [--color-primary-fg:var(--color-neutral-50)]!',
		'indigo' => '[--color-primary:var(--color-indigo-500)]! [--color-primary-stop:var(--color-indigo-700)]! [--color-primary-fg:var(--color-neutral-50)]!',
		'violet' => '[--color-primary:var(--color-violet-500)]! [--color-primary-stop:var(--color-violet-700)]! [--color-primary-fg:var(--color-neutral-50)]!',
		'purple' => '[--color-primary:var(--color-purple-500)]! [--color-primary-stop:var(--color-purple-700)]! [--color-primary-fg:var(--color-neutral-50)]!',
		'fuchsia' => '[--color-primary:var(--color-fuchsia-500)]! [--color-primary-stop:var(--color-fuchsia-700)]! [--color-primary-fg:var(--color-neutral-50)]!',
		'pink' => '[--color-primary:var(--color-pink-500)]! [--color-primary-stop:var(--color-pink-700)]! [--color-primary-fg:var(--color-neutral-50)]!',
		'rose' => '[--color-primary:var(--color-rose-500)]! [--color-primary-stop:var(--color-rose-700)]! [--color-primary-fg:var(--color-neutral-50)]!',
		default => '',
	};

	$variantClasses = match ($variant) {
		'primary' => [
			'bg-[var(--color-primary)]! hover:bg-[var(--color-primary)]/90!',
			'text-[var(--color-primary-fg)]!',
			'border! border-black/10!',
			$colors => filled($color),
		],
		'gradient' => [
			'bg-gradient-to-br! from-[var(--color-primary)]! to-[var(--color-primary-stop)]!',
			'text-[var(--color-primary-fg)]!',
			'border-none! shadow-sm! hover:brightness-110! active:scale-[0.98]!',
			$colors => filled($color),
		],
		'solid' => [
			'bg-[var(--color-primary)]/10! hover:bg-[var(--color-primary)]/20!',
			'text-[var(--color-primary)]!',
			$colors => filled($color),
		],
		'soft' => [
			'text-[var(--color-primary)]! hover:brightness-90!',
			'bg-transparent!',
			$colors => filled($color),
		],
		'outline' => [
			'border-2! border-[var(--color-primary)]! bg-transparent! hover:bg-[var(--color-primary)]/10!',
			'text-[var(--color-primary)]!',
			'shadow-none! hover:shadow-sm! transition-all!',
			$colors => filled($color),
		],
		'ghost' => [
			'bg-transparent! hover:bg-[var(--color-primary)]/10!',
			'text-[var(--color-primary)]!',
			$colors => filled($color),
		],
		'danger' => [
			' ',
			'bg-red-500! hover:bg-red-600!',
			'text-white!',
		],
		'none' => [],
		default => [],
	};

	$classes = [
		'relative! inline-flex! items-center! font-medium! justify-center! gap-x-2! whitespace-nowrap! transition-colors! duration-200!',
		'disabled:opacity-75! disabled:cursor-not-allowed! hover:disabled:cursor-not-allowed! cursor-pointer!',
		'[&_a]:no-underline! [&_a]:decoration-none! [&_a:hover]:no-underline!' => $variant !== 'none',
		'rounded-field!' => $variant !== 'none',

		'[&>[data-loading]:first-child]:flex!',
		'[&>[data-loading]:first-child~*]:opacity-0!',
		'[&:has(>[data-loading])]:cursor-not-allowed! hover:[&:has(>[data-loading])]:cursor-not-allowed! [&:has(>[data-loading])]:opacity-80!',
		$sizeClasses,
		...$variantClasses,
	];

	$hasWireLoading = filled($attributes->whereStartsWith('wire:loading')->first());
	$hasWireClick = filled($attributes->whereStartsWith('wire:click')->first());

	$loadingAttributes = new \Illuminate\View\ComponentAttributeBag();

	$loadingAttributes = $loadingAttributes->merge(
		$hasWireLoading || $hasWireClick || $type === 'submit'
			? [
				'wire:loading.attr' => 'data-loading',
				'wire:target' => $attributes->has('wire:target') ? $attributes->get('wire:target') : ($attributes->whereStartsWith('wire:click')->first() ?? null),
			]
			: [],
	);

	$loadingAttributes = $loadingAttributes->merge(
		$loading
			? [
				'data-loading' => 'true',
			]
			: [],
	);

@endphp

<x-ui.button.abstract
	:as="$as"
	:type="$type"
	:attributes="$attributes->class($classes)->merge([
		'role' => $as === 'a' && !$attributes->has('href') ? 'button' : null,
		'aria-busy' => $loading ? 'true' : 'false',
		'aria-disabled' => $attributes->has('disabled') ? 'true' : 'false',

		'aria-label' => $squared && blank($slot) ? Str::title($icon ?? $iconAfter ?? 'Button') : null,
	])"
	data-slot="button"
>
	<div
		@class ([
			'absolute! inset-0! hidden! items-center! justify-center!',
		])
		{{ $loadingAttributes }}
	>
		<x-ui.icon.loading
			:variant="$iconVariant"
			data-slot="loading-indicator"
			:attributes="$iconAttributes"
		/>
	</div>

	@if (filled($icon))
		<x-ui.icon
			:name="$icon"
			:variant="$iconVariant"
			:attributes="$iconAttributes"
			data-slot="right-icon"
		/>
	@endif

	@if ($slot->isNotEmpty())
		<span data-text> {{ $slot }} </span>
	@endif

	@if (filled($iconAfter))
		<x-ui.icon
			:name="$iconAfter"
			:variant="$iconVariant"
			:attributes="$iconAttributes"
			data-slot="left-icon"
		/>
	@endif
</x-ui.button.abstract>
