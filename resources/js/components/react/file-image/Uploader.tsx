import React, { useCallback, useRef } from 'react';
import { readFileAsDataUrl } from './utils';

interface UploaderProps {
	onFileSelected: (dataUrl: string, file: File) => void;
}

export const Uploader: React.FC<UploaderProps> = ({ onFileSelected }) => {
	const inputRef = useRef<HTMLInputElement>(null);
	const [dragging, setDragging] = React.useState(false);
	const [error, setError] = React.useState<string | null>(null);

	const ACCEPTED = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

	const handleFile = useCallback(
		async (file: File) => {
			setError(null);
			if (!ACCEPTED.includes(file.type)) {
				setError('Formato no soportado. Usa JPG, PNG, WebP o GIF.');
				return;
			}
			if (file.size > 15 * 1024 * 1024) {
				setError('El archivo es demasiado grande (máx. 15 MB).');
				return;
			}
			const dataUrl = await readFileAsDataUrl(file);
			onFileSelected(dataUrl, file);
		},
		[onFileSelected],
	);

	const onDrop = useCallback(
		(e: React.DragEvent) => {
			e.preventDefault();
			setDragging(false);
			const file = e.dataTransfer.files[0];
			if (file) handleFile(file);
		},
		[handleFile],
	);

	const onInputChange = useCallback(
		(e: React.ChangeEvent<HTMLInputElement>) => {
			const file = e.target.files?.[0];
			if (file) handleFile(file);
		},
		[handleFile],
	);

	return (
		<div
			className={[
				'relative flex flex-col items-center justify-center',
				'min-h-[320px] w-full rounded-2xl border-2 border-dashed',
				'transition-all duration-200 cursor-pointer select-none',
				dragging
					? 'border-brand-500 bg-brand-50/60 scale-[1.01]'
					: 'border-zinc-200 bg-zinc-50/50 hover:border-brand-400 hover:bg-brand-50/30',
			].join(' ')}
			onClick={() => inputRef.current?.click()}
			onDragOver={(e) => { e.preventDefault(); setDragging(true); }}
			onDragLeave={() => setDragging(false)}
			onDrop={onDrop}
		>
			{}
			<div className={[
				'mb-5 flex size-16 items-center justify-center rounded-2xl shadow-lg transition-all duration-200',
				dragging ? 'bg-brand-500 shadow-brand-500/30' : 'bg-white shadow-zinc-200/80',
			].join(' ')}>
				<svg
					className={['size-8 transition-colors', dragging ? 'text-white' : 'text-brand-500'].join(' ')}
					xmlns="http://www.w3.org/2000/svg"
					viewBox="0 0 24 24"
					fill="currentColor"
				>
					<path fillRule="evenodd" d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z" clipRule="evenodd" />
				</svg>
			</div>

			<p className="text-sm font-bold text-zinc-700 mb-1">
				{dragging ? 'Suelta para subir' : 'Arrastra una imagen aquí'}
			</p>
			<p className="text-xs text-zinc-400 font-medium mb-4">
				o haz clic para seleccionar · JPG, PNG, WebP, GIF · máx. 15 MB
			</p>

			{error && (
				<p className="absolute bottom-4 text-xs font-semibold text-red-500 bg-red-50 px-3 py-1 rounded-full border border-red-200">
					{error}
				</p>
			)}

			<input
				ref={inputRef}
				type="file"
				accept="image/*"
				className="sr-only"
				onChange={onInputChange}
				onClick={(e) => e.stopPropagation()}
			/>
		</div>
	);
};
