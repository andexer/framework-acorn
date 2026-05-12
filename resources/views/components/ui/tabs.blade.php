@props ([
	'variant' => 'outlined',
	'size' => 'default',
	'activeTab' => null,
])
@php

	$classes = match ($variant) {
		'outlined' => [
			' text-neutral-800 rounded-3xl',

			'[&:has(:first-child[data-slot=tabs-group].justify-start_>_:first-child[data-active=true])_[data-slot=tabs-panel]]:rounded-tl-none',

			'[&:has(:first-child[data-slot=tabs-group].justify-start_>_:first-child:is(:focus,:hover))_[data-slot=tabs-panel]]:rounded-tl-none',

			'[&:has(:first-child[data-slot=tabs-group].justify-end_>_:last-child[data-active=true])_[data-slot=tabs-panel]]:rounded-tr-none',

			'[&:has(:first-child[data-slot=tabs-group].justify-end_>_:last-child:is(:focus,:hover))_[data-slot=tabs-panel]]:rounded-tr-none',
		],
		'non-contained' => [],
		default => [],
	};

	$modelAttrs = collect($attributes->getAttributes())->keys()->first(fn($key) => str_starts_with($key, 'wire:model'));

	$model = $modelAttrs ? $attributes->get($modelAttrs) : null;

	$isLive = $modelAttrs && str_contains($modelAttrs, '.live');

@endphp

<div
	{{ $attributes->class(Arr::toCssClasses($classes)) }}
	x-data="function() {

	const $entangle = (prop, live) => {
		const binding = $wire.$entangle(prop);
		return live ? binding.live : binding;
	};


	const $initState = (model, live) => model ? $entangle(model, live) : null;

	return {
		state: $initState(@js($model), @js($isLive)),


		tabs: [],
		panels: [],

		init() {




			this.$nextTick(() => {
				this.state = (this.$el._x_model?.get() ?? @js($activeTab)) ?? null;
				this.collectTabsAndPanels();
				this.initializeActiveTab();
			})

			this.$watch('state', (newValue) => {
				if (newValue !== null) {
					this.setActiveTab(newValue);
				}
			});
		},

		collectTabsAndPanels() {
			const tabElements = this.$el.querySelectorAll('[data-slot=tab]');
			this.tabs = Array.from(tabElements).map((tab, index) => {

				tab.setAttribute('data-tab-order', index)


				const isActive = this.isActive({ name: tab.dataset?.name, index: index });

				tab.setAttribute('data-active', isActive);

				return {
					element: tab,
					name: tab.dataset?.name,
					index: index
				}
			});

			const panelElements = this.$el.querySelectorAll('[data-slot=tabs-panel]');
			this.panels = Array.from(panelElements).map((panel, index) => {
				panel.setAttribute('data-panel-order', index)
				return {
					element: panel,
					name: panel.dataset?.name,
					index: index
				}
			});
		},

		initializeActiveTab() {

			if (this.state) {
				this.setActiveTab(this.state);
			} else if (this.tabs.length > 0) {
				const firstTab = this.tabs[0];
				this.setActiveTab(firstTab.name ?? firstTab.index);
			}
		},

		setActiveTab(tabIdentifier) {

			this.state = tabIdentifier;


			this.tabs.forEach(tab => {
				const isActive = this.isActive(tab, tabIdentifier);
				tab.element.setAttribute('data-active', isActive);
			});


			this.panels.forEach(panel => {
				const isActive = this.isActive(panel, tabIdentifier);
				panel.element.style.display = isActive ? 'block' : 'none';
			});
		},

		isActive(item, identifier = this.state) {
			return item.name != null ?
				item.name === identifier :
				Number(item.index) === Number(identifier);
		},

		handleTabClick(el) {
			const tab = el;
			const tabName = tab.dataset?.name;
			const tabIndex = Number(tab.dataset.tabOrder)
			this.setActiveTab(tabName ?? tabIndex);
		}
	}
}"
	wire:ignore
>
	{{ $slot }}
</div>
