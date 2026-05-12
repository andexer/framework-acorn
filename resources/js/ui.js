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
	registerToastStore();

	window.toastItem = toastItem;

	registerModalMagic(defineReactiveMagicProperty);
	Alpine.data('modalComponent', modalComponent);
	Alpine.data('modalGrabHandleComponent', modalGrabHandleComponent);
	Alpine.data('autocompleteComponent', autocompleteComponent);
	Alpine.data('comboboxComponent', comboboxComponent);
	Alpine.data('selectComponent', selectComponent);
	Alpine.data('sliderComponent', sliderComponent);
	Alpine.data('CreateNewOptionActivator', ComboboxActivator);
});
