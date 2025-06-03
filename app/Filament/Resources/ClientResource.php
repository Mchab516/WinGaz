<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    //protected static ?string $navigationGroup = 'Menu Administrateur';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nom')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('code_client')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('categorie')
                ->label('Catégorie')
                ->required()
                ->options([
                    'Direct' => 'Direct',
                    'Indirect' => 'Indirect',
                ])
                ->native(false),

            Forms\Components\TextInput::make('adresse')
                ->maxLength(255),

            Forms\Components\Select::make('ville_id')
                ->relationship('ville', 'nom')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('nom')->searchable(),
            Tables\Columns\TextColumn::make('code_client')->searchable(),
            Tables\Columns\TextColumn::make('categorie'),
            Tables\Columns\TextColumn::make('adresse'),
            Tables\Columns\TextColumn::make('ville.nom')->label('Ville'),
            Tables\Columns\TextColumn::make('createur.email')->label('Créé par')->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('created_at')->label('Créé le')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')->label('Modifié le')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
        ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }

    // Ajout de la logique de traçabilité automatique
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
