@props ([
	'alignment' => 'start',
	'ariaLabelledby' => null,
	'autofocus' => true,
	'closeButton' => true,
	'closeByClickingAway' => true,
	'closeByEscaping' => true,
	'openEventName' => 'open-modal',
	'closeEventName' => 'close-modal',
	'description' => null,
	'displayClasses' => 'inline-block!',
	'footer' => null,
	'header' => null,
	'heading' => null,
	'icon' => null,
	'iconVariant' => 'primary',
	'id' => null,
	'slideover' => false,
	'stickyFooter' => false,
	'stickyHeader' => false,
	'trigger' => null,
	'visible' => true,
	'width' => 'sm',
	'backdrop' => 'blur',
	'persistent' => false,
	'animation' => null,
	'position' => 'top',
	'view' => null,
	'bare' => false,
])

@php
	$hasDescription = filled($description);
	$hasFooter = filled($footer);
	$hasHeading = filled($heading);
	$hasIcon = filled($icon);
	$hasSlot = !empty(trim($slot ?? ''));

	$resolvedView = $view ?? ($slideover ? 'drawer-right' : 'modal');
	$isDrawerLeft = $resolvedView === 'drawer-left';
	$isDrawerRight = $resolvedView === 'drawer-right';
	$isDrawer = $isDrawerLeft || $isDrawerRight;

	$animation = $animation ?? ($isDrawer ? 'slide' : 'scale');

	$modalId = $id ?? 'modal-' . uniqid();

	$widthClass = match ($width) {
		'xs' => 'max-w-xs!',
		'sm' => 'max-w-sm!',
		'md' => 'max-w-md!',
		'lg' => 'max-w-lg!',
		'xl' => 'max-w-xl!',
		'2xl' => 'max-w-2xl!',
		'3xl' => 'max-w-3xl!',
		'4xl' => 'max-w-4xl!',
		'5xl' => 'max-w-5xl!',
		'6xl' => 'max-w-6xl!',
		'7xl' => 'max-w-7xl!',
		'full' => 'max-w-full!',
		'screen-sm' => 'max-w-screen-sm!',
		'screen-md' => 'max-w-screen-md!',
		'screen-lg' => 'max-w-screen-lg!',
		'screen-xl' => 'max-w-screen-xl!',
		'screen-2xl' => 'max-w-screen-2xl!',
		'screen' => 'max-w-full!',
		default => $width,
	};

	$alignmentClass = match ($alignment) {
		'start', 'left' => 'text-left!',
		'center' => 'text-center!',
		'end', 'right' => 'text-right!',
		default => $alignment,
	};

	$iconColorClass = match ($iconVariant) {
		'primary' => 'bg-white! text-primary-fg!',
		'secondary' => 'bg-neutral-100! text-neutral-600!',
		'success' => 'bg-green-100! text-green-600!',
		'warning' => 'bg-yellow-100! text-yellow-600!',
		'danger' => 'bg-red-100! text-red-600!',
		'info' => 'bg-cyan-100! text-cyan-600!',
		default => 'bg-white! text-primary!',
	};

	$slideEnterFromClass = $isDrawerLeft ? '-translate-x-14!' : 'translate-x-14!';
@endphp

<div
	x-data="modalComponent({
	persistent: @js($persistent),
	closeByClickingAway: @js($closeByClickingAway),
	closeByEscaping: @js($closeByEscaping),
	autofocus: @js($autofocus),
	closeEventName: @js($closeEventName),
	openEventName: @js($openEventName),
	modalId: @js($modalId),
})"
	x-id="['modal']"
	x-bind:data-modal-open="isOpen"
	x-bind:data-modal-id="modalId"
	@if ($ariaLabelledby)
		aria-labelledby="{{ $ariaLabelledby }}"
	@elseif ($hasHeading)
		x-bind:aria-labelledby="$id('modal') + '-heading'"
	@endif
	{{ $attributes->class(['modal-component', $displayClasses, 'hidden!' => !$visible]) }}
	x-on:keydown.window="handleEscapeKey($event)"
>
	@if ($trigger)
		<div
			x-on:click="open()"
			{{ $trigger->attributes->class('modal-trigger cursor-pointer!') }}
		>
			{{ $trigger }}
		</div>
	@endif

	<template x-teleport="body">
		<div
			x-show="isOpen"
			class="fixed! inset-0! overflow-y-auto!"
			aria-modal="true"
			role="dialog"
			style="display: none; z-index: 9999"
		>
			<x-ui.modal.backdrop />

			<div
				data-slot="modal-container"
				@if ($isDrawer) data-slideover="true" @endif
				x-on:click="handleBackdropClick($event)"
				@class ([
					'relative! z-50! flex! min-h-full! w-full!',
					'items-center! justify-center!' => !$isDrawer,
					'p-4!' => !$isDrawer && $width !== 'screen',
					'items-start! pt-16!' =>
						$width !== 'screen' && ($position === 'top' && !$isDrawer),
					'items-end! pb-16!' => $position === 'bottom' && !$isDrawer,
					'overflow-x-hidden!' => $isDrawer,
					'justify-start!' => $isDrawerLeft,
					'justify-end!' => $isDrawerRight,
				])
			>
				<div
					x-ref="modalContent"
					data-slot="modal-contents"
					x-show="isOpen"
					@if ($animation === 'scale')
						x-transition:enter="transition! ease-out! duration-200!"
						x-transition:enter-start="opacity-0! scale-95!"
						x-transition:enter-end="opacity-100! scale-100!"
						x-transition:leave="transition! ease-in! duration-200!"
						x-transition:leave-start="opacity-100! scale-100!"
						x-transition:leave-end="opacity-0! scale-95!"
					@elseif ($animation === 'slide')
						x-transition:enter="transition! transform-gpu! duration-420! ease-[cubic-bezier(0.22,1,0.36,1)]!"
						x-transition:enter-start="{{ $slideEnterFromClass }}"
						x-transition:enter-end="translate-x-0!"
						x-transition:leave="transition! transform-gpu! duration-280! ease-[cubic-bezier(0.4,0,0.2,1)]!"
						x-transition:leave-start="translate-x-0!"
						x-transition:leave-end="{{ $slideEnterFromClass }}"
					@else
						x-transition:enter="transition! ease-out! duration-200!"
						x-transition:enter-start="opacity-0!"
						x-transition:enter-end="opacity-100!"
						x-transition:leave="transition! ease-in! duration-200!"
						x-transition:leave-start="opacity-100!"
						x-transition:leave-end="opacity-0!"
					@endif
					@class ([
						'relative! flex! w-full! flex-col!',
						'will-change-transform!',
						'bg-white! shadow-xl!' => !$bare,
						'ring-1! ring-neutral-900/5!' => !$bare,
						$widthClass,
						'rounded-[calc(1.5*var(--radius-box))]!' =>
							!$isDrawer && $width !== 'screen' && !$bare,
						'h-dvh! max-h-dvh!' => $isDrawer || $width === 'screen',
						'overflow-hidden!' => $stickyHeader || $stickyFooter || $isDrawer,
					])
					style="display: none"
				>
					<x-ui.modal.grab-handle />

					@if ($bare)
						{{ $slot }}
					@else
						@if ($hasHeading || $closeButton)
							<div
								@class ([
								'modal-header flex! items-start! gap-3!',
								'p-3!' => in_array($width, [
									'xs',
									'sm',
									'md',
									'lg',
									'xl',
									'2xl',
									'3xl',
									'4xl',
									'screen-md',
									'screen-sm',
									'screen-lg',
									'screen-xl',
									'screen-2xl',
								]),
								'p-4!' => in_array($width, ['5xl', '6xl', '7xl', 'full']),
								'p-6!' => $width === 'screen',
								'border-neutral-100!' => $hasSlot || $hasFooter,
								'sticky! top-0! z-40! bg-white!' => $stickyHeader,
								'border-b!' => $hasHeading,
								$alignmentClass,
							])
							>
								@if ($hasIcon)
									<div class="mr-4! shrink-0!">
										<div
											@class (['rounded-full! p-2!', $iconColorClass])
										>
											<x-ui.icon :name="$icon" />
										</div>
									</div>
								@endif

								@if ($hasHeading || $hasDescription)
									<div class="min-w-0! flex-1!">
										@if ($hasHeading)
											<h2
												x-bind:id="
													$id('modal') + '-heading'
												"
												class="text-lg! font-semibold! text-neutral-900!"
											>
												{{ $heading }}
											</h2>
										@endif

										@if ($hasDescription)
											<p class="mt-1! text-sm! text-neutral-600!">
												{{ $description }}
											</p>
										@endif
									</div>
								@endif

								@if ($closeButton)
									<x-ui.button
										x-on:click="$data.close()"
										variant="none"
										:icon="null"
										size="sm"
										class="rounded-field! bg-black/5! p-0! hover:bg-black/10!"
										icon-after="x-mark"
									/>
								@endif
							</div>
						@endif
						@if ($hasSlot)
							<div
								@class ([
								'modal-content flex-1! min-h-0! px-6! py-4! text-neutral-900!',
								'overflow-y-auto!' =>
									$isDrawer || $width === 'screen' || $stickyFooter || $stickyHeader,
								'max-h-[calc(100vh-13.8rem)]!' =>
									($stickyHeader || $stickyFooter) && !$isDrawer && $width !== 'screen',
							])
							>
								{{ $slot }}
							</div>
						@endif
						@if ($hasFooter)
							<div
								@class ([
								'modal-footer flex! flex-wrap! gap-3! px-6! py-4!',
								'border-t! border-neutral-200!',
								'sticky! bottom-0! z-10! bg-white!' => $stickyFooter,
							])
							>
								{{ $footer }}
							</div>
						@endif
					@endif
				</div>
			</div>
		</div>
	</template>
</div>
