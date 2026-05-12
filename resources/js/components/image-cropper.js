import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';

export default ({
	livewire,
	aspectRatio = 1,
	viewMode = 1,
	dragMode = 'move',
	autoCropArea = 1,
	restore = false,
	guides = true,
	center = true,
	highlight = false,
	cropBoxMovable = true,
	cropBoxResizable = true,
	toggleDragModeOnDblclick = true,
}) => {
	return {
		_cropper: null,
		_image: null,

		init() {
			this.$nextTick(() => {
				this.setup();
			});
		},

		setup() {
			this._image = this.$el.querySelector('img');
			if (!this._image) return;

			if (this._image.complete) {
				this.initCropper();
			} else {
				this._image.onload = () => this.initCropper();
			}
		},

		initCropper() {
			if (this._cropper || !this._image) return;

			setTimeout(() => {
				this._cropper = new Cropper(this._image, {
					aspectRatio: parseFloat(aspectRatio),
					viewMode,
					dragMode,
					autoCropArea,
					restore,
					guides,
					center,
					highlight,
					cropBoxMovable,
					cropBoxResizable,
					toggleDragModeOnDblclick,
				});
			}, 100);
		},

		getCroppedDataUrl(type = 'image/png', quality = 1) {
			if (!this._cropper) return null;
			const canvas = this._cropper.getCroppedCanvas();
			return canvas ? canvas.toDataURL(type, quality) : null;
		},

		async save() {
			const dataUrl = this.getCroppedDataUrl();
			if (dataUrl) {
				await livewire.saveCroppedImage(dataUrl);
			}
		},

		destroy() {
			if (this._cropper) {
				this._cropper.destroy();
				this._cropper = null;
			}
		}
	}
}
