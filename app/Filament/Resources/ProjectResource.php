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

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?string $navigationGroup = 'Properties';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)->schema([
                    Forms\Components\Group::make()
                        ->schema([
                        Section::make('information')
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (callable $set, ?string $state) => $set('slug', Str::slug($state))),
                                TextInput::make('slug')
                                    ->disabled()
                                    ->unique(Project::class, 'slug', ignoreRecord: true)
                                    ->required(),

                            ])->columns(2),

                        Section::make('images')
                            ->schema([
                                Forms\Components\FileUpload::make('images')
                                    ->multiple()
                                    ->directory('projects')
                                    ->disk('public')
                                    ->image(),
                            ]),
                        Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('area_range_from')
                                    ->label('Minimum Area')
                                    ->numeric()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('area_range_to')
                                    ->label('Maximum Area')
                                    ->numeric()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('building_style')
                                    ->label('Building Style')
                                    ->maxLength(255),
                            ])->columns(3),
                        Section::make()
                            ->schema([
                                RichEditor::make('description')
                                    ->label('Description')
                                    ->columnSpanFull(),
                        ]),
                        Section::make()
                            ->schema([
                                Forms\Components\Select::make('developer_id')
                                    ->required()
                                    ->native(false)
                                    ->relationship('developer', 'name'),
                                Forms\Components\Select::make('property_type_id')
                                    ->required()
                                    ->relationship('propertyType', 'name'),

                                Forms\Components\Select::make('purpose')
                                    ->required()
                                    ->options([
                                        'sale' => 'sale',
                                        'rent' => 'rent',
                                    ])
                                    ->default('sale'),
                                Forms\Components\TextInput::make('video')
                                    ->label('Video Link')
                                    ->maxLength(255),
                                Forms\Components\Select::make('city_id')
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
                                    ->required()
                                    ->native(false)
                                    ->searchable('name')
                                    ->options(fn (callable $get) => $get('stateOptions') ?? [])
                                    ->reactive(),
                                Forms\Components\Textarea::make('address')
                                    ->required()
                                    ->columnSpanFull()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('threedurl')
                                    ->columnSpanFull(),
                                Forms\Components\FileUpload::make('mediaPDF')
                                    ->label('Media PDF')
                                    ->columnSpanFull(),
                                Forms\Components\Hidden::make('user_id')
                                    ->default(auth()->id()),


                            ])->columns(3),

                            Section::make('Project facilities')
                                ->schema([
                                    Forms\Components\Repeater::make('facilities')
                                        ->relationship()
                                        ->schema([
                                            Forms\Components\FileUpload::make('icon')
                                                ->image()
                                                ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/gif', 'image/webp', 'image/svg+xml'])
                                                ->directory('projects/facilities')
                                                ->disk('public')
                                                ->nullable()
                                                ->previewable()
                                                ->downloadable(),

                                            Forms\Components\TextInput::make('title')
                                                ->required(),
                                            Forms\Components\TextInput::make('content')
                                                ->nullable(),
                                        ])
                                        ->columns(3)
                                        ->defaultItems(0)
                                        ->reorderable(false),
                                ]),

                            Section::make('Project operational services')
                                ->schema([
                                    Forms\Components\Repeater::make('operationalServices')
                                        ->relationship()
                                        ->schema([
                                            Forms\Components\FileUpload::make('icon')
                                                ->image()
                                                ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/gif', 'image/webp', 'image/svg+xml'])
                                                ->directory('projects/operational-services')
                                                ->disk('public')
                                                ->nullable(),
                                            Forms\Components\TextInput::make('title')
                                                ->required(),
                                        ])
                                        ->columns(2)
                                        ->defaultItems(0)
                                        ->reorderable(false),
                                ]),

                            Section::make('Project warranties')
                                ->schema([
                                    Forms\Components\Repeater::make('warranties')
                                        ->relationship()
                                        ->schema([
                                            Forms\Components\FileUpload::make('icon')
                                                ->image()
                                                ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/gif', 'image/webp', 'image/svg+xml'])
                                                ->directory('projects/warranties')
                                                ->disk('public')
                                                ->nullable(),
                                            Forms\Components\TextInput::make('title')
                                                ->required(),
                                            Forms\Components\TextInput::make('content')
                                                ->nullable(),
                                        ])
                                        ->columns(3)
                                        ->defaultItems(0)
                                        ->reorderable(false),
                                ]),
                            Section::make('Project landmarks')
                                ->schema([
                                    Forms\Components\Repeater::make('landmarks')
                                        ->relationship()
                                        ->schema([
                                            Forms\Components\TextInput::make('title')
                                                ->required()
                                                ->label('name'),
                                            Forms\Components\TextInput::make('distance')
                                                ->label('distance')
                                                ->placeholder('example: 5 km'),
                                        ])
                                        ->columns(2)
                                        ->defaultItems(0)
                                        ->reorderable(false),
                                ]),
                        ])
                        ->columnSpan(2),
                    Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->required(),
                                Forms\Components\Toggle::make('is_featured')
                                    ->required(),
                                Forms\Components\TextInput::make('AdLicense')
                                    ->maxLength(255),

                                TextInput::make('latitude')
                                    // ->readOnly()
                                    ->required(),

                                TextInput::make('longitude')
                                    // ->readOnly()
                                    ->required(),

                                Map::make('location')
                                    ->label('Location')
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
                                        'zoomDelta'           => 1,
                                        'zoomSnap'            => 2,

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
                    ->label('Image')
                    ->circular(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('qr_code'),
                Tables\Columns\TextColumn::make('developer.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('propertyType.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),
                Tables\Columns\TextColumn::make('purpose')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('state.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('AdLicense')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                TableAction::make('generate_qr')
                    ->label('Generate QR')
                    ->icon('heroicon-o-qr-code')
                    ->action(function (Project $record) {
                        $record->generateQrCode();
                        Notification::make()
                            ->title('QR Code generated successfully')
                            ->success()
                            ->send();
                    })
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
            // ... your existing actions ...
            Action::make('generate_qr')
                ->label('Generate QR')
                ->icon('heroicon-o-qr-code')
                ->action(function (Project $record) {
                    $record->generateQrCode();
                    Notification::make()
                        ->title('QR Code generated successfully')
                        ->success()
                        ->send();
                })
        ];
    }
}
