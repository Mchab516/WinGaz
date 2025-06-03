<x-filament::widget>
    <x-filament::card class="text-center">
        <div class="flex justify-center space-x-4">
            <x-filament::button color="primary" wire:click="goToChargement">
                🔵 Chargement
            </x-filament::button>

            <x-filament::button color="success" wire:click="goToVente">
                🟢 Vente
            </x-filament::button>

            <x-filament::button color="danger" wire:click="goToReporting">
                🔴 Reporting
            </x-filament::button>
        </div>
    </x-filament::card>
</x-filament::widget>