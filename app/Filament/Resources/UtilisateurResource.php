<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UtilisateurResource\Pages;
use App\Models\Utilisateur;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UtilisateurResource extends Resource
{
    protected static ?string $model = Utilisateur::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    // Groupe + ordre dans la sidebar
    protected static ?string $navigationGroup = 'Menu Administrateur';
    protected static ?int $navigationSort = 90;

    /**
     * Affiche le menu uniquement pour l'admin (profil_id = 1).
     */
    public static function shouldRegisterNavigation(): bool
    {
        $user = Filament::auth()->user();

        return $user && (int) $user->profil_id === 1;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nom')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('prenom')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true), // ← email unique (édition incluse)

            // Création : password requis + confirmé
            // Édition : on peut laisser vide (rien n'est modifié)
            Forms\Components\TextInput::make('password')
                ->label('Mot de passe')
                ->password()
                ->required(fn(string $context) => $context === 'create')
                ->minLength(8)
                ->confirmed() // compare avec password_confirmation
                ->dehydrated(fn($state) => filled($state)) // n’envoie que si rempli
                ->visible(fn(string $context) => in_array($context, ['create', 'edit'])),

            Forms\Components\TextInput::make('password_confirmation')
                ->label('Confirmer le mot de passe')
                ->password()
                ->required(fn(string $context) => $context === 'create')
                ->dehydrated(false) // ne pas stocker
                ->visible(fn(string $context) => in_array($context, ['create', 'edit'])),

            Forms\Components\Select::make('profil_id')
                ->relationship('profil', 'libelle') // affiche le libellé
                ->searchable()
                ->preload()
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
                Tables\Columns\TextColumn::make('profil.libelle')->label('Profil')->sortable(),
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
