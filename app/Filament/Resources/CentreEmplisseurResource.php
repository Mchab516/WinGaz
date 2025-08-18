<?php

namespace App\Filament\Resources;

use App\Support\ProfilGate as PG;
use App\Filament\Resources\CentreEmplisseurResource\Pages;
use App\Models\CentreEmplisseur;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

// ✅ corbeille & actions
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;

class CentreEmplisseurResource extends Resource
{
    protected static ?string $model = CentreEmplisseur::class;

    protected static ?string $navigationLabel = 'Gestion des centres emplisseurs';
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?int $navigationSort = 2;

    /** Afficher le menu seulement si le profil a le droit */
    public static function shouldRegisterNavigation(): bool
    {
        return PG::can('can_centres');
    }

    /** Autoriser l’accès direct via URL selon le droit */
    public static function canAccess(): bool
    {
        return PG::can('can_centres');
    }

    /** ⚡ Charger toutes les relations pour éviter les N+1 queries */
    public static function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->with(['ville', 'createur', 'deletedBy']);
    }

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
                ->options(fn() => Cache::remember('ville_options', 600, function () {
                    return \App\Models\Ville::orderBy('nom')->pluck('nom', 'id')->toArray();
                }))
                ->searchable()
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code_sap')->sortable(),
                Tables\Columns\TextColumn::make('nom')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('adresse'),
                Tables\Columns\TextColumn::make('ville.nom')->label('Ville'),

                Tables\Columns\TextColumn::make('createur.email')
                    ->label('Créé par')->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Modifié le')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),

                // ✅ traçabilité corbeille
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Supprimé le')->dateTime()->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('deletedBy.email')
                    ->label('Supprimé par')->toggleable(isToggledHiddenByDefault: true),
            ])

            // ✅ filtre Corbeille
            ->filters([
                TrashedFilter::make(),
            ])

            // ✅ actions
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(), // soft delete si SoftDeletes sur le modèle
                RestoreAction::make()
                    ->visible(fn($record) => method_exists($record, 'trashed') && $record->trashed()),
                ForceDeleteAction::make()
                    ->visible(fn() => PG::can('can_admin_menu')), // admin uniquement
            ])

            // ✅ actions groupées
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make()
                        ->visible(fn() => PG::can('can_admin_menu')),
                ]),
            ])

            // ✅ Pagination optimisée
            ->paginated([10, 25, 50]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCentreEmplisseurs::route('/'),
            'create' => Pages\CreateCentreEmplisseur::route('/create'),
            'edit'   => Pages\EditCentreEmplisseur::route('/{record}/edit'),
        ];
    }
}
