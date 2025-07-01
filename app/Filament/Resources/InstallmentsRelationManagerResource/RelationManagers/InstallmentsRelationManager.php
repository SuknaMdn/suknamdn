<?php

namespace App\Filament\Resources\InstallmentsRelationManagerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\OrderInstallment;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
class InstallmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'installments';
    protected static ?string $title = 'جدول الدفعات';
    public function form(Form $form): Form
    {
        return $form
            ->schema([
               Forms\Components\Placeholder::make('milestone_name')
                    ->label('اسم الدفعة')
                    ->content(fn ($record) => $record->milestone?->name ?? '-'),

                Forms\Components\TextInput::make('amount')
                    ->label('المبلغ')
                    ->prefix('SAR')
                    ->numeric()
                    ->disabled(), // لو ما تبي المستخدم يعدله

                Forms\Components\Select::make('status')
                    ->label('الحالة')
                    ->options([
                        'pending' => 'معلق',
                        'paid' => 'مدفوع',
                        'due' => 'مستحق',
                        'overdue' => 'متأخر',
                    ])
                    ->default('pending')
                    ->required(),

                Forms\Components\FileUpload::make('receipt_url')
                    ->label('إيصال الدفع')
                    ->disk('public')
                    ->directory('receipts')
                    ->visibility('private')
                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                    ->maxSize(1024),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('amount')
            ->columns([
                Tables\Columns\TextColumn::make('amount')
                    ->label('المبلغ')
                    ->money('SAR'),
                Tables\Columns\TextColumn::make('milestone.title')
                    ->label('اسم الدفعة'),
                    
                TextColumn::make('status')
                ->label('الحالة')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'pending' => 'gray',
                    'due' => 'warning',
                    'paid' => 'success',
                    'overdue' => 'danger',
                    default => 'gray',
                })
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'pending' => 'معلق',
                    'due' => 'مستحق',
                    'paid' => 'مدفوع',
                    'overdue' => 'متأخر',
                    default => 'غير معروف',
                }),
                Tables\Columns\TextColumn::make('receipt_url')
                    ->label('الإيصال')
                    ->url(fn (OrderInstallment $record) => $record->receipt_url ? asset('storage/' . $record->receipt_url) : null)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => $record?->receipt_url !== null),
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('تاريخ الدفع')
                    ->dateTime('Y-m-d H:i:s'),
                Tables\Columns\TextColumn::make('unitOrder.unit.title')
                    ->label('الوحدة'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('confirm_payment')
                ->label('تأكيد الدفع')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(function (OrderInstallment $record) {
                    if ($record->status === 'paid') {
                        Notification::make()->title('الدفعة مدفوعة بالفعل.')->warning()->send();
                        return;
                    }
                    
                    $record->update(['status' => 'paid', 'paid_at' => now()]);
                    
                    // إرسال إشعار للمستخدم
                    $user = $record->unitOrder->user;
                    $user->notify(new \App\Notifications\PaymentConfirmed($record));
                    
                    Notification::make()->title('تم تأكيد الدفعة بنجاح!')->success()->send();
                })
                ->visible(fn (OrderInstallment $record) => $record->status !== 'paid' && $record->receipt_url),

            // Action لعرض الإيصال
            Tables\Actions\Action::make('view_receipt')
                ->label('عرض الإيصال')
                ->icon('heroicon-o-eye')
                ->url(fn (OrderInstallment $record) => asset('storage/' . $record->receipt_url))
                ->openUrlInNewTab()
                ->visible(fn (OrderInstallment $record): bool => !is_null($record->receipt_url)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
