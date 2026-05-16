import React, { useCallback, useState } from 'react';
import CropperModule from 'react-easy-crop';
import type { Area } from 'react-easy-crop';


const Cropper = CropperModule as any;
import type { CroppedAreaPixels } from './types';

interface AspectRatioOption {
	label: string;
	value: number | null;
}

interface CropEditorProps {
	imageSrc: string;
	aspectRatio?: number;
	availableRatios?: AspectRatioOption[];
	cropShape?: 'rect' | 'round';
	onCropComplete: (croppedArea: Area, croppedAreaPixels: CroppedAreaPixels) => void;
	onCancel: () => void;
	onConfirm: () => void;
}

export const CropEditor: React.FC<CropEditorProps> = ({
	imageSrc,
	aspectRatio: initialAspectRatio = 1,
	cropShape = 'rect',
	availableRatios = [
		{ label: '1:1', value: 1 },
		{ label: '4:3', value: 4 / 3 },
		{ label: '16:9', value: 16 / 9 },
		{ label: 'Libre', value: null },
	],
	onCropComplete,
	onCancel,
	onConfirm,
}) => {
	const [crop, setCrop] = useState({ x: 0, y: 0 });
	const [zoom, setZoom] = useState(1);
	const [rotation, setRotation] = useState(0);
	const [aspect, setAspect] = useState<number | null>(initialAspectRatio);

	const handleCropComplete = useCallback(
		(croppedArea: Area, croppedAreaPixels: Area) => {
			onCropComplete(croppedArea, croppedAreaPixels as CroppedAreaPixels);
		},
		[onCropComplete],
	);

	return (
		<div className="flex flex-col gap-0 w-full">
			{}
			<div className="flex items-center justify-center gap-1 pb-3">
				{availableRatios.map((ratio) => (
					<button
						key={ratio.label}
						type="button"
						onClick={() => setAspect(ratio.value)}
						className={`inline-flex! items-center! justify-center! text-[11px] uppercase tracking-wider font-black rounded-lg transition-all duration-200 border-none! cursor-pointer! w-16! h-7! min-w-16! min-h-7! max-w-16! max-h-7! p-0! ${
							aspect === ratio.value
								? 'bg-gradient-to-br! from-brand-500! to-brand-600! text-white! shadow-md! shadow-brand-200! scale-105!'
								: 'bg-zinc-100! text-zinc-500! hover:bg-zinc-200! hover:text-zinc-700!'
						}`}
					>
						{ratio.label}
					</button>
				))}
			</div>

			{}
			<div className="relative w-full rounded-xl overflow-hidden bg-zinc-900" style={{ height: '340px' }}>
				<Cropper
					image={imageSrc}
					crop={crop}
					zoom={zoom}
					rotation={rotation}
					aspect={aspect ?? undefined}
					cropShape={cropShape}
					onCropChange={setCrop}
					onZoomChange={setZoom}
					onCropComplete={handleCropComplete}
					showGrid
					style={{
						containerStyle: { borderRadius: '12px' },
					}}
				/>
			</div>

			{}
			<div className="flex flex-col gap-3 pt-5 pb-1">
				{}
				<div className="flex items-center gap-3">
					<span className="w-16 shrink-0 text-xs font-semibold text-zinc-500 text-right">Zoom</span>
					<input
						type="range"
						min={1}
						max={3}
						step={0.01}
						value={zoom}
						onChange={(e) => setZoom(Number(e.target.value))}
						className="flex-1 accent-brand-500 h-1.5 rounded-full cursor-pointer"
					/>
					<span className="w-10 text-xs font-mono text-zinc-400">{zoom.toFixed(1)}×</span>
				</div>

				{}
				<div className="flex items-center gap-3">
					<span className="w-16 shrink-0 text-xs font-semibold text-zinc-500 text-right">Rotación</span>
					<input
						type="range"
						min={-180}
						max={180}
						step={1}
						value={rotation}
						onChange={(e) => setRotation(Number(e.target.value))}
						className="flex-1 accent-brand-500 h-1.5 rounded-full cursor-pointer"
					/>
					<span className="w-10 text-xs font-mono text-zinc-400">{rotation}°</span>
				</div>

				{}
				<div className="flex items-center gap-2 justify-center pt-1">
					<button
						type="button"
						onClick={() => setRotation((r) => Math.max(-180, r - 90))}
						className="relative! inline-flex! items-center! font-medium! justify-center! transition-all! duration-200! cursor-pointer! rounded-xl! border-2! border-zinc-200! bg-white! hover:bg-zinc-50! text-zinc-500! hover:text-brand-500! hover:border-brand-200! shadow-sm! w-8! h-8! min-w-8! min-h-8! max-w-8! max-h-8! p-0!"
						title="-90°"
					>
						<svg className="size-4!" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
							<path strokeLinecap="round" strokeLinejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
						</svg>
					</button>
					<button
						type="button"
						onClick={() => setRotation(0)}
						className="relative! inline-flex! items-center! font-medium! justify-center! transition-all! duration-200! cursor-pointer! rounded-xl! border-2! border-zinc-200! bg-white! hover:bg-zinc-50! text-zinc-500! hover:text-brand-500! hover:border-brand-200! shadow-sm! w-8! h-8! min-w-8! min-h-8! max-w-8! max-h-8! p-0!"
						title="Reset"
					>
						<svg className="size-4!" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
							<path strokeLinecap="round" strokeLinejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
						</svg>
					</button>
					<button
						type="button"
						onClick={() => setRotation((r) => Math.min(180, r + 90))}
						className="relative! inline-flex! items-center! font-medium! justify-center! transition-all! duration-200! cursor-pointer! rounded-xl! border-2! border-zinc-200! bg-white! hover:bg-zinc-50! text-zinc-500! hover:text-brand-500! hover:border-brand-200! shadow-sm! w-8! h-8! min-w-8! min-h-8! max-w-8! max-h-8! p-0!"
						title="+90°"
					>
						<svg className="size-4!" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
							<path strokeLinecap="round" strokeLinejoin="round" d="m15 15 6-6m0 0-6-6m6 6H9a6 6 0 0 0 0 12h3" />
						</svg>
					</button>
				</div>
			</div>

			{}
			<div className="flex gap-3 pt-3 justify-center">
				<button
					type="button"
					onClick={onCancel}
					title="Cancelar"
					className="relative! inline-flex! items-center! font-medium! justify-center! transition-all! duration-200! cursor-pointer! rounded-xl! border-2! border-zinc-200! bg-white! hover:bg-zinc-50! text-zinc-600! hover:text-zinc-900! shadow-sm! w-8! h-8! min-w-8! min-h-8! max-w-8! max-h-8! p-0!"
				>
					<svg className="size-4!" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
						<path strokeLinecap="round" strokeLinejoin="round" d="M6 18 18 6M6 6l12 12" />
					</svg>
				</button>
				<button
					type="button"
					onClick={onConfirm}
					title="Aplicar Recorte"
					className="relative! inline-flex! items-center! font-medium! justify-center! transition-all! duration-200! cursor-pointer! rounded-xl! bg-gradient-to-br! from-brand-500! to-brand-600! text-white! border-none! shadow-md! shadow-brand-200! hover:brightness-110! active:scale-95! w-8! h-8! min-w-8! min-h-8! max-w-8! max-h-8! p-0!"
				>
					<svg className="size-4!" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3">
						<path strokeLinecap="round" strokeLinejoin="round" d="m4.5 12.75 6 6 9-13.5" />
					</svg>
				</button>
			</div>
		</div>
	);
};
