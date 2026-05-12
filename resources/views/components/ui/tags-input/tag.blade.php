@php

	$colorClasses = match ($tagColor) {
		default => 'text-neutral-900 bg-neutral-900/15 border-black/10 ',
		'red' => 'text-red-700 bg-red-400/15 border-red-400 ',
		'orange' => 'text-orange-700 bg-orange-400/15 border-orange-400 ',
		'amber' => 'text-amber-700 bg-amber-400/15 border-amber-400 ',
		'yellow' => 'text-yellow-800 bg-yellow-400/15 border-yellow-400 ',
		'lime' => 'text-lime-800 bg-lime-400/15 border-lime-400 ',
		'green' => 'text-green-800 bg-green-400/15 border-green-400 ',
		'emerald' => 'text-emerald-800 bg-emerald-400/15 border-emerald-400 ',
		'teal' => 'text-teal-800 bg-teal-400/15 border-teal-400 ',
		'cyan' => 'text-cyan-800 bg-cyan-400/15 border-cyan-400 ',
		'sky' => 'text-sky-800 bg-sky-400/15 border-sky-400 ',
		'blue' => 'text-blue-800 bg-blue-400/15 border-blue-400 ',
		'indigo' => 'text-indigo-700 bg-indigo-400/15 border-indigo-400 ',
		'violet' => 'text-violet-700 bg-violet-400/15 border-violet-400 ',
		'purple' => 'text-purple-700 bg-purple-400/15 border-purple-400 ',
		'fuchsia' => 'text-fuchsia-700 bg-fuchsia-400/15 border-fuchsia-400 ',
		'pink' => 'text-pink-700 bg-pink-400/15 border-pink-400 ',
		'rose' => 'text-rose-700 bg-rose-400/15 border-rose-400 ',
	};

	$variantClasses = match ($tagVariant) {
		'rounded' => ' rounded-field border',
		'pill' => 'rounded-full border ',
	};

	$classes = ['px-2.5 py-0.5 text-xs font-medium', $variantClasses, $colorClasses];

@endphp

<div
	@class (Arr::toCssClasses($classes))
	draggable="true"
	x-on:dragstart="onDragStart(index)"
	x-on:dragover.prevent=""
	x-bind:class="{ 'opacity-50': dragIndex === index }"
	x-on:drop="onDrop(event, index)"
>
	<span x-text="tag" class="select-none text-start"></span>
	<button
		type="button"
		x-on:click="deleteTag(index)"
		class="ml-1 cursor-pointer text-current hover:text-red-500 transition"
	>
		&times;
	</button>
</div>
