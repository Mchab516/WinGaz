<x-filament::page>
    <x-filament::card>

        {{-- Bouton Exporter aligné à droite --}}
        <div class="text-right mb-4">
            <a href="{{ route('export-reporting', request()->query()) }}">

                <x-filament::button color="success" size="sm" icon="heroicon-m-arrow-down-tray">
                    Exporter (Excel)
                </x-filament::button>
            </a>
        </div>

        {{-- Filtres --}}
        <form method="GET" class="flex gap-4 items-center mb-6 flex-wrap" id="filters-form">

            {{-- Sélection année --}}
            <select name="annee" class="w-40 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 rounded-md text-sm text-black dark:text-white">
                <option value="">Année</option>
                @foreach (range(now()->year, now()->year - 3) as $year)
                <option value="{{ $year }}" @selected(request('annee')==$year)>{{ $year }}</option>
                @endforeach
            </select>

            {{-- Sélection mois --}}
            <select name="mois" class="w-40 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 rounded-md text-sm text-black dark:text-white">
                <option value="">Mois</option>
                @foreach ([
                '01' => 'Janvier', '02' => 'Février', '03' => 'Mars', '04' => 'Avril',
                '05' => 'Mai', '06' => 'Juin', '07' => 'Juillet', '08' => 'Août',
                '09' => 'Septembre', '10' => 'Octobre', '11' => 'Novembre', '12' => 'Décembre'
                ] as $key => $month)
                <option value="{{ $key }}" @selected(request('mois')==$key)>{{ $month }}</option>
                @endforeach
            </select>

            {{-- Recherche texte --}}
            <input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}"
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-black dark:text-white rounded-lg text-sm w-64" />

            {{-- Bouton Afficher --}}
            <x-filament::button color="primary" size="sm" type="submit">
                Afficher
            </x-filament::button>
        </form>

        {{-- Tableau --}}
        <div class="overflow-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                @php
                $sortColumn = request('sort');
                $sortDirection = request('direction', 'asc');
                @endphp

                <thead class="bg-gray-800 text-white">
                    <tr>
                        @foreach ([
                        'societe' => 'Société',
                        'annee' => 'Année',
                        'mois' => 'Mois',
                        'centre_emplisseur' => 'Centre Emplisseur',
                        'code_client' => 'Code Client',
                        'categorie_client' => 'Catégorie Client',
                        'code_region' => 'Code Région',
                        'region' => 'Région',
                        'prefecture' => 'Préfecture',
                        'commune_decoupage' => 'Commune Découpage',
                        'commune' => 'Commune Déclarée',
                        ] as $key => $label)
                        @php
                        $isCurrentSort = $sortColumn === $key;
                        $newDirection = $isCurrentSort && $sortDirection === 'asc' ? 'desc' : 'asc';
                        $icon = $isCurrentSort ? ($sortDirection === 'asc' ? '▲' : '▼') : '⇅';
                        @endphp
                        <th class="px-4 py-2 whitespace-nowrap">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => $key, 'direction' => $newDirection]) }}"
                                class="flex items-center gap-1 hover:underline">
                                {{ $label }} <span class="text-xs">{{ $icon }}</span>
                            </a>
                        </th>
                        @endforeach

                        {{-- Quantités (exemple simple, sans tri) --}}
                        <th class="px-4 py-2">3kg</th>
                        <th class="px-4 py-2">6kg</th>
                        <th class="px-4 py-2">9kg</th>
                        <th class="px-4 py-2">12kg</th>
                        <th class="px-4 py-2">35kg</th>
                        <th class="px-4 py-2">40kg</th>
                        <th class="px-4 py-2">3kg VR</th>
                        <th class="px-4 py-2">6kg VR</th>
                        <th class="px-4 py-2">9kg VR</th>
                        <th class="px-4 py-2">12kg VR</th>
                        <th class="px-4 py-2">35kg VR</th>
                        <th class="px-4 py-2">40kg VR</th>
                    </tr>
                </thead>

                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-700 text-black dark:text-white">
                    @forelse ($this->records as $record)
                    <tr class="hover:bg-gray-800 transition-colors">
                        <td class="px-4 py-2">{{ $record->societe }}</td>
                        <td class="px-4 py-2">{{ $record->annee }}</td>
                        <td class="px-4 py-2">{{ $record->mois }}</td>
                        <td class="px-4 py-2">{{ $record->centreEmplisseur?->nom }}</td>
                        <td class="px-4 py-2">{{ $record->client?->code_client }}</td>
                        <td class="px-4 py-2">{{ $record->client?->categorie }}</td>
                        <td class="px-4 py-2">{{ $record->region?->id }}</td>
                        <td class="px-4 py-2">{{ $record->region?->nom }}</td>
                        <td class="px-4 py-2">{{ $record->prefecture?->nom }}</td>
                        <td class="px-4 py-2">{{ $record->communeDecoupage?->nom }}</td>
                        <td class="px-4 py-2">{{ $record->commune?->nom }}</td>
                        <td class="px-4 py-2">{{ $record->qte_charge_3kg }}</td>
                        <td class="px-4 py-2">{{ $record->qte_charge_6kg }}</td>
                        <td class="px-4 py-2">{{ $record->qte_charge_9kg }}</td>
                        <td class="px-4 py-2">{{ $record->qte_charge_12kg }}</td>
                        <td class="px-4 py-2">{{ $record->qte_charge_35kg }}</td>
                        <td class="px-4 py-2">{{ $record->qte_charge_40kg }}</td>
                        <td class="px-4 py-2">{{ $record->qte_vendu_3kg }}</td>
                        <td class="px-4 py-2">{{ $record->qte_vendu_6kg }}</td>
                        <td class="px-4 py-2">{{ $record->qte_vendu_9kg }}</td>
                        <td class="px-4 py-2">{{ $record->qte_vendu_12kg }}</td>
                        <td class="px-4 py-2">{{ $record->qte_vendu_35kg }}</td>
                        <td class="px-4 py-2">{{ $record->qte_vendu_40kg }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="24" class="text-center text-gray-500 dark:text-gray-400 px-4 py-6">
                            Aucun élément trouvé pour les filtres sélectionnés.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </x-filament::card>
</x-filament::page>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.querySelector('input[name="search"]');
        const form = document.getElementById('filters-form');

        let previousValue = searchInput.value;

        searchInput.addEventListener("input", function() {
            if (previousValue && searchInput.value.trim() === "") {
                form.submit(); // Champ vidé → soumettre
            }
            previousValue = searchInput.value;
        });
    });
</script>