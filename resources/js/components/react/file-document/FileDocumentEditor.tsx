import React, { useCallback, useRef, useState } from 'react';
import { readFileAsDataUrl, formatBytes } from './utils';

interface FileDocumentEditorProps {
	wireId: string;
	initialUrl?: string;
	maxSizeMB?: number;
	accept?: string;
}

interface FileResult {
	base64: string;
	name: string;
	type: string;
	size: number;
}

function getLivewireComponent(wireId: string) {
	// eslint-disable-next-line @typescript-eslint/no-explicit-any
	const livewire = (window as any).Livewire;
	return livewire?.find(wireId) ?? null;
}

function notify(type: 'success' | 'error' | 'info' | 'warning', content: string) {
	window.dispatchEvent(new CustomEvent('notify', {
		detail: { type, content, duration: 4000 }
	}));
}

export const FileDocumentEditor: React.FC<FileDocumentEditorProps> = ({
	wireId,
	initialUrl,
	maxSizeMB = 5,
	accept = '.pdf,.doc,.docx,.xls,.xlsx,.txt',
}) => {
	const fileInputRef = useRef<HTMLInputElement>(null);
	const [fileResult, setFileResult] = useState<FileResult | null>(null);
	const [tempFile, setTempFile] = useState<{ base64: string; name: string; type: string; size: number } | null>(null);
	const [isModalOpen, setIsModalOpen] = useState(false);

	const handleFileSelected = useCallback(async (e: React.ChangeEvent<HTMLInputElement>) => {
		const file = e.target.files?.[0];
		if (!file) return;

		// Strict document validation (prevent images if uploaded via drag-drop or file picker bypass)
		if (file.type.startsWith('image/')) {
			notify('error', 'El componente de documentos no acepta imágenes. Usa el cargador de imágenes para eso.');
			e.target.value = '';
			return;
		}

		if (file.size > maxSizeMB * 1024 * 1024) {
			notify('error', `El archivo es demasiado grande. Máximo ${maxSizeMB}MB.`);
			e.target.value = '';
			return;
		}

		try {
			const base64 = await readFileAsDataUrl(file);
			setTempFile({
				base64,
				name: file.name,
				type: file.type,
				size: file.size,
			});
			setIsModalOpen(true);
		} catch (error) {
			console.error('Error reading file:', error);
		}
		
		e.target.value = '';
	}, [maxSizeMB]);

	const handleConfirm = useCallback(() => {
		if (!tempFile) return;
		
		setFileResult(tempFile);
		setIsModalOpen(false);

		// Dispatch to Livewire
		const wire = getLivewireComponent(wireId);
		if (wire) {
			wire.fileReady(tempFile.base64, tempFile.name);
		} else {
			document.dispatchEvent(new CustomEvent('file-document-ready', {
				detail: { wireId, base64: tempFile.base64, name: tempFile.name },
				bubbles: true,
			}));
		}
	}, [tempFile, wireId]);

	const currentUrl = fileResult?.base64 || initialUrl;
	const isPdf = tempFile?.type === 'application/pdf' || (currentUrl?.includes('pdf') && !currentUrl.startsWith('data:'));
	const isImage = tempFile?.type.startsWith('image/') || (currentUrl?.startsWith('data:image/'));

	return (
		<div className="flex items-center gap-5 w-full">
			{/* Trigger Area */}
			<div 
				className="relative shrink-0 size-24 sm:size-28 rounded-2xl bg-zinc-50 border-2 border-dashed border-zinc-300 flex items-center justify-center overflow-hidden cursor-pointer hover:border-brand-400 group transition-all shadow-sm"
				onClick={() => fileInputRef.current?.click()}
			>
				{currentUrl ? (
					<div className="w-full h-full flex flex-col items-center justify-center p-2 bg-zinc-100">
						{isImage ? (
							<img src={currentUrl} className="w-full h-full object-cover" alt="Preview" />
						) : (
							<svg className="size-10 text-brand-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
								<path strokeLinecap="round" strokeLinejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
							</svg>
						)}
						<div className="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
							<svg className="size-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
								<path strokeLinecap="round" strokeLinejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
							</svg>
						</div>
					</div>
				) : (
					<svg className="size-8 text-zinc-300 group-hover:text-brand-500 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
						<path strokeLinecap="round" strokeLinejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
					</svg>
				)}
			</div>

			{/* Labels */}
			<div className="flex flex-col gap-1.5">
				<button 
					type="button"
					className="text-sm font-bold text-brand-600 hover:text-brand-700 text-left transition-colors w-fit" 
					onClick={() => fileInputRef.current?.click()}
				>
					{currentUrl ? (fileResult?.name || 'Ver archivo') : 'Subir documento'}
				</button>
				<p className="text-xs font-medium text-zinc-500 leading-relaxed">
					PDF, Imágenes o Office.<br />
					Máximo {maxSizeMB} MB.
				</p>
			</div>

			<input 
				type="file" 
				ref={fileInputRef} 
				className="hidden" 
				accept={accept} 
				onChange={handleFileSelected} 
			/>

			{/* Modal Viewer */}
			{isModalOpen && tempFile && (
				<div className="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6 bg-zinc-900/80 backdrop-blur-sm">
					<div className="bg-white rounded-3xl w-full max-w-4xl h-[80vh] flex flex-col overflow-hidden shadow-2xl animate-in fade-in zoom-in-95 duration-200">
						{/* Header */}
						<div className="flex items-center justify-between px-6 py-4 border-b border-zinc-100 bg-zinc-50/50">
							<div className="flex flex-col">
								<h3 className="text-lg font-black text-zinc-800 tracking-tight leading-none">Previsualizar Documento</h3>
								<p className="text-xs font-bold text-zinc-500 mt-1 uppercase tracking-wider">{tempFile.name} • {formatBytes(tempFile.size)}</p>
							</div>
							<button 
								onClick={() => setIsModalOpen(false)}
								className="relative! inline-flex! items-center! font-medium! justify-center! transition-colors! duration-200! cursor-pointer! rounded-xl! bg-gradient-to-br! from-[var(--color-primary)]! to-[var(--color-primary-stop)]! text-[var(--color-primary-fg)]! border-none! shadow-sm! hover:brightness-110! active:scale-[0.98]! [--color-primary:var(--color-red-500)]! [--color-primary-stop:var(--color-red-700)]! [--color-primary-fg:var(--color-white)]! w-8! h-8! min-w-8! min-h-8! max-w-8! max-h-8! p-0!"
							>
								<svg className="size-4!" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
									<path strokeLinecap="round" strokeLinejoin="round" d="M6 18 18 6M6 6l12 12" />
								</svg>
							</button>
						</div>

						{/* Body (Viewer) */}
						<div className="flex-1 bg-zinc-100 relative overflow-hidden">
							{isPdf ? (
								<iframe 
									src={`${tempFile.base64}#toolbar=0`} 
									className="w-full h-full border-none"
									title="PDF Preview"
								/>
							) : isImage ? (
								<div className="w-full h-full flex items-center justify-center p-8">
									<img src={tempFile.base64} className="max-w-full max-h-full object-contain rounded-lg shadow-md" alt="Preview" />
								</div>
							) : (
								<div className="w-full h-full flex flex-col items-center justify-center gap-4 text-zinc-400">
									<svg className="size-24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1">
										<path strokeLinecap="round" strokeLinejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
									</svg>
									<div className="text-center">
										<p className="text-zinc-600 font-bold italic">No se puede previsualizar este tipo de archivo</p>
										<p className="text-sm mt-1">Pero puedes subirlo haciendo clic en Aplicar.</p>
									</div>
								</div>
							)}
						</div>

						{/* Footer Actions */}
						<div className="p-6 border-t border-zinc-100 flex justify-center gap-3 bg-zinc-50/50">
							<button
								type="button"
								onClick={() => setIsModalOpen(false)}
								className="relative! inline-flex! items-center! font-medium! justify-center! transition-all! duration-200! cursor-pointer! rounded-xl! border-2! border-[var(--color-primary)]! bg-transparent! hover:bg-[var(--color-primary)]/10! text-[var(--color-primary)]! shadow-none! hover:shadow-sm! [--color-primary:var(--color-zinc-600)]! w-8! h-8! min-w-8! min-h-8! max-w-8! max-h-8! p-0!"
								title="Cancelar"
							>
								<svg className="size-4!" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
									<path strokeLinecap="round" strokeLinejoin="round" d="M6 18 18 6M6 6l12 12" />
								</svg>
							</button>
							<button
								type="button"
								onClick={handleConfirm}
								className="relative! inline-flex! items-center! font-medium! justify-center! transition-colors! duration-200! cursor-pointer! rounded-xl! bg-gradient-to-br! from-[var(--color-primary)]! to-[var(--color-primary-stop)]! text-[var(--color-primary-fg)]! border-none! shadow-sm! hover:brightness-110! active:scale-[0.98]! [--color-primary:var(--color-brand-500)]! [--color-primary-stop:var(--color-brand-700)]! [--color-primary-fg:var(--color-white)]! w-8! h-8! min-w-8! min-h-8! max-w-8! max-h-8! p-0!"
								title="Confirmar Subida"
							>
								<svg className="size-4!" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3">
									<path strokeLinecap="round" strokeLinejoin="round" d="m4.5 12.75 6 6 9-13.5" />
								</svg>
							</button>
						</div>
					</div>
				</div>
			)}
		</div>
	);
};
