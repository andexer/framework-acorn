<?php

use Livewire\Component;
use Livewire\Attributes\Locked;
use App\Http\Requests\FormValidator;

new class extends Component
{
	#[Locked]
	public int $step = 1;

	public string $name = '';
	public string $email = '';
	public string $city = '';
	public string $postal_code = '';

	/**
	 * Define las reglas de validación usando el FormRequest.
	 */
	public function rules(): array
	{
		return (new FormValidator())->rules();
	}

	/**
	 * Define los atributos amigables para los errores.
	 */
	public function validationAttributes(): array
	{
		return (new FormValidator())->attributes();
	}

	public function nextStep()
	{
		if ($this->step === 1) {
			$this->validateOnly('name');
			$this->validateOnly('email');
		} elseif ($this->step === 2) {
			$this->validateOnly('city');
			$this->validateOnly('postal_code');
		}

		$this->step++;
	}

	public function prevStep()
	{
		if ($this->step > 1) {
			$this->step--;
		}
	}

	public function submit()
	{
		$this->validate();

		// Simular guardado
		session()->flash('message', __('¡Registro completado con éxito!'));
		$this->reset(['name', 'email', 'city', 'postal_code', 'step']);
	}
};
