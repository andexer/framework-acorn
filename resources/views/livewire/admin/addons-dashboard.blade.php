<div class="space-y-4">
	<div class="flex items-center justify-between">
		<h2 class="text-lg font-semibold">{{ __('Addons del Core', 'framework') }}</h2>
		<a href="{{ admin_url('plugins.php') }}" class="button button-secondary">{{ __('Ir a Plugins', 'framework') }}</a>
	</div>

	@if (empty($addons))
		<div class="notice notice-info inline-block"><p>{{ __('No hay addons heredados detectados.', 'framework') }}</p></div>
	@else
		<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
			@foreach ($addons as $addon)
				<div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
					<div class="flex items-start justify-between gap-2">
						<h3 class="text-base font-semibold m-0">{{ $addon['name'] }}</h3>
						<span class="text-xs px-2 py-1 rounded {{ $addon['active'] ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">{{ $addon['active'] ? __('Activo', 'framework') : __('Inactivo', 'framework') }}</span>
					</div>
					<p class="text-sm text-gray-600 mt-2">{{ $addon['description'] !== '' ? $addon['description'] : __('Sin descripción.', 'framework') }}</p>
					<div class="mt-3 text-sm text-gray-700">
						<div><strong>{{ __('Autor', 'framework') }}:</strong> {{ $addon['author'] !== '' ? $addon['author'] : __('N/A', 'framework') }}</div>
						<div><strong>{{ __('Versión', 'framework') }}:</strong> {{ $addon['version'] !== '' ? $addon['version'] : __('N/A', 'framework') }}</div>
					</div>
				</div>
			@endforeach
		</div>
	@endif
</div>
