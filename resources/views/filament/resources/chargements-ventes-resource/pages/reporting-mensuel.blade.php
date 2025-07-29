<x-filament::page>
    <x-filament::card>

        {{-- En-tête --}}
        <h2 class="text-xl font-bold mb-4">Reporting mensuel</h2>

        {{-- Filtres --}}
        <form method="GET" class="flex gap-4 items-center mb-6">
            {{-- Sélection année --}}
            <select name="annee" class="w-40 border border-gray-300 rounded-md text-sm">
                <option value="">Année</option>
                @foreach (range(now()->year, now()->year - 3) as $year)
                <option value="{{ $year }}" @selected(request('annee')==$year)>{{ $year }}</option>
                @endforeach
            </select>

            {{-- Sélection mois --}}
            <select name="mois" class="w-40 border border-gray-300 rounded-md text-sm">
                <option value="">Mois</option>
                @foreach ([
                '01' => 'Janvier', '02' => 'Février', '03' => 'Mars', '04' => 'Avril',
                '05' => 'Mai', '06' => 'Juin', '07' => 'Juillet', '08' => 'Août',
                '09' => 'Septembre', '10' => 'Octobre', '11' => 'Novembre', '12' => 'Décembre'
                ] as $key => $month)
                <option value="{{ $key }}" @selected(request('mois')==$key)>{{ $month }}</option>
                @endforeach
            </select>

            {{-- Recherche --}}
            <input type="text" name="search" placeholder="Rechercher..."
                value="{{ request('search') }}"
                class="px-4 py-2 border border-gray-300 rounded-lg text-sm w-64" />

            {{-- Bouton Afficher --}}
            <x-filament::button color="primary" size="sm" type="submit">
                Afficher
            </x-filament::button>
        </form>

        {{-- Tableau --}}
        <div class="overflow-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2">Société</th>
                        <th class="px-4 py-2">Année</th>
                        <th class="px-4 py-2">Mois</th>
                        <th class="px-4 py-2">Centre Emplisseur</th>
                        <th class="px-4 py-2">Code Client</th>
                        <th class="px-4 py-2">Catégorie Client</th>
                        <th class="px-4 py-2">Code Région</th>
                        <th class="px-4 py-2">Région</th>
                        <th class="px-4 py-2">Préfecture</th>
                        <th class="px-4 py-2">Commune Découpage</th>
                        <th class="px-4 py-2">Commune Déclarée</th>
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
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach ($this->records as $record)
                    <tr class="hover:bg-gray-50">
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
                    @endforeach
                </tbody>
            </table>
        </div>

    </x-filament::card>
</x-filament::page>