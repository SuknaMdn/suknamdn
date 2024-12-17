<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FavoriteResource\Pages;
use App\Filament\Resources\FavoriteResource\RelationManagers;
use App\Models\Favorite;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;

class FavoriteResource extends Resource
{
    protected static ?string $model = Favorite::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\Select::make('user_id')
                //     ->relationship('user', 'username')
                //     ->required(),

                // Forms\Components\Select::make('favoritable_type')
                //     ->options([
                //         'App\Models\Unit' => 'Unit',
                //         'App\Models\Project' => 'Project',
                //     ])
                //     ->live() // This makes the field reactive
                //     ->required(),

                // Forms\Components\Select::make('favoritable_id')
                //     ->options(function (Get $get) {
                //         $modelClass = $get('favoritable_type');

                //         if (!$modelClass) {
                //             return [];
                //         }
                //         return $modelClass::query()->pluck('title', 'id');
                //     })
                //     ->searchable()
                //     ->required()
                //     ->hidden(fn (Get $get) => !$get('favoritable_type')), // Hide until a type is selected
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.username')
                    ->searchable()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('favoritable_type')
                //     ->badge()
                //     ->color(fn (string $state): string => match ($state) {
                //         'App\Models\Unit' => 'primary',
                //         'App\Models\Project' => 'success',
                //         default => 'gray',
                //     })
                //     ->formatStateUsing(fn (string $state): string => match ($state) {
                //         'App\Models\Unit' => 'Unit',
                //         'App\Models\Project' => 'Project',
                //         default => $state,
                //     }),

                Tables\Columns\TextColumn::make('favoritable.title')
                    ->label('Favorited Item')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListFavorites::route('/'),
            // 'create' => Pages\CreateFavorite::route('/create'),
            // 'edit' => Pages\EditFavorite::route('/{record}/edit'),
        ];
    }
}
