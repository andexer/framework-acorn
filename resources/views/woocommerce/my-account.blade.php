<x-layouts::app>
<div class="max-w-4xl mx-auto py-10 px-4">
	<div class="bg-white border border-slate-200 rounded-xl p-8 shadow-sm">
		<h2 class="text-2xl font-bold text-slate-900 mb-6">Mi Cuenta</h2>
		{{-- <livewire:counter /> --}}
		{{-- <livewire:form-validate /> --}}
		{{-- <livewire:modal-user /> --}}
		{{--!! do_shortcode('[framework_map]') !!} --}}
		<livewire:mapa-direcciones />
		<br />
		<hr />
		<br />
		<livewire:image-cropper />
	</div>
</div>
</x-layouts::app>

