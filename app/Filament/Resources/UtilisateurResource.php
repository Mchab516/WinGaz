<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UtilisateurResource\Pages;
use App\Models\Utilisateur;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class UtilisateurResource extends Resource
{
    protected static ?string $model = Utilisateur::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    /**
     * Affiche le menu uniquement pour le profil administrateur
     */
    public static function getNavigationItems(): array
    {
        if (Auth::check() && Auth::user()->profil_id === 1) {
            return parent::getNavigationItems();
        }

        return [];
    }


    /**
     * Groupe du menu dans le panneau latéral
     */
    public static function getNavigationGroup(): ?string
    {
        return 'Menu Administrateur';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nom')->required()->maxLength(255),
            Forms\Components\TextInput::make('prenom')->required()->maxLength(255),
            Forms\Components\TextInput::make('email')->email()->required()->maxLength(255),
            Forms\Components\TextInput::make('password')
                ->password()
                ->required()
                ->maxLength(255)
                ->dehydrated(fn($state) => filled($state))
                ->hiddenOn('edit'),
            Forms\Components\Select::make('profil_id')
                ->relationship('profil', 'id')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom')->searchable(),
                Tables\Columns\TextColumn::make('prenom')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('profil.id')->label('Profil ID')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Créé le')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->label('Modifié le')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUtilisateurs::route('/'),
            'create' => Pages\CreateUtilisateur::route('/create'),
            'edit' => Pages\EditUtilisateur::route('/{record}/edit'),
        ];
    }
}
