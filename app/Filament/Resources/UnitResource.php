<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnitResource\Pages;
use App\Filament\Resources\UnitResource\RelationManagers;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Filament\Actions\Action;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Notifications\Notification;

class UnitResource extends Resource
{
    protected static ?string $model = Unit::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationGroup = 'العقارات';
    public static function getNavigationSort(): ?int
    {
        return -2;
    }
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('المعلومات الأساسية')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                TextInput::make('title')
                                    ->label('العنوان')
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                        $projectTitle = optional(\App\Models\Project::find($get('project_id')))->title;
                                        if ($projectTitle && $state) {
                                            $slug = Str::slug($projectTitle . ' ' . $state);
                                            $set('slug', $slug);
                                        }
                                    }),

                                Select::make('project_id')
                                    ->label('المشروع')
                                    ->relationship('project', 'title')
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                        $unitTitle = $get('title');
                                        $projectTitle = optional(\App\Models\Project::find($state))->title;
                                        if ($projectTitle && $unitTitle) {
                                            $slug = Str::slug($projectTitle . ' ' . $unitTitle);
                                            $set('slug', $slug);
                                        }
                                    }),

                                TextInput::make('slug')
                                    ->label('الرابط')
                                    ->required()
                                    ->unique(ignoreRecord: true),

                                Forms\Components\Textarea::make('description')
                                    ->label('الوصف')
                                    ->required()
                                    ->columnSpan('full'),
                            ])
                            ->columns(3),
                    ]),

                Forms\Components\Section::make('الموقع')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('latitude')
                                    ->label('خط العرض')
                                    ->numeric()
                                    ->hidden(),
                                Forms\Components\TextInput::make('longitude')
                                    ->label('خط الطول')
                                    ->numeric()
                                    ->hidden(),
                            ]),
                        \Dotswan\MapPicker\Fields\Map::make('location')
                            ->label('الموقع على الخريطة')
                            ->columnSpanFull()
                            ->defaultLocation(25.2048, 55.2708)
                            ->afterStateUpdated(function ($set, ?array $state): void {
                                if ($state) {
                                    $set('latitude', $state['lat']);
                                    $set('longitude', $state['lng']);
                                    $set('geojson', json_encode($state['geojson'] ?? []));
                                }
                            })
                            ->afterStateHydrated(function ($state, $record, $set): void {
                                if ($record) {
                                    $set('location', [
                                        'lat' => $record->latitude ?? 25.2048,
                                        'lng' => $record->longitude ?? 55.2708,
                                        'geojson' => json_decode($record->geojson ?? '{}')
                                    ]);
                                }
                            })
                            ->extraStyles([
                                'min-height: 50vh',
                            ])
                            ->showMarker()
                            ->markerColor("#000000")
                            ->showFullscreenControl()
                            ->showZoomControl()
                            ->draggable()
                            ->zoom(18)
                            ->geoMan(true)
                            ->geoManEditable(true)
                            ->drawPolygon()
                            ->drawPolyline()
                            ->drawCircle()
                            ->dragMode()
                            ->editPolygon()
                            ->deleteLayer()
                            ->setColor('#000000')
                            ->setFilledColor('#cad9ec'),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('معلومات المبنى')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('building_number')
                                    ->label('رقم المبنى')
                                    ->required(),
                                Forms\Components\TextInput::make('unit_number')
                                    ->label('رقم الوحدة')
                                    ->required(),
                                Forms\Components\TextInput::make('unit_type')
                                    ->label('نوع الوحدة')
                                    ->required(),
                                Forms\Components\TextInput::make('floor')
                                    ->label('الدور')
                                    ->required(),
                            ])->columns(4),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('تفاصيل المساحة')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('total_area')
                                    ->label('المساحة الإجمالية')
                                    ->numeric()
                                    ->step('0.01')
                                    ->required(),
                                Forms\Components\TextInput::make('internal_area')
                                    ->label('المساحة الداخلية')
                                    ->numeric()
                                    ->step('0.01')
                                    ->required(),
                                Forms\Components\TextInput::make('external_area')
                                    ->label('المساحة الخارجية')
                                    ->numeric()
                                    ->step('0.01')
                                    ->required(),
                            ]),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('توزيع الغرف')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('total_rooms')
                                    ->label('إجمالي الغرف')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TextInput::make('bedrooms')
                                    ->label('غرف النوم')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TextInput::make('living_rooms')
                                    ->label('غرف المعيشة')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TextInput::make('bathrooms')
                                    ->label('دورات المياه')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TextInput::make('kitchens')
                                    ->label('المطابخ')
                                    ->numeric()
                                    ->required(),
                            ])->columns(5),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('معلومات البيع')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('sale_type')
                                    ->label('نوع البيع')
                                    ->hidden()
                                    ->options([
                                        'cash' => 'نقدي',
                                        'bank' => 'بنكي',
                                    ])
                                    ->default('direct')
                                    ->required(),
                                Forms\Components\TextInput::make('unit_price')
                                    ->label('سعر الوحدة')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TextInput::make('property_tax')
                                    ->label('الضريبة العقارية')
                                    ->numeric()
                                    ->default(15)
                                    ->required(),
                                Forms\Components\TextInput::make('total_amount')
                                    ->label('المبلغ الإجمالي')
                                    ->numeric()
                                    ->required(),
                            ])->columns(4),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('معلومات إضافية')
                    ->schema([
                        Forms\Components\Select::make('after_sales_services')
                            ->label('خدمات ما بعد البيع')
                            ->multiple()
                            ->relationship('afterSalesServices', 'title')
                            ->preload()
                            ->searchable(),

                        Forms\Components\Select::make('additional_features')
                            ->label('مميزات إضافية')
                            ->multiple()
                            ->relationship('additionalFeatures', 'title')
                            ->preload()
                            ->searchable(),
                        Forms\Components\Select::make('case')
                            ->label('حالة الوحدة')
                            ->default(0)
                            ->options([
                                0 => 'جديد',
                                1 => 'محجوز',
                                2 => 'مباع',
                            ])
                            ->required(),

                    ])
                    ->columns(2),

                Forms\Components\Section::make('الصور')
                    ->schema([
                        Forms\Components\Repeater::make('images')
                            ->relationship()
                            ->grid(3)
                            ->schema([
                                Forms\Components\FileUpload::make('image_path')
                                    ->label('الصورة')
                                    ->image()
                                    ->directory('unit-images')
                                    ->disk('public')
                                    ->required(),
                                Forms\Components\Select::make('type')
                                    ->label('النوع')
                                    ->options([
                                        'image' => 'صورة',
                                        'floor_plan' => 'مخطط أرضي',
                                    ])
                                    ->default('image')
                                    ->required(),
                            ])
                            ->columns(1),
                    ])
                    ->collapsible()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('اسم الوحدة')
                    ->searchable(),
                Tables\Columns\TextColumn::make('project.title')
                    ->label('المشروع'),
                Tables\Columns\TextColumn::make('unit_number')
                    ->label('رقم الوحدة'),
                Tables\Columns\TextColumn::make('unit_type')
                    ->label('نوع الوحدة'),
                Tables\Columns\TextColumn::make('floor')
                    ->label('الدور'),
                Tables\Columns\TextColumn::make('total_area')
                    ->label('المساحة الإجمالية'),
                Tables\Columns\TextColumn::make('total_rooms')
                    ->label('إجمالي الغرف'),
                Tables\Columns\TextColumn::make('unit_price')
                    ->label('سعر الوحدة'),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('المبلغ الإجمالي'),
                Tables\Columns\ImageColumn::make('qr_code')
                    ->label('كود QR'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('عرض'),
                Tables\Actions\EditAction::make()
                    ->label('تعديل'),
                TableAction::make('generate_qr')
                    ->label('إنشاء كود QR')
                    ->icon('heroicon-o-qr-code')
                    ->action(function (Unit $record) {
                        $record->generateQrCode();
                        Notification::make()
                            ->title('تم إنشاء كود QR بنجاح')
                            ->success()
                            ->send();
                    })
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
            'index' => Pages\ListUnits::route('/'),
            'create' => Pages\CreateUnit::route('/create'),
            'view' => Pages\ViewUnit::route('/{record}'),
            'edit' => Pages\EditUnit::route('/{record}/edit'),
        ];
    }

    public static function getActions(): array
    {
        return [
            Action::make('generate_qr')
                ->label('إنشاء كود QR')
                ->icon('heroicon-o-qr-code')
                ->action(function (Unit $record) {
                    $record->generateQrCode();
                    Notification::make()
                        ->title('تم إنشاء كود QR بنجاح')
                        ->success()
                        ->send();
                })
        ];
    }

    public static function getModelLabel(): string
    {
        return 'وحدة';
    }

    public static function getPluralModelLabel(): string
    {
        return 'الوحدات';
    }

    public static function getNavigationLabel(): string
    {
        return 'الوحدات';
    }
}