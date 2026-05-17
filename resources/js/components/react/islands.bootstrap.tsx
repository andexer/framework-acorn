/**
 * islands.bootstrap.tsx
 *
 * Punto de entrada único para todos los React Islands del framework.
 * Reemplaza los archivos .bootstrap.tsx individuales de cada componente.
 */
import React from 'react';
import { createRoot } from 'react-dom/client';
import 'leaflet/dist/leaflet.css';

// ─── Componentes ─────────────────────────────────────────────────────────────
import { MapComponent }          from './map/MapComponent';
import { getCountry, DEFAULT_COUNTRY } from './map/countries';
import { MapaDireccionesPicker } from './map-direcciones/MapaDireccionesPicker';
import { FileImageEditor }       from './file-image/FileImageEditor';
import { FileDocumentEditor }    from './file-document/FileDocumentEditor';

// ─── Helpers comunes ──────────────────────────────────────────────────────────
function parseNumber(value: string | undefined, fallback: number): number {
	if (!value || value === 'NaN' || value === 'null' || value === 'undefined') return fallback;
	const parsed = Number(value);
	return Number.isFinite(parsed) && parsed !== 0 ? parsed : fallback;
}

function parseOptionalNumber(value: string | undefined, fallback: number | undefined): number | undefined {
	if (value === undefined || value === '') return fallback;
	const parsed = Number(value);
	return Number.isFinite(parsed) ? parsed : fallback;
}

// ─────────────────────────────────────────────────────────────────────────────
// ISLAND: Mapa de Direcciones
// ─────────────────────────────────────────────────────────────────────────────
type MapaMountState = {
	container: HTMLElement;
	root: ReturnType<typeof createRoot>;
	lat: number;
	lng: number;
	height: number;
};

const mapaMounts = new Map<string, MapaMountState>();

function getWireComponentId(container: HTMLElement): string {
	const fromData = container.dataset.livewireId ?? '';
	if (fromData !== '') return fromData;
	const root = container.closest<HTMLElement>('[wire\\:id]');
	return root?.getAttribute('wire:id') ?? '';
}

function getWireComponent(id: string) {
	const livewire = window.Livewire;
	if (!livewire) return null;
	return livewire.find(id);
}

function renderMapaMount(state: MapaMountState, componentId: string) {
	state.root.render(
		<React.StrictMode>
			<MapaDireccionesPicker
				lat={state.lat}
				lng={state.lng}
				height={state.height}
				onPick={(lat, lng) => {
					state.lat = lat;
					state.lng = lng;
					renderMapaMount(state, componentId);
					getWireComponent(componentId)?.setCoordinates(lat, lng);
				}}
				onOutside={(lat, lng) => {
					getWireComponent(componentId)?.setCoordinates(lat, lng);
				}}
			/>
		</React.StrictMode>,
	);
}

function mountMapaPicker(container: HTMLElement) {
	const componentId = getWireComponentId(container);
	if (componentId === '') return;

	if (mapaMounts.has(componentId)) {
		const state = mapaMounts.get(componentId)!;
		if (state.container === container) return;
		try {
			state.root.unmount();
		} catch (e) {
			// Silence unmount errors
		}
	}

	const state: MapaMountState = {
		container,
		root: createRoot(container),
		lat: parseNumber(container.dataset.lat, 10.480600),
		lng: parseNumber(container.dataset.lng, -66.903600),
		height: parseNumber(container.dataset.height, 460),
	};

	mapaMounts.set(componentId, state);
	renderMapaMount(state, componentId);
}

function mountAllMapas() {
	document.querySelectorAll<HTMLElement>('[data-mapa-direcciones-root="1"]').forEach(mountMapaPicker);
}

function syncMapaFromLivewire(event: Event) {
	const e = event as CustomEvent<{ componentId?: string; lat?: number; lng?: number }>;
	const componentId = e.detail?.componentId ?? '';
	if (componentId === '') return;

	const state = mapaMounts.get(componentId);
	if (!state) {
		let container = document.querySelector<HTMLElement>(`[data-livewire-id="${componentId}"]`);
		if (!container) {
			container = document.querySelector<HTMLElement>(`[wire\\:id="${componentId}"] [data-mapa-direcciones-root="1"]`);
		}
		if (container) mountMapaPicker(container);
		return;
	}

	const lat = Number(e.detail?.lat);
	const lng = Number(e.detail?.lng);
	if (!Number.isFinite(lat) || !Number.isFinite(lng)) return;

	state.lat = lat;
	state.lng = lng;
	renderMapaMount(state, componentId);
}

window.addEventListener('framework-mapa-direcciones-sync', syncMapaFromLivewire);

// ─────────────────────────────────────────────────────────────────────────────
// ISLAND: File Image Editor
// ─────────────────────────────────────────────────────────────────────────────
type ImageMountState = {
	container: HTMLElement;
	root: ReturnType<typeof createRoot>;
	wireId: string;
};

const imageMounts = new Map<string, ImageMountState>();

function getImageWireId(container: HTMLElement): string {
	const fromData = container.dataset.wireId ?? '';
	if (fromData !== '') return fromData;
	const root = container.closest<HTMLElement>('[wire\\:id]');
	return root?.getAttribute('wire:id') ?? '';
}

function mountImageEditor(container: HTMLElement) {
	const wireId = getImageWireId(container);
	const key = wireId || container.id || Math.random().toString(36).slice(2);

	if (imageMounts.has(key)) {
		const state = imageMounts.get(key)!;
		if (state.container === container) return;
		state.root.unmount();
	}

	const root = createRoot(container);
	imageMounts.set(key, { container, root, wireId });

	root.render(
		<React.StrictMode>
			<FileImageEditor
				wireId={wireId}
				initialUrl={container.dataset.initialUrl || undefined}
				aspectRatio={parseOptionalNumber(container.dataset.aspectRatio, undefined)}
				maxSizeMB={parseNumber(container.dataset.maxSizeMb, 1)}
				quality={parseNumber(container.dataset.quality, 0.92)}
				accept={container.dataset.accept}
				variant={container.dataset.variant as any}
				cropShape={container.dataset.cropShape as any}
			/>
		</React.StrictMode>,
	);
}

function mountAllImageEditors() {
	document.querySelectorAll<HTMLElement>('[data-file-image-root="1"]').forEach(mountImageEditor);
}

// ─────────────────────────────────────────────────────────────────────────────
// ISLAND: File Document Editor
// ─────────────────────────────────────────────────────────────────────────────
type DocumentMountState = {
	container: HTMLElement;
	root: ReturnType<typeof createRoot>;
	wireId: string;
};

const documentMounts = new Map<string, DocumentMountState>();

function getDocumentWireId(container: HTMLElement): string {
	const fromData = container.dataset.wireId ?? '';
	if (fromData !== '') return fromData;
	const root = container.closest<HTMLElement>('[wire\\:id]');
	return root?.getAttribute('wire:id') ?? '';
}

function mountDocumentEditor(container: HTMLElement) {
	const wireId = getDocumentWireId(container);
	const key = wireId || container.id || Math.random().toString(36).slice(2);

	if (documentMounts.has(key)) {
		const state = documentMounts.get(key)!;
		if (state.container === container) return;
		state.root.unmount();
	}

	const root = createRoot(container);
	documentMounts.set(key, { container, root, wireId });

	root.render(
		<React.StrictMode>
			<FileDocumentEditor
				wireId={wireId}
				initialUrl={container.dataset.initialUrl || undefined}
				maxSizeMB={parseNumber(container.dataset.maxSizeMb, 5)}
				accept={container.dataset.accept || '.pdf'}
			/>
		</React.StrictMode>,
	);
}

function mountAllDocumentEditors() {
	document.querySelectorAll<HTMLElement>('[data-file-document-root="1"]').forEach(mountDocumentEditor);
}

// ─────────────────────────────────────────────────────────────────────────────
// ISLAND: Generic Map Shortcode ([framework_map])
// ─────────────────────────────────────────────────────────────────────────────
function mountAllMapShortcodes() {
	document.querySelectorAll<HTMLDivElement>('[id^="map-root"]').forEach((container) => {
		if ((container as any).__mapMounted) return;
		(container as any).__mapMounted = true;

		const code    = container.dataset.country ?? DEFAULT_COUNTRY;
		const height  = container.dataset.height ? parseInt(container.dataset.height, 10) : 480;
		const country = { ...getCountry(code), code };

		const root = createRoot(container);
		root.render(
			<React.StrictMode>
				<MapComponent country={country} height={height} />
			</React.StrictMode>,
		);
	});
}

// ─────────────────────────────────────────────────────────────────────────────
// INICIALIZACIÓN GLOBAL
// ─────────────────────────────────────────────────────────────────────────────
function mountAll() {
	mountAllMapShortcodes();
	mountAllMapas();
	mountAllImageEditors();
	mountAllDocumentEditors();
}

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', mountAll);
} else {
	mountAll();
}

// Re-montar tras navegación Livewire
window.addEventListener('livewire:navigated', mountAll);

// Sincronizar montajes tras commits de Livewire (para evitar que wire:ignore rompa islands)
document.addEventListener('livewire:init', () => {
	if (typeof window.Livewire === 'undefined') return;

	(window as any).Livewire.hook('commit', ({ component }: { component: { el: HTMLElement } }) => {
		const el = component.el;

		el?.querySelectorAll<HTMLElement>('[data-mapa-direcciones-root="1"]').forEach((c) => {
			if (!mapaMounts.has(getWireComponentId(c))) mountMapaPicker(c);
		});

		el?.querySelectorAll<HTMLElement>('[data-file-image-root="1"]').forEach((c) => {
			const key = getImageWireId(c) || c.id;
			if (!imageMounts.has(key)) mountImageEditor(c);
		});

		el?.querySelectorAll<HTMLElement>('[data-file-document-root="1"]').forEach((c) => {
			const key = getDocumentWireId(c) || c.id;
			if (!documentMounts.has(key)) mountDocumentEditor(c);
		});
	});
});
