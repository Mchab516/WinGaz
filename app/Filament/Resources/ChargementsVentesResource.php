<?php

namespace App\Filament\Resources;

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
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\Select::make('annee')
                        ->label('Année')
                        ->options([
                            '2023' => '2023',
                            '2024' => '2024',
                            '2025' => '2025',
                        ])
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
                        ->relationship('centreEmplisseur', 'nom')
                        ->searchable()
                        ->required(),

                    Forms\Components\Select::make('prefecture_id')
                        ->relationship('prefecture', 'nom')
                        ->searchable()
                        ->reactive()
                        ->required(),

                    Forms\Components\Select::make('commune_id')
                        ->label('Commune')
                        ->options(function (callable $get) {
                            $prefectureId = $get('prefecture_id');
                            if (!$prefectureId) return [];

                            return \App\Models\Commune::where('prefecture_id', $prefectureId)
                                ->pluck('nom', 'id');
                        })
                        ->searchable()
                        ->required(),
                ]),
            ]),

            Forms\Components\Section::make('Quantité chargé :')->schema([
                Forms\Components\Grid::make(6)->schema([
                    Forms\Components\TextInput::make('qte_charge_3kg')->label('3 Kg')->numeric(),
                    Forms\Components\TextInput::make('qte_charge_6kg')->label('6 Kg')->numeric(),
                    Forms\Components\TextInput::make('qte_charge_9kg')->label('9 Kg')->numeric(),
                    Forms\Components\TextInput::make('qte_charge_12kg')->label('12 Kg')->numeric(),
                    Forms\Components\TextInput::make('qte_charge_35kg')->label('35 Kg')->numeric(),
                    Forms\Components\TextInput::make('qte_charge_40kg')->label('40 Kg')->numeric(),
                ]),
            ]),

            Forms\Components\Section::make('Quantité vendu :')->schema([
                Forms\Components\Grid::make(6)->schema([
                    Forms\Components\TextInput::make('qte_vendu_3kg')->label('3 Kg')->numeric(),
                    Forms\Components\TextInput::make('qte_vendu_6kg')->label('6 Kg')->numeric(),
                    Forms\Components\TextInput::make('qte_vendu_9kg')->label('9 Kg')->numeric(),
                    Forms\Components\TextInput::make('qte_vendu_12kg')->label('12 Kg')->numeric(),
                    Forms\Components\TextInput::make('qte_vendu_35kg')->label('35 Kg')->numeric(),
                    Forms\Components\TextInput::make('qte_vendu_40kg')->label('40 Kg')->numeric(),
                ]),
            ]),
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                Tables\Columns\TextColumn::make('updated_at')->label('Modifié le')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
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
