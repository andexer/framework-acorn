@props ([
	'align' => 'right',
	'label' => '',
	'name' => $attributes->whereStartsWith('wire:model')->first() ?? $attributes->whereStartsWith('x-model')->first(),
	'description' => null,
	'disabled' => false,
	'maxWidth' => 'max-w-md',
	'checked' => false,
	'size' => 'md',
	'switchClass' => '',
	'thumbClass' => '',
	'iconOn' => null,
	'iconOff' => null,
	'onClass' => '',
	'offClass' => '',
	'thumbOnClass' => '',
	'thumbOffClass' => '',
])

@php
	$id = $name ?? Str::uuid();

	$sizeConfig = match ($size) {
		'sm' => [
			'switch' => 'h-4! w-7! min-h-4! min-w-7! max-h-4! max-w-7! flex-shrink-0! shrink-0!',
			'thumb' => 'h-3! w-3! min-h-3! min-w-3! max-h-3! max-w-3! flex-shrink-0! shrink-0!',
			'activeTranslate' => 'translate-x-3!',
			'iconSize' => 'size-2.5!',
		],
		'lg' => [
			'switch' => 'h-8! w-14! min-h-8! min-w-14! max-h-8! max-w-14! flex-shrink-0! shrink-0!',
			'thumb' => 'h-7! w-7! min-h-7! min-w-7! max-h-7! max-w-7! flex-shrink-0! shrink-0!',
			'activeTranslate' => 'translate-x-6!',
			'iconSize' => 'size-6!',
		],

		default => [
			'switch' => 'h-6! w-11! min-h-6! min-w-11! max-h-6! max-w-11! flex-shrink-0! shrink-0!',
			'thumb' => 'h-5! w-5! min-h-5! min-w-5! max-h-5! max-w-5! flex-shrink-0! shrink-0!',
			'activeTranslate' => 'translate-x-5!',
			'iconSize' => 'size-3!',
		],
	};

	$wrapperClasses = ['w-fit', $maxWidth];

	$containerClasses = [
		'flex items-center gap-x-2',
		match ($align) {
			'left' => 'flex-row',
			default => 'flex-row-reverse',
		},
	];

	$switchClasses = [
		'relative inline-flex flex-shrink-0! shrink-0! cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none disabled:cursor-not-allowed disabled:opacity-50',
		$sizeConfig['switch'],
		$switchClass,
	];

	$thumbClasses = [
		'pointer-events-none inline-flex flex-shrink-0! shrink-0! items-center justify-center transform rounded-full shadow ring-0 transition duration-200 ease-in-out',
		$sizeConfig['thumb'],
		$thumbClass,
	];

@endphp

<div
	{{ $attributes->class(Arr::toCssClasses($wrapperClasses)) }}
	x-modelable="_checked"
	x-data="{ _checked: @js($checked) }"
>
	<div class="">
		<div class="{{ Arr::toCssClasses($containerClasses) }}">
			<div class="flex-shrink-0 flex items-center">
				<button
					type="button"
					class="{{ Arr::toCssClasses($switchClasses) }}"
					x-bind:class="_checked ? '[:where(&)]:bg-neutral-800 [:where(&)]: {{ $onClass }}' :
						'{{ $offClass }} [:where(&)]:bg-neutral-300 [:where(&)]:'"
					x-on:click="_checked = !_checked"
					@disabled ($disabled)
					x-bind:aria-_checked="_checked"
					role="switch"
					aria-labelledby="{{ $id }}-label"
				>
					<span
						x-bind:class="_checked ?
							'{{ $sizeConfig['activeTranslate'] }} {{ $thumbOnClass }} [:where(&)]:bg-white [:where(&)]:' :
							'translate-x-[0.05rem] [:where(&)]:bg-white {{ $thumbOffClass }}'"
						class="{{ Arr::toCssClasses($thumbClasses) }}"
					>
						@if ($iconOn)
							<x-ui.icon
								name="{{ $iconOn }}"
								x-show="_checked"
								class="{{ $sizeConfig['iconSize'] }} text-black! !"
							/>
						@endif

						@if ($iconOff)
							<x-ui.icon
								name="{{ $iconOff }}"
								x-show="!_checked"
								class="{{ $sizeConfig['iconSize'] }} text-black!"
							/>
						@endif
					</span>
				</button>

				@if ($name)
					<input
						type="hidden"
						name="{{ $name }}"
						{{ $attributes }}
						x-bind:value="_checked ? '1' : '0'"
					/>
				@endif
			</div>

			<label
				id="{{ $id }}-label"
				class="block text-start flex-1 text-sm font-medium text-black cursor-pointer select-none"
				@if (!$disabled) x-on:click="_checked = !_checked" @endif
			>
				{{ $label }}
			</label>
		</div>

		@if ($description)
			<p class="text-sm text-gray-500 mt-1 text-start">
				{{ $description }}
			</p>
		@endif
	</div>
</div>
