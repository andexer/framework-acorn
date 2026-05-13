<?php

use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component
{
	/** Base64 content of the document */
	public ?string $documentData = null;

	/** Original filename */
	public ?string $filename = null;

	/** Accept attribute for file input */
	public string $accept = '.pdf,.doc,.docx,.xls,.xlsx,.txt';

	/** Max size in MB */
	public float $maxSizeMb = 5.0;

	public function mount(?string $documentData = null, ?string $filename = null, ?string $accept = null, float $maxSizeMb = 5.0): void
	{
		$this->documentData = $documentData;
		$this->filename = $filename;
		if ($accept) $this->accept = $accept;
		$this->maxSizeMb = $maxSizeMb;
		
		$this->enqueueAssets();
	}

	#[On('fileReady')]
	public function fileReady(string $base64, string $name): void
	{
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

	private function enqueueAssets(): void
	{
		if (wp_script_is('framework-file-document-app', 'enqueued')) {
			return;
		}

		$manifest = get_plugin_manifest();
		$entry = 'resources/js/components/react/file-document.bootstrap.tsx';
		if (! isset($manifest[$entry])) {
			return;
		}

		$baseUrl = get_plugin_uri('public/build/');

		foreach (get_manifest_entry_css_files($manifest, $entry) as $cssFile) {
			wp_enqueue_style('framework-file-document-' . md5($cssFile), $baseUrl . $cssFile, [], null);
		}

		wp_enqueue_script(
			'framework-file-document-app',
			$baseUrl . $manifest[$entry]['file'],
			['framework-app'],
			null,
			['strategy' => 'defer', 'in_footer' => true],
		);
	}
};
?>

<div class="w-full">
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
</div>