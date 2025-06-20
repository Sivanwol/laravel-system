<x-filament::page>
    <form wire:submit="invite">
        {{ $this->form }}

        <x-filament::button
            type="submit"
            class="mt-4"
        >
            Invite User
        </x-filament::button>
    </form>
</x-filament::page>
