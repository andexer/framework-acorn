@aware ([
	'icon' => '',
	'iconAfter' => 'chevron-up-down',
	'disabled' => false,
	'clearable' => false,
	'searchable' => false,
	'triggerClass' => '',
	'invalid' => false,
	'trigger' => null,
	'size' => 'default',
	'placeholder' => 'select...',
])

@php
	$classes = [
		'border-red-600/30! border-2! data-open:border-red-600/30! data-open:ring-red-600/20!' => $invalid,
		'border-black/10! data-open:border-black/15! data-open:ring-neutral-900/15!' => !$invalid,
		'border! bg-white! border-gray-300! rounded-box! text-start! text-zinc-900!',
		'data-open:ring-2! data-open:ring-offset-0! data-open:outline-none!',
		'col-span-4! col-start-1! row-start-1! justify-self-stretch!',
		'disabled:opacity-60! flex! items-center! disabled:cursor-auto! cursor-pointer!',
		'overflow-hidden! whitespace-nowrap! truncate!',
		match ($size) {
			'sm' => 'h-10! px-3! text-sm!',
			default => 'h-12! px-4! text-sm!',
		},
	];
@endphp

<div
	x-ref="trigger"
	x-on:click="handleButtonClick"
	data-slot="trigger"
	{{ $attributes->class([
		'relative grid place-items-center grid-cols-[40px_1fr_auto]',
		'[&>[data-slot=icon]+[data-slot=select-control]]:pl-10',
		'[&_[data-slot=icon]]:opacity-40 [&_[data-slot=icon]]:cursor-auto' => $disabled,
	]) }}
>
	@if (filled($icon))
		<x-ui.icon
			:name="$icon"
			class="col-span-1 col-start-1 row-start-1 h-full w-full flex items-center justify-center z-10 !size-[1.10rem]"
		/>
	@endif

	<button
		type="button"
		aria-haspopup="listbox"
		x-bind:aria-expanded="__isOpen ? 'true' : 'false'"
		x-bind:aria-controls="$id('rover-options')"
		x-bind:aria-activedescendant="$rover.getActiveItemId() || false"
		x-bind:aria-multiselectable="__isMultiple || false"
		aria-label="{{ $placeholder }}"
		x-bind:data-open="__isOpen"
		data-slot="select-control"
		@class ($classes)
		@disabled ($disabled)
	>
		<span wire:ignore class="truncate self-center w-full">
			<span x-ref:trigger-value>{{ $placeholder }}</span>
		</span>
	</button>

	@if (filled($iconAfter))
		<div
			data-slot="icon"
			@if ($clearable) x-show="!hasSelection" @endif
			x-cloak
			tabindex="0"
			class="col-start-3 row-start-1 pr-1 justify-self-center"
		>
			<x-ui.icon
				:name="$iconAfter"
				@class([
				'rounded-md hover:bg-neutral-800/5',
				match ($size) {
					'sm' => 'size-6 p-0.75',
					default => 'size-8 p-1',
				},
			])
			/>
		</div>
	@endif

	@if ($clearable)
		<div
			data-slot="select-clear"
			role="button"
			tabindex="0"
			aria-label="Clear selection"
			x-show="hasSelection"
			x-cloak
			x-on:click.stop="clear()"
			x-on:keydown.enter.stop="clear"
			x-on:keydown.space.prevent.stop="clear"
			x-bind:aria-disabled="!hasSelection"
			class="col-start-3 row-start-1 pr-1 justify-self-center"
		>
			<x-ui.icon
				name="x-mark"
				@class([
				'rounded-md hover:bg-neutral-800/5',
				match ($size) {
					'sm' => 'size-6 p-0.75',
					default => 'size-8 p-1',
				},
			])
			/>
		</div>
	@endif
</div>
