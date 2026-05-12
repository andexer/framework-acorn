<div class="wrap" id="framework-map-settings-page">
	<div class="mt-4 grid grid-cols-1 gap-6 xl:grid-cols-2">
		<x-ui.card class="!max-w-none border-orange-200/70 bg-gradient-to-br from-orange-50 to-white">
			<div class="flex items-start justify-between gap-4">
				<div>
					<x-ui.heading level="h2" size="lg" class="text-orange-900">
						{{ __('Mapa Interactivo', 'framework') }}
					</x-ui.heading>
					<x-ui.description class="mt-1 text-orange-700/90">
						{{ __('Configura el país por defecto para el shortcode [framework_map].', 'framework') }}
					</x-ui.description>
				</div>
				<x-ui.badge variant="outline" color="orange" pill>
					{{ __('Activo', 'framework') }}
				</x-ui.badge>
			</div>

			<div class="mt-5 rounded-xl border border-orange-200 bg-orange-50/60 p-4">
				<x-ui.text class="text-sm text-orange-800">
					<strong>{{ __('País actual:', 'framework') }}</strong>
					{{ $currentName }}
					(<span class="font-mono">{{ $currentCode }}</span>)
				</x-ui.text>
			</div>

			<form method="POST" action="options.php" id="framework-map-settings-form" class="mt-5 space-y-4">
				@php settings_fields($settingsGroup) @endphp
				@php do_settings_sections($pageSlug) @endphp

				<div class="flex items-center gap-3 pt-2">
					<x-ui.button type="submit" color="orange" variant="gradient" icon="check">
						{{ __('Guardar configuración', 'framework') }}
					</x-ui.button>
					<x-ui.link href="{{ get_home_url() }}" target="_blank" class="text-orange-700 hover:text-orange-800">
						{{ __('Ver sitio', 'framework') }}
					</x-ui.link>
				</div>
			</form>
		</x-ui.card>

		<x-ui.card class="!max-w-none border-orange-200/70 bg-white">
			<div class="flex items-center gap-2">
				<x-ui.icon name="information-circle" class="size-5 text-orange-600" />
				<x-ui.heading level="h3" size="md" class="text-orange-900">
					{{ __('Uso del shortcode', 'framework') }}
				</x-ui.heading>
			</div>

			<div class="mt-4 space-y-3">
				<div class="rounded-lg border border-orange-100 bg-orange-50/40 p-3">
					<x-ui.text class="text-sm text-orange-900"><strong>{{ __('Por defecto', 'framework') }}</strong></x-ui.text>
					<code class="mt-1 inline-block rounded bg-orange-100 px-2 py-1 text-xs text-orange-900">[framework_map]</code>
				</div>

				<div class="rounded-lg border border-orange-100 bg-orange-50/40 p-3">
					<x-ui.text class="text-sm text-orange-900"><strong>{{ __('País específico', 'framework') }}</strong></x-ui.text>
					<code class="mt-1 inline-block rounded bg-orange-100 px-2 py-1 text-xs text-orange-900">[framework_map country="AR"]</code>
				</div>

				<div class="rounded-lg border border-orange-100 bg-orange-50/40 p-3">
					<x-ui.text class="text-sm text-orange-900"><strong>{{ __('Altura personalizada', 'framework') }}</strong></x-ui.text>
					<code class="mt-1 inline-block rounded bg-orange-100 px-2 py-1 text-xs text-orange-900">[framework_map height="600"]</code>
				</div>

				<div class="rounded-lg border border-orange-100 bg-orange-50/40 p-3">
					<x-ui.text class="text-sm text-orange-900"><strong>{{ __('Combinado', 'framework') }}</strong></x-ui.text>
					<code class="mt-1 inline-block rounded bg-orange-100 px-2 py-1 text-xs text-orange-900">[framework_map country="MX" height="400"]</code>
				</div>
			</div>
		</x-ui.card>

		<x-ui.card class="!max-w-none border-orange-200/70 bg-white">
			{!! do_shortcode('[framework_map]') !!}
		</x-ui.card>
	</div>
</div>
