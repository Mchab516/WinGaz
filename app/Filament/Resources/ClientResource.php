<?php

namespace App\Filament\Resources;

use App\Support\ProfilGate as PG;
use App\Filament\Resources\ClientResource\Pages;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

// ✅ imports pour corbeille & actions
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;
    protected static ?string $navigationLabel = 'Gestion des clients';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 1;

    /** Afficher le menu seulement si le profil a le droit */
    public static function shouldRegisterNavigation(): bool
    {
        return PG::can('can_clients');
    }

    /** Autoriser l’accès direct via URL selon le droit */
    public static function canAccess(): bool
    {
        return PG::can('can_clients');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('code_client')->required()->maxLength(255),
            Forms\Components\TextInput::make('nom')->required()->maxLength(255),

            Forms\Components\Select::make('categorie')
                ->label('Catégorie')
                ->required()
                ->options([
                    'Direct' => 'Direct',
                    'Indirect' => 'Indirect',
                ])
                ->native(false),

            Forms\Components\TextInput::make('adresse')->maxLength(255),

            Forms\Components\Select::make('ville_id')
                ->label('Ville')
                ->options(fn() => \App\Models\Ville::pluck('nom', 'id')->toArray())
                ->searchable()
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code_client')->searchable(),
                Tables\Columns\TextColumn::make('nom')->searchable(),
                Tables\Columns\TextColumn::make('categorie'),
                Tables\Columns\TextColumn::make('adresse'),
                Tables\Columns\TextColumn::make('ville.nom')->label('Ville'),
                Tables\Columns\TextColumn::make('createur.email')
                    ->label('Créé par')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Modifié le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // ✅ colonnes traçabilité corbeille
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Supprimé le')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deletedBy.email')
                    ->label('Supprimé par')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            // ✅ filtre corbeille (Sans / Avec / Uniquement supprimés)
            ->filters([
                TrashedFilter::make(),
            ])

            // ✅ actions ligne
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(), // soft delete automatique si SoftDeletes sur le modèle
                RestoreAction::make()
                    ->visible(fn($record) => method_exists($record, 'trashed') && $record->trashed()),
                ForceDeleteAction::make()
                    ->visible(fn() => PG::can('can_admin_menu')), // Admin uniquement
            ])

            // ✅ actions groupées
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make()
                        ->visible(fn() => PG::can('can_admin_menu')),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit'   => Pages\EditClient::route('/{record}/edit'),
        ];
    }

    // Traçabilité create / update
    public static function beforeCreate($data): array
    {
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();
        return $data;
    }

    public static function beforeSave($data): array
    {
        $data['updated_by'] = Auth::id();
        return $data;
    }
}
