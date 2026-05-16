export type FileImageStep = 'upload' | 'crop' | 'preview';

export interface CroppedAreaPixels {
	x: number;
	y: number;
	width: number;
	height: number;
}

export interface FileImageEditorProps {
	
	wireId: string;
	
	initialUrl?: string;
	
	aspectRatio?: number;
	
	maxSizeMB?: number;
	
	quality?: number;

	variant?: 'default' | 'avatar';
	cropShape?: 'rect' | 'round';
	accept?: string;
}

export interface ImageResult {
	base64: string;
	blob: Blob;
	width: number;
	height: number;
	sizeKb: number;
}
