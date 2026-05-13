import React from 'react';
import { formatBytes } from './utils';
import type { ImageResult } from './types';

interface PreviewProps {
	result: ImageResult;
	compressing: boolean;
	onConfirm: () => void;
	onRedo: () => void;
}

export const Preview: React.FC<PreviewProps> = ({ result, compressing, onConfirm, onRedo }) => {
	return (
		<div className="flex flex-col gap-5 w-full">
			{/* Image preview */}
			<div className="relative w-full rounded-2xl overflow-hidden bg-zinc-100 border border-zinc-200 flex items-center justify-center"
				style={{ minHeight: '260px' }}
			>
				{compressing ? (
					<div className="absolute inset-0 flex flex-col items-center justify-center bg-white/80 backdrop-blur-sm z-10 gap-3">
						<div className="size-10 rounded-full border-4 border-brand-500 border-t-transparent animate-spin" />
						<p className="text-xs font-semibold text-zinc-500">Comprimiendo…</p>
					</div>
				) : null}
				<img
					src={result.base64}
					alt="Preview"
					className="max-h-[380px] max-w-full object-contain"
				/>
			</div>

			{/* Metadata badges */}
			<div className="flex flex-wrap items-center gap-2">
				<span className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-zinc-100 text-xs font-semibold text-zinc-600 border border-zinc-200">
					<svg className="size-3.5 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
						<rect x="3" y="3" width="18" height="18" rx="2" />
						<path d="M3 9h18M9 21V9" />
					</svg>
					{result.width} × {result.height} px
				</span>
				<span className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-50 text-xs font-semibold text-brand-700 border border-brand-100">
					<svg className="size-3.5 text-brand-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
						<path strokeLinecap="round" strokeLinejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
					</svg>
					{formatBytes(result.sizeKb * 1024)}
				</span>
			</div>

			{/* Action buttons */}
			<div className="flex gap-2">
				<button
					type="button"
					onClick={onRedo}
					disabled={compressing}
					className="flex-1 h-9 rounded-xl border-2 border-zinc-200 bg-white text-sm font-semibold text-zinc-600 hover:border-zinc-300 transition-colors disabled:opacity-50"
				>
					← Volver a editar
				</button>
				<button
					type="button"
					onClick={onConfirm}
					disabled={compressing}
					className="flex-[2] h-9 rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 text-sm font-bold text-white shadow-sm hover:brightness-110 active:scale-[0.98] transition-all disabled:opacity-60 disabled:cursor-not-allowed"
				>
					{compressing ? 'Procesando…' : '✓ Usar esta imagen'}
				</button>
			</div>
		</div>
	);
};
