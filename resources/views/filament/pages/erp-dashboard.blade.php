<div>
    <div class="mb-6 flex flex-wrap gap-3">
        <x-filament::button wire:click="syncProducts" color="primary">
            Sync products from ERP
        </x-filament::button>
        <x-filament::button wire:click="syncServices" color="gray">
            Sync services from ERP
        </x-filament::button>
    </div>

    <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <h3 class="mb-4 text-lg font-semibold">Sync log</h3>
        {{ $this->table }}
    </div>
</div>
