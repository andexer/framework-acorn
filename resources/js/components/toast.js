const typeConfig = {
	info:    { textColor: 'text-blue-600',    background: 'bg-blue-50/95',    borderColor: 'border-blue-200',    ariaLabel: 'Information' },
	success: { textColor: 'text-emerald-600', background: 'bg-emerald-50/95', borderColor: 'border-emerald-200', ariaLabel: 'Success' },
	error:   { textColor: 'text-red-600',     background: 'bg-red-50/95',     borderColor: 'border-red-200',     ariaLabel: 'Error' },
	warning: { textColor: 'text-amber-600',   background: 'bg-amber-50/95',   borderColor: 'border-amber-200',   ariaLabel: 'Warning' },
};

export function registerToastStore() {
	Alpine.store('toasts', {
		items: [],
		maxToasts: 5,
		pausedIds: new Set(),

		add(details) {
			if (!details?.content && !details?.title) return;
			this.items.unshift({
				id: Date.now() + Math.random(),
				type: details.type || 'info',
				title: details.title || null,
				description: details.description || null,
				content: details.content || details.description || null,
				duration: details.duration ?? 5000,
				showProgress: details.showProgress !== false,
			});
			if (this.items.length > this.maxToasts) {
				this.items = this.items.slice(0, this.maxToasts);
			}
		},

		remove(id) {
			this.items = this.items.filter(t => t.id !== id);
			this.pausedIds.delete(id);
		},

		pauseFrom(targetId) {
			const idx = this.items.findIndex(t => t.id === targetId);
			if (idx === -1) return;
			this.pausedIds.clear();
			for (let i = 0; i <= idx; i++) this.pausedIds.add(this.items[i].id);
		},

		resumeAll() {
			this.pausedIds.clear();
		},

		isPaused(id) {
			return this.pausedIds.has(id);
		},

		getClasses(type) {
			const c = typeConfig[type] ?? typeConfig.info;
			return `${c.background} ${c.borderColor}`;
		},

		getIconColor(type) {
			return (typeConfig[type] ?? typeConfig.info).textColor;
		},

		getAriaLabel(type) {
			return (typeConfig[type] ?? typeConfig.info).ariaLabel;
		},
	});
}

export function toastItem(toast) {
	return {
		toast,
		visible: false,
		progress: 100,
		startTime: null,
		animationId: null,
		totalPausedTime: 0,
		pauseStartTime: null,

		get store() { return Alpine.store('toasts'); },

		get showProgressBar() { return this.toast.showProgress !== false && this.toast.duration > 0; },
		get isInfo()    { return this.toast.type === 'info'; },
		get isSuccess() { return this.toast.type === 'success'; },
		get isError()   { return this.toast.type === 'error'; },
		get isWarning() { return this.toast.type === 'warning'; },
		get iconColor()      { return this.store.getIconColor(this.toast.type); },
		get ariaLabel()      { return this.store.getAriaLabel(this.toast.type); },
		get toastClasses()   { return this.store.getClasses(this.toast.type); },
		get progressOpacity(){ return this.store.isPaused(this.toast.id) ? 'opacity-[0.04]' : 'opacity-[0.08]'; },

		init() {
			this.$nextTick(() => {
				this.visible = true;
				this.startTimer();
			});
		},

		startTimer() {
			if (!this.toast.duration || this.toast.duration <= 0) return;
			this.startTime = performance.now();
			this.updateProgress();
		},

		updateProgress() {
			const now = performance.now();
			const paused = this.store.isPaused(this.toast.id);

			if (paused && !this.pauseStartTime) {
				this.pauseStartTime = now;
			} else if (!paused && this.pauseStartTime) {
				this.totalPausedTime += now - this.pauseStartTime;
				this.pauseStartTime = null;
			}

			const elapsed = now - this.startTime - this.totalPausedTime;

			if (paused) {
				this.animationId = requestAnimationFrame(() => this.updateProgress());
				return;
			}

			this.progress = Math.max(0, 100 - (elapsed / this.toast.duration) * 100);

			if (elapsed >= this.toast.duration) {
				this.dismiss();
				return;
			}

			this.animationId = requestAnimationFrame(() => this.updateProgress());
		},

		onMouseEnter() { this.store.pauseFrom(this.toast.id); },
		onMouseLeave() { this.store.resumeAll(); },

		dismiss() {
			this.visible = false;
			if (this.animationId) cancelAnimationFrame(this.animationId);
			setTimeout(() => this.store.remove(this.toast.id), 300);
		},
	};
}
