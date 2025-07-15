<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NafathAuthenticationResource\Pages;
use App\Filament\Resources\NafathAuthenticationResource\RelationManagers;
use App\Models\NafathAuthentication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NafathAuthenticationResource extends Resource
{
    protected static ?string $model = NafathAuthentication::class;

    protected static ?string $navigationIcon = 'heroicon-o-finger-print';
    protected static ?string $navigationLabel = 'توثيق نافذ';
    protected static ?string $modelLabel = 'توثيق نافذ';
    protected static ?string $pluralModelLabel = 'توثيق نافذ';
    protected static ?string $navigationGroup = 'نفاذ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات الطلب')
                    ->schema([
                        Forms\Components\TextInput::make('nafath_request_id')
                            ->label('معرف الطلب')
                            ->required(),
                        Forms\Components\TextInput::make('nafath_trans_id')
                            ->label('معرف المعاملة'),
                        Forms\Components\Select::make('user_type')
                            ->label('نوع المستخدم')
                            ->options([
                                'citizen' => 'مواطن',
                                'resident' => 'مقيم',
                            ]),
                        Forms\Components\Select::make('authentication_status')
                            ->label('حالة التوثيق')
                            ->options([
                                'pending' => 'قيد الانتظار',
                                'completed' => 'مكتمل',
                                'failed' => 'فشل',
                            ]),
                        Forms\Components\DateTimePicker::make('authenticated_at')
                            ->label('تاريخ التوثيق'),
                    ])->columns(2),
                
                Forms\Components\Section::make('المعلومات الشخصية')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->label('الاسم الأول'),
                        Forms\Components\TextInput::make('second_name')
                            ->label('الاسم الثاني'),
                        Forms\Components\TextInput::make('third_name')
                            ->label('الاسم الثالث'),
                        Forms\Components\TextInput::make('last_name')
                            ->label('الاسم الأخير'),
                        Forms\Components\TextInput::make('father_name')
                            ->label('اسم الأب'),
                        Forms\Components\TextInput::make('grandfather_name')
                            ->label('اسم الجد'),
                        Forms\Components\TextInput::make('family_name')
                            ->label('اسم العائلة'),
                        Forms\Components\Select::make('gender')
                            ->label('الجنس')
                            ->options([
                                'male' => 'ذكر',
                                'female' => 'أنثى',
                            ]),
                        Forms\Components\TextInput::make('nationality')
                            ->label('الجنسية'),
                        Forms\Components\TextInput::make('nationality_code')
                            ->label('كود الجنسية'),
                        Forms\Components\DatePicker::make('date_of_birth_g')
                            ->label('تاريخ الميلاد (ميلادي)'),
                        Forms\Components\TextInput::make('date_of_birth_h')
                            ->label('تاريخ الميلاد (هجري)'),
                    ])->columns(3),
                
                Forms\Components\Section::make('معلومات الهوية')
                    ->schema([
                        Forms\Components\TextInput::make('national_id')
                            ->label('رقم الهوية'),
                        Forms\Components\TextInput::make('id_version_number')
                            ->label('رقم نسخة الهوية'),
                        Forms\Components\TextInput::make('id_issue_place')
                            ->label('مكان الإصدار'),
                        Forms\Components\DatePicker::make('id_issue_date_g')
                            ->label('تاريخ الإصدار (ميلادي)'),
                        Forms\Components\TextInput::make('id_issue_date_h')
                            ->label('تاريخ الإصدار (هجري)'),
                        Forms\Components\DatePicker::make('id_expiry_date_g')
                            ->label('تاريخ الانتهاء (ميلادي)'),
                        Forms\Components\TextInput::make('id_expiry_date_h')
                            ->label('تاريخ الانتهاء (هجري)'),
                    ])->columns(3),
                
                Forms\Components\Section::make('معلومات الإقامة (للمقيمين)')
                    ->schema([
                        Forms\Components\TextInput::make('iqama_number')
                            ->label('رقم الإقامة'),
                        Forms\Components\TextInput::make('iqama_version_number')
                            ->label('رقم نسخة الإقامة'),
                        Forms\Components\DatePicker::make('iqama_expiry_date_g')
                            ->label('تاريخ انتهاء الإقامة (ميلادي)'),
                        Forms\Components\TextInput::make('iqama_expiry_date_h')
                            ->label('تاريخ انتهاء الإقامة (هجري)'),
                        Forms\Components\DatePicker::make('iqama_issue_date_g')
                            ->label('تاريخ إصدار الإقامة (ميلادي)'),
                        Forms\Components\TextInput::make('iqama_issue_date_h')
                            ->label('تاريخ إصدار الإقامة (هجري)'),
                        Forms\Components\TextInput::make('iqama_issue_place_code')
                            ->label('كود مكان الإصدار'),
                        Forms\Components\TextInput::make('iqama_issue_place_desc')
                            ->label('وصف مكان الإصدار'),
                        Forms\Components\TextInput::make('sponsor_name')
                            ->label('اسم الكفيل'),
                    ])->columns(3),
                
                Forms\Components\Section::make('معلومات إضافية')
                    ->schema([
                        Forms\Components\TextInput::make('legal_status')
                            ->label('الحالة القانونية'),
                        Forms\Components\TextInput::make('social_status_code')
                            ->label('كود الحالة الاجتماعية'),
                        Forms\Components\TextInput::make('social_status_desc')
                            ->label('وصف الحالة الاجتماعية'),
                        Forms\Components\TextInput::make('occupation_code')
                            ->label('كود المهنة'),
                        Forms\Components\TextInput::make('place_of_birth')
                            ->label('مكان الولادة'),
                        Forms\Components\TextInput::make('passport_number')
                            ->label('رقم الجواز'),
                        Forms\Components\Toggle::make('is_minor')
                            ->label('قاصر'),
                        Forms\Components\TextInput::make('total_dependents')
                            ->label('عدد المعالين')
                            ->numeric(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nafath_request_id')
                    ->label('معرف الطلب')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('national_id')
                    ->label('رقم الهوية')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('first_name')
                    ->label('الاسم الأول')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('second_name')
                    ->label('الاسم الثاني')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('last_name')
                    ->label('الاسم الأخير')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('user_type')
                    ->label('نوع المستخدم')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'citizen' => 'مواطن',
                        'resident' => 'مقيم',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('authentication_status')
                    ->label('حالة التوثيق')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'قيد الانتظار',
                        'completed' => 'مكتمل',
                        'failed' => 'فشل',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('authenticated_at')
                    ->label('تاريخ التوثيق')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_type')
                    ->label('نوع المستخدم')
                    ->options([
                        'citizen' => 'مواطن',
                        'resident' => 'مقيم',
                    ]),
                
                Tables\Filters\SelectFilter::make('authentication_status')
                    ->label('حالة التوثيق')
                    ->options([
                        'pending' => 'قيد الانتظار',
                        'completed' => 'مكتمل',
                        'failed' => 'فشل',
                    ]),
                
                Tables\Filters\Filter::make('authenticated_at')
                    ->label('تاريخ التوثيق')
                    ->form([
                        Forms\Components\DatePicker::make('authenticated_from')
                            ->label('من تاريخ'),
                        Forms\Components\DatePicker::make('authenticated_until')
                            ->label('إلى تاريخ'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['authenticated_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('authenticated_at', '>=', $date),
                            )
                            ->when(
                                $data['authenticated_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('authenticated_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('عرض'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNafathAuthentications::route('/'),
            'create' => Pages\CreateNafathAuthentication::route('/create'),
            'edit' => Pages\EditNafathAuthentication::route('/{record}/edit'),
        ];
    }
}