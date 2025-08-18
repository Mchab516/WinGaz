<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChargementsVentesResource\Pages;
use App\Models\ChargementsVentes;
use App\Models\Region;
use App\Models\Prefecture;
use App\Models\Commune;
use App\Support\ProfilGate as PG;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

// Corbeille & actions
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;

class ChargementsVentesResource extends Resource
{
    protected static ?string $model = ChargementsVentes::class;

    protected static ?string $navigationLabel = 'Gestion des chargements/ventes';
    protected static ?string $slug = 'chargements-ventes';
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return PG::can('can_chargements_ventes');
    }

    public static function canAccess(): bool
    {
        return PG::can('can_chargements_ventes');
    }

    /** ⚡ Charger toutes les relations pour éviter N+1 queries */
    public static function getTableQuery(): Builder
    {
        return parent::getTableQuery()->with([
            'client',
            'centreEmplisseur',
            'region',
            'prefecture',
            'communeDecoupage',
            'commune',
            'createur',
            'modificateur',
            'deletedBy'
        ]);
    }

    public static function form(Form $form): Form
    {
        $currentYear  = now()->year;
        $currentMonth = str_pad((string) now()->month, 2, '0', STR_PAD_LEFT);

        return $form->schema([
            Forms\Components\Section::make('Données générales')
                ->schema([
                    Forms\Components\TextInput::make('societe')
                        ->label('Société')
                        ->default('WINXO')
                        ->disabled()
                        ->dehydrated(true)
                        ->required(),

                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\Select::make('annee')
                            ->label('Année')
                            ->options(
                                collect(range($currentYear - 1, $currentYear + 4))
                                    ->mapWithKeys(fn($y) => [$y => $y])
                                    ->toArray()
                            )
                            ->default($currentYear)
                            ->required(),

                        Forms\Components\Select::make('mois')
                            ->label('Mois')
                            ->options([
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
                            ])
                            ->default($currentMonth)
                            ->required(),

                        Forms\Components\Select::make('client_id')
                            ->label('Client')
                            ->relationship('client', 'nom')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('centre_emplisseur_id')
                            ->label('Centre emplisseur')
                            ->relationship('centreEmplisseur', 'nom')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('region_id')
                            ->label('Région')
                            ->options(fn() => Cache::remember(
                                'regions_options',
                                600,
                                fn() =>
                                Region::orderBy('nom')->pluck('nom', 'id')->toArray()
                            ))
                            ->reactive()
                            ->afterStateUpdated(function (callable $set) {
                                $set('prefecture_id', null);
                                $set('commune_id', null);
                            }),

                        Forms\Components\Select::make('prefecture_id')
                            ->label('Préfecture')
                            ->options(function (callable $get) {
                                $regionId = $get('region_id');
                                return $regionId
                                    ? Prefecture::where('id_region', $regionId)->pluck('nom', 'id')
                                    : [];
                            })
                            ->reactive()
                            ->required(),

                        Forms\Components\Select::make('commune_id')
                            ->label('Commune')
                            ->options(function (callable $get) {
                                $prefectureId = $get('prefecture_id');
                                return $prefectureId
                                    ? Commune::where('id_prefectures', $prefectureId)->pluck('nom', 'id')
                                    : [];
                            })
                            ->reactive()
                            ->required(),
                    ]),
                ]),

            Forms\Components\Section::make('Quantité chargée :')
                ->schema([
                    Forms\Components\Grid::make(6)->schema([
                        Forms\Components\TextInput::make('qte_charge_3kg')->label('3 Kg')->numeric()->default(0)->required(),
                        Forms\Components\TextInput::make('qte_charge_6kg')->label('6 Kg')->numeric()->default(0)->required(),
                        Forms\Components\TextInput::make('qte_charge_9kg')->label('9 Kg')->numeric()->default(0)->required(),
                        Forms\Components\TextInput::make('qte_charge_12kg')->label('12 Kg')->numeric()->default(0)->required(),
                        Forms\Components\TextInput::make('qte_charge_35kg')->label('35 Kg')->numeric()->default(0)->required(),
                        Forms\Components\TextInput::make('qte_charge_40kg')->label('40 Kg')->numeric()->default(0)->required(),
                    ]),
                ]),

            Forms\Components\Section::make('Quantité vendue :')
                ->schema([
                    Forms\Components\Grid::make(6)->schema([
                        Forms\Components\TextInput::make('qte_vendu_3kg')->label('3 Kg')->numeric()->default(0)->required(),
                        Forms\Components\TextInput::make('qte_vendu_6kg')->label('6 Kg')->numeric()->default(0)->required(),
                        Forms\Components\TextInput::make('qte_vendu_9kg')->label('9 Kg')->numeric()->default(0)->required(),
                        Forms\Components\TextInput::make('qte_vendu_12kg')->label('12 Kg')->numeric()->default(0)->required(),
                        Forms\Components\TextInput::make('qte_vendu_35kg')->label('35 Kg')->numeric()->default(0)->required(),
                        Forms\Components\TextInput::make('qte_vendu_40kg')->label('40 Kg')->numeric()->default(0)->required(),
                    ]),
                ]),

            Forms\Components\Hidden::make('created_by')->default(fn() => Auth::id()),
            Forms\Components\Hidden::make('updated_by')->default(fn() => Auth::id()),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('societe')->label('Société')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('annee')->label('Année')->sortable(),
                Tables\Columns\TextColumn::make('mois')->label('Mois')->sortable(),
                Tables\Columns\TextColumn::make('centreEmplisseur.nom')->label('Centre emplisseur')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('client.code_client')->label('Code client')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('client.categorie')->label('Catégorie client')->sortable(),
                Tables\Columns\TextColumn::make('region.id')->label('Code Region')->sortable(),
                Tables\Columns\TextColumn::make('region.nom')->label('Region')->sortable(),
                Tables\Columns\TextColumn::make('prefecture.nom')->label('Province_Prefecture')->sortable(),
                Tables\Columns\TextColumn::make('communeDecoupage.nom')->label('Commune Découpage')->sortable(),
                Tables\Columns\TextColumn::make('commune.nom')->label('Commune Déclarée')->sortable(),
                Tables\Columns\TextColumn::make('qte_charge_3kg')->label('3 kg'),
                Tables\Columns\TextColumn::make('qte_charge_6kg')->label('6 kg'),
                Tables\Columns\TextColumn::make('qte_charge_9kg')->label('9 kg'),
                Tables\Columns\TextColumn::make('qte_charge_12kg')->label('12 kg'),
                Tables\Columns\TextColumn::make('qte_charge_35kg')->label('35 kg'),
                Tables\Columns\TextColumn::make('qte_charge_40kg')->label('40 kg'),
                Tables\Columns\TextColumn::make('qte_vendu_3kg')->label('3 kg VR'),
                Tables\Columns\TextColumn::make('qte_vendu_6kg')->label('6 kg VR'),
                Tables\Columns\TextColumn::make('qte_vendu_9kg')->label('9 kg VR'),
                Tables\Columns\TextColumn::make('qte_vendu_12kg')->label('12 kg VR'),
                Tables\Columns\TextColumn::make('qte_vendu_35kg')->label('35 kg VR'),
                Tables\Columns\TextColumn::make('qte_vendu_40kg')->label('40 kg VR'),
                Tables\Columns\TextColumn::make('createur.email')->label('Créé par')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')->label('Créé le')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('modificateur.email')
                    ->label('Modifié par')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn($state, $record) => $record->created_at != $record->updated_at ? $state : null),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Modifié le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn($state, $record) => $record->created_at != $record->updated_at ? $state : null),
                Tables\Columns\TextColumn::make('deleted_at')->label('Supprimé le')->dateTime()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deletedBy.email')->label('Supprimé par')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->searchable()
            ->paginated([10, 25, 50]) // ✅ pagination optimisée
            ->actions([
                Tables\Actions\EditAction::make()->label('Modifier')
                    ->visible(function (ChargementsVentes $record) {
                        $u = Auth::user();
                        $isAdmin  = (int)($u?->profil_id) === 1;
                        $isCompta = strtolower($u?->profil?->identifiant ?? '') === 'compta';
                        if ($isAdmin || $isCompta) return true;
                        return !\App\Support\MonthLocker::isLocked('WINXO', $record->annee, $record->mois);
                    }),

                Tables\Actions\DeleteAction::make()->label('Supprimer')
                    ->visible(function (ChargementsVentes $record) {
                        $u = Auth::user();
                        $isAdmin  = (int)($u?->profil_id) === 1;
                        $isCompta = strtolower($u?->profil?->identifiant ?? '') === 'compta';
                        if ($isAdmin || $isCompta) return true;
                        return !\App\Support\MonthLocker::isLocked('WINXO', $record->annee, $record->mois);
                    }),

                RestoreAction::make()
                    ->visible(fn($record) => method_exists($record, 'trashed') && $record->trashed()),

                ForceDeleteAction::make()
                    ->visible(function () {
                        $u = Auth::user();
                        return (int)($u?->profil_id) === 1
                            || strtolower($u?->profil?->identifiant ?? '') === 'compta';
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn() => in_array(strtolower(Auth::user()?->profil?->identifiant ?? ''), ['compta']) || Auth::user()?->profil_id === 1),
                    RestoreBulkAction::make()
                        ->visible(fn() => in_array(strtolower(Auth::user()?->profil?->identifiant ?? ''), ['compta']) || Auth::user()?->profil_id === 1),
                    ForceDeleteBulkAction::make()
                        ->visible(fn() => in_array(strtolower(Auth::user()?->profil?->identifiant ?? ''), ['compta']) || Auth::user()?->profil_id === 1),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'   => Pages\ListChargementsVentes::route('/'),
            'create'  => Pages\CreateChargementsVentes::route('/create'),
            'edit'    => Pages\EditChargementsVentes::route('/{record}/edit'),
            'reporting-mensuel' => Pages\ReportingMensuel::route('/reporting-mensuel'),
        ];
    }
}
