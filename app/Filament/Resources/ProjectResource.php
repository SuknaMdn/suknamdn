<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;
use Filament\Actions\Action;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

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
                Grid::make(3)->schema([
                    Forms\Components\Group::make()
                        ->schema([

                            Section::make('إعدادات الميزات الخاصة')->schema([
                                Toggle::make('enables_payment_plan')
                                    ->label('تفعيل نظام الدفعات (البيع على الخارطة)')
                                    ->helperText('عند التفعيل، ستظهر الأقسام الخاصة بالبيع على الخارطة.')
                                    ->reactive(), // يجعل الواجهة تتفاعل فورًا
                            ]),

                            Section::make('معلومات البيع على الخارطة')->schema([
                                TextInput::make('completion_percentage')
                                    ->label('نسبة الإنجاز (%)')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100),
                                TextInput::make('architect_office_name')
                                    ->label('مكتب التصميم المعماري')
                                    ->maxLength(255),
                                TextInput::make('construction_supervisor_office')
                                    ->label('مكتب الاستشاري المشرف')
                                    ->maxLength(255),
                                TextInput::make('main_contractor')
                                    ->label('المقاول الرئيسي')
                                    ->maxLength(255),
                            ])->hidden(fn (Get $get): bool => !$get('enables_payment_plan')), // الإخفاء الشرطي

                            Section::make('جدول دفعات المشروع (قالب رئيسي)')->schema([
                                Repeater::make('paymentMilestones')
                                    ->relationship()
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('اسم الدفعة')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('percentage')
                                            ->label('النسبة %')
                                            ->numeric()
                                            ->required()
                                            ->minValue(0)
                                            ->maxValue(100),
                                        Textarea::make('completion_milestone')
                                            ->label('شرط الاستحقاق')
                                            ->required()
                                            ->placeholder('مثال: عند نسبة تقدم 20%')
                                            ->rows(3),
                                    ])
                                    ->reorderable('order')
                                    ->defaultItems(7)
                                    ->addActionLabel('إضافة دفعة')
                                    ->columns(3),
                            ])->hidden(fn (Get $get): bool => !$get('enables_payment_plan')),

                            Section::make('المعلومات الأساسية')
                                ->schema([
                                    TextInput::make('title')
                                        ->label('عنوان المشروع')
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn (callable $set, ?string $state) => $set('slug', Str::slug($state))),
                                    TextInput::make('slug')
                                        ->label('الرابط الثابت')
                                        ->disabled()
                                        ->unique(Project::class, 'slug', ignoreRecord: true)
                                        ->required()
                                        ->maxLength(255),
                                ])->columns(2),

                            Section::make('صور المشروع')
                                ->schema([
                                    Forms\Components\FileUpload::make('images')
                                        ->label('صور المشروع')
                                        ->multiple()
                                        ->directory('projects')
                                        ->disk('public')
                                        ->image()
                                        ->imageEditor()
                                        ->maxFiles(10)
                                        ->helperText('يمكنك رفع حتى 10 صور'),
                                ]),

                            Section::make('تفاصيل المساحة والبناء')
                                ->schema([
                                    Forms\Components\TextInput::make('area_range_from')
                                        ->label('الحد الأدنى للمساحة')
                                        ->numeric()
                                        ->minValue(0)
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('area_range_to')
                                        ->label('الحد الأقصى للمساحة')
                                        ->numeric()
                                        ->minValue(0)
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('building_style')
                                        ->label('نمط البناء')
                                        ->maxLength(255),
                                ])->columns(3),

                            Section::make('الوصف')
                                ->schema([
                                    RichEditor::make('description')
                                        ->label('وصف المشروع')
                                        ->columnSpanFull()
                                        ->toolbarButtons([
                                            'bold',
                                            'italic',
                                            'underline',
                                            'bulletList',
                                            'orderedList',
                                            'link',
                                        ]),
                                ]),

                            Section::make('المعلومات العامة')
                                ->schema([
                                    Forms\Components\Select::make('developer_id')
                                        ->label('المطور')
                                        ->required()
                                        ->native(false)
                                        ->relationship('developer', 'name')
                                        ->searchable()
                                        ->preload(),
                                    Forms\Components\Select::make('property_type_id')
                                        ->label('نوع العقار')
                                        ->required()
                                        ->relationship('propertyType', 'name')
                                        ->native(false)
                                        ->searchable()
                                        ->preload(),
                                    Forms\Components\Select::make('purpose')
                                        ->label('الغرض')
                                        ->required()
                                        ->options([
                                            'sale' => 'للبيع',
                                            'rent' => 'للإيجار',
                                        ])
                                        ->default('sale')
                                        ->native(false),
                                    Forms\Components\TextInput::make('video')
                                        ->label('رابط الفيديو')
                                        ->maxLength(255)
                                        ->url(),
                                    Forms\Components\Select::make('city_id')
                                        ->label('المدينة')
                                        ->required()
                                        ->relationship('city', 'name')
                                        ->preload()
                                        ->searchable('name')
                                        ->native(false)
                                        ->reactive()
                                        ->afterStateUpdated(function (callable $set, $state) {
                                            $states = \App\Models\State::where('city_id', $state)->pluck('name', 'id');
                                            $set('state_id', null);
                                            $set('stateOptions', $states);
                                        }),
                                    Forms\Components\Select::make('state_id')
                                        ->label('المنطقة')
                                        ->required()
                                        ->native(false)
                                        ->searchable('name')
                                        ->options(fn (callable $get) => $get('stateOptions') ?? [])
                                        ->reactive(),
                                    Forms\Components\Textarea::make('address')
                                        ->label('العنوان')
                                        ->required()
                                        ->columnSpanFull()
                                        ->maxLength(255)
                                        ->rows(3),
                                    Forms\Components\TextInput::make('threedurl')
                                        ->label('رابط الجولة ثلاثية الأبعاد')
                                        ->columnSpanFull()
                                        ->url(),
                                    Forms\Components\FileUpload::make('mediaPDF')
                                        ->label('ملف PDF للوسائط')
                                        ->columnSpanFull()
                                        ->acceptedFileTypes(['application/pdf'])
                                        ->maxSize(10240), // 10MB
                                    Forms\Components\Hidden::make('user_id')
                                        ->default(auth()->id()),
                                ])->columns(3),

                            Section::make('مرافق المشروع')
                                ->schema([
                                    Forms\Components\Repeater::make('facilities')
                                        ->relationship()
                                        ->schema([
                                            Forms\Components\FileUpload::make('icon')
                                                ->label('أيقونة')
                                                ->image()
                                                ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/gif', 'image/webp', 'image/svg+xml'])
                                                ->directory('projects/facilities')
                                                ->disk('public')
                                                ->nullable()
                                                ->previewable()
                                                ->downloadable()
                                                ->maxSize(2048), // 2MB
                                            Forms\Components\TextInput::make('title')
                                                ->label('العنوان')
                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\TextInput::make('content')
                                                ->label('المحتوى')
                                                ->nullable()
                                                ->maxLength(255),
                                        ])
                                        ->columns(3)
                                        ->defaultItems(0)
                                        ->reorderable(false)
                                        ->addActionLabel('إضافة مرفق')
                                        ->deleteAction(
                                            fn (Forms\Components\Actions\Action $action) => $action->requiresConfirmation()
                                        ),
                                ]),

                            Section::make('الخدمات التشغيلية للمشروع')
                                ->schema([
                                    Forms\Components\Repeater::make('operationalServices')
                                        ->relationship()
                                        ->schema([
                                            Forms\Components\FileUpload::make('icon')
                                                ->label('أيقونة')
                                                ->image()
                                                ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/gif', 'image/webp', 'image/svg+xml'])
                                                ->directory('projects/operational-services')
                                                ->disk('public')
                                                ->nullable()
                                                ->maxSize(2048), // 2MB
                                            Forms\Components\TextInput::make('title')
                                                ->label('العنوان')
                                                ->required()
                                                ->maxLength(255),
                                        ])
                                        ->columns(2)
                                        ->defaultItems(0)
                                        ->reorderable(false)
                                        ->addActionLabel('إضافة خدمة')
                                        ->deleteAction(
                                            fn (Forms\Components\Actions\Action $action) => $action->requiresConfirmation()
                                        ),
                                ]),

                            Section::make('ضمانات المشروع')
                                ->schema([
                                    Forms\Components\Repeater::make('warranties')
                                        ->relationship()
                                        ->schema([
                                            Forms\Components\FileUpload::make('icon')
                                                ->label('أيقونة')
                                                ->image()
                                                ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/gif', 'image/webp', 'image/svg+xml'])
                                                ->directory('projects/warranties')
                                                ->disk('public')
                                                ->nullable()
                                                ->maxSize(2048), // 2MB
                                            Forms\Components\TextInput::make('title')
                                                ->label('العنوان')
                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\TextInput::make('content')
                                                ->label('المحتوى')
                                                ->nullable()
                                                ->maxLength(255),
                                        ])
                                        ->columns(3)
                                        ->defaultItems(0)
                                        ->reorderable(false)
                                        ->addActionLabel('إضافة ضمان')
                                        ->deleteAction(
                                            fn (Forms\Components\Actions\Action $action) => $action->requiresConfirmation()
                                        ),
                                ]),

                            Section::make('معالم المشروع')
                                ->schema([
                                    Forms\Components\Repeater::make('landmarks')
                                        ->relationship()
                                        ->schema([
                                            Forms\Components\TextInput::make('title')
                                                ->label('اسم المعلم')
                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\TextInput::make('distance')
                                                ->label('المسافة')
                                                ->placeholder('مثال: 5 كم')
                                                ->maxLength(255),
                                        ])
                                        ->columns(2)
                                        ->defaultItems(0)
                                        ->reorderable(false)
                                        ->addActionLabel('إضافة معلم')
                                        ->deleteAction(
                                            fn (Forms\Components\Actions\Action $action) => $action->requiresConfirmation()
                                        ),
                                ]),
                        ])
                        ->columnSpan(2),

                    Group::make()
                        ->schema([
                            Section::make('إعدادات الحالة والموقع')
                                ->schema([
                                    Forms\Components\Toggle::make('is_active')
                                        ->label('نشط')
                                        ->default(true)
                                        ->required(),
                                    Forms\Components\Toggle::make('is_featured')
                                        ->label('مميز')
                                        ->default(false)
                                        ->required(),
                                    Forms\Components\TextInput::make('AdLicense')
                                        ->label('رخصة الإعلان')
                                        ->maxLength(255),
                                    // Forms\Components\RichEditor::make('project_ownership')
                                    //     ->label('حقوق ملكية المشروع')
                                    //     ->toolbarButtons([
                                    //         'bold',
                                    //         'italic',
                                    //         'underline',
                                    //         'bulletList',
                                    //         'orderedList',
                                    //     ]),
                                    Forms\Components\FileUpload::make('ad_license_qr')
                                        ->label('QR للمعلومات عن ترخيص الإعلان')
                                        ->image()
                                        ->directory('projects/qr-codes')
                                        ->visibility('public')
                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                                        ->maxSize(2048), // 2MB max
                                    TextInput::make('latitude')
                                        ->label('خط العرض')
                                        ->numeric()
                                        ->required(),
                                    TextInput::make('longitude')
                                        ->label('خط الطول')
                                        ->numeric()
                                        ->required(),
                                    Map::make('location')
                                        ->label('الموقع')
                                        ->columnSpanFull()
                                        ->defaultLocation(latitude: 24.7136, longitude: 46.6753)
                                        ->afterStateHydrated(function ($state, $record, callable $set): void {
                                            $set('location', ['lat' => $record?->latitude, 'lng' => $record?->longitude]);
                                        })
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            $set('latitude', $state['lat']);
                                            $set('longitude', $state['lng']);
                                        })
                                        ->extraStyles([
                                            'min-height: 50vh',
                                            'border-radius: 7px'
                                        ])
                                        ->liveLocation(true, true, 1000)
                                        ->showMarker()
                                        ->markerColor("#000000")
                                        ->showFullscreenControl()
                                        ->showZoomControl()
                                        ->draggable()
                                        ->tilesUrl("https://tile.openstreetmap.de/{z}/{x}/{y}.png/")
                                        ->zoom(12)
                                        ->detectRetina()
                                        ->showMyLocationButton()
                                        ->extraTileControl([])
                                        ->extraControl([
                                            'zoomDelta' => 1,
                                            'zoomSnap' => 2,
                                        ])
                                ]),
                        ])
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images.0')
                    ->label('الصورة')
                    ->circular(),
                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable()
                    ->limit(20)
                    ->sortable(),
                Tables\Columns\ImageColumn::make('qr_code')
                    ->label('رمز QR'),
                Tables\Columns\TextColumn::make('developer.name')
                    ->label('المطور')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('propertyType.name')
                    ->label('نوع العقار')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('مميز')
                    ->boolean(),
                Tables\Columns\TextColumn::make('city.name')
                    ->label('المدينة')
                    ->searchable(),
                Tables\Columns\TextColumn::make('state.name')
                    ->label('المنطقة')
                    ->searchable(),
                Tables\Columns\TextColumn::make('AdLicense')
                    ->label('رخصة الإعلان')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('الناشر')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('developer_id')
                    ->label('المطور')
                    ->relationship('developer', 'name'),
                Tables\Filters\SelectFilter::make('property_type_id')
                    ->label('نوع العقار')
                    ->relationship('propertyType', 'name'),
                Tables\Filters\SelectFilter::make('purpose')
                    ->label('الغرض')
                    ->options([
                        'sale' => 'للبيع',
                        'rent' => 'للإيجار',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('نشط'),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('مميز'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('تعديل'),
                Tables\Actions\ViewAction::make()
                    ->label('عرض'),
                TableAction::make('generate_qr')
                    ->label('إنشاء رمز QR')
                    ->icon('heroicon-o-qr-code')
                    ->action(function (Project $record) {
                        $record->generateQrCode();
                        Notification::make()
                            ->title('تم إنشاء رمز QR بنجاح')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('إنشاء رمز QR')
                    ->modalDescription('هل أنت متأكد من إنشاء رمز QR لهذا المشروع؟')
                    ->modalSubmitActionLabel('إنشاء'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد'),
                ]),
            ])
            ->emptyStateHeading('لا توجد مشاريع')
            ->emptyStateDescription('لم يتم إنشاء أي مشاريع بعد.')
            ->emptyStateIcon('heroicon-o-building-office');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\LandmarksRelationManager::class,
            RelationManagers\FacilitiesRelationManager::class,
            RelationManagers\OperationalServicesRelationManager::class,
            RelationManagers\WarrantiesRelationManager::class,
            RelationManagers\UnitsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
            'view' => Pages\ViewProject::route('/{record}'),
        ];
    }

    public static function getActions(): array
    {
        return [
            Action::make('generate_qr')
                ->label('إنشاء رمز QR')
                ->icon('heroicon-o-qr-code')
                ->action(function (Project $record) {
                    $record->generateQrCode();
                    Notification::make()
                        ->title('تم إنشاء رمز QR بنجاح')
                        ->success()
                        ->send();
                })
                ->requiresConfirmation()
                ->modalHeading('إنشاء رمز QR')
                ->modalDescription('هل أنت متأكد من إنشاء رمز QR لهذا المشروع؟')
                ->modalSubmitActionLabel('إنشاء'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'مشروع';
    }

    public static function getPluralModelLabel(): string
    {
        return 'المشاريع';
    }

    public static function getNavigationLabel(): string
    {
        return 'المشاريع';
    }
}