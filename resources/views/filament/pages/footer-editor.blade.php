<div>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament::button type="submit">
                Save footer
            </x-filament::button>
        </div>
    </form>
</div>
