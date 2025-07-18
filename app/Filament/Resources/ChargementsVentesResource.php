<?php

namespace App\Filament\Resources;

use Illuminate\Support\Facades\Auth;

use Filament\Forms\Components\Hidden;
use App\Filament\Resources\ChargementsVentesResource\Pages;
use App\Filament\Resources\ChargementsVentesResource\RelationManagers;
use App\Models\ChargementsVentes;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChargementsVentesResource extends Resource
{
    protected static ?string $navigationLabel = 'Gestion des chargements/ventes';
    protected static ?string $slug = 'chargements-ventes'; // important pour la route


    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Intitulé :')->schema([
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
                        ->relationship('client', 'nom')
                        ->searchable()
                        ->required(),

                    Forms\Components\Select::make('centre_emplisseur_id')
                        ->label('Centre emplisseur')
                        ->relationship('centreEmplisseur', 'nom')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\Select::make('region_id')
                        ->label('Région')
                        ->options(\App\Models\Region::pluck('nom', 'id'))
                        ->reactive()
                        ->afterStateUpdated(function (callable $set) {
                            $set('id_prefectures', null); // Reset
                            $set('commune_id', null);    // Reset
                        }),

                    Forms\Components\Select::make('id_prefectures')
                        ->label('Préfecture')
                        ->options(function (callable $get) {
                            $regionId = $get('region_id');
                            if (!$regionId) return [];

                            return \App\Models\Prefecture::where('id_region', $regionId)->pluck('nom', 'id');
                        })
                        ->searchable()
                        ->reactive()
                        ->required(),

                    Forms\Components\Select::make('commune_id')
                        ->label('Commune')
                        ->options(function (callable $get) {
                            $prefectureId = $get('id_prefectures');
                            if (!$prefectureId) return [];

                            return \App\Models\Commune::where('id_prefectures', $prefectureId)->pluck('nom', 'id');
                        })
                        ->searchable()
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

                    Forms\Components\Hidden::make('created_by')
                        ->default(fn() => Auth::id())
                        ->dehydrated(fn($record) => $record === null),

                    Forms\Components\Hidden::make('updated_by')
                        ->default(fn() => Auth::id())
                        ->dehydrated(true),


                ]),

            ]),

        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('societe')
                    ->label('Société')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('annee')->label('Année')->sortable(),
                Tables\Columns\TextColumn::make('mois')->label('Mois')->sortable(),
                Tables\Columns\TextColumn::make('centreEmplisseur.nom')->label('Centre emplisseur')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('centreEmplisseur.code_sap')->label('Code client')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('client.categorie')->label('Catégorie client')->sortable(),
                Tables\Columns\TextColumn::make('prefecture.nom')->label('Préfecture')->sortable(),
                Tables\Columns\TextColumn::make('commune.code')->label('Code commune')->sortable(),
                Tables\Columns\TextColumn::make('commune.nom')->label('Nom commune')->sortable(),

                // Quantités chargées
                Tables\Columns\TextColumn::make('qte_charge_3kg')->label('3 kg'),
                Tables\Columns\TextColumn::make('qte_charge_6kg')->label('6 kg'),
                Tables\Columns\TextColumn::make('qte_charge_9kg')->label('9 kg'),
                Tables\Columns\TextColumn::make('qte_charge_12kg')->label('12 kg'),
                Tables\Columns\TextColumn::make('qte_charge_35kg')->label('35 kg'),
                Tables\Columns\TextColumn::make('qte_charge_40kg')->label('40 kg'),

                // Quantités vendues
                Tables\Columns\TextColumn::make('qte_vendu_3kg')->label('3 kg V.'),
                Tables\Columns\TextColumn::make('qte_vendu_6kg')->label('6 kg V.'),
                Tables\Columns\TextColumn::make('qte_vendu_9kg')->label('9 kg V.'),
                Tables\Columns\TextColumn::make('qte_vendu_12kg')->label('12 kg V.'),
                Tables\Columns\TextColumn::make('qte_vendu_35kg')->label('35 kg V.'),
                Tables\Columns\TextColumn::make('qte_vendu_40kg')->label('40 kg V.'),
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
                Tables\Actions\EditAction::make()->label('Modifier'),
                Tables\Actions\DeleteAction::make()->label('Supprimer'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChargementsVentes::route('/'),
            'create' => Pages\CreateChargementsVentes::route('/create'),
            'edit' => Pages\EditChargementsVentes::route('/{record}/edit'),
        ];
    }
}
