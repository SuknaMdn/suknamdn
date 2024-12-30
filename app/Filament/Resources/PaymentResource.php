<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Finances';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Section::make('Payment Details')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('amount')
                        ->numeric()
                        ->required()
                        ->prefix('SAR')
                        ->columnSpan(1),

                    Forms\Components\Select::make('currency')
                        ->options(['SAR' => 'Saudi Riyal'])
                        ->default('SAR')
                        ->columnSpan(1),

                    Forms\Components\Select::make('status')
                        ->options([
                            'initiated' => 'Initiated',
                            'pending' => 'Pending',
                            'paid' => 'Paid',
                            'failed' => 'Failed',
                            'canceled' => 'Canceled'
                        ])
                        ->default('pending')
                        ->columnSpan(1),

                    Forms\Components\TextInput::make('payment_method')
                        ->columnSpan(1),

                    Forms\Components\TextInput::make('transaction_id')
                        ->columnSpan(2),
                ]),

            Forms\Components\Section::make('Additional Information')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('user_id')
                        ->relationship('user', 'username')
                        ->searchable()
                        ->preload()
                        ->columnSpan(1),

                    Forms\Components\Select::make('payable_type')
                        ->label('Payable Type')
                        ->options([
                            'App\Models\Unit' => 'Unit',
                        ])
                        ->required()
                        ->reactive(),

                    Forms\Components\Select::make('payable_id')
                        ->label('Payable ID')
                        ->options(function (callable $get) {
                            if ($get('payable_type') === 'App\Models\Unit') {
                                return \App\Models\Unit::all()->pluck('title', 'id');
                            }

                            return [];
                        })
                        ->required()
                        ->searchable()
                        ->preload(),

                    Forms\Components\TextInput::make('payment_type')
                        ->columnSpan(1),

                    Forms\Components\TextInput::make('invoice_number')
                        ->unique(ignorable: fn ($record) => $record)
                        ->columnSpan(1),

                    Forms\Components\DatePicker::make('paid_at')
                        ->native(true)
                        ->columnSpan(1),

                    Forms\Components\DatePicker::make('due_date')
                        ->native(true)
                        ->columnSpan(1),

                    Forms\Components\TextInput::make('redirect_url')
                        ->columnSpan(2),

                    Forms\Components\Textarea::make('description')
                        ->columnSpan(2),
                ]),

            Forms\Components\KeyValue::make('metadata')
                ->label('Additional Metadata')
                ->addable(false)
                ->deletable(false)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('transaction_id')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('payable_name')
                ->label('Paid For')
                ->sortable(),

            Tables\Columns\TextColumn::make('amount')
                ->money('SAR')
                ->sortable(),

            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'initiated' => 'warning',
                    'pending' => 'warning',
                    'paid' => 'success',
                    'failed' => 'danger',
                    'canceled' => 'gray',
                }),

            Tables\Columns\TextColumn::make('payment_type')
                ->searchable(),

            Tables\Columns\TextColumn::make('user.username')
                ->label('Paid By')
                ->searchable(),

            Tables\Columns\TextColumn::make('created_at')
                ->dateTime('Y M d H:i A')
                ->sortable(),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'initiated' => 'Initiated',
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'failed' => 'Failed',
                    'canceled' => 'Canceled'
                ]),

            Tables\Filters\SelectFilter::make('payment_type')
                ->options([
                    'subscription' => 'Subscription',
                    'property_deposit' => 'Property Deposit',
                ])
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
