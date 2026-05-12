<div class="max-w-xl mx-auto p-4 sm:p-6 w-full">
	@if (session()->has('message'))
		<x-ui.alerts variant="success" class="mb-6">
			{{ session('message') }}
		</x-ui.alerts>
	@endif

	<x-ui.card class="p-5 sm:p-8 !bg-white !shadow-sm">
		<div class="mb-6 sm:mb-8">
			<x-ui.heading level="2" class="text-xl sm:text-2xl font-bold mb-1">
				@if($step === 1)
					{{ __('Datos Personales') }}
				@elseif($step === 2)
					{{ __('Dirección de Envío') }}
				@else
					{{ __('Confirmar Registro') }}
				@endif
			</x-ui.heading>
			<p class="text-neutral-500 text-xs sm:text-sm">
				{{ sprintf(__('Paso %s de 3'), $step) }}
			</p>

			{{-- Progress Bar --}}
			<div class="mt-4 h-1.5 w-full bg-neutral-100 rounded-full overflow-hidden">
				<div class="h-full bg-brand-500 transition-all duration-500 ease-in-out"
					style="width: {{ ($step / 3) * 100 }}%"></div>
			</div>
		</div>

		<form wire:submit.prevent="submit" class="space-y-5 sm:space-y-6">
			@if($step === 1)
				<div class="space-y-4" x-transition>
					<x-ui.field required>
						<x-ui.label for="name">{{ __('Nombre Completo') }}</x-ui.label>
						<x-ui.input id="name" wire:model.blur="name" placeholder="Ej. Juan Pérez" />
						<x-ui.error name="name" />
					</x-ui.field>

					<x-ui.field required>
						<x-ui.label for="email">{{ __('Correo Electrónico') }}</x-ui.label>
						<x-ui.input id="email" type="email" wire:model.blur="email" placeholder="juan@ejemplo.com" />
						<x-ui.error name="email" />
					</x-ui.field>
				</div>
			@endif

			@if($step === 2)
				<div class="space-y-4" x-transition>
					<x-ui.field required>
						<x-ui.label for="city">{{ __('Ciudad') }}</x-ui.label>
						<x-ui.input id="city" wire:model.blur="city" placeholder="Ej. Madrid" />
						<x-ui.error name="city" />
					</x-ui.field>

					<x-ui.field required>
						<x-ui.label for="postal_code">{{ __('Código Postal') }}</x-ui.label>
						<x-ui.input id="postal_code" wire:model.blur="postal_code" placeholder="Ej. 28001" />
						<x-ui.error name="postal_code" />
					</x-ui.field>
				</div>
			@endif

			@if($step === 3)
				<div class="bg-neutral-50 p-4 sm:p-6 rounded-2xl space-y-3 sm:space-y-4 text-sm sm:text-base" x-transition>
					<div class="flex flex-col sm:flex-row sm:justify-between border-b border-neutral-200 pb-2 gap-1 sm:gap-0">
						<span class="text-neutral-500">{{ __('Nombre') }}:</span>
						<span class="font-medium break-words">{{ $name }}</span>
					</div>
					<div class="flex flex-col sm:flex-row sm:justify-between border-b border-neutral-200 pb-2 gap-1 sm:gap-0">
						<span class="text-neutral-500">{{ __('Email') }}:</span>
						<span class="font-medium text-brand-600 break-words">{{ $email }}</span>
					</div>
					<div class="flex flex-col sm:flex-row sm:justify-between border-b border-neutral-200 pb-2 gap-1 sm:gap-0">
						<span class="text-neutral-500">{{ __('Ciudad') }}:</span>
						<span class="font-medium break-words">{{ $city }}</span>
					</div>
					<div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0">
						<span class="text-neutral-500">{{ __('C.P.') }}:</span>
						<span class="font-medium break-words">{{ $postal_code }}</span>
					</div>
				</div>
			@endif

			<div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-between gap-3 sm:gap-4 pt-6">
				@if($step > 1)
					<x-ui.button variant="outline" wire:click="prevStep" icon="arrow-left" color="slate" class="w-full sm:w-auto">
						{{ __('Anterior') }}
					</x-ui.button>
				@else
					<div class="hidden sm:block"></div>
				@endif

				@if($step < 3)
					<x-ui.button wire:click="nextStep" icon-after="arrow-right" color="brand" class="w-full sm:w-auto">
						{{ __('Siguiente') }}
					</x-ui.button>
				@else
					<x-ui.button type="submit" variant="gradient" color="brand" icon-after="check" class="w-full sm:w-auto">
						{{ __('Completar Registro') }}
					</x-ui.button>
				@endif
			</div>
		</form>
	</x-ui.card>
</div>