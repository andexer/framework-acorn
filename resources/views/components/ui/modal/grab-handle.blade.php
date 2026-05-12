<div
	x-data="modalGrabHandleComponent({ modal: $data })"
	x-on:touchstart="handleTouchStart($event)"
	x-on:touchmove="handleTouchMove($event)"
	x-on:touchend="handleTouchEnd()"
	x-on:touchcancel="handleTouchEnd()"
	class="relative! flex! justify-center! pt-[2px]! sm:hidden!"
>
	<span
		class="pointer-fine:hidden! absolute! left-1/2! top-1/2! size-12! -translate-1/2!"
	></span>
	<div
		class="transition! h-[5px]! w-[10%]! rounded-box! bg-neutral-300!"
		x-bind:class="{ 'scale-x-125!': moving }"
	></div>
</div>
