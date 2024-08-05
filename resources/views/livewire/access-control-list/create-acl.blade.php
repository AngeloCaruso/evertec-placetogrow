<div>
    <form wire:submit="create">
        {{ $this->form }}

        <x-filament::button class="mt-5" type="submit" icon="heroicon-s-document-plus">
            {{ __('Save') }}
        </x-filament::button>
    </form>

    <x-filament-actions::modals />
</div>