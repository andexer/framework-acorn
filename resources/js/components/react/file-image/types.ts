export type FileImageStep = 'upload' | 'crop' | 'preview';

export interface CroppedAreaPixels {
	x: number;
	y: number;
	width: number;
	height: number;
}

export interface FileImageEditorProps {
	/** Wire component ID for dispatching back to Livewire */
	wireId: string;
	/** Initial image URL (if editing existing) */
	initialUrl?: string;
	/** Aspect ratio: undefined = free, 1 = square, 16/9, etc. */
	aspectRatio?: number;
	/** Max output size in MB (browser-image-compression) */
	maxSizeMB?: number;
	/** Output image quality 0–1 */
	quality?: number;
}

export interface ImageResult {
	base64: string;
	blob: Blob;
	width: number;
	height: number;
	sizeKb: number;
}
