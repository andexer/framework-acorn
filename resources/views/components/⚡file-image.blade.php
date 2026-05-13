<?php

use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component
{
	/** Base64 content to return or show */
	public ?string $imageData = null;

	/** Aspect ratio (null = free, 1 = square, etc.) */
	public ?float $aspectRatio = 1.0;

	/** Max size in MB for the compressor */
	public float $maxSizeMb = 1.0;

	public function mount(?string $imageData = null, ?float $aspectRatio = null, float $maxSizeMb = 1.0): void
	{
		$this->imageData = $imageData;
		$this->aspectRatio = $aspectRatio;
		$this->maxSizeMb = $maxSizeMb;
		
		$this->enqueueAssets();
	}

	#[On('imageReady')]
	public function imageReady(string $base64): void
	{
		$this->imageData = $base64;
		
		$this->dispatch(
			'notify',
			type: 'success',
			content: __('Imagen optimizada y recortada', 'framework'),
			duration: 3000
		);

		// If the parent component needs the data, it should listen to 'file-image-updated'
		$this->dispatch('file-image-updated', base64: $base64);
	}

	private function enqueueAssets(): void
	{
		if (wp_script_is('framework-file-image-app', 'enqueued')) {
			return;
		}

		$manifest = get_plugin_manifest();
		$entry = 'resources/js/components/react/file-image.bootstrap.tsx';
		if (! isset($manifest[$entry])) {
			return;
		}

		$baseUrl = get_plugin_uri('public/build/');

		foreach (get_manifest_entry_css_files($manifest, $entry) as $cssFile) {
			wp_enqueue_style('framework-file-image-' . md5($cssFile), $baseUrl . $cssFile, [], null);
		}

		wp_enqueue_script(
			'framework-file-image-app',
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
			data-file-image-root="1"
			data-wire-id="{{ $this->getId() }}"
			data-initial-url="{{ $imageData }}"
			data-aspect-ratio="{{ $aspectRatio ?? '' }}"
			data-max-size-mb="{{ $maxSizeMb }}"
			data-quality="0.9"
			class="w-full"
		>
			{{-- Skeleton loader while React boots (Simple Preview style) --}}
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