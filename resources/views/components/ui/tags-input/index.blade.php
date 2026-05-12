@props ([
	'disabled' => false,
	'name' => $attributes->whereStartsWith('wire:model')->first() ?? $attributes->whereStartsWith('x-model')->first(),
	'placeholder' => 'Add tags...',
	'maxTags' => null,
	'minTagLength' => 1,
	'maxTagLength' => 50,
	'allowDuplicates' => false,
	'allowedChars' => null,
	'blockedWords' => [],
	'splitKeys' => [' ', ',', ';'],
	'createOnBlur' => true,
	'createOnPaste' => true,
	'trimWhitespace' => true,
	'tagColor' => 'default',
	'tagVariant' => 'rounded',
	'showCounter' => true,
	'showClearAll' => true,
	'emptyMessage' => 'No tags added',
	'maxTagsMessage' => 'Maximum tags reached',
	'duplicateMessage' => 'Tag already exists',
	'invalidMessage' => 'Invalid tag format',
	'ariaLabel' => 'Tags input',
	'ariaDescription' => null,
	'persist' => false,
	'persistKey' => null,
	'suggestions' => [],
	'allowCustom' => true,
	'sortTags' => false,
	'sortDirection' => 'asc',
])

@php
	$inputClasses = [
		'input relative w-full border-none outline-none py-1.5 px-2 text-base text-neutral-800 transition bg-transparent duration-75 placeholder:text-neutral-400 focus:ring-0 disabled:text-neutral-500 ',
	];

	$modelAttrs = collect($attributes->getAttributes())->keys()->first(fn($key) => str_starts_with($key, 'wire:model'));

	$model = $modelAttrs ? $attributes->get($modelAttrs) : null;

	$isLive = $modelAttrs && str_contains($modelAttrs, '.live');
@endphp

<div
	x-data="function() {

	const $entangle = (prop, live) => {

		const binding = $wire.$entangle(prop);

		return live ? binding.live : binding;
	};

	const $initState = (prop, live) => {

		if (!prop) return [];

		return $entangle(prop, live);
	};

	return {
		state: $initState(@js($model), @js($isLive)),
		newTag: '',
		focused: false,
		trimWhitespace: @js($trimWhitespace),

		error: '',
		dragIndex: -1,

		splitKeys: @js($splitKeys),


		suggestions: @js($suggestions),
		filteredSuggestions: [],
		showSuggestions: false,
		selectedSuggestionIndex: -1,


		sortTags: @js($sortTags),
		sortDirection: @js($sortDirection),


		maxTags: @js($maxTags),
		minTagLength: @js($minTagLength),
		maxTagLength: @js($maxTagLength),
		allowDuplicates: @js($allowDuplicates),
		allowedChars: @js($allowedChars),
		blockedWords: @js($blockedWords),
		allowCustom: @js($allowCustom),
		createOnBlur: @js($createOnBlur),
		createOnPaste: @js($createOnPaste),


		messages: {
			maxTags: @js($maxTagsMessage),
			duplicate: @js($duplicateMessage),
			invalid: @js($invalidMessage),
			empty: @js($emptyMessage)
		},
		init: function() {


			this.$nextTick(() => {
				this.state = this.$root._x_model?.get() ?? [];
			})

			this.$watch('state', (value) => {
				if (this.sortTags) {
					Alpine.nextTick(() => {
						this.state = [...value].sort((a, b) => {
							const result = a.localeCompare(b);
							return this.sortDirection === 'desc' ? -result : result;
						});
					});
				}


				this.$root?._x_model?.set(value);
			});
		},

		addTag: function(tag) {
			if (!tag) return false;

			if (this.trimWhitespace) {
				tag = tag.trim();
				if (!tag) return false;
			}


			if (this.maxTags && this.state.length >= this.maxTags) {
				this.showError(this.messages.maxTags);
				return false;
			}


			if (!this.validateTag(tag)) {
				this.showError(this.messages.invalid);
				return false;
			}


			if (!this.allowDuplicates) {
				const exists = this.state.some(t => t.toLowerCase() === tag.toLowerCase());
				if (exists) {
					this.showError(this.messages.duplicate);
					return false;
				}
			}


			if (!this.allowCustom && !this.suggestions.includes(tag)) {
				this.showError('Only predefined state are allowed');
				return false;
			}


			Alpine.mutateDom(() => {
				this.state.push(tag);
			});

			this.newTag = '';
			this.clearError();
			return true;
		},


		validateTag: function(tag) {

			if (tag.length < this.minTagLength || tag.length > this.maxTagLength) {
				return false;
			}


			if (this.allowedChars && !new RegExp(this.allowedChars).test(tag)) {
				return false;
			}


			if (this.blockedWords.some((word) => tag.toLowerCase() === word.toLowerCase())) {
				return false;
			}

			return true;
		},

		deleteTag: function(index) {
			if (typeof index === 'string') {

				index = this.state.findIndex(tag => tag === index);
			}
			if (index >= 0 && index < this.state.length) {
				Alpine.mutateDom(() => {
					this.state.splice(index, 1);
				});
				this.clearError();
			}
		},

		clearAllTags: function() {
			Alpine.mutateDom(() => {
				this.state = [];
			});
			this.clearError();
		},

		showError: function(message) {
			this.error = message;
			setTimeout(() => this.clearError(), 3000);
		},

		clearError: function() {
			this.error = '';
		},

		selectSuggestion: function(index) {
			if (index >= 0 && index < this.filteredSuggestions.length) {
				this.addTag(this.filteredSuggestions[index]);
			}
		},

		hideSuggestions: function() {
			this.showSuggestions = false;
			this.selectedSuggestionIndex = -1;
		},
		updateSuggestions: function() {
			const query = this.newTag.toLowerCase().trim();

			if (!query || this.suggestions.length === 0) {
				this.filteredSuggestions = [];
				this.showSuggestions = false;
				return;
			}

			this.filteredSuggestions = this.suggestions
				.filter(s => s.toLowerCase().includes(query) && !this.state.includes(s))
				.slice(0, 5);

			this.showSuggestions = this.filteredSuggestions.length > 0;
			this.selectedSuggestionIndex = -1;
		},

		handleKeydown: function(event) {
			if (event.key === 'Enter') {
				event.preventDefault();

				if (this.selectedSuggestionIndex >= 0 && this.filteredSuggestions[this.selectedSuggestionIndex]) {
					this.addTag(this.filteredSuggestions[this.selectedSuggestionIndex]);
					this.hideSuggestions();
				} else if (this.newTag.trim()) {
					this.addTag(this.newTag);
				}

			} else if (event.key === 'ArrowDown') {
				event.preventDefault();
				if (this.filteredSuggestions.length > 0) {
					this.selectedSuggestionIndex = (this.selectedSuggestionIndex + 1) % this.filteredSuggestions.length;
				}

			} else if (event.key === 'ArrowUp') {
				event.preventDefault();
				if (this.filteredSuggestions.length > 0) {
					this.selectedSuggestionIndex = (this.selectedSuggestionIndex - 1 + this.filteredSuggestions.length) % this.filteredSuggestions.length;
				}

			} else if (event.key === 'Backspace' && !this.newTag && this.state.length > 0) {
				this.deleteTag(this.state.length - 1);
			}
		},
		handlePaste: function(event) {
			if (!this.createOnPaste) return;

			Alpine.nextTick(() => {
				this.processSplitKeys(this.newTag);
			});
		},
		handleInput: function(event) {

			const inputValue = event.target.value;
			const lastChar = inputValue.slice(-1);


			if (this.splitKeys.includes(lastChar)) {

				const tagToAdd = inputValue.slice(0, -1);

				if (tagToAdd.trim()) {

					this.addTag(tagToAdd);

					this.newTag = '';

					event.target.value = '';
				} else {

					this.newTag = '';
					event.target.value = '';
				}
			} else {

				this.newTag = inputValue;
				this.updateSuggestions();
			}
		},
		processSplitKeys: function(text) {
			const pattern = this.splitKeys
				.map(key => key.replace(/[/\\\\^$*+?.()|[\\]{}]/g, '\\\\$&'))
				.join('|');

			const state = text.split(new RegExp(pattern, 'g'));

			if (state.length > 1) {
				this.newTag = '';
				state.forEach(tag => {
					if (tag.trim()) {
						this.addTag(tag);
					}
				});
			}
		},
		hasMaxTags: function() {
			return this.maxTags && this.state.length >= this.maxTags;
		},

		isEmpty: function() {
			return this.state.length === 0;
		},
		tagCount: function() {
			return this.state.length;
		},

		onDragStart: function(index) {
			this.dragIndex = index;
		},

		onDrop: function(event, dropIndex) {
			if (this.dragIndex === -1 || this.dragIndex === dropIndex) return;

			Alpine.mutateDom(() => {
				const updatedTags = [...this.state];


				const [movedTag] = updatedTags.splice(this.dragIndex, 1);


				updatedTags.splice(dropIndex, 0, movedTag);

				this.state = updatedTags;
				this.dragIndex = -1;
			});
		}
	}
}"
	x-id="['tags-input']"
	{{ $attributes->whereStartsWith('wire:model') }}
	{{ $attributes->whereStartsWith('x-model') }}
	class="contents"
>
	<div
		{{ $attributes->class([
			'rounded-box bg-white w-full shadow-sm ring-1 ring-neutral-950/10 transition duration-75 focus-within:ring-2 focus-within:ring-neutral-950/10 ',
			'[--suggestion-popup-round:var(--radius-box)] [--suggestion-popup-padding:--spacing(1)]',
		]) }}
	>
		@if ($showCounter || $showClearAll)
			<div class="flex items-center justify-between p-2">
				@if ($showCounter)
					<span class="text-sm text-neutral-500">
						<span x-text="tagCount"></span>
						@if ($maxTags)
							/ {{ $maxTags }}
						@endif
						tags
					</span>
				@endif

				@if ($showClearAll)
					<button
						type="button"
						x-on:click="clearAllTags()"
						x-bind:disabled="isEmpty"
						class="text-sm hover:opacity-70 transition-opacity text-neutral-400 duration-300 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed"
					>
						<x-ui.icon name="trash" class="size-5" />
					</button>
				@endif
			</div>
		@endif

		<input
			type="text"
			@class (Arr::toCssClasses($inputClasses))
			name="{{ $name }}"
			placeholder="{{ $placeholder }}"
			x-bind:disabled="hasMaxTags() || @js($disabled)"
			x-on:focus="focused = true"
			x-on:blur="
				focused = false;
				createOnBlur && newTag.trim() && addTag(newTag);
				hideSuggestions();
			"
			x-on:paste="handlePaste"
			x-model="newTag"
			x-on:input.stop="handleInput"
			x-on:change.stop
			x-bind:id="$id('tags-input')"
			x-ref="input"
			role="textbox"
			x-bind:aria-label="@js($ariaLabel)"
			x-bind:aria-describedby="error ? 'error-message' : ''"
			x-on:keydown="handleKeydown"
		/>

		<div
			x-show="showSuggestions"
			x-transition
			x-ref="suggestions"
			x-anchor.bottom-start="$refs.input"
			style="display: none"
			class="absolute z-50 bg-white w-40 mt-1 backdrop-blur-xl border border-neutral-200 rounded-(--suggestion-popup-round) shadow-lg p-(--suggestion-popup-padding)"
		>
			<template
				x-for="(suggestion, index) in filteredSuggestions"
				x-bind:key="index"
			>
				<div
					tabindex="0"
					x-on:click.stop="
						addTag(suggestion);
						hideSuggestions();
					"
					x-on:keydown.enter="
						addTag(suggestion);
						hideSuggestions();
					"
					x-text="suggestion"
					class="px-3 py-2 rounded-[calc(var(--suggestion-popup-round)-var(--suggestion-popup-padding))] text-sm cursor-pointer hover:bg-neutral-100"
					:class="{
						'bg-white/5 ': selectedSuggestionIndex === index,
					}"
				></div>
			</template>
		</div>

		<template x-if="state?.length">
			<div class="inline-block w-full">
				<div
					class="flex w-full flex-wrap gap-1.5 p-2 border-t border-t-neutral-200"
				>
					<template
						x-for="(tag, index) in state"
						:key="`${tag}-${index}`"
					>
						<x-ui.tags-input.tag :$tagVariant :$tagColor />
					</template>
				</div>
			</div>
		</template>

		<div
			x-show="error"
			style="display: none"
			x-transition
			class="p-2 text-sm text-red-600"
			id="error-message"
		>
			<span x-text="error"></span>
		</div>
	</div>
</div>
