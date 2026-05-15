<div class="max-w-4xl">
	<x-ui.card class="overflow-hidden border-orange-100 shadow-xl shadow-orange-900/5">
		<x-slot name="header">
			<div class="flex items-center gap-3">
				<div class="size-10 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600">
					<x-ui.icon name="command-line" class="size-6" />
				</div>
				<div>
					<h3 class="text-lg font-black text-orange-900 leading-none">{{ __('Opciones de Desarrollo', 'framework') }}</h3>
					<p class="text-sm text-orange-700/60 mt-1">{{ __('Controla el comportamiento interno del framework.', 'framework') }}</p>
				</div>
			</div>
		</x-slot>

		<div class="space-y-8 p-6">
			<div class="flex items-center justify-between gap-8 p-4 rounded-2xl bg-orange-50/30 border border-orange-100/50">
				<div class="flex-grow">
					<h4 class="font-bold text-orange-950">{{ __('Modo Debug (Acorn)', 'framework') }}</h4>
					<p class="text-sm text-orange-800/70 mt-1">
						{{ __('Cuando está activo, se muestran errores detallados y trazas en pantalla. Se recomienda desactivar en producción.', 'framework') }}
					</p>
				</div>
				
				<x-ui.switch 
					wire:model.live="debug" 
					label="{{ $debug ? __('Activo', 'framework') : __('Inactivo', 'framework') }}" 
					size="lg"
				/>
			</div>

			<div class="flex items-start gap-4 p-4 rounded-2xl bg-blue-50/30 border border-blue-100/50">
				<x-ui.icon name="information-circle" class="size-5 text-blue-500 mt-0.5" />
				<div class="text-sm text-blue-900/80 leading-relaxed">
					{{ __('Esta configuración afecta globalmente a todos los addons y componentes del Core que utilicen el sistema de configuración estándar de Laravel.', 'framework') }}
				</div>
			</div>
		</div>

		<x-slot name="footer">
			<div class="flex items-center justify-between text-xs text-orange-700/40 font-medium">
				<span>{{ __('Framework Version') }}: {{ \App\Framework\AddonBootstrapper::version() }}</span>
				<span>{{ __('Estado del Entorno') }}: {{ config('app.env') }}</span>
			</div>
		</x-slot>
	</x-ui.card>
</div>
