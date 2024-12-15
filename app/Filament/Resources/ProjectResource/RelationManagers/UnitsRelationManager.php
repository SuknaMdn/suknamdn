<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Grid;
use Dotswan\MapPicker\Fields\Map;
use Illuminate\Support\Str;

class UnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'units';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $state, callable $set) {
                                        $set('slug', Str::slug($state));
                                    }),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->disabled(),
                                Forms\Components\Textarea::make('description')
                                    ->columnSpan('full'),

                                Forms\Components\TextInput::make('unit_number')
                                    ->required(),
                                Forms\Components\Select::make('unit_type')
                                    ->options([
                                        'apartment' => 'Apartment',
                                        'villa' => 'Villa',
                                        'penthouse' => 'Penthouse',
                                        'studio' => 'Studio',

                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('floor')
                                    ->required(),
                            ]),
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
                            ->defaultLocation(23.8859, 45.0792)
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
                                        'lat' => $record->latitude ?? 23.8859,
                                        'lng' => $record->longitude ?? 45.0792,
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
                            ->zoom(4)
                            // GeoMan drawing tools
                            ->geoMan(true)
                            ->geoManEditable(true)
                            ->drawPolygon()
                            ->drawPolyline()
                            ->detectRetina()
                            ->showMyLocationButton(true)
                            ->geoManPosition('topleft')
                            ->drawCircleMarker()
                            ->rotateMode()
                            ->drawMarker()
                            ->drawCircle()
                            ->dragMode()
                            ->cutPolygon()
                            ->editPolygon()
                            ->deleteLayer()
                            ->setColor('#3388ff')
                            ->setFilledColor('#cad9ec'),
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
                            ]),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Sales Information')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('sale_type')
                                    ->options([
                                        'direct' => 'Direct',
                                        // Add other sale types as needed
                                    ])
                                    ->default('direct'),
                                Forms\Components\TextInput::make('unit_price')
                                    ->numeric()
                                    ->step('0.01'),
                                Forms\Components\TextInput::make('property_tax')
                                    ->numeric()
                                    ->step('0.01'),
                                Forms\Components\TextInput::make('total_amount')
                                    ->numeric()
                                    ->step('0.01'),
                            ]),
                    ])
                    ->collapsible(),

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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
