@aware ([
	'collapsable' => true,
])

@props ([
	'stickyHeader' => true,
	'scrollable' => true,
	'collapsable' => true,
	'brand' => null,
])

@php
	$classes = [
		'isolate',
		'[grid-area:sidebar]',
		'z-40 bg-white lg:block',
		'border-r border-black/5',
		'transition-[width] duration-500',
		'overflow-x-visible',
		'!overflow-y-auto' => $scrollable,
	];
@endphp

<div
	{{ $attributes->class($classes) }}
	data-slot="sidebar"
	style="z-index: 99"
	@if ($collapsable)
		x-data="{
 collapsable: @js($collapsable)
 }"
	@endif
	x-init="
		if (window.matchMedia('(pointer: coarse)').matches) {
			$el.addEventListener('click', (event) => {
				if (event.target.closest('[data-slot=sidebar-brand]')) {
					return;
				}

				if (event.target.closest('[data-slot=sidebar-toggle]')) {
					return;
				}

				if ($data.isMobile) {
					return;
				}

				toggle();
			});
		}
	"
>
	@if (filled($brand))
		<div
			@class ([
			"justify-between items-center group w-full
												 [:not(:has([data-collapsed]_&))_&]:flex
												 min-h-[var(--header-height)]
												 [:not(:has([data-collapsed]_&))_&]:px-4
												 mx-auto flex-shrink-0",

			'sticky z-10 top-0 bg-white' => $stickyHeader,
		])
		>
			<div
				@class ([
				"[:not(:has([data-collapsed]_&))_&]:mx-auto grow
																		 [:has([data-collapsed]_&)_&]:[&_[data-slot=brand-name]]:hidden",
				'[:has([data-collapsed]_&)_&]:group-hover:hidden' => $collapsable,
			])
				data-slot="sidebar-brand"
			>
				{{ $brand }}
			</div>

			@if ($collapsable)
				<x-ui.sidebar.toggle
					x-bind:data-collapsable="collapsable"
					class="[&:not([data-collapsable=true])]:hidden [:has([data-collapsed]_&)_&]:group-hover:inline-flex [:has([data-collapsed]_&)_&]:hidden [:has([data-collapsed]_&)_&]:cursor-ew-resize"
				/>
			@endif
		</div>
	@endif

	<div
		@class ([
		'flex flex-col min-h-[calc(100vh-var(--header-height))]',
		'z-0' => $stickyHeader,
	])
	>
		{{ $slot }}
	</div>
</div>
