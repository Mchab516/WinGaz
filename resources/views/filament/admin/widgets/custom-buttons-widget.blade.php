<x-filament::widget>
    <x-filament::card>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

            {{-- Bouton : Gestion des clients --}}
            <x-filament::button
                tag="a"
                href="{{ route('filament.admin.resources.clients.index') }}"
                icon="heroicon-o-users"
                color="info"
                size="xl"
                class="w-full h-32 text-xl font-bold justify-center">
                Gestion des clients
            </x-filament::button>

            {{-- Bouton : Gestion des centres emplisseurs --}}
            <x-filament::button
                tag="a"
                href="{{ route('filament.admin.resources.centre-emplisseurs.index') }}"
                icon="heroicon-o-building-office"
                color="warning"
                size="xl"
                class="w-full h-32 text-xl font-bold justify-center">
                Gestion des centres emplisseurs
            </x-filament::button>

            {{-- Bouton : Gestion des chargements/ventes --}}
            <x-filament::button
                tag="a"
                href="{{ route('filament.admin.resources.chargements-ventes.index') }}"
                icon="heroicon-o-truck"
                color="primary"
                size="xl"
                class="w-full h-32 text-xl font-bold justify-center">
                Gestion des chargements/ventes
            </x-filament::button>

            {{-- ✅ Bouton : Reporting Mensuel - visible uniquement pour Admin et Comptabilité --}}
            @php
            $profilId = auth()->user()->profil_id;
            @endphp

            @if(in_array($profilId, [1, 3]))
            <x-filament::button
                tag="a"
                href="{{ route('filament.admin.resources.chargements-ventes.reporting-mensuel') }}"
                icon="heroicon-o-chart-bar"
                color="success"
                size="xl"
                class="w-full h-32 text-xl font-bold justify-center">
                Reporting Mensuel
            </x-filament::button>
            @endif

        </div>
    </x-filament::card>
</x-filament::widget>