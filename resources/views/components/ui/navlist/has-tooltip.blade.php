@props (['tooltip' => null, 'condition' => false])

@if ($condition)
	<div
		x-data="{
		tooltipEl: null,
	
		showTooltip() {
			if (this.tooltipEl) return;
	
			if (!this.$data.collapsedSidebar) return;
	
			const rect = this.$el.getBoundingClientRect()
	
			this.tooltipEl = document.createElement('div')
			this.tooltipEl.className =
				'fixed z-[99999] px-2 py-2 mx-1 text-sm rounded-md shadow-md bg-neutral-200 text-black whitespace-nowrap pointer-events-none opacity-0 transition-opacity duration-150'
			this.tooltipEl.textContent = @js($tooltip)
	
	
	
			let left = rect.right + 8
			let top = rect.top + rect.height / 2
	
			document.body.appendChild(this.tooltipEl)
	
	
			const tooltipRect = this.tooltipEl.getBoundingClientRect()
			if (left + tooltipRect.width > window.innerWidth) {
				left = rect.left - tooltipRect.width - 8
			}
	
			this.tooltipEl.style.left = `${left}px`
			this.tooltipEl.style.top = `${top}px`
			this.tooltipEl.style.transform = 'translateY(-50%)'
	
			requestAnimationFrame(() => {
				if (this.tooltipEl) this.tooltipEl.style.opacity = '1'
			})
		},
	
		hideTooltip() {
			if (!this.tooltipEl) return
	
			this.tooltipEl.style.opacity = '0'
	
	
			setTimeout(() => {
				this.tooltipEl?.remove()
				this.tooltipEl = null
			}, 150)
		}
	}"
		x-on:mouseenter="showTooltip()"
		x-on:mouseleave="hideTooltip()"
		x-on:destroy="hideTooltip()"
		class="inline-flex"
	>
		{{ $slot }}
	</div>
@else
	{{ $slot }}
@endif
