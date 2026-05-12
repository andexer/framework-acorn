<div
	@class ([
	'grid items-center justify-center px-(--popup-padding) mb-(--popup-padding) grid-cols-[20px_1fr]',
	'[&>[data-slot=icon]+[data-slot=search-control]]:pl-8',
	'w-full border-b py-(--popup-padding) border-neutral-200 ',
])
>
	<x-ui.icon
		name="magnifying-glass"
		class="col-span-1 col-start-1 row-start-1 !text-neutral-500 !size-5 ml-1.5"
	/>

	<input
		x-rover:input
		{{ $attributes }}
		x-on:input.stop
		data-slot="search-control"
		placeholder="search..."
		type="text"
		@class ([
			'bg-transparent h-8 placeholder:text-neutral-500 text-neutral-900',
			'ring-0 ring-offset-0 outline-none focus:ring-0 border-0',
			'col-span-4 col-start-1 row-start-1',
		])
	/>
</div>
