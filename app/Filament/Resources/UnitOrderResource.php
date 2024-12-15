<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnitOrderResource\Pages;
use App\Filament\Resources\UnitOrderResource\RelationManagers;
use App\Models\UnitOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\ToggleButtons;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\DateFilter;
use Illuminate\Support\Facades\Date;

class UnitOrderResource extends Resource
{
    protected static ?string $model = UnitOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Order Details')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->columns(2)
                            ->schema([
                                Forms\Components\Select::make('unit_id')
                                    ->relationship('unit', 'title')
                                    ->native(false)
                                    ->required(),
                                Forms\Components\Select::make('user_id')
                                    ->relationship('user', 'username')  // Assumes you have a User model
                                    ->native(false)
                                    ->required(),
                                Forms\Components\Select::make('payment_plan')
                                    ->native(false)
                                    ->options([
                                        'cash' => 'Cash',
                                        'bank_transfer' => 'Bank Transfer',
                                    ])
                                    ->required(),
                                Forms\Components\Select::make('payment_method')
                                    ->native(false)
                                    ->options([
                                        'cash' => 'Cash',
                                        'installments' => 'Installments',
                                    ])
                                    ->required(),
                                Forms\Components\Select::make('payment_status')
                                    ->native(false)
                                    ->options([
                                        'pending' => 'Pending',
                                        'paid' => 'Paid',
                                        'canceled' => 'Canceled',
                                    ])
                                    ->required(),
                                Forms\Components\Toggle::make('tax_exemption_status')
                                    ->required(),
                                Forms\Components\Textarea::make('note')
                                    ->required(),
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'processing' => 'Processing',
                                        'confirmed' => 'Confirmed',
                                        'canceled' => 'Canceled',
                                    ])
                                    ->default('pending')
                                    ->required(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('unit.title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.username')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'confirmed' => 'success',
                        'canceled' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('payment_plan'),
                Tables\Columns\TextColumn::make('payment_method'),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'canceled' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('tax_exemption_status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '1' => 'Yes',
                        '0' => 'No',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'danger',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->relationship('user', 'username'),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'confirmed' => 'Confirmed',
                        'canceled' => 'Canceled',
                    ]),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'canceled' => 'Canceled',
                    ]),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->options([
                        'cash' => 'Cash',
                        'installments' => 'Installments',
                    ]),
                    Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('From Date'),
                        Forms\Components\DatePicker::make('to')
                            ->label('To Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['to'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from'] ?? null) {
                            $indicators['from'] = 'From: ' . $data['from'];
                        }
                        if ($data['to'] ?? null) {
                            $indicators['to'] = 'To: ' . $data['to'];
                        }
                        return $indicators;
                    }),
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

    public static function getNavigationBadge(): ?string
    {
        return UnitOrder::where('status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): string
    {
        return static::getNavigationBadge() > 10 ? 'danger' : 'warning';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUnitOrders::route('/'),
            'create' => Pages\CreateUnitOrder::route('/create'),
            'edit' => Pages\EditUnitOrder::route('/{record}/edit'),
        ];
    }
}
