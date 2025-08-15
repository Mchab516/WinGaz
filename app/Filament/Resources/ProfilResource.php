<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProfilResource\Pages;
use App\Models\Profil;
use App\Support\ProfilGate as PG;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProfilResource extends Resource
{
    protected static ?string $model = Profil::class;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'Menu Administrateur';
    protected static ?int $navigationSort = 91;

    /** Menu visible pour l'admin (profil_id=1) ou si le flag UI est coché */
    public static function shouldRegisterNavigation(): bool
    {
        $user = Filament::auth()->user();

        return (($user && (int) $user->profil_id === 1) || PG::can('can_admin_menu'));
    }

    /** Accès direct autorisé pour l'admin (profil_id=1) ou si le flag UI est coché */
    public static function canAccess(): bool
    {
        $user = Filament::auth()->user();

        return (($user && (int) $user->profil_id === 1) || PG::can('can_admin_menu'));
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('libelle')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('code_sap')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('site')
                ->maxLength(255)
                ->default(null),

            Forms\Components\TextInput::make('nature')
                ->maxLength(255)
                ->default(null),

            Forms\Components\TextInput::make('identifiant')
                ->required()
                ->maxLength(255),

            // ---- Permissions pilotées depuis l’UI ----
            Forms\Components\Section::make('Autorisations d’accès')
                ->columns(2)
                ->schema([
                    Forms\Components\Toggle::make('can_clients')->label('Gestion des clients'),
                    Forms\Components\Toggle::make('can_centres')->label('Centres emplisseurs'),
                    Forms\Components\Toggle::make('can_chargements_ventes')->label('Chargements / Ventes'),
                    Forms\Components\Toggle::make('can_reporting')->label('Reporting mensuel'),
                    Forms\Components\Toggle::make('can_admin_menu')->label('Menu Administrateur (Utilisateurs / Profils)'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('libelle')->searchable(),
                Tables\Columns\TextColumn::make('code_sap')->searchable(),
                Tables\Columns\TextColumn::make('site')->searchable(),
                Tables\Columns\TextColumn::make('nature')->searchable(),
                Tables\Columns\TextColumn::make('identifiant')->searchable(),

                // Indicateurs visuels des droits
                Tables\Columns\IconColumn::make('can_clients')->boolean()->label('Clients'),
                Tables\Columns\IconColumn::make('can_centres')->boolean()->label('Centres'),
                Tables\Columns\IconColumn::make('can_chargements_ventes')->boolean()->label('Ch/Ventes'),
                Tables\Columns\IconColumn::make('can_reporting')->boolean()->label('Reporting'),
                Tables\Columns\IconColumn::make('can_admin_menu')->boolean()->label('Admin'),

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
            'index'  => Pages\ListProfils::route('/'),
            'create' => Pages\CreateProfil::route('/create'),
            'edit'   => Pages\EditProfil::route('/{record}/edit'),
        ];
    }
}
