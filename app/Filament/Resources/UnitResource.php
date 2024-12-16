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

    protected static ?string $navigationGroup = 'Properties';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required(),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                Forms\Components\Select::make('project_id')
                                    ->label('Project')
                                    ->relationship('project', 'title')
                                    ->required(),
                                Forms\Components\Textarea::make('description')
                                    ->columnSpan('full'),
                            ])
                            ->columns(3),
                    ]),

                Forms\Components\Section::make('Location')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('latitude')
                                    ->numeric()
                                    ->hidden(),
                                Forms\Components\TextInput::make('longitude')
                                    ->numeric()
                                    ->hidden(),
                            ]),
                        \Dotswan\MapPicker\Fields\Map::make('location')
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
                            // GeoMan drawing tools
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

                Forms\Components\Section::make('Building Information')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('building_number'),
                                Forms\Components\TextInput::make('unit_number'),
                                Forms\Components\TextInput::make('unit_type'),
                                Forms\Components\TextInput::make('floor'),
                            ])->columns(4),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Area Details')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('total_area')
                                    ->numeric()
                                    ->step('0.01'),
                                Forms\Components\TextInput::make('internal_area')
                                    ->numeric()
                                    ->step('0.01'),
                                Forms\Components\TextInput::make('external_area')
                                    ->numeric()
                                    ->step('0.01'),
                            ]),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Room Configuration')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('total_rooms')
                                    ->numeric(),
                                Forms\Components\TextInput::make('bedrooms')
                                    ->numeric(),
                                Forms\Components\TextInput::make('living_rooms')
                                    ->numeric(),
                                Forms\Components\TextInput::make('bathrooms')
                                    ->numeric(),
                                Forms\Components\TextInput::make('kitchens')
                                    ->numeric(),
                            ])->columns(5),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Sales Information')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('sale_type')
                                    ->options([
                                        'direct' => 'Direct',
                                        'installments' => 'Installments',
                                    ])
                                    ->default('direct'),
                                Forms\Components\TextInput::make('unit_price')
                                    ->numeric()
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        $unitPrice = floatval($get('unit_price')) ?? 0;
                                        $propertyTax = floatval($get('property_tax')) ?? 0;
                                        $totalAmount = $unitPrice + ($unitPrice * ($propertyTax / 100));
                                        $set('total_amount', $totalAmount);
                                    }),
                                Forms\Components\TextInput::make('property_tax')
                                    ->numeric()
                                    ->default(15)
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        $unitPrice = floatval($get('unit_price')) ?? 0;
                                        $propertyTax = floatval($get('property_tax')) ?? 0;
                                        $totalAmount = $unitPrice + ($unitPrice * ($propertyTax / 100));
                                        $set('total_amount', $totalAmount);
                                    }),
                                Forms\Components\TextInput::make('total_amount')
                                    ->numeric()
                                    ->disabled(),


                            ])->columns(4),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Select::make('after_sales_services')
                            ->label('After Sales Services')
                            ->multiple()
                            ->relationship('afterSalesServices', 'title')
                            ->preload()
                            ->searchable(),

                        Forms\Components\Select::make('additional_features')
                            ->label('Additional Features')
                            ->multiple()
                            ->relationship('additionalFeatures', 'title')
                            ->preload()
                            ->searchable(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Images')
                    ->schema([
                        Forms\Components\Repeater::make('images')
                            ->relationship()
                            ->grid(3) // This sets up 3 columns for repeater items
                            ->schema([
                                Forms\Components\FileUpload::make('image_path')
                                    ->image()
                                    ->directory('unit-images')
                                    ->disk('public')
                                    ->required(),
                                Forms\Components\Select::make('type')
                                    ->options([
                                        'image' => 'Image',
                                        'floor_plan' => 'Floor Plan',
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
                    ->searchable(),
                Tables\Columns\ImageColumn::make('qr_code'),
                Tables\Columns\TextColumn::make('unit_number'),
                Tables\Columns\TextColumn::make('unit_type'),
                Tables\Columns\TextColumn::make('floor'),
                Tables\Columns\TextColumn::make('total_area'),
                Tables\Columns\TextColumn::make('internal_area'),
                Tables\Columns\TextColumn::make('external_area'),
                Tables\Columns\TextColumn::make('total_rooms'),
                Tables\Columns\TextColumn::make('bedrooms'),
                Tables\Columns\TextColumn::make('living_rooms'),
                Tables\Columns\TextColumn::make('bathrooms'),
                Tables\Columns\TextColumn::make('kitchens'),
                Tables\Columns\TextColumn::make('sale_type'),
                Tables\Columns\TextColumn::make('unit_price'),
                Tables\Columns\TextColumn::make('property_tax'),
                Tables\Columns\TextColumn::make('total_amount'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                TableAction::make('generate_qr')
                    ->label('Generate QR')
                    ->icon('heroicon-o-qr-code')
                    ->action(function (Unit $record) {
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
            // ... your existing actions ...
            Action::make('generate_qr')
                ->label('Generate QR')
                ->icon('heroicon-o-qr-code')
                ->action(function (Unit $record) {
                    $record->generateQrCode();
                    Notification::make()
                        ->title('QR Code generated successfully')
                        ->success()
                        ->send();
                })
        ];
    }
}
