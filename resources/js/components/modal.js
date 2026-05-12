const focusableSelector = [
	'input:not([type="hidden"]):not([disabled])',
	'button:not([disabled])',
	'select:not([disabled])',
	'textarea:not([disabled])',
	'[href]',
	'[tabindex]:not([tabindex="-1"])',
].join(', ');

function hasTextSelection(element) {
	return element?.selectionStart !== undefined && element?.selectionEnd !== undefined;
}

function resolveModalApi(context = null) {
	return context?.$modal ?? window.Modal ?? window.$modal ?? null;
}

function lockBodyScroll() {
	const body = document.body;
	if (!body) return;

	if (body.dataset.modalScrollLock === 'true') return;

	body.dataset.modalScrollLock = 'true';
	body.dataset.modalOriginalOverflow = body.style.overflow ?? '';
	body.dataset.modalOriginalPaddingRight = body.style.paddingRight ?? '';

	const scrollbarWidth = Math.max(0, window.innerWidth - document.documentElement.clientWidth);
	const computedPaddingRight = parseFloat(window.getComputedStyle(body).paddingRight || '0');

	body.style.overflow = 'hidden';
	if (scrollbarWidth > 0) {
		body.style.paddingRight = `${computedPaddingRight + scrollbarWidth}px`;
	}
}

function unlockBodyScroll() {
	const body = document.body;
	if (!body) return;

	if (body.dataset.modalScrollLock !== 'true') return;

	body.style.overflow = body.dataset.modalOriginalOverflow ?? '';
	body.style.paddingRight = body.dataset.modalOriginalPaddingRight ?? '';

	delete body.dataset.modalScrollLock;
	delete body.dataset.modalOriginalOverflow;
	delete body.dataset.modalOriginalPaddingRight;
}

export function registerModalMagic(defineReactiveMagicProperty) {
	defineReactiveMagicProperty('modal', {
		openModals: new Set(),

		open(id) {
			if (this.openModals.has(id)) return;

			this.openModals.add(id);
			window.dispatchEvent(new CustomEvent('open-modal', { detail: { id } }));
		},

		close(id) {
			if (!this.openModals.has(id)) return;

			this.openModals.delete(id);
			window.dispatchEvent(new CustomEvent('close-modal', { detail: { id } }));
		},

		closeAll() {
			this.openModals.forEach((id) => {
				this.close(id);
			});
		},

		getOpenedModals() {
			return Array.from(Alpine.raw(this.openModals));
		},

		isOpen(id) {
			return this.openModals.has(id);
		},
	});
	window.$modal = window.Modal;
}

export default function modalComponent({
	persistent = false,
	closeByClickingAway = true,
	closeByEscaping = true,
	autofocus = true,
	closeEventName = 'close-modal',
	openEventName = 'open-modal',
	modalId = null,
} = {}) {
	return {
		isOpen: false,
		persistent,
		closeByClickingAway,
		closeByEscaping,
		autofocus,
		closeEventName,
		openEventName,
		modalId,

		_closeEventHandler: null,
		_openEventHandler: null,

		init() {
			this.setupEventListeners();

			this.$watch('isOpen', (value) => {
				if (value && this.autofocus) {
					this.$nextTick(() => {
						const firstFocusable = this.$refs.modalContent?.querySelector(focusableSelector);
						firstFocusable?.focus();
					});
				}

				this.syncBodyScrollLock();

				this.$dispatch(value ? 'modal-opened' : 'modal-closed', { id: this.modalId });
			});
		},

		setupEventListeners() {
			this._closeEventHandler = (event) => {
				if (event.detail?.id !== this.modalId) return;
				this.isOpen = false;
			};

			this._openEventHandler = (event) => {
				if (event.detail?.id !== this.modalId) return;
				this.isOpen = true;
			};

			window.addEventListener(this.closeEventName, this._closeEventHandler);
			window.addEventListener(this.openEventName, this._openEventHandler);
		},

		teardownEventListeners() {
			if (this._closeEventHandler) {
				window.removeEventListener(this.closeEventName, this._closeEventHandler);
				this._closeEventHandler = null;
			}

			if (this._openEventHandler) {
				window.removeEventListener(this.openEventName, this._openEventHandler);
				this._openEventHandler = null;
			}
		},

		syncBodyScrollLock() {
			const modalApi = resolveModalApi(this);
			const hasOpenedModals = typeof modalApi?.getOpenedModals === 'function'
				? modalApi.getOpenedModals().length > 0
				: this.isOpen;

			if (hasOpenedModals) {
				lockBodyScroll();
			} else {
				unlockBodyScroll();
			}
		},

		open() {
			resolveModalApi(this)?.open?.(this.modalId);
			this.isOpen = true;
		},

		close() {
			if (this.persistent) return;
			this.forceClose();
		},

		forceClose() {
			resolveModalApi(this)?.close?.(this.modalId);
			this.isOpen = false;
		},

		handleBackdropClick(event) {
			if (!this.closeByClickingAway || this.persistent || event.target !== event.currentTarget) {
				return;
			}

			if (!hasTextSelection(document.activeElement)) {
				this.close();
			}
		},

		handleEscapeKey(event) {
			if (event.key === 'Escape' && this.closeByEscaping && !this.persistent) {
				this.close();
			}
		},

		toggle() {
			this.isOpen ? this.close() : this.open();
		},

		destroy() {
			this.teardownEventListeners();
			this.forceClose();
			this.syncBodyScrollLock();
		},
	};
}

export function modalGrabHandleComponent({ modal = null } = {}) {
	return {
		modal,
		startY: 0,
		currentY: 0,
		moving: false,
		modalContainer: null,
		modalContents: null,

		init() {
			this.modalContainer = this.$el.closest('[data-slot="modal-container"]');
			this.modalContents = this.$el.closest('[data-slot="modal-contents"]');

			if (this.modal && typeof this.modal === 'object') {
				this.$watch('modal.isOpen', (value) => {
					if (!value) return;

					this.$nextTick(() => {
						this.resetState();
					});
				});
			}
		},

		get distance() {
			return this.moving ? Math.max(0, this.currentY - this.startY) : 0;
		},

		get progress() {
			const progress = Math.max(1 - this.distance / 200, 0.5);
			return progress > 0.8 ? 1 : progress;
		},

		resetTransform() {
			if (!this.modalContainer) return;
			this.modalContainer.style.transform = '';
			this.modalContainer.style.opacity = 1;
		},

		disableDefaultAnimations() {
			if (!this.modalContents) return;
			this.modalContents.style.transition = 'none';
		},

		enableDefaultAnimations() {
			if (!this.modalContents) return;
			this.modalContents.style.transition = '';
		},

		resetState() {
			this.enableDefaultAnimations();
			this.resetTransform();
			this.moving = false;
			this.startY = 0;
			this.currentY = 0;
		},

		handleTouchStart(event) {
			this.disableDefaultAnimations();
			this.moving = true;
			this.startY = event.touches[0].clientY;
			this.currentY = this.startY;
		},

		handleTouchMove(event) {
			if (!this.moving || !this.modalContainer) return;

			this.currentY = event.touches[0].clientY;

			requestAnimationFrame(() => {
				this.modalContainer.style.transform = `translateY(${this.distance}px)`;
				this.modalContainer.style.opacity = this.progress;
			});
		},

		handleTouchEnd() {
			if (!this.moving) return;

			if (this.distance > 100) {
				this.modal?.close?.();
			} else {
				this.enableDefaultAnimations();
				this.resetTransform();
			}

			this.moving = false;
		},
	};
}
