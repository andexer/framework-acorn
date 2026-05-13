import React, { useCallback, useRef, useState } from 'react';
import imageCompression from 'browser-image-compression';
import type { Area } from 'react-easy-crop';
import { CropEditor } from './CropEditor';
import { getCroppedImg, readFileAsDataUrl } from './utils';
import type { CroppedAreaPixels, FileImageEditorProps, ImageResult } from './types';

function getLivewireComponent(wireId: string) {
	const livewire = (window as unknown as { Livewire?: { find: (id: string) => { imageReady: (base64: string) => void } | null } }).Livewire;
	return livewire?.find(wireId) ?? null;
}

function notify(type: 'success' | 'error' | 'info' | 'warning', content: string) {
	window.dispatchEvent(new CustomEvent('notify', {
		detail: { type, content, duration: 4000 }
	}));
}

export const FileImageEditor: React.FC<FileImageEditorProps> = ({
	wireId,
	initialUrl,
	aspectRatio,
	maxSizeMB = 1,
	quality = 0.92,
}) => {
	const fileInputRef = useRef<HTMLInputElement>(null);
	const [imageSrc, setImageSrc] = useState<string | null>(null);
	const [croppedAreaPixels, setCroppedAreaPixels] = useState<CroppedAreaPixels | null>(null);
	const [result, setResult] = useState<ImageResult | null>(null);
	const [compressing, setCompressing] = useState(false);
	const [isModalOpen, setIsModalOpen] = useState(false);

	const handleFileSelected = useCallback(async (e: React.ChangeEvent<HTMLInputElement>) => {
		const file = e.target.files?.[0];
		if (!file) return;
		
		if (file.size > maxSizeMB * 1024 * 1024) {
			notify('error', `La imagen es demasiado pesada. El límite es de ${maxSizeMB}MB.`);
			e.target.value = '';
			return;
		}

		if (!file.type.startsWith('image/')) {
			notify('error', 'El componente de imágenes solo acepta archivos de imagen. Usa el cargador de documentos para otros archivos.');
			e.target.value = '';
			return;
		}

		if (!['image/jpeg', 'image/png', 'image/webp', 'image/gif'].includes(file.type)) {
			notify('error', 'Formato de imagen no soportado. Usa JPG, PNG, WebP o GIF.');
			e.target.value = '';
			return;
		}

		try {
			const dataUrl = await readFileAsDataUrl(file);
			setImageSrc(dataUrl);
			setIsModalOpen(true);
		} catch (error) {
			console.error('Error reading file:', error);
		}
	}, []);

	const handleCropComplete = useCallback((_croppedArea: Area, pixels: CroppedAreaPixels) => {
		setCroppedAreaPixels(pixels);
	}, []);

	const handleCropConfirm = useCallback(async () => {
		if (!imageSrc || !croppedAreaPixels) return;
		setCompressing(true);
		try {
			const { blob, width, height } = await getCroppedImg(imageSrc, croppedAreaPixels, 0, undefined, 'image/jpeg', quality);

			const compressedFile = await imageCompression(
				new File([blob], 'image.jpg', { type: 'image/jpeg' }),
				{ maxSizeMB, useWebWorker: true, fileType: 'image/jpeg', initialQuality: quality },
			);
			const compressedBase64 = await imageCompression.getDataUrlFromFile(compressedFile);

			const newResult = {
				base64: compressedBase64,
				blob: compressedFile,
				width,
				height,
				sizeKb: Math.round(compressedFile.size / 1024),
			};
			setResult(newResult);
			setIsModalOpen(false);

			
			const wire = getLivewireComponent(wireId);
			if (wire) {
				wire.imageReady(newResult.base64);
			} else {
				document.dispatchEvent(new CustomEvent('file-image-ready', {
					detail: { wireId, base64: newResult.base64 },
					bubbles: true,
				}));
			}
		} catch (e) {
			console.error('[file-image] crop/compress error:', e);
		} finally {
			setCompressing(false);
		}
	}, [imageSrc, croppedAreaPixels, maxSizeMB, quality, wireId]);

	const currentDisplayUrl = result?.base64 || initialUrl;

	return (
		<div className="flex items-center gap-5 w-full">
			{}
			<div 
				className="relative shrink-0 size-24 sm:size-28 rounded-2xl bg-zinc-50 border-2 border-dashed border-zinc-300 flex items-center justify-center overflow-hidden cursor-pointer hover:border-brand-400 group transition-colors shadow-sm"
				onClick={() => fileInputRef.current?.click()}
			>
				{currentDisplayUrl ? (
					<>
						<img src={currentDisplayUrl} className="w-full h-full object-cover" alt="Preview" />
						<div className="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
							<svg className="size-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
								<path strokeLinecap="round" strokeLinejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
							</svg>
						</div>
					</>
				) : (
					<svg className="size-8 text-zinc-300 group-hover:text-brand-500 transition-colors" viewBox="0 0 24 24" fill="currentColor">
						<path fillRule="evenodd" d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z" clipRule="evenodd" />
					</svg>
				)}
			</div>

			<div className="flex flex-col gap-1.5">
				<button 
					type="button"
					className="text-sm font-bold text-brand-600 hover:text-brand-700 text-left transition-colors w-fit" 
					onClick={() => fileInputRef.current?.click()}
				>
					{currentDisplayUrl ? 'Cambiar imagen' : 'Subir imagen'}
				</button>
				<p className="text-xs font-medium text-zinc-500">
					Formatos: JPG, PNG o WebP.<br />
					Tamaño máximo: {maxSizeMB} MB.
				</p>
			</div>
			<input type="file" ref={fileInputRef} className="hidden" accept="image/jpeg,image/png,image/webp,image/gif" onChange={handleFileSelected} />

			{}
			{isModalOpen && imageSrc && (
				<div className="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6 bg-zinc-900/80 backdrop-blur-sm">
					<div className="bg-white rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl animate-in fade-in zoom-in-95 duration-200">
						{}
						<div className="flex items-center justify-between px-6 py-4 border-b border-zinc-100 bg-zinc-50/50">
							<h3 className="text-lg font-black text-zinc-800 tracking-tight">Editor de Imagen</h3>
							<button 
								onClick={() => setIsModalOpen(false)}
								className="relative! inline-flex! items-center! font-medium! justify-center! transition-colors! duration-200! cursor-pointer! rounded-xl! bg-gradient-to-br! from-[var(--color-primary)]! to-[var(--color-primary-stop)]! text-[var(--color-primary-fg)]! border-none! shadow-sm! hover:brightness-110! active:scale-[0.98]! [--color-primary:var(--color-red-500)]! [--color-primary-stop:var(--color-red-700)]! [--color-primary-fg:var(--color-white)]! w-8! h-8! min-w-8! min-h-8! max-w-8! max-h-8! p-0!"
							>
								<svg className="size-4!" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
									<path strokeLinecap="round" strokeLinejoin="round" d="M6 18 18 6M6 6l12 12" />
								</svg>
							</button>
						</div>

						{}
						<div className="p-6">
							<CropEditor
								imageSrc={imageSrc}
								aspectRatio={aspectRatio}
								onCropComplete={handleCropComplete}
								onCancel={() => setIsModalOpen(false)}
								onConfirm={handleCropConfirm}
							/>
						</div>

						{}
						{compressing && (
							<div className="absolute inset-0 bg-white/80 backdrop-blur-sm flex flex-col items-center justify-center z-50">
								<div className="size-12 rounded-full border-4 border-brand-500 border-t-transparent animate-spin mb-4" />
								<p className="text-sm font-bold text-zinc-800">Optimizando imagen...</p>
							</div>
						)}
					</div>
				</div>
			)}
		</div>
	);
};
