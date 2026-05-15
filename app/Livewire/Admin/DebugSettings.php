<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class DebugSettings extends Component
{
	public bool $debug;

	public function mount(): void
	{
		$this->debug = (bool) get_option('framework_debug', true);
	}

	public function updatedDebug(bool $value): void
	{
		update_option('framework_debug', $value);
		
		$this->dispatch('notify', 
			title: $value ? __('Debug activado', 'framework') : __('Debug desactivado', 'framework'),
			description: $value 
				? __('Las trazas de errores ahora son visibles.', 'framework') 
				: __('El sistema ahora oculta los errores detallados.', 'framework'),
			type: 'success',
			icon: 'check-circle'
		);
	}

	public function render()
	{
		return view('framework::livewire.admin.debug-settings');
	}
}
