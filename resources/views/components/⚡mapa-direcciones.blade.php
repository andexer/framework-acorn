<?php

use App\Actions\Map\CheckVenezuelaBoundsAction;
use App\Actions\Map\ReverseGeocodeAction;
use Livewire\Component;

new class extends Component
{
	public string $estado = '';
	public string $ciudad = '';
	public string $municipio = '';
	public string $parroquia = '';
	public string $codigo_postal = '';
	public string $latitud = '10.480600';
	public string $longitud = '-66.903600';
	public string $latitud_valida = '10.480600';
	public string $longitud_valida = '-66.903600';
	public string $direccion_completa = '';
	public string $punto_referencia = '';
	public bool $fuera_de_venezuela = false;
	public int $altura_mapa = 460;

	public function mount(?float $latitud = null, ?float $longitud = null, ?int $altura = null, ?string $punto_referencia = ''): void
	{
		$this->punto_referencia = $punto_referencia;
		if ($latitud !== null && $longitud !== null) {
			$this->latitud = number_format((float) $latitud, 6, '.', '');
			$this->longitud = number_format((float) $longitud, 6, '.', '');
		}

		if ($altura !== null && $altura >= 320) {
			$this->altura_mapa = $altura;
		}

		$this->latitud_valida = number_format((float) $this->latitud, 6, '.', '');
		$this->longitud_valida = number_format((float) $this->longitud, 6, '.', '');
		$this->latitud = $this->latitud_valida;
		$this->longitud = $this->longitud_valida;

		$this->enqueueMapaDireccionesAssets();
		$this->setCoordinates((float) $this->latitud, (float) $this->longitud);
	}

	public function setCoordinates(float $lat, float $lng): void
	{
		$lat = round($lat, 6);
		$lng = round($lng, 6);

		if (! app(CheckVenezuelaBoundsAction::class)($lat, $lng)) {
			$this->fuera_de_venezuela = true;
			$this->latitud = $this->latitud_valida;
			$this->longitud = $this->longitud_valida;
			$this->dispatchSyncEvent((float) $this->latitud_valida, (float) $this->longitud_valida);
			return;
		}

		$this->latitud_valida = number_format($lat, 6, '.', '');
		$this->longitud_valida = number_format($lng, 6, '.', '');
		$this->latitud = $this->latitud_valida;
		$this->longitud = $this->longitud_valida;
		$this->fuera_de_venezuela = false;
		$this->hydrateAddressFromEndpoint($lat, $lng);
		$this->dispatchSyncEvent($lat, $lng);
	}

	public function updatedLatitud(): void
	{
		$this->syncFromManualInput();
	}

	public function updatedLongitud(): void
	{
		$this->syncFromManualInput();
	}

	private function syncFromManualInput(): void
	{
		if (! is_numeric($this->latitud) || ! is_numeric($this->longitud)) {
			return;
		}

		$this->setCoordinates((float) $this->latitud, (float) $this->longitud);
	}

	private function hydrateAddressFromEndpoint(float $lat, float $lng): void
	{
		$payload = app(ReverseGeocodeAction::class)->handle($lat, $lng);

		if (! is_array($payload)) {
			return;
		}

		$this->estado = (string) ($payload['estado'] ?? '');
		$this->ciudad = (string) ($payload['ciudad'] ?? '');
		$this->municipio = (string) ($payload['municipio'] ?? '');
		$this->parroquia = (string) ($payload['parroquia'] ?? '');
		$this->codigo_postal = (string) ($payload['codigo_postal'] ?? '');
		$this->direccion_completa = (string) ($payload['direccion_completa'] ?? '');
		$this->fuera_de_venezuela = (bool) ($payload['fuera_de_venezuela'] ?? false);
	}

	private function dispatchSyncEvent(float $lat, float $lng): void
	{
		$this->dispatch(
			'framework-mapa-direcciones-sync',
			componentId: $this->getId(),
			lat: $lat,
			lng: $lng,
		);
	}

	private function enqueueMapaDireccionesAssets(): void
	{
		if (wp_script_is('framework-mapa-direcciones-app', 'enqueued')) {
			return;
		}

		$manifest = get_plugin_manifest();
		$entry = 'resources/js/components/react/map-direcciones.bootstrap.tsx';
		if (! isset($manifest[$entry])) {
			return;
		}

		$baseUrl = get_plugin_uri('public/build/');

		foreach (get_manifest_entry_css_files($manifest, $entry) as $cssFile) {
			wp_enqueue_style('framework-mapa-direcciones-' . md5($cssFile), $baseUrl . $cssFile, [], null);
		}

		wp_enqueue_script(
			'framework-mapa-direcciones-app',
			$baseUrl . $manifest[$entry]['file'],
			['framework-app'],
			null,
			['strategy' => 'defer', 'in_footer' => true],
		);
	}
};
?>

@php
	$livewireId = isset($__livewire) ? $__livewire->getId() : null;
@endphp

<div class="w-full">
	<div>
		{{-- Header --}}
		<div class="px-8 py-6 border-b border-orange-50 bg-white flex items-center justify-between">
			<div class="flex items-center gap-4">
				<div class="size-12 rounded-2xl bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center shadow-lg shadow-orange-200">
					<svg class="size-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
						<path strokeLinecap="round" strokeLinejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
						<path strokeLinecap="round" strokeLinejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
					</svg>
				</div>
				<div>
					<h2 class="text-xl font-black text-zinc-900 tracking-tight leading-tight">{{ __('Ubicación y Dirección', 'framework') }}</h2>
					<p class="text-sm font-bold text-zinc-500">{{ __('Información de tu ubicación', 'framework') }}</p>
				</div>
			</div>
		</div>

		<div class="p-8 space-y-8">
			{{-- Map Area (Includes Search) --}}
			<div wire:ignore>
				<div
					data-mapa-direcciones-root="1"
					data-livewire-id="{{ $livewireId }}"
					data-lat="{{ $latitud }}"
					data-lng="{{ $longitud }}"
					data-height="{{ $altura_mapa }}"
				>
					{{-- Skeleton --}}
					<div class="animate-pulse space-y-4">
						<div class="h-10 w-full bg-zinc-100 rounded-2xl"></div>
						<div class="h-[460px] w-full bg-zinc-100 rounded-[2rem]"></div>
					</div>
				</div>
			</div>

			{{-- Form Fields --}}
			<div class="grid grid-cols-1 gap-6">
				{{-- Full Address --}}
				<x-ui.field>
					<x-ui.label for="direccion_completa" class="text-zinc-700 font-bold mb-2">{{ __('Dirección Completa', 'framework') }}</x-ui.label>
					<x-ui.input 
						id="direccion_completa" 
						name="direccion_completa" 
						:value="$direccion_completa" 
						class="py-3 rounded-2xl border-zinc-200"
						readonly 
						placeholder="Ej: Detodo24 parque cristal"
					/>
				</x-ui.field>

				{{-- Punto de Referencia --}}
				<x-ui.field>
					<x-ui.label for="punto_referencia" class="text-zinc-700 font-bold mb-2">{{ __('Punto de Referencia', 'framework') }}</x-ui.label>
					<x-ui.input 
						id="punto_referencia" 
						name="punto_referencia" 
						wire:model.blur="punto_referencia"
						class="py-3 rounded-2xl border-zinc-200"
						placeholder="Al lado del banco BNC"
					/>
				</x-ui.field>

				{{-- Grid: Estado, Municipio, Parroquia --}}
				<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
					<x-ui.field>
						<x-ui.label for="estado" class="text-zinc-700 font-bold mb-2">{{ __('Estado', 'framework') }}</x-ui.label>
						<x-ui.input id="estado" name="estado" :value="$estado" readonly class="rounded-2xl border-zinc-200 py-3" />
					</x-ui.field>

					<x-ui.field>
						<x-ui.label for="municipio" class="text-zinc-700 font-bold mb-2">{{ __('Municipio', 'framework') }}</x-ui.label>
						<x-ui.input id="municipio" name="municipio" :value="$municipio" readonly class="rounded-2xl border-zinc-200 py-3" />
					</x-ui.field>

					<x-ui.field>
						<x-ui.label for="parroquia" class="text-zinc-700 font-bold mb-2">{{ __('Parroquia', 'framework') }}</x-ui.label>
						<x-ui.input id="parroquia" name="parroquia" :value="$parroquia" readonly class="rounded-2xl border-zinc-200 py-3" />
					</x-ui.field>
				</div>

				{{-- Grid: Ciudad, CP --}}
				<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
					<x-ui.field>
						<x-ui.label for="ciudad" class="text-zinc-700 font-bold mb-2">{{ __('Ciudad', 'framework') }}</x-ui.label>
						<x-ui.input id="ciudad" name="ciudad" :value="$ciudad" readonly class="rounded-2xl border-zinc-200 py-3" />
					</x-ui.field>

					<x-ui.field>
						<x-ui.label for="codigo_postal" class="text-zinc-700 font-bold mb-2">{{ __('Código Postal', 'framework') }}</x-ui.label>
						<x-ui.input 
							id="codigo_postal" 
							name="codigo_postal" 
							:value="$codigo_postal" 
							class="py-3 rounded-2xl border-zinc-200"
							readonly 
						/>
					</x-ui.field>
				</div>
				
				{{-- Coordinates (Hidden/Discreet) --}}
				<div class="pt-4 flex gap-4 text-[10px] font-bold text-zinc-400 uppercase tracking-widest">
					<span>LAT: {{ $latitud }}</span>
					<span>LNG: {{ $longitud }}</span>
					@if($fuera_de_venezuela)
						<span class="text-red-500 font-black ml-auto">⚠️ {{ __('FUERA DE VENEZUELA', 'framework') }}</span>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
