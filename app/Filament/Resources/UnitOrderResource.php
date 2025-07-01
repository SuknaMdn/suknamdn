<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstallmentsRelationManagerResource\RelationManagers\InstallmentsRelationManager;
use App\Filament\Resources\UnitOrderResource\Pages;
use App\Filament\Resources\UnitOrderResource\RelationManagers;
use App\Models\UnitOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\ToggleButtons;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\DateFilter;
use Illuminate\Support\Facades\Date;
use Filament\Actions\Action;

class UnitOrderResource extends Resource
{
    protected static ?string $model = UnitOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationLabel = 'طلبات الوحدات';
    
    protected static ?string $modelLabel = 'طلب وحدة';
    
    protected static ?string $pluralModelLabel = 'طلبات الوحدات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('تفاصيل الطلب')
                    ->schema([
                        Forms\Components\Section::make('المستندات التعاقدية')
                        ->schema([
                            Forms\Components\FileUpload::make('istisna_contract_url')
                                ->label('عقد الاستصناع')
                                ->disk('public')
                                ->directory('contracts')
                                ->visibility('private')
                                ->required(),

                            Forms\Components\FileUpload::make('price_quote_url')
                                ->label('عرض السعر للبنك')
                                ->disk('public')
                                ->directory('quotes')
                                ->visibility('private')
                                ->required(),
                        ]),
                        Forms\Components\Grid::make()
                            ->columns(2)
                            ->schema([
                                Forms\Components\Select::make('unit_id')
                                    ->label('الوحدة العقارية')
                                    ->relationship('unit', 'title')
                                    ->native(false)
                                    ->required(),
                                Forms\Components\Select::make('user_id')
                                    ->label('العميل')
                                    ->relationship('user', 'username')
                                    ->native(false)
                                    ->required(),
                                Forms\Components\Select::make('payment_plan')
                                    ->label('خطة الدفع')
                                    ->native(false)
                                    ->options([
                                        'cash' => 'نقدي',
                                        'bank_transfer' => 'تحويل بنكي',
                                    ])
                                    ->required(),
                                Forms\Components\Select::make('payment_method')
                                    ->label('طريقة الدفع')
                                    ->native(false)
                                    ->options([
                                        'cash' => 'نقدي',
                                        'installments' => 'أقساط',
                                    ])
                                    ->required(),
                                Forms\Components\Select::make('payment_status')
                                    ->label('حالة الدفع')
                                    ->native(false)
                                    ->options([
                                        'pending' => 'قيد الانتظار',
                                        'paid' => 'تم الدفع',
                                        'canceled' => 'ملغي',
                                    ])
                                    ->required(),
                                Forms\Components\Toggle::make('tax_exemption_status')
                                    ->label('إعفاء ضريبي')
                                    ->required(),
                                Forms\Components\Textarea::make('note')
                                    ->label('ملاحظات')
                                    ->required(),
                                Forms\Components\Select::make('status')
                                    ->label('حالة الطلب')
                                    ->options([
                                        'pending' => 'قيد الانتظار',
                                        'processing' => 'قيد المعالجة',
                                        'confirmed' => 'تم التأكيد',
                                        'canceled' => 'ملغي',
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
                    ->label('الوحدة العقارية')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.username')
                    ->label('العميل')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('حالة الطلب')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'confirmed' => 'success',
                        'canceled' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('payment_plan')
                    ->label('خطة الدفع'),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('طريقة الدفع'),
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('حالة الدفع')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'canceled' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('tax_exemption_status')
                    ->label('الإعفاء الضريبي')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '1' => 'نعم',
                        '0' => 'لا',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'danger',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('العميل')
                    ->relationship('user', 'username'),

                Tables\Filters\SelectFilter::make('status')
                    ->label('حالة الطلب')
                    ->options([
                        'pending' => 'قيد الانتظار',
                        'processing' => 'قيد المعالجة',
                        'confirmed' => 'تم التأكيد',
                        'canceled' => 'ملغي',
                    ]),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('حالة الدفع')
                    ->options([
                        'pending' => 'قيد الانتظار',
                        'paid' => 'تم الدفع',
                        'canceled' => 'ملغي',
                    ]),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('طريقة الدفع')
                    ->options([
                        'cash' => 'نقدي',
                        'installments' => 'أقساط',
                    ]),
                    Tables\Filters\Filter::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('من تاريخ'),
                        Forms\Components\DatePicker::make('to')
                            ->label('إلى تاريخ'),
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
                            $indicators['from'] = 'من: ' . $data['from'];
                        }
                        if ($data['to'] ?? null) {
                            $indicators['to'] = 'إلى: ' . $data['to'];
                        }
                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('تعديل'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد'),
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

    public static function getRelations(): array
    {
        return [
            InstallmentsRelationManager::class,
        ];
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