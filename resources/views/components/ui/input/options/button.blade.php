<button
	{{ $attributes }}
	data-slot="input-option"
	type="button"
	class="w-8! h-8! min-w-8! min-h-8! max-w-8! max-h-8! flex! cursor-pointer! items-center! justify-center! rounded-md! p-1! text-neutral-400! hover:text-neutral-700! bg-neutral-200! hover:bg-neutral-100! transition-colors! [&_[data-slot=icon]]:size-4!"
>
	{{ $slot }}
</button>
