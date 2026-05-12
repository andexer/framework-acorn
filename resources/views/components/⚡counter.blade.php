<?php

use Livewire\Component;

new class extends Component {
	
	public int $count = 0;

	public function increment()
	{
		$this->count++;
		$this->dispatch(
			'notify',
			type: 'success',
			content: "¡Incrementado! El nuevo total es {$this->count}",
			duration: 2500
		);
	}

	public function decrement()
	{
		$this->count--;
		$this->dispatch(
			'notify',
			type: 'info',
			content: "Reducido. El nuevo total es {$this->count}",
			duration: 2500
		);
	}

	public function resetCount()
	{
		$this->count = 0;
		$this->dispatch(
			'notify',
			type: 'warning',
			content: "El contador se ha reiniciado correctamente.",
			duration: 3500
		);
	}
};
?>

<div class="flex flex-col items-center justify-center w-full max-w-md mx-auto">
	<x-ui.card size="md">
		<!-- Header Section -->
		<div class="p-8 flex items-center justify-between border-b border-zinc-100 bg-zinc-50/40">
			<div class="flex items-center gap-4">
				<div
					class="p-2.5 bg-brand-500 rounded-2xl shadow-lg shadow-brand-500/25 rotate-3 hover:rotate-0 transition-transform">
					<x-ui.icon name="calculator" class="size-6 !text-white" variant="solid" />
				</div>
				<div>
					<x-ui.heading level="h3" size="md">Live Counter</x-ui.heading>
					<x-ui.text>Dashboard
						v1.0</x-ui.text>
				</div>
			</div>
			<x-ui.badge variant="outline" color="brand" pill size="sm">
				En Vivo
			</x-ui.badge>
		</div>

		<!-- Display Area -->
		<div class="p-10 text-center relative bg-white">
			<div class="absolute inset-0 opacity-[0.04] pointer-events-none"
				style="background-image: radial-gradient(var(--color-brand-500) 1.5px, transparent 1.5px); background-size: 24px 24px;">
			</div>

			<div class="relative inline-flex items-center justify-center">
				<span
					class="text-9xl font-black tracking-tighter text-zinc-900 tabular-nums drop-shadow-sm select-none">
					{{ $count }}
				</span>
				<div class="absolute -top-4 -right-6">
					<div class="relative flex size-4">
						<span
							class="absolute inline-flex w-full h-full rounded-full opacity-75 animate-ping bg-brand-400"></span>
						<span
							class="relative inline-flex size-4 rounded-full bg-brand-500 border-2 border-white shadow-sm"></span>
					</div>
				</div>
			</div>

			<div class="mt-6">
				<x-ui.text>Métrica de Interacción</x-ui.text>
			</div>
		</div>

		<!-- Action Controls -->
		<div class="p-8 bg-zinc-50/50 border-t border-zinc-100">
			<div class="grid grid-cols-2 gap-5">
				<x-ui.button wire:click="decrement" variant="outline" size="lg" icon="minus">
					Reducir
				</x-ui.button>

				<x-ui.button wire:click="increment" variant="gradient" size="lg" icon="plus">
					Aumentar
				</x-ui.button>
			</div>

			<div class="mt-8 flex justify-center">
				<x-ui.button wire:click="resetCount" variant="ghost" color="red" size="sm" icon="arrow-path">
					Reiniciar
				</x-ui.button>
			</div>
		</div>
	</x-ui.card>
</div>