<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NafathResource\Pages;
use App\Filament\Resources\NafathResource\RelationManagers;
use App\Models\Nafath;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\BelongsToSelect;
class NafathResource extends Resource
{
    protected static ?string $model = Nafath::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Settings';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\BelongsToSelect::make('user_id')
                    ->relationship('user', 'userName') // Defines the relationship and the field to display
                    ->label('User')
                    ->required(),
                Forms\Components\TextInput::make('national_id')
                    ->required()
                    ->label('National ID'),
                Forms\Components\TextInput::make('id_type')
                    ->required()
                    ->label('ID Type'),
                Forms\Components\TextInput::make('status')
                    ->label('Status'),
                Forms\Components\Textarea::make('response_data')
                    ->label('Response Data'),
                Forms\Components\DatePicker::make('verified_at')
                    ->label('Verified At'),
                Forms\Components\DatePicker::make('expires_at')
                    ->label('Expires At'),
                Forms\Components\TextInput::make('request_id')
                    ->required()
                    ->label('Request ID'),
                Forms\Components\TextInput::make('transaction_id')
                    ->label('Transaction ID'),
                Forms\Components\TextInput::make('random_number')
                    ->label('Random Number'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name') // Accesses the 'name' field via the 'user' relationship
                ->label('User')
                ->sortable()
                ->searchable(),

                Tables\Columns\TextColumn::make('national_id')
                    ->label('National ID'),
                Tables\Columns\TextColumn::make('id_type')
                    ->label('ID Type'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status'),
                Tables\Columns\TextColumn::make('verified_at')
                    ->label('Verified At'),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expires At'),
                Tables\Columns\TextColumn::make('request_id')
                    ->label('Request ID'),
                Tables\Columns\TextColumn::make('transaction_id')
                    ->label('Transaction ID'),
                Tables\Columns\TextColumn::make('random_number')
                    ->label('Random Number'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'PENDING' => 'Pending',
                        'VERIFIED' => 'Verified',
                        'REJECTED' => 'Rejected',
                        'CANCELED' => 'Canceled',
                        'EXPIRED' => 'Expired',
                        'COMPLETED' => 'Completed',
                    ])
                    ->label('Status'),
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
            'index' => Pages\ListNafaths::route('/'),
            // 'create' => Pages\CreateNafath::route('/create'),
            'edit' => Pages\EditNafath::route('/{record}/edit'),
        ];
    }
}
