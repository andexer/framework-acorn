@aware ([
	'progressBarAlignment' => 'bottom',
	'progressBarVariant' => 'full',
])
<div
	x-data="toastItem(toast)"
	x-show="visible"
	x-transition:enter="transition ease-out duration-500"
	x-transition:enter-start="opacity-0 translate-y-4 scale-95"
	x-transition:enter-end="opacity-100 translate-y-0 scale-100"
	x-transition:leave="transition ease-in duration-300"
	x-transition:leave-start="opacity-100 scale-100"
	x-transition:leave-end="opacity-0 scale-95"
	x-bind:class="[
		'pointer-events-auto relative w-full rounded-2xl border p-4 shadow-2xl backdrop-blur-md overflow-hidden transition-all duration-300',
		toastClasses,
	]"
	x-on:mouseenter="onMouseEnter()"
	x-on:mouseleave="onMouseLeave()"
>
	<template x-if="showProgressBar">
		<div
			@class ([
			'absolute inset-0 overflow-hidden pointer-events-none z-0',
			'h-full' => $progressBarVariant === 'full',
			'h-1 bottom-0' =>
				$progressBarVariant === 'thin' && $progressBarAlignment === 'bottom',
			'h-1 top-0' =>
				$progressBarVariant === 'thin' && $progressBarAlignment === 'top',
		])
		>
			<div
				class="h-full bg-current transition-all duration-100 ease-linear"
				x-bind:class="[iconColor, progressOpacity]"
				x-bind:style="'width: ' + progress + '%'"
			></div>
		</div>
	</template>

	<div class="relative z-10 flex items-start gap-3">
		<div class="shrink-0 mt-0.5" x-bind:class="iconColor">
			<div aria-hidden="true" class="flex items-center justify-center">
				<template x-if="isInfo">
					<svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
						<path
							stroke-linecap="round"
							stroke-linejoin="round"
							d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"
						/>
					</svg>
				</template>
				<template x-if="isSuccess">
					<svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
						<path
							stroke-linecap="round"
							stroke-linejoin="round"
							d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
						/>
					</svg>
				</template>
				<template x-if="isError">
					<svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
						<path
							stroke-linecap="round"
							stroke-linejoin="round"
							d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
						/>
					</svg>
				</template>
				<template x-if="isWarning">
					<svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
						<path
							stroke-linecap="round"
							stroke-linejoin="round"
							d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"
						/>
					</svg>
				</template>
			</div>
			<span class="sr-only" x-text="ariaLabel"></span>
		</div>

		<div class="flex-1 pt-0.5">
			<p x-text="
					toast.content
				" class="text-sm font-bold leading-tight text-zinc-900 break-words"></p>
		</div>

		<div class="shrink-0">
			<button
				type="button"
				x-on:click="dismiss()"
				class="flex items-center justify-center !w-4 !h-4 !min-w-4 !min-h-4 !max-w-4 !max-h-4 !rounded-full !bg-white/80 !border !border-white/90 !backdrop-blur-md !text-gray-800 hover:!text-black hover:!bg-white/60 transition-all duration-200 focus:outline-none active:scale-95 shadow-sm"
				aria-label="Close notification"
			>
				&times;
			</button>
		</div>
	</div>
</div>
