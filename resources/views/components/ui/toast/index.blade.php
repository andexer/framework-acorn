@props ([
	'position' => 'bottom-right',
	'maxToasts' => 5,
	'progressBarVariant' => 'full',
	'progressBarAlignment' => 'bottom',
])

@php
	$placement = match ($position) {
		'bottom-right' => 'bottom-0 right-0 pr-6 pb-6',
		'bottom-left' => 'bottom-0 left-0 pl-6 pb-6',
		'top-right' => 'top-0 right-0 pr-6 pt-6',
		'top-left' => 'top-0 left-0 pl-6 pt-6',
		'top-center' => 'top-0 left-1/2 -translate-x-1/2 pt-6',
		'bottom-center' => 'bottom-0 left-1/2 -translate-x-1/2 pb-6',
		default => 'bottom-0 right-0 pr-6 pb-6',
	};

	$sessionToast = session()->pull('notify');
	$isAlignmentsToBottom = Str::startsWith($position, 'bottom');
@endphp

<div
	x-data="{}"
	x-init="$store.toasts.maxToasts = @js($maxToasts);
@if (filled($sessionToast)) $store.toasts.add(@js($sessionToast)); @endif"
	x-on:notify.window="$store.toasts.add($event.detail)"
	@class ([
		'fixed flex w-full max-w-sm gap-3 z-[9999] pointer-events-none',
		'flex-col-reverse' => $isAlignmentsToBottom,
		'flex-col' => !$isAlignmentsToBottom,
		$placement,
	])
	role="status"
	aria-live="polite"
>
	<template x-for="toast in $store.toasts.items" :key="toast.id">
		<x-ui.toast.item
			:progressBarVariant="$progressBarVariant"
			:progressBarAlignment="$progressBarAlignment"
		/>
	</template>
</div>
