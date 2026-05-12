<?php

use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public $image;
    public $imageUrl;
    public $croppedImage;
    public float $aspectRatio = 1.0;

    public function mount(?string $imageUrl = null, float $aspectRatio = 1.0): void
    {
        $this->imageUrl = $imageUrl ?? 'https://raw.githubusercontent.com/roadmanfong/react-cropper/master/example/img/child.jpg';
        $this->aspectRatio = $aspectRatio;
    }

    public function updatedImage(): void
    {
        $this->validate([
            'image' => 'image|max:2048',
        ]);

        $this->imageUrl = $this->image->temporaryUrl();
        $this->dispatch('image-updated');
    }

    public function saveCroppedImage(string $base64): void
    {
        $this->croppedImage = $base64;
        
        $this->dispatch(
            'notify',
            type: 'success',
            content: __('Imagen recortada con éxito', 'framework'),
            duration: 3000
        );
    }
};
?>

<div class="w-full">
    <x-ui.card size="xl" class="max-w-none! p-0! overflow-hidden! ring-1! ring-zinc-200/50! shadow-2xl!">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-8 border-b border-zinc-100 bg-zinc-50/50">
            <div class="flex items-center gap-5">
                <div class="flex size-14 items-center justify-center rounded-2xl bg-brand-500 shadow-xl shadow-brand-500/20 rotate-3 transition-transform hover:rotate-0">
                    <x-ui.icon name="scissors" class="size-7 text-white!" variant="solid" />
                </div>
                <div>
                    <x-ui.heading level="h2" size="xl" class="font-black! tracking-tight!">
                        {{ __('Editor de Imagen', 'framework') }}
                    </x-ui.heading>
                    <x-ui.text class="text-zinc-500! font-medium!">
                        {{ __('Recorta y ajusta tu imagen profesionalmente', 'framework') }}
                    </x-ui.text>
                </div>
            </div>

            <div class="flex items-center">
                <label class="group relative flex cursor-pointer items-center gap-2 rounded-xl border border-zinc-200 bg-white px-5 py-2.5 transition-all hover:border-brand-500 hover:bg-brand-50/50 shadow-sm">
                    <x-ui.icon name="cloud-arrow-up" class="size-5 text-zinc-400 group-hover:text-brand-500" />
                    <span class="text-sm font-black text-zinc-600 group-hover:text-brand-600 uppercase tracking-tight">{{ __('Cambiar Imagen', 'framework') }}</span>
                    <input type="file" wire:model="image" class="hidden" accept="image/*">
                </label>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[600px]">
            <div class="lg:col-span-8 p-0 bg-zinc-100/30 overflow-hidden relative border-b lg:border-b-0 lg:border-r border-zinc-100 flex items-center justify-center">
                <div 
                    class="w-full h-full flex items-center justify-center p-10"
                    x-data="imageCropperComponent({
                        livewire: $wire,
                        aspectRatio: {{ $aspectRatio }},
                    })"
                    @image-updated.window="destroy(); $nextTick(() => setup())"
                >
                    @if($imageUrl)
                        <div class="w-full max-w-full rounded-2xl overflow-hidden shadow-2xl border-4 border-white bg-white">
                            <img 
                                src="{{ $imageUrl }}" 
                                alt="To crop" 
                                class="max-w-full h-auto block"
                                style="max-height: 60vh;"
                            >
                        </div>

                        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex items-center gap-2 rounded-2xl border border-white/40 bg-white/90 p-2.5 shadow-2xl backdrop-blur-md z-50">
                            <x-ui.button variant="ghost" squared size="md" icon="arrow-path" @click="_cropper.reset()" />
                            <div class="h-6 w-px bg-zinc-200 mx-1"></div>
                            <x-ui.button variant="ghost" squared size="md" icon="magnifying-glass-plus" @click="_cropper.zoom(0.1)" />
                            <x-ui.button variant="ghost" squared size="md" icon="magnifying-glass-minus" @click="_cropper.zoom(-0.1)" />
                            <div class="h-6 w-px bg-zinc-200 mx-1"></div>
                            <x-ui.button 
                                variant="gradient" 
                                color="brand" 
                                size="md" 
                                icon="check" 
                                class="px-8!"
                                @click="save()"
                            >
                                {{ __('Recortar Ahora', 'framework') }}
                            </x-ui.button>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-24 opacity-40">
                            <x-ui.icon name="photo" class="size-20 text-zinc-300 mb-6" />
                            <x-ui.text class="text-zinc-400! font-black! tracking-[0.2em] uppercase text-xs">{{ __('Cargar Imagen', 'framework') }}</x-ui.text>
                        </div>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-4 p-10 flex flex-col gap-12 bg-white">
                <div class="flex flex-col gap-6">
                    <div class="flex items-center gap-3">
                        <div class="size-2 rounded-full bg-brand-500 shadow-sm shadow-brand-500/50"></div>
                        <x-ui.heading level="h3" size="xs" class="font-black! text-zinc-400! uppercase! tracking-[0.25em]! text-[10px]!">
                            {{ __('Vista Previa', 'framework') }}
                        </x-ui.heading>
                    </div>
                    
                    <div class="relative aspect-square w-full rounded-[3rem] bg-zinc-50 ring-1 ring-zinc-200/50 flex items-center justify-center overflow-hidden shadow-inner group transition-all hover:ring-brand-500/30">
                        @if($croppedImage)
                            <img src="{{ $croppedImage }}" class="h-full w-full object-contain p-4" alt="Cropped result">
                            <div class="absolute inset-0 bg-zinc-900/60 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center backdrop-blur-sm">
                                <x-ui.button 
                                    variant="primary" 
                                    color="white" 
                                    size="sm" 
                                    icon="arrow-down-tray"
                                    class="bg-white! text-brand-600!"
                                    @click="const link = document.createElement('a'); link.download = 'cropped.png'; link.href = '{{ $croppedImage }}'; link.click();"
                                >
                                    {{ __('Descargar', 'framework') }}
                                </x-ui.button>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center p-12 text-center gap-5">
                                <div class="size-16 rounded-full bg-zinc-100/50 flex items-center justify-center">
                                    <x-ui.icon name="eye" class="size-8 text-zinc-200" />
                                </div>
                                <x-ui.text size="xs" class="text-zinc-400! font-semibold! leading-relaxed!">
                                    {{ __('El resultado aparecerá aquí después del recorte', 'framework') }}
                                </x-ui.text>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col gap-8">
                    <div class="flex flex-col gap-5">
                        <div class="flex items-center gap-3">
                            <div class="size-2 rounded-full bg-brand-500 shadow-sm shadow-brand-500/50"></div>
                            <x-ui.heading level="h3" size="xs" class="font-black! text-zinc-400! uppercase! tracking-[0.25em]! text-[10px]!">
                                {{ __('Proporciones', 'framework') }}
                            </x-ui.heading>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-2.5">
                            @foreach([
                                1.0 => '1:1 Square',
                                1.33 => '4:3 Standard',
                                1.77 => '16:9 Widescreen'
                            ] as $ratio => $label)
                                <x-ui.button 
                                    variant="{{ abs($aspectRatio - $ratio) < 0.01 ? 'primary' : 'outline' }}"
                                    color="brand"
                                    size="sm"
                                    class="w-full! justify-start! px-5!"
                                    wire:click="$set('aspectRatio', {{ $ratio }})"
                                    @click="setTimeout(() => $dispatch('image-updated'), 50)"
                                >
                                    {{ $label }}
                                </x-ui.button>
                            @endforeach
                        </div>
                    </div>

                    @if($croppedImage)
                        <div class="p-8 rounded-[2rem] bg-brand-50/40 border border-brand-100/50 shadow-sm">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="size-8 rounded-full bg-brand-500 flex items-center justify-center shadow-lg shadow-brand-500/20">
                                    <x-ui.icon name="check" class="size-4 text-white!" variant="solid" />
                                </div>
                                <x-ui.text size="sm" class="text-brand-950! font-black! tracking-tight!">
                                    {{ __('¡Imagen Lista!', 'framework') }}
                                </x-ui.text>
                            </div>
                            <x-ui.text size="xs" class="text-brand-800! font-medium! leading-relaxed!">
                                {{ __('Tu imagen ha sido procesada y optimizada para su uso.', 'framework') }}
                            </x-ui.text>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </x-ui.card>
</div>