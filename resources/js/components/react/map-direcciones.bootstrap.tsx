import React from 'react';
import { createRoot } from 'react-dom/client';
import 'leaflet/dist/leaflet.css';
import { MapaDireccionesPicker } from './map-direcciones/MapaDireccionesPicker';

type MountState = {
	container: HTMLElement;
	root: ReturnType<typeof createRoot>;
	lat: number;
	lng: number;
	height: number;
};

const mounts = new Map<string, MountState>();

function parseNumber(value: string | undefined, fallback: number): number {
	const parsed = Number(value);
	return Number.isFinite(parsed) ? parsed : fallback;
}

type LivewireWireComponent = {
	setCoordinates: (lat: number, lng: number) => unknown;
};

function getWireComponent(id: string) {
	const livewire = (window as unknown as { Livewire?: { find: (componentId: string) => LivewireWireComponent | null } }).Livewire;
	if (!livewire) {
		return null;
	}
	return livewire.find(id);
}

function getComponentId(container: HTMLElement): string {
	const fromData = container.dataset.livewireId ?? '';
	if (fromData !== '') {
		return fromData;
	}

	const root = container.closest<HTMLElement>('[wire\\:id]');
	return root?.getAttribute('wire:id') ?? '';
}

function renderMount(state: MountState, componentId: string) {
	state.root.render(
		<React.StrictMode>
			<MapaDireccionesPicker
				lat={state.lat}
				lng={state.lng}
				height={state.height}
				onPick={(lat, lng) => {
					state.lat = lat;
					state.lng = lng;
					renderMount(state, componentId);
					getWireComponent(componentId)?.setCoordinates(lat, lng);
				}}
				onOutside={(lat, lng) => {
					getWireComponent(componentId)?.setCoordinates(lat, lng);
				}}
			/>
		</React.StrictMode>,
	);
}

function mountPicker(container: HTMLElement) {
	const componentId = getComponentId(container);
	if (componentId === '' || mounts.has(componentId)) {
		return;
	}

	const state: MountState = {
		container,
		root: createRoot(container),
		lat: parseNumber(container.dataset.lat, 8.5),
		lng: parseNumber(container.dataset.lng, -66.5),
		height: parseNumber(container.dataset.height, 460),
	};

	mounts.set(componentId, state);
	renderMount(state, componentId);
}

function mountAll() {
	document.querySelectorAll<HTMLElement>('[data-mapa-direcciones-root="1"]').forEach((container) => {
		mountPicker(container);
	});
}

function syncFromLivewire(event: Event) {
	const customEvent = event as CustomEvent<{ componentId?: string; lat?: number; lng?: number }>;
	const componentId = customEvent.detail?.componentId ?? '';
	if (componentId === '') {
		return;
	}

	const state = mounts.get(componentId);
	if (!state) {
		let container = document.querySelector<HTMLElement>(`[data-livewire-id="${componentId}"]`);
		if (!container) {
			container = document.querySelector<HTMLElement>(`[wire\\:id="${componentId}"] [data-mapa-direcciones-root="1"]`);
		}
		if (container) {
			mountPicker(container);
		}
		return;
	}

	const lat = Number(customEvent.detail?.lat);
	const lng = Number(customEvent.detail?.lng);
	if (!Number.isFinite(lat) || !Number.isFinite(lng)) {
		return;
	}

	state.lat = lat;
	state.lng = lng;
	renderMount(state, componentId);
}

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', mountAll);
} else {
	mountAll();
}

window.addEventListener('framework-mapa-direcciones-sync', syncFromLivewire);
