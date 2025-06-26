<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CentreEmplisseurResource\Pages;
use App\Models\CentreEmplisseur;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CentreEmplisseurResource extends Resource
{
    protected static ?string $model = CentreEmplisseur::class;

    protected static ?string $navigationLabel = 'Gestion des centres emplisseurs';

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    //protected static ?string $navigationGroup = 'Menu Administrateur';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\TextInput::make('code_sap')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('nom')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('adresse')
                ->maxLength(255),

            Forms\Components\Select::make('ville_id')
                ->label('Ville')
                ->options(fn() => \App\Models\Ville::pluck('nom', 'id')->toArray())
                ->searchable()
                ->required(),


        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([

            Tables\Columns\TextColumn::make('code_sap')
                ->sortable(),

            Tables\Columns\TextColumn::make('nom')
                ->searchable()
                ->sortable(),


            Tables\Columns\TextColumn::make('adresse'),

            Tables\Columns\TextColumn::make('ville.nom')
                ->label('Ville'),

            Tables\Columns\TextColumn::make('createur.email')->label('Créé par')->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('created_at')->label('Créé le')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')->label('Modifié le')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
        ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
        ;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCentreEmplisseurs::route('/'),
            'create' => Pages\CreateCentreEmplisseur::route('/create'),
            'edit' => Pages\EditCentreEmplisseur::route('/{record}/edit'),
        ];
    }
}
