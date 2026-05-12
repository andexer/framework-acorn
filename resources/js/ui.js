import modalComponent, { modalGrabHandleComponent, registerModalMagic } from './components/modal.js';
import autocompleteComponent from './components/autocomplete.js';
import comboboxComponent, { CreateNewOptionActivator as ComboboxActivator } from './components/combobox.js';
import selectComponent, { CreateNewOptionActivator as SelectActivator } from './components/select.js';
import sliderComponent from './components/slider.js';
import { registerToastStore, toastItem } from './components/toast.js';

export function defineReactiveMagicProperty(name, rawObject) {
    const instance = Alpine.reactive(rawObject);
    if (typeof instance.init === 'function') {
        instance.init();
    }
    Alpine.magic(name, () => instance);
    window[name[0].toUpperCase() + name.slice(1)] = instance;
}

document.addEventListener('livewire:init', () => {
	console.log('Framework: initializing UI components...');

	if (typeof Alpine === 'undefined') {
		console.error('Framework Error: Alpine is not defined during livewire:init');
		return;
	}

	try {
		console.log('Registering Toast store...');
		registerToastStore();
		window.toastItem = toastItem;
	} catch (e) {
		console.error('Failed to register Toast:', e);
	}

	try {
		console.log('Registering Modal magic...');
		registerModalMagic(defineReactiveMagicProperty);
		Alpine.data('modalComponent', modalComponent);
		Alpine.data('modalGrabHandleComponent', modalGrabHandleComponent);
	} catch (e) {
		console.error('Failed to register Modal:', e);
	}

	try {
		console.log('Registering Autocomplete...');
		Alpine.data('autocompleteComponent', autocompleteComponent);
	} catch (e) {
		console.error('Failed to register Autocomplete:', e);
	}

	try {
		console.log('Registering Combobox...');
		Alpine.data('comboboxComponent', comboboxComponent);
		Alpine.data('CreateNewOptionActivator', ComboboxActivator);
	} catch (e) {
		console.error('Failed to register Combobox:', e);
	}

	try {
		console.log('Registering Select...');
		Alpine.data('selectComponent', selectComponent);
	} catch (e) {
		console.error('Failed to register Select:', e);
	}

	try {
		console.log('Registering Slider...');
		Alpine.data('sliderComponent', sliderComponent);
	} catch (e) {
		console.error('Failed to register Slider:', e);
	}

	console.log('Framework UI initialization complete.');
});
