<div class="space-y-6">

	<div class="flex items-center justify-between">
		<div>
			<h2 class="text-2xl font-black text-orange-900 tracking-tight">{{ __('Addons del Core', 'framework') }}</h2>
			<p class="text-orange-700/80 mt-1">{{ __('Módulos extendidos y heredados del framework central.', 'framework') }}</p>
		</div>
		<a href="{{ admin_url('plugins.php') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-orange-700 shadow-sm ring-1 ring-inset ring-orange-200 hover:bg-orange-50 transition-colors">
			{{ __('Administrar Plugins', 'framework') }}
			<svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
		</a>
	</div>

	@if (empty($addons))
		<div class="bg-orange-50/50 border border-orange-100 rounded-[2rem] p-12 text-center shadow-sm">
			<div class="size-20 rounded-3xl bg-white shadow-sm border border-orange-100 flex items-center justify-center mx-auto mb-6">
				<svg class="size-10 text-orange-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
					<path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75L16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" />
				</svg>
			</div>
			<h3 class="text-xl font-black text-orange-900 mb-2">{{ __('No hay addons instalados', 'framework') }}</h3>
			<p class="text-orange-700/80">{{ __('Los plugins marcados como "Framework Addon" aparecerán aquí automáticamente.', 'framework') }}</p>
		</div>
	@else
		<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
			@foreach ($addons as $addon)
				<div class="group relative bg-white border border-orange-100/80 rounded-[2rem] overflow-hidden shadow-sm hover:shadow-xl hover:shadow-orange-900/5 transition-all duration-300 flex flex-col">
					{{-- Header with Image --}}
					<div class="h-48 w-full bg-gradient-to-br from-orange-100 to-orange-50 relative overflow-hidden flex items-center justify-center">
						@if(str_contains($addon['image'], '.svg'))
							{{-- Default Placeholder icon if no image --}}
							<div class="size-24 rounded-[2rem] bg-white/60 backdrop-blur-md shadow-sm border border-white/80 flex items-center justify-center text-orange-400 group-hover:scale-110 transition-transform duration-500">
								<svg class="size-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
									<path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75L16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" />
								</svg>
							</div>
						@else
							<img src="{{ $addon['image'] }}" alt="{{ $addon['name'] }}" class="absolute inset-0 w-full h-full object-cover opacity-90 group-hover:scale-105 group-hover:opacity-100 transition-all duration-500" />
							<div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
						@endif
						
						{{-- Status Badge --}}
						<div class="absolute top-4 right-4">
							@if($addon['active'])
								<div class="bg-emerald-500/90 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full shadow-sm border border-emerald-400/50">
									{{ __('Activo', 'framework') }}
								</div>
							@else
								<div class="bg-zinc-800/80 backdrop-blur-md text-zinc-300 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full shadow-sm border border-zinc-600/50">
									{{ __('Inactivo', 'framework') }}
								</div>
							@endif
						</div>
					</div>

					{{-- Content --}}
					<div class="p-6 flex flex-col flex-grow">
						<h3 class="text-xl font-black text-zinc-900 tracking-tight leading-tight mb-2">{{ $addon['name'] }}</h3>
						<p class="text-sm text-zinc-500 leading-relaxed flex-grow">
							{{ $addon['description'] !== '' ? $addon['description'] : __('Este addon no proporciona una descripción detallada en sus cabeceras.', 'framework') }}
						</p>
						
						{{-- Meta Footer --}}
						<div class="mt-6 pt-4 border-t border-zinc-100 flex items-center justify-between text-xs font-semibold text-zinc-400">
							<div class="flex items-center gap-1.5 truncate max-w-[60%]">
								<svg class="size-4 text-zinc-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
									<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
								</svg>
								<span class="truncate">{{ $addon['author'] !== '' ? $addon['author'] : 'Framework' }}</span>
							</div>
							<div class="flex items-center gap-1.5 bg-zinc-50 border border-zinc-100 px-2 py-1 rounded-lg text-zinc-500 flex-shrink-0">
								v{{ $addon['version'] !== '' ? $addon['version'] : '1.0' }}
							</div>
						</div>
					</div>
				</div>
			@endforeach
		</div>
	@endif
</div>
