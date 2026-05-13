import React from 'react';
import { createRoot } from 'react-dom/client';
import { FileImageEditor } from './file-image/FileImageEditor';

type MountState = {
	container: HTMLElement;
	root: ReturnType<typeof createRoot>;
	wireId: string;
};

const mounts = new Map<string, MountState>();

function parseNumber(value: string | undefined, fallback: number | undefined): number | undefined {
	if (value === undefined || value === '') return fallback;
	const parsed = Number(value);
	return Number.isFinite(parsed) ? parsed : fallback;
}

function getWireId(container: HTMLElement): string {
	const fromData = container.dataset.wireId ?? '';
	if (fromData !== '') return fromData;
	const root = container.closest<HTMLElement>('[wire\\:id]');
	return root?.getAttribute('wire:id') ?? '';
}

function mountEditor(container: HTMLElement) {
	const wireId = getWireId(container);
	const key = wireId || container.id || Math.random().toString(36).slice(2);

	if (mounts.has(key)) {
		const state = mounts.get(key)!;
		if (state.container === container) return;
		state.root.unmount();
	}

	const root = createRoot(container);
	const state: MountState = { container, root, wireId };
	mounts.set(key, state);

	root.render(
		<React.StrictMode>
			<FileImageEditor
				wireId={wireId}
				initialUrl={container.dataset.initialUrl || undefined}
				aspectRatio={parseNumber(container.dataset.aspectRatio, undefined)}
				maxSizeMB={parseNumber(container.dataset.maxSizeMb, 1)}
				quality={parseNumber(container.dataset.quality, 0.92)}
			/>
		</React.StrictMode>,
	);
}

function mountAll() {
	document.querySelectorAll<HTMLElement>('[data-file-image-root="1"]').forEach(mountEditor);
}


if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', mountAll);
} else {
	mountAll();
}

window.addEventListener('livewire:navigated', mountAll);

document.addEventListener('livewire:init', () => {
	if (typeof window.Livewire !== 'undefined') {
		(window as any).Livewire.hook('commit', ({ component }: { component: { el: HTMLElement } }) => {
			const islands = component.el?.querySelectorAll<HTMLElement>('[data-file-image-root="1"]');
			islands?.forEach((el) => {
				const key = getWireId(el) || el.id;
				if (!mounts.has(key)) mountEditor(el);
			});
		});
	}
});
