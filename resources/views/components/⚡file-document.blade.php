<?php

use App\Actions\File\EnqueueFileDocumentAssetsAction;
use App\Actions\File\ValidateBase64FileAction;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
	/** Base64 content of the document */
	public ?string $documentData = null;

	/** Original filename */
	public ?string $filename = null;

	public string $label = '';

	public bool $required = false;

	/** Accept attribute for file input */
	public string $accept = '.pdf,.doc,.docx,.xls,.xlsx,.txt';

	/** Max size in MB */
	public float $maxSizeMb = 5.0;

	public function mount(
		?string $documentData = null, 
		?string $filename = null, 
		?string $accept = null, 
		float $maxSizeMb = 5.0,
		string $label = '',
		bool $required = false
	): void
	{
		$this->documentData = $documentData;
		$this->filename = $filename;
		if ($accept) {
			$this->accept = $accept;
		}
		$this->maxSizeMb = $maxSizeMb;
		$this->label = $label;
		$this->required = $required;

		app(EnqueueFileDocumentAssetsAction::class)();
	}

	#[On('fileReady')]
	public function fileReady(string $base64, string $name): void
	{
		// Seguridad: Validar base64
		$isValid = app(ValidateBase64FileAction::class)(
			$base64,
			[], // Podríamos pasar mimes permitidos basados en $this->accept si fuera necesario
			$this->maxSizeMb + 0.5
		);

		if (! $isValid) {
			$this->dispatch(
				'notify',
				type: 'error',
				content: __('El archivo no es válido o excede el tamaño permitido', 'framework'),
				duration: 5000
			);

			return;
		}

		$this->documentData = $base64;
		$this->filename = $name;

		$this->dispatch(
			'notify',
			type: 'success',
			content: __('Documento cargado correctamente', 'framework'),
			duration: 3000
		);

		$this->dispatch('file-document-updated', base64: $base64, name: $name);
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
				data-file-document-root="1"
				data-wire-id="{{ $this->getId() }}"
				data-initial-url="{{ $documentData }}"
				data-accept="{{ $accept }}"
				data-max-size-mb="{{ $maxSizeMb }}"
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