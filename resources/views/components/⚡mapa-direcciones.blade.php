<?php

use App\Http\Controllers\MapController;
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
	public bool $fuera_de_venezuela = false;
	public int $altura_mapa = 460;

	public function mount(?float $latitud = null, ?float $longitud = null, ?int $altura = null): void
	{
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

		if (! MapController::insideVenezuelaBounds($lat, $lng)) {
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
		$request = new \WP_REST_Request(
			'GET',
			'/' . trim(MapController::REST_NAMESPACE, '/') . MapController::REST_REVERSE_ROUTE
		);
		$request->set_query_params([
			'lat' => number_format($lat, 6, '.', ''),
			'lng' => number_format($lng, 6, '.', ''),
		]);

		$response = rest_do_request($request);
		if ($response->is_error()) {
			return;
		}

		$status = $response->get_status();
		if ($status < 200 || $status >= 300) {
			return;
		}

		$body = $response->get_data();
		if (! is_array($body)) {
			return;
		}

		$this->estado = (string) ($body['estado'] ?? '');
		$this->ciudad = (string) ($body['ciudad'] ?? '');
		$this->municipio = (string) ($body['municipio'] ?? '');
		$this->parroquia = (string) ($body['parroquia'] ?? '');
		$this->codigo_postal = (string) ($body['codigo_postal'] ?? '');
		$this->direccion_completa = (string) ($body['direccion_completa'] ?? '');
		$this->fuera_de_venezuela = (bool) ($body['fuera_de_venezuela'] ?? false);
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
	<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
		<x-ui.card class="!max-w-none border-orange-200/70 bg-white">
			<div class="mb-4 flex items-center justify-between gap-3">
				<x-ui.heading level="h3" size="md" class="text-orange-900">
					{{ __('Mapa de direcciones', 'framework') }}
				</x-ui.heading>
				<x-ui.badge variant="outline" :color="$fuera_de_venezuela ? 'red' : 'orange'" pill>
					{{ $fuera_de_venezuela ? __('Fuera de Venezuela', 'framework') : __('Dentro de Venezuela', 'framework') }}
				</x-ui.badge>
			</div>

			<div wire:ignore>
				<div
					data-mapa-direcciones-root="1"
					data-livewire-id="{{ $livewireId }}"
					data-lat="{{ $latitud }}"
					data-lng="{{ $longitud }}"
					data-height="{{ $altura_mapa }}"
				></div>
			</div>
		</x-ui.card>

		<x-ui.card class="!max-w-none border-orange-200/70 bg-white">
			<x-ui.heading level="h3" size="md" class="mb-4 text-orange-900">
				{{ __('Datos seleccionados', 'framework') }}
			</x-ui.heading>

			<div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
				<x-ui.field>
					<x-ui.label for="estado">{{ __('Estado', 'framework') }}</x-ui.label>
					<x-ui.input id="estado" name="estado" :value="$estado" readonly />
				</x-ui.field>

				<x-ui.field>
					<x-ui.label for="ciudad">{{ __('Ciudad', 'framework') }}</x-ui.label>
					<x-ui.input id="ciudad" name="ciudad" :value="$ciudad" readonly />
				</x-ui.field>

				<x-ui.field>
					<x-ui.label for="municipio">{{ __('Municipio', 'framework') }}</x-ui.label>
					<x-ui.input id="municipio" name="municipio" :value="$municipio" readonly />
				</x-ui.field>

				<x-ui.field>
					<x-ui.label for="parroquia">{{ __('Parroquia', 'framework') }}</x-ui.label>
					<x-ui.input id="parroquia" name="parroquia" :value="$parroquia" readonly />
				</x-ui.field>

				<x-ui.field>
					<x-ui.label for="codigo_postal">{{ __('Código postal', 'framework') }}</x-ui.label>
					<x-ui.input id="codigo_postal" name="codigo_postal" :value="$codigo_postal" readonly />
				</x-ui.field>

				<x-ui.field>
					<x-ui.label for="latitud">{{ __('Latitud', 'framework') }}</x-ui.label>
					<x-ui.input id="latitud" name="latitud" wire:model.blur="latitud" />
				</x-ui.field>

				<x-ui.field>
					<x-ui.label for="longitud">{{ __('Longitud', 'framework') }}</x-ui.label>
					<x-ui.input id="longitud" name="longitud" wire:model.blur="longitud" />
				</x-ui.field>
			</div>

			<div class="mt-4 rounded-lg border border-orange-100 bg-orange-50/50 p-3">
				<x-ui.text class="text-sm text-orange-900">
					<strong>{{ __('Dirección completa', 'framework') }}:</strong>
				</x-ui.text>
				<x-ui.text class="mt-1 block text-sm text-orange-800">
					{{ $direccion_completa !== '' ? $direccion_completa : __('Sin datos de dirección.', 'framework') }}
				</x-ui.text>
				<ul class="mt-3 space-y-1">
					<li><x-ui.text class="text-xs text-orange-900"><strong>{{ __('Estado', 'framework') }}:</strong> {{ $estado !== '' ? $estado : '-' }}</x-ui.text></li>
					<li><x-ui.text class="text-xs text-orange-900"><strong>{{ __('Ciudad', 'framework') }}:</strong> {{ $ciudad !== '' ? $ciudad : '-' }}</x-ui.text></li>
					<li><x-ui.text class="text-xs text-orange-900"><strong>{{ __('Municipio', 'framework') }}:</strong> {{ $municipio !== '' ? $municipio : '-' }}</x-ui.text></li>
					<li><x-ui.text class="text-xs text-orange-900"><strong>{{ __('Parroquia', 'framework') }}:</strong> {{ $parroquia !== '' ? $parroquia : '-' }}</x-ui.text></li>
					<li><x-ui.text class="text-xs text-orange-900"><strong>{{ __('Código postal', 'framework') }}:</strong> {{ $codigo_postal !== '' ? $codigo_postal : '-' }}</x-ui.text></li>
					<li><x-ui.text class="text-xs text-orange-900"><strong>{{ __('Latitud', 'framework') }}:</strong> {{ $latitud }}</x-ui.text></li>
					<li><x-ui.text class="text-xs text-orange-900"><strong>{{ __('Longitud', 'framework') }}:</strong> {{ $longitud }}</x-ui.text></li>
				</ul>
			</div>
		</x-ui.card>
	</div>
</div>
