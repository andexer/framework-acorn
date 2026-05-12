@props ([
	'name' => $attributes->whereStartsWith('wire:model')->first() ?? $attributes->whereStartsWith('x-model')->first(),
	'length' => 4,
	'type' => 'text',
	'allowedPattern' => '[0-9]',
	'autofocus' => false,
])

@php

	$modelAttrs = collect($attributes->getAttributes())->keys()->first(fn($key) => str_starts_with($key, 'wire:model'));

	$model = $modelAttrs ? $attributes->get($modelAttrs) : null;

	$isLive = $modelAttrs && str_contains($modelAttrs, '.live');
@endphp

<div
	x-data="function() {


	const $entangle = (prop, live) => {
		const binding = $wire.$entangle(prop);
		return live ? binding.live : binding;
	};


	const $initState = (model, live) => model ? $entangle(model, live) : '';

	return {

		_state: $initState(@js($model), @js($isLive)),


		_inputs: [],

		length: @js($length),
		allowedPattern: @js($allowedPattern),
		autofocus: @js($autofocus),


		init() {
			$nextTick(() => {
				this.setupInputs();
				this.updateInputAvailability();


				const externalState = this.$root?._x_model?.get();
				if (externalState) {
					const chars = String(externalState).slice(0, this.length).split('');
					this._state = chars.join('');

					chars.forEach((char, i) => {
						if (this._inputs[i]) {
							this._inputs[i].value = char;
						}
					});
				}


				const lastEmptyInputBox = this._state.length;

				if (this._inputs[lastEmptyInputBox]) {
					this._inputs[lastEmptyInputBox].disabled = false;

					if (this.autofocus) {

						requestAnimationFrame(() => this._inputs[lastEmptyInputBox].focus())
					}
				}
			});


			this.$watch('_state', (value) => {

				this.$root?._x_model?.set(value);
				this.updateInputAvailability();


				if (value.length === this.length) {

					$nextTick(() => this.onComplete());
				}
			});
		},


		setupInputs() {
			this._inputs = Array.from(this.$el.querySelectorAll('[data-slot=otp-input]'));

			this._inputs.forEach((input, index) => {
				input.setAttribute('data-order', index);
				input.setAttribute('aria-label', `Digit ${index + 1} of ${this.length}`);
			});
		},


		updateInputAvailability() {

			const enableCount = this._state.length < this.length ? this._state.length + 1 : this.length;

			this._inputs.forEach((input, index) => {
				input.disabled = index >= enableCount;
			});
		},


		onComplete() {
			this.$dispatch('otp-complete', { code: this._state });
		},


		handleInput(el) {
			const index = parseInt(el.dataset.order);
			let value = el.value;


			if (value.length > 1) {
				value = value.slice(-1);
				el.value = value;
			}


			const regex = new RegExp(`^${this.allowedPattern}$`);
			if (!regex.test(value)) {
				el.value = '';
				return;
			}


			requestAnimationFrame(() => {
				this.$updateStateFromInputs();

				const next = this._inputs[index + 1];
				if (next) this.focusAndSelect(next);
			});
		},


		handlePaste(e) {
			const pasted = e.clipboardData.getData('text');
			const regex = new RegExp(`^${this.allowedPattern}$`);
			const validChars = Array.from(pasted).filter(char => regex.test(char));
			const startIndex = parseInt(e.target.dataset.order);


			for (let i = startIndex; i < this._inputs.length; i++) {
				this._inputs[i].value = '';
			}


			validChars.slice(0, this.length - startIndex).forEach((char, offset) => {
				this.enableAndFill(char, offset + startIndex);
			});


			$nextTick(() => {
				this.$updateStateFromInputs();

				const nextIndex = startIndex + validChars.length;
				const next = this._inputs[nextIndex];

				if (next) {
					this.focusAndSelect(next);
				} else if (validChars.length + startIndex >= this.length) {

					const lastInput = this._inputs[this.length - 1];
					if (lastInput) {
						requestAnimationFrame(() => {
							lastInput.focus();
							lastInput.select();
						});
					}
				}
			});
		},


		handleBackspace(e) {
			const input = e.target;
			const index = parseInt(input.dataset.order);

			input.value = '';


			this.shiftInputsToLeft(index);
			this.$updateStateFromInputs();


			if (index > 0 && input.value === '') {
				const prev = this._inputs[index - 1];
				if (prev) {
					requestAnimationFrame(() => {
						prev.focus();
						prev.select();
					});
				}
			} else {
				requestAnimationFrame(() => {
					input.focus();
					input.select();
				});
			}
		},


		shiftInputsToLeft(fromIndex) {
			for (let i = fromIndex; i < this._inputs.length - 1; i++) {
				const current = this._inputs[i];
				const next = this._inputs[i + 1];

				if (next && next.value) {
					current.value = next.value;
					current.disabled = false;
				} else break;
			}


			for (let i = this._inputs.length - 1; i > fromIndex; i--) {
				if (this._inputs[i].value) {
					this._inputs[i].value = '';
					break;
				}
			}
		},


		enableAndFill(char, index) {
			const input = this._inputs[index];
			if (!input) return;

			input.disabled = false;
			input.value = char;
		},


		focusAndSelect(el) {
			if (!el) return;
			el.disabled = false;
			requestAnimationFrame(() => {
				el.focus();
				el.select();
			});
		},


		$updateStateFromInputs() {
			this._state = this._inputs.map(input => input.value || '').join('');
		},


		clear() {
			this._inputs.forEach(input => {
				input.value = '';
				input.disabled = true;
			});

			if (this._inputs[0]) this._inputs[0].disabled = false;
			this._state = '';

			if (this.autofocus && this._inputs[0]) {
				requestAnimationFrame(() => this._inputs[0].focus());
			}
		},


		focus() {
			const firstEmpty = this._inputs.find(input => !input.value) || this._inputs[0];
			if (firstEmpty) this.focusAndSelect(firstEmpty);
		},


		handleClick(e) {
			const clickedInput = e.target.closest('[data-slot=otp-input]');


			if (clickedInput && !clickedInput.disabled) {
				this.focusAndSelect(clickedInput);
				return;
			}


			const firstEmpty = this._inputs.find(input => !input.value && !input.disabled);

			if (firstEmpty) {
				this.focusAndSelect(firstEmpty);
			} else {

				const lastInput = this._inputs[this.length - 1];
				if (lastInput && !lastInput.disabled) {
					this.focusAndSelect(lastInput);
				}
			}
		}
	}
}"
	{{ $attributes->except('class') }}
	class="contents"
	x-on:otp-clear.window="clear()"
	x-on:otp-focus.window="focus()"
	wire:ignore
>
	<div
		x-ref="inputsWrapper"
		role="group"
		aria-label="One Time Password Input"
		{{ $attributes->class('text-start') }}
	>
		<div
			@class ([
			'flex rounded-box items-center z-isolate',
			'[:where(&>[data-slot=otp-input]:has(+[data-slot=separator]))]:rounded-r-box',
			'[:where(&>[data-slot=separator]+[data-slot=otp-input])]:rounded-l-box',
		])
			x-on:click="handleClick($event)"
		>
			@if ($slot->isNotEmpty())
				{{ $slot }}
			@else
				@foreach (range(1, $length) as $column)
					<x-ui.otp.input />
				@endforeach
			@endif
		</div>
	</div>
</div>
