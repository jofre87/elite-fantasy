<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="mt-10 space-y-6" x-data="{ showModal: false }">
    <div class="relative mb-5">
        <flux:heading>{{ __('Eliminar cuenta') }}</flux:heading>
        <flux:subheading>{{ __('Elimina tu cuenta y todos tus datos') }}</flux:subheading>
    </div>

    <!-- Botón para abrir el modal -->
    <button type="button"
        class="relative items-center font-medium justify-center gap-2 whitespace-nowrap h-10 text-sm rounded-lg px-4 inline-flex bg-red-500 hover:bg-red-600 text-white"
        x-on:click="showModal = true">
        {{ __('Eliminar cuenta') }}
    </button>

    <!-- Modal manual con AlpineJS -->
    <div x-show="showModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
        x-on:keydown.escape.window="showModal = false">
        <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg max-w-lg w-full space-y-6"
            x-on:click.outside="showModal = false">
            <form wire:submit="deleteUser" class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('¿Estás seguro de que deseas eliminar tu cuenta?') }}</flux:heading>
                    <flux:subheading>
                        {{ __('Una vez que tu cuenta sea eliminada, todos tus datos serán eliminados permanentemente. Por favor, introduce tu contraseña para confirmar que deseas eliminar tu cuenta de forma permanente.') }}
                    </flux:subheading>
                </div>

                <flux:input wire:model="password" :label="__('Contraseña')" type="password" />

                <div class="flex justify-end space-x-2">
                    <!-- Botón Cancelar -->
                    <button type="button" x-on:click="showModal = false"
                        class="relative items-center font-medium justify-center gap-2 whitespace-nowrap h-10 text-sm rounded-lg px-4 inline-flex bg-zinc-800/5 hover:bg-zinc-800/10 text-zinc-800 dark:text-white">
                        {{ __('Cancelar') }}
                    </button>

                    <flux:button variant="danger" type="submit">{{ __('Eliminar cuenta') }}</flux:button>
                </div>
            </form>
        </div>
    </div>
</section>