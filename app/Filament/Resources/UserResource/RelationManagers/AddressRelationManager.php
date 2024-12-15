<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressRelationManager extends RelationManager
{
    protected static string $relationship = 'Address';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('city_id')
                    ->relationship('city', 'name')
                    ->required()
                    ->native(false)
                    ->searchable()
                    ->label('City')
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('state_id', null)),

                Forms\Components\Select::make('state_id')
                    ->relationship('state', 'name', fn ($query, $get) =>
                        $query->whereHas('city', fn ($query) =>
                            $query->where('id', $get('city_id'))
                        )
                    )
                    ->required()
                    ->native(false)
                    ->searchable()
                    ->label('State'),
                Forms\Components\TextInput::make('postal_code')
                    ->required()
                    ->label('Postal Code'),
                Forms\Components\TextInput::make('country')
                    ->required()
                    ->label('Country'),
                Forms\Components\Toggle::make('is_default')
                    ->label('Default Address'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('city.name')
                    ->label('City'),
                Tables\Columns\TextColumn::make('state.name')
                    ->label('State'),
                Tables\Columns\TextColumn::make('postal_code')
                    ->label('Postal Code'),
                Tables\Columns\TextColumn::make('country')
                    ->label('Country'),
                Tables\Columns\BooleanColumn::make('is_default')
                    ->label('Default Address'),
            ])
            ->filters([
                // Add any filters you need here
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
