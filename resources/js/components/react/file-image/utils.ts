import type { CroppedAreaPixels } from './types';

/**
 * Creates an HTMLImageElement from a URL (waits for load).
 */
function createImage(url: string): Promise<HTMLImageElement> {
	return new Promise((resolve, reject) => {
		const img = new Image();
		img.addEventListener('load', () => resolve(img));
		img.addEventListener('error', (err) => reject(err));
		img.setAttribute('crossOrigin', 'anonymous');
		img.src = url;
	});
}

/**
 * Returns a degree-to-radian value.
 */
function getRadianAngle(deg: number): number {
	return (deg * Math.PI) / 180;
}

/**
 * Returns the new bounding box size of a rectangle after rotation.
 */
function rotateSize(width: number, height: number, rotation: number) {
	const rad = getRadianAngle(rotation);
	return {
		width: Math.abs(Math.cos(rad) * width) + Math.abs(Math.sin(rad) * height),
		height: Math.abs(Math.sin(rad) * width) + Math.abs(Math.cos(rad) * height),
	};
}

/**
 * Core canvas function: rotates, flips and crops the source image.
 * Returns a Blob (PNG fallback, JPEG for photos).
 */
export async function getCroppedImg(
	imageSrc: string,
	pixelCrop: CroppedAreaPixels,
	rotation = 0,
	flip = { horizontal: false, vertical: false },
	outputMimeType: 'image/jpeg' | 'image/png' | 'image/webp' = 'image/jpeg',
	quality = 0.92,
): Promise<{ blob: Blob; base64: string; width: number; height: number }> {
	const image = await createImage(imageSrc);
	const canvas = document.createElement('canvas');
	const ctx = canvas.getContext('2d');

	if (!ctx) throw new Error('Could not create canvas context');

	const { width: bBoxWidth, height: bBoxHeight } = rotateSize(image.width, image.height, rotation);

	canvas.width = bBoxWidth;
	canvas.height = bBoxHeight;

	ctx.translate(bBoxWidth / 2, bBoxHeight / 2);
	ctx.rotate(getRadianAngle(rotation));
	ctx.scale(flip.horizontal ? -1 : 1, flip.vertical ? -1 : 1);
	ctx.translate(-image.width / 2, -image.height / 2);
	ctx.drawImage(image, 0, 0);

	const cropCanvas = document.createElement('canvas');
	const cropCtx = cropCanvas.getContext('2d');
	if (!cropCtx) throw new Error('Could not create crop canvas context');

	cropCanvas.width = pixelCrop.width;
	cropCanvas.height = pixelCrop.height;

	cropCtx.drawImage(
		canvas,
		pixelCrop.x,
		pixelCrop.y,
		pixelCrop.width,
		pixelCrop.height,
		0,
		0,
		pixelCrop.width,
		pixelCrop.height,
	);

	return new Promise((resolve, reject) => {
		cropCanvas.toBlob(
			(blob) => {
				if (!blob) {
					reject(new Error('Canvas is empty'));
					return;
				}
				const reader = new FileReader();
				reader.readAsDataURL(blob);
				reader.onloadend = () => {
					resolve({
						blob,
						base64: reader.result as string,
						width: cropCanvas.width,
						height: cropCanvas.height,
					});
				};
			},
			outputMimeType,
			quality,
		);
	});
}

/**
 * Converts a File to a data URL (for previewing in React).
 */
export function readFileAsDataUrl(file: File): Promise<string> {
	return new Promise((resolve, reject) => {
		const reader = new FileReader();
		reader.onload = () => resolve(reader.result as string);
		reader.onerror = reject;
		reader.readAsDataURL(file);
	});
}

/**
 * Format bytes to a human-readable string.
 */
export function formatBytes(bytes: number): string {
	if (bytes < 1024) return `${bytes} B`;
	if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`;
	return `${(bytes / (1024 * 1024)).toFixed(2)} MB`;
}
