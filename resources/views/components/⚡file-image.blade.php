<?php

use App\Actions\File\EnqueueFileImageAssetsAction;
use App\Actions\File\ValidateBase64FileAction;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
	/** Base64 content to return or show */
	public ?string $imageData = null;

	/** Aspect ratio (null = free, 1 = square, etc.) */
	public ?float $aspectRatio = 1.0;
	
	public string $label = '';
	
	public bool $required = false;

	public string $accept = 'image/jpeg,image/png,image/webp,image/gif';

	/** Max size in MB for the compressor */
	public float $maxSizeMb = 1.0;

	public function mount(
		?string $imageData = null, 
		?float $aspectRatio = 1.0, 
		float $maxSizeMb = 1.0,
		string $label = '',
		bool $required = false,
		string $accept = 'image/jpeg,image/png,image/webp,image/gif'
	): void
	{
		$this->imageData = $imageData;
		$this->aspectRatio = $aspectRatio;
		$this->maxSizeMb = $maxSizeMb;
		$this->label = $label;
		$this->required = $required;
		$this->accept = $accept;

		app(EnqueueFileImageAssetsAction::class)();
	}

	#[On('imageReady')]
	public function imageReady(string $base64): void
	{
		// Seguridad: Validar que sea un base64 de imagen válido
		$isValid = app(ValidateBase64FileAction::class)(
			$base64,
			['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
			$this->maxSizeMb + 0.5 // Margen por overhead de base64
		);

		if (! $isValid) {
			$this->dispatch(
				'notify',
				type: 'error',
				content: __('El archivo no es una imagen válida o excede el tamaño', 'framework'),
				duration: 5000
			);

			return;
		}

		$this->imageData = $base64;

		$this->dispatch(
			'notify',
			type: 'success',
			content: __('Imagen optimizada y recortada', 'framework'),
			duration: 3000
		);

		$this->dispatch('file-image-updated', base64: $base64);
	}
};
?>

<div class="w-full">
	<x-ui.field>
		@if($label)
			<x-ui.label :required="$required">{{ $label }}</x-ui.label>
		@endif

		{{-- REACT ISLAND MOUNT POINT --}}
		<div wire:ignore>
			<div 
				data-file-image-root="1"
				data-wire-id="{{ $this->getId() }}"
				data-initial-url="{{ $imageData }}"
				data-aspect-ratio="{{ $aspectRatio ?? '' }}"
				data-max-size-mb="{{ $maxSizeMb }}"
				data-accept="{{ $accept }}"
				data-quality="0.9"
				class="w-full"
			>
				{{-- Skeleton loader while React boots --}}
				<div class="flex items-center gap-5 w-full animate-pulse">
					<div class="shrink-0 size-24 sm:size-28 rounded-2xl bg-zinc-200 border-2 border-dashed border-zinc-300"></div>
					<div class="flex flex-col gap-2">
						<div class="h-4 w-24 bg-zinc-200 rounded-md"></div>
						<div class="h-3 w-40 bg-zinc-200 rounded-md"></div>
					</div>
				</div>
			</div>
		</div>
	</x-ui.field>
</div>