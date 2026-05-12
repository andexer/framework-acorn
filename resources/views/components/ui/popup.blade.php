@props ([
	'autofocus' => true,
])

<div
	@if ($autofocus)
		x-data="{ shown: false }"
		x-modelable="shown"
		x-trap="shown"
		x-init="
			$nextTick(() => {
				let observer = new MutationObserver(() => {
					shown = $el._x_isShown;
				});
				observer.observe($el, {
					attributes: true,
					attributeFilter: ['style'],
				});
			})
		"
	@endif
	{{ $attributes->class(['absolute z-50 bg-white w-full mt-1 backdrop-blur-xl border border-neutral-200 rounded-(--popup-round) shadow-lg p-(--popup-padding)']) }}
	x-transition:enter="transition ease-out duration-200"
	x-transition:enter-start="opacity-0 transform scale-95"
	x-transition:enter-end="opacity-100 transform scale-100"
	x-transition:leave="transition ease-in duration-150"
	x-transition:leave-start="opacity-100 transform scale-100"
	x-transition:leave-end="opacity-0 transform scale-95"
	style="display: none"
>
	{{ $slot }}
</div>
