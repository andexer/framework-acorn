@props (['animate' => 'glow', 'speed' => 'normal'])

<x-ui.skeleton.abstract
	:attributes="$attributes->class(['[:where(&)]:h-4 [:where(&)]:rounded-md', '[:where(&)]:bg-neutral-400/20'])"
	data-slot="skeleton"
>
	{{ $slot }}
</x-ui.skeleton.abstract>
