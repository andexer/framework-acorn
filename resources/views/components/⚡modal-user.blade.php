<?php

use Livewire\Component;

new class extends Component
{
    public $name = 'Andexer Developer';
    public $email = 'andexer@example.com';
    public $role = 'Administrator';
    public $bio = 'Passionate developer building awesome web experiences with the TALL stack.';

    public function save()
    {
        if (! current_user_can('edit_users')) {
            $this->dispatch('toast', message: 'You do not have permission to perform this action.', type: 'error');
            return;
        }

        // save logic here

        $this->dispatch('close-modal', id: 'user-profile-modal');
        $this->dispatch('toast', message: 'Profile updated successfully!', type: 'success');
    }
};
?>

<div>
    <x-ui.modal.trigger id="user-profile-modal">
        <x-ui.button class="group! relative! overflow-hidden! bg-white! hover:bg-neutral-50! text-neutral-800! border! border-neutral-200! shadow-sm! transition-all! duration-300! hover:shadow-md!">
            <span class="absolute! inset-0! h-full! w-1/4! bg-gradient-to-r! from-transparent! via-brand-500/10! to-transparent! -translate-x-full! group-hover:animate-[shimmer_1.5s_infinite]!"></span>
            <div class="flex! items-center! gap-2!">
                <div class="flex! h-6! w-6! items-center! justify-center! rounded-full! bg-gradient-to-tr! from-brand-500! to-indigo-500! text-[10px]! font-bold! text-white! shadow-sm!">
                    AD
                </div>
                <span>Edit Profile</span>
            </div>
        </x-ui.button>
    </x-ui.modal.trigger>

    <x-ui.modal id="user-profile-modal" bare view="drawer-right" width="xl" backdrop="blur">
        <div class="relative! ml-auto! flex! h-dvh! w-full! max-w-xl! flex-col! bg-white! shadow-2xl! border-l! border-neutral-200! md:my-3! md:mr-3! md:h-[calc(100dvh-1.5rem)]! md:rounded-2xl! md:border!">
            <div class="flex! items-start! justify-between! border-b! border-neutral-200! px-6! py-5!">
                <div class="min-w-0! pr-4!">
                    <h2 class="text-xl! font-semibold! text-neutral-900!">Update profile</h2>
                    <p class="mt-1! text-sm! text-neutral-500!">Make changes to your personal details.</p>
                </div>

                <span x-on:click="$data.close();" class="inline-flex! h-9! w-9! items-center! justify-center! rounded-full! bg-neutral-200! text-neutral-500! hover:bg-neutral-100! hover:text-neutral-800! transition-colors! duration-200!">
                    <x-ui.icon name="x-mark" class="h-4! w-4!" />
                </span>
            </div>

            <div class="flex-1! overflow-y-auto! px-6! py-6!">
                <div class="space-y-5!">
                    <div class="inline-flex! items-center! rounded-full! border! border-brand-200! bg-brand-50! px-2.5! py-1! text-xs! font-medium! text-brand-700!">
                        {{ $role }}
                    </div>

                    <x-ui.field>
                        <x-ui.label for="name" class="font-medium! text-neutral-700!">Name</x-ui.label>
                        <x-ui.input wire:model="name" id="name" placeholder="Your name" class="transition-shadow! duration-200! focus:border-brand-500! focus:ring-brand-500!" />
                    </x-ui.field>

                    <x-ui.field>
                        <x-ui.label for="email" class="font-medium! text-neutral-700!">Email</x-ui.label>
                        <x-ui.input wire:model="email" id="email" type="email" placeholder="you@email.com" class="transition-shadow! duration-200! focus:border-brand-500! focus:ring-brand-500!" />
                    </x-ui.field>

                    <x-ui.field>
                        <x-ui.label for="bio" class="font-medium! text-neutral-700!">Short bio</x-ui.label>
                        <x-ui.textarea wire:model="bio" id="bio" rows="4" placeholder="Tell us about yourself" class="resize-none! transition-shadow! duration-200! focus:border-brand-500! focus:ring-brand-500!"></x-ui.textarea>
                    </x-ui.field>
                </div>
            </div>

            <div class="flex! items-center! justify-end! gap-3! border-t! border-neutral-200! bg-white! px-6! py-4!">
                <x-ui.button x-on:click="$data.close();" variant="outline" class="hover:bg-neutral-100! transition-colors!">
                    Cancel
                </x-ui.button>
                <x-ui.button wire:click="save" wire:target="save" icon="check" class="bg-brand-600! hover:bg-brand-700! text-white! shadow-sm! hover:shadow-md! transition-all! duration-200!">
                    Save Changes
                </x-ui.button>
            </div>
        </div>
    </x-ui.modal>
</div>
