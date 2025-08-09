<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChargementsVentesResource\Pages;
use App\Models\ChargementsVentes;
use App\Models\Client;
use App\Models\CentreEmplisseur;
use App\Models\Region;
use App\Models\Prefecture;
use App\Models\Commune;
use App\Models\User; // ✅ pour typer $user
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ChargementsVentesResource extends Resource
{
    protected static ?string $model = ChargementsVentes::class;

    protected static ?string $navigationLabel = 'Gestion des chargements/ventes';
    protected static ?string $slug = 'chargements-ventes';
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Données générales')->schema([
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
                            collect(range(now()->year, now()->year + 4))
                                ->mapWithKeys(fn($year) => [$year => $year])
                                ->toArray()
                        )
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
                        ->options(Region::pluck('nom', 'id'))
                        ->reactive()
                        ->afterStateUpdated(fn(callable $set) => [
                            $set('prefecture_id', null),
                            $set('commune_id', null),
                        ])
                        ->dehydrated(false),

                    Forms\Components\Select::make('prefecture_id')
                        ->label('Préfecture')
                        ->options(function (callable $get) {
                            $regionId = $get('region_id');
                            if (!$regionId) {
                                return [];
                            }
                            return Prefecture::where('id_region', $regionId)->pluck('nom', 'id');
                        })
                        ->reactive()
                        ->required(),

                    Forms\Components\Select::make('commune_id')
                        ->label('Commune')
                        ->options(function (callable $get) {
                            $prefectureId = $get('prefecture_id');
                            if (!$prefectureId) {
                                return [];
                            }
                            return Commune::where('id_prefectures', $prefectureId)->pluck('nom', 'id');
                        })
                        ->reactive()
                        ->required(),
                ]),
            ]),

            Forms\Components\Section::make('Quantité chargée :')->schema([
                Forms\Components\Grid::make(6)->schema([
                    Forms\Components\TextInput::make('qte_charge_3kg')->label('3 Kg')->numeric()->required(),
                    Forms\Components\TextInput::make('qte_charge_6kg')->label('6 Kg')->numeric()->required(),
                    Forms\Components\TextInput::make('qte_charge_9kg')->label('9 Kg')->numeric()->required(),
                    Forms\Components\TextInput::make('qte_charge_12kg')->label('12 Kg')->numeric()->required(),
                    Forms\Components\TextInput::make('qte_charge_35kg')->label('35 Kg')->numeric()->required(),
                    Forms\Components\TextInput::make('qte_charge_40kg')->label('40 Kg')->numeric()->required(),
                ]),
            ]),

            Forms\Components\Section::make('Quantité vendue :')->schema([
                Forms\Components\Grid::make(6)->schema([
                    Forms\Components\TextInput::make('qte_vendu_3kg')->label('3 Kg')->numeric()->required(),
                    Forms\Components\TextInput::make('qte_vendu_6kg')->label('6 Kg')->numeric()->required(),
                    Forms\Components\TextInput::make('qte_vendu_9kg')->label('9 Kg')->numeric()->required(),
                    Forms\Components\TextInput::make('qte_vendu_12kg')->label('12 Kg')->numeric()->required(),
                    Forms\Components\TextInput::make('qte_vendu_35kg')->label('35 Kg')->numeric()->required(),
                    Forms\Components\TextInput::make('qte_vendu_40kg')->label('40 Kg')->numeric()->required(),
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
            ])
            ->searchable()
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Modifier')
                    ->visible(function (ChargementsVentes $record) {
                        /** @var User|null $user */
                        $user = Auth::user();

                        // ✅ Admin & Comptabilité: toujours autorisés
                        if ($user?->hasAnyRole(['Admin', 'Comptabilité'])) {
                            return true;
                        }

                        // ✅ Sinon: bloqué si mois clôturé
                        return !\App\Support\MonthLocker::isLocked('WINXO', $record->annee, $record->mois);
                    }),

                Tables\Actions\DeleteAction::make()
                    ->label('Supprimer')
                    ->visible(function (ChargementsVentes $record) {
                        /** @var User|null $user */
                        $user = Auth::user();

                        // ✅ Admin & Comptabilité: toujours autorisés
                        if ($user?->hasAnyRole(['Admin', 'Comptabilité'])) {
                            return true;
                        }

                        // ✅ Sinon: bloqué si mois clôturé
                        return !\App\Support\MonthLocker::isLocked('WINXO', $record->annee, $record->mois);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(function () {
                            /** @var User|null $user */
                            $user = Auth::user();
                            // ✅ seul Admin/Comptabilité voient le bulk delete
                            return $user?->hasAnyRole(['Admin', 'Comptabilité']) === true;
                        }),
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

    public static function canAccess(): bool
    {
        $user = Filament::auth()->user();

        // Si tu restes avec profil_id pour le panneau:
        return $user && in_array($user->profil_id, [1, 2, 3]);
    }
}
