<x-filament::widget>
    <x-filament::card>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            @php
            $buttons = [
            [
            'title' => 'Chargement',
            'icon' => 'truck',
            'color' => 'info', // bleu ciel
            ],
            [
            'title' => 'Vente',
            'icon' => 'fire', // symbole alternatif pour gaz
            'color' => 'warning',
            ],
            [
            'title' => 'Reporting',
            'icon' => 'chart-bar',
            'color' => 'success',
            ],
            ];
            @endphp

            @foreach ($buttons as $btn)
            <x-filament::button
                icon="heroicon-o-{{ $btn['icon'] }}"
                color="{{ $btn['color'] }}"
                size="xl"
                class="w-full h-32 text-xl font-bold justify-center">
                {{ $btn['title'] }}
            </x-filament::button>
            @endforeach
        </div>
    </x-filament::card>
</x-filament::widget>