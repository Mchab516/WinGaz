<x-filament::page>
    <x-filament::card>

        {{-- Bouton Exporter aligné à droite --}}
        <div class="flex justify-end mb-4">
            <a href="{{ route('export-reporting', request()->query()) }}">
                <x-filament::button color="success" size="sm" icon="heroicon-m-arrow-down-tray">
                    Exporter (Excel)
                </x-filament::button>
            </a>
        </div>

        {{-- Filtres --}}
        <form method="GET" class="flex flex-wrap gap-4 items-center mb-4" id="filters-form">
            {{-- Année --}}
            <select name="annee"
                class="w-40 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 rounded-md text-sm text-black dark:text-white">
                <option value="">Année</option>
                @foreach (range(now()->year, now()->year - 3) as $year)
                <option value="{{ $year }}" @selected(request('annee')==$year)>{{ $year }}</option>
                @endforeach
            </select>

            {{-- Mois --}}
            <select name="mois"
                class="w-40 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 rounded-md text-sm text-black dark:text-white">
                <option value="">Mois</option>
                @foreach ([
                '01' => 'Janvier',
                '02' => 'Février',
                '03' => 'Mars',
                '04' => 'Avril',
                '05' => 'Mai',
                '06' => 'Juin',
                '07' => 'Juillet',
                '08' => 'Août',
                '09' => 'Septembre',
                '10' => 'Octobre',
                '11' => 'Novembre',
                '12' => 'Décembre',
                ] as $key => $month)
                <option value="{{ $key }}" @selected(request('mois')==$key)>{{ $month }}</option>
                @endforeach
            </select>

            {{-- Recherche --}}
            <input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}"
                class="px-4 py-2 w-64 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-black dark:text-white rounded-md text-sm" />

            <x-filament::button color="primary" size="sm" type="submit">Afficher</x-filament::button>
        </form>

        {{-- === Barre Clôture / Déclôture (séparée des filtres) === --}}
        @php
        $peutClore = auth()->user()->hasAnyRole(['Admin','Comptabilité']);
        $anneeSel = request('annee');
        $moisSel = request('mois');
        $isLocked = $anneeSel && $moisSel
        ? \App\Models\MonthLock::where(['societe'=>'WINXO','annee'=>$anneeSel,'mois'=>$moisSel])->exists()
        : false;
        @endphp

        @if($peutClore)
        <div class="flex justify-end mb-4 gap-2">
            @if($anneeSel && $moisSel)
            @if(!$isLocked)
            <form method="POST" action="{{ route('close-month') }}" class="inline-block">
                @csrf
                <input type="hidden" name="annee" value="{{ $anneeSel }}">
                <input type="hidden" name="mois" value="{{ $moisSel }}">
                <x-filament::button type="submit" color="danger" size="sm" icon="heroicon-m-lock-closed">
                    Clôturer ({{ $anneeSel }}-{{ $moisSel }})
                </x-filament::button>
            </form>
            @else
            <form method="POST" action="{{ route('open-month') }}" class="inline-block">
                @csrf
                @method('DELETE')
                <input type="hidden" name="annee" value="{{ $anneeSel }}">
                <input type="hidden" name="mois" value="{{ $moisSel }}">
                <x-filament::button type="submit" color="warning" size="sm" icon="heroicon-m-lock-open">
                    Déclôturer ({{ $anneeSel }}-{{ $moisSel }})
                </x-filament::button>
            </form>
            @endif
            @else
            <x-filament::button color="gray" size="sm" icon="heroicon-m-lock-closed" disabled>
                Clôturer (sélectionnez Année & Mois)
            </x-filament::button>
            @endif
        </div>
        @endif
        {{-- === Fin Clôture / Déclôture === --}}

        {{-- Tableau --}}
        <div class="overflow-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                @php
                $sortColumn = request('sort');
                $sortDirection= request('direction', 'asc');
                @endphp

                <thead>
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
                        <th class="px-4 py-2 whitespace-nowrap bg-blue-600 text-black dark:text-white">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => $key, 'direction' => $newDirection]) }}"
                                class="flex items-center gap-1 hover:underline">
                                {{ $label }} <span class="text-xs">{{ $icon }}</span>
                            </a>
                        </th>
                        @endforeach

                        {{-- Colonnes Quantité (non triables) --}}
                        @foreach ([
                        '3kg', '6kg', '9kg', '12kg', '35kg', '40kg',
                        '3kg VR', '6kg VR', '9kg VR', '12kg VR', '35kg VR', '40kg VR',
                        ] as $qty)
                        <th class="px-4 py-2 whitespace-nowrap bg-blue-600 text-black dark:text-white text-center">
                            {{ $qty }}
                        </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-700 text-black dark:text-white">
                    @forelse ($this->records as $record)
                    <tr class="focus-within:bg-blue-100 dark:focus-within:bg-gray-800 transition-colors">
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
                        <td class="px-4 py-2 text-center">{{ $record->qte_charge_3kg }}</td>
                        <td class="px-4 py-2 text-center">{{ $record->qte_charge_6kg }}</td>
                        <td class="px-4 py-2 text-center">{{ $record->qte_charge_9kg }}</td>
                        <td class="px-4 py-2 text-center">{{ $record->qte_charge_12kg }}</td>
                        <td class="px-4 py-2 text-center">{{ $record->qte_charge_35kg }}</td>
                        <td class="px-4 py-2 text-center">{{ $record->qte_charge_40kg }}</td>
                        <td class="px-4 py-2 text-center">{{ $record->qte_vendu_3kg }}</td>
                        <td class="px-4 py-2 text-center">{{ $record->qte_vendu_6kg }}</td>
                        <td class="px-4 py-2 text-center">{{ $record->qte_vendu_9kg }}</td>
                        <td class="px-4 py-2 text-center">{{ $record->qte_vendu_12kg }}</td>
                        <td class="px-4 py-2 text-center">{{ $record->qte_vendu_35kg }}</td>
                        <td class="px-4 py-2 text-center">{{ $record->qte_vendu_40kg }}</td>
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

        {{-- Pagination --}}
        <div class="mt-6 flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4 text-sm text-gray-800 dark:text-gray-200">
            <div class="flex-1">
                Affichage de <b>{{ $this->records->firstItem() }}</b> à <b>{{ $this->records->lastItem() }}</b> sur
                {{ $this->records->total() }}
            </div>

            <div class="flex flex-wrap items-center justify-end gap-3">
                {{-- Par page --}}
                <form method="GET" class="flex items-center gap-2">
                    <label for="perPage">Par page :</label>
                    <select name="perPage" id="perPage"
                        class="appearance-none border rounded-md px-3 py-1 pr-6 bg-white dark:bg-gray-900 border-gray-300 dark:border-gray-600 text-black dark:text-white text-sm"
                        onchange="this.form.submit()"
                        style="background-image: url('data:image/svg+xml;utf8,<svg fill=\'%23ffffff\' xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' width=\'14\' height=\'14\'><path d=\'M7 10l5 5 5-5z\'/></svg>');
                               background-repeat: no-repeat;
                               background-position: right 0.5rem center;
                               background-size: 0.8rem;">
                        @foreach ([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" @selected(request('perPage', 10)==$size)>{{ $size }}</option>
                        @endforeach
                        @foreach (request()->except('perPage', 'page') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                    </select>
                </form>

                {{-- Pagination links --}}
                <div>
                    <nav class="flex items-center space-x-1">
                        @if ($this->records->onFirstPage())
                        <span class="px-2 py-1 text-gray-400">&laquo;</span>
                        @else
                        <a href="{{ $this->records->previousPageUrl() }}"
                            class="px-2 py-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700">&laquo;</a>
                        @endif

                        @foreach ($this->records->getUrlRange(1, $this->records->lastPage()) as $page => $url)
                        @if ($page == $this->records->currentPage())
                        <span class="px-3 py-1 rounded bg-primary-600 text-white">{{ $page }}</span>
                        @else
                        <a href="{{ $url }}"
                            class="px-3 py-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700">{{ $page }}</a>
                        @endif
                        @endforeach

                        @if ($this->records->hasMorePages())
                        <a href="{{ $this->records->nextPageUrl() }}"
                            class="px-2 py-1 rounded hover:bg-gray-2 00 dark:hover:bg-gray-700">&raquo;</a>
                        @else
                        <span class="px-2 py-1 text-gray-400">&raquo;</span>
                        @endif
                    </nav>
                </div>
            </div>
        </div>

        {{-- Style custom sans hover --}}
        <style>
            tr:focus,
            tr:focus-visible,
            tr:focus-within {
                outline: none !important;
            }

            :root:not(.dark) tr:focus-within td {
                background-color: rgba(59, 130, 246, 0.07) !important;
                color: inherit !important;
            }

            .dark tr:focus-within td {
                background-color: rgba(255, 255, 255, 0.06) !important;
                color: inherit !important;
            }
        </style>

    </x-filament::card>
</x-filament::page>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[name="search"]');
        const form = document.getElementById('filters-form');
        let typingTimer;
        const delay = 500;

        searchInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                form.submit();
            }, delay);
        });

        searchInput.addEventListener('keydown', function() {
            clearTimeout(typingTimer);
        });
    });
</script>