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
	// From data attribute or walk up to the Livewire root
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
		// If it's the exact same DOM node, do nothing
		if (state.container === container) return;
		
		// The DOM node was replaced (e.g. Livewire morphing). Unmount the old one.
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

// Initial mount
if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', mountAll);
} else {
	mountAll();
}

// Re-mount after Livewire navigation / morphing
window.addEventListener('livewire:navigated', mountAll);

// Support Livewire morph: after a component re-renders the island may be
// re-created, so we listen for Livewire's commit hook.
document.addEventListener('livewire:init', () => {
	// @ts-expect-error — Livewire global hook
	if (typeof window.Livewire !== 'undefined') {
		// eslint-disable-next-line @typescript-eslint/no-explicit-any
		(window as any).Livewire.hook('commit', ({ component }: { component: { el: HTMLElement } }) => {
			const islands = component.el?.querySelectorAll<HTMLElement>('[data-file-image-root="1"]');
			islands?.forEach((el) => {
				const key = getWireId(el) || el.id;
				if (!mounts.has(key)) mountEditor(el);
			});
		});
	}
});
