<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdditionalFeatureResource\Pages;
use App\Models\AdditionalFeature;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AfterSalesServiceResource\RelationManagers\UnitsRelationManager;
use Filament\Forms\Components\Grid;

class AdditionalFeatureResource extends Resource
{
    protected static ?string $model = AdditionalFeature::class;

    protected static ?string $navigationIcon = 'heroicon-o-plus-circle';
    protected static ?string $navigationGroup = 'Properties Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required(),
                        Forms\Components\TextInput::make('description'),
                    ])
                    ->columns(2),
                Forms\Components\FileUpload::make('icon')
                    ->directory('additional-features')
                    ->disk('public')
                    ->image(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('icon'),
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('description'),
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
            UnitsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdditionalFeatures::route('/'),
            // 'create' => Pages\CreateAdditionalFeature::route('/create'),
            // 'edit' => Pages\EditAdditionalFeature::route('/{record}/edit'),
        ];
    }
}
