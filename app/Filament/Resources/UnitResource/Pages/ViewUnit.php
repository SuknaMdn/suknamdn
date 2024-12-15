<?php

namespace App\Filament\Resources\UnitResource\Pages;

use App\Filament\Resources\UnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;

class ViewUnit extends ViewRecord
{
    protected static string $resource = UnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Unit - ' . $this->record->title;
    }

    public function getSubheading(): string
    {
        return 'Project - ' . $this->record->project->title;
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make([
                    'default' => 3,
                    'sm' => 1,
                    'lg' => 3,
                ])
                    ->schema([
                        // Left side (spans 2 columns)
                        Grid::make()
                            ->columnSpan(2)
                            ->schema([
                                Section::make('Basic Information')
                                    ->icon('heroicon-o-home')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextEntry::make('title')
                                                    ->label('Unit Title')
                                                    ->weight('bold'),
                                                TextEntry::make('slug'),
                                                TextEntry::make('description')
                                                    ->columnSpanFull()
                                                    ->markdown(),
                                            ]),
                                    ]),

                                Section::make('Location Details')
                                    ->icon('heroicon-o-map-pin')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextEntry::make('building_number')
                                                    ->label('Building No.'),
                                                TextEntry::make('unit_number')
                                                    ->label('Unit No.'),
                                                TextEntry::make('floor')
                                                    ->label('Floor Level'),
                                            ])->columns(3),
                                    ]),

                                Section::make('Unit Specifications')
                                    ->icon('heroicon-o-squares-2x2')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                TextEntry::make('unit_type')
                                                    ->label('Type'),
                                                TextEntry::make('total_rooms')
                                                    ->label('Total Rooms'),
                                                TextEntry::make('bedrooms'),
                                                TextEntry::make('living_rooms'),
                                                TextEntry::make('bathrooms'),
                                                TextEntry::make('kitchens'),
                                            ]),
                                    ]),
                            ]),

                        // Right side (spans 1 column)
                        Grid::make()
                            ->columnSpan(1)
                            ->schema([
                                Section::make('Status')
                                    ->icon('heroicon-o-signal')
                                    ->schema([
                                        TextEntry::make('sale_type')
                                            ->badge()
                                            ->color(fn (string $state): string => match ($state) {
                                                'sale' => 'success',
                                                'rent' => 'info',
                                                default => 'warning',
                                            }),
                                        TextEntry::make('status')
                                            ->badge()
                                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                                '0' => 'Inactive',
                                                '1' => 'Active',
                                                default => 'Unknown',
                                            })
                                            ->color(fn (string $state): string => match ($state) {
                                                0 => 'danger', // unactive
                                                1 => 'success', // active
                                                default => 'secondary',
                                            }),
                                        TextEntry::make('case')
                                            ->badge()
                                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                                '0' => 'available',
                                                '1' => 'sold',
                                                '2' => 'reserved',
                                                default => 'Unknown',
                                            })
                                            ->color(fn (string $state): string => match ($state) {
                                                'available' => 'success',
                                                'sold' => 'danger',
                                                'reserved' => 'warning',
                                                default => 'secondary',
                                            })
                                            ->label('Case'),
                                    ])->columns(3),

                                Section::make('Area Information')
                                    ->icon('heroicon-o-square-3-stack-3d')
                                    ->schema([
                                        Grid::make(1)
                                            ->schema([
                                                TextEntry::make('total_area')
                                                    ->label('Total Area')
                                                    ->suffix(' m²'),
                                                TextEntry::make('internal_area')
                                                    ->label('Internal Area')
                                                    ->suffix(' m²'),
                                                TextEntry::make('external_area')
                                                    ->label('External Area')
                                                    ->suffix(' m²'),
                                            ])->columns(3),
                                    ]),

                                Section::make('Financial Details')
                                    ->icon('tabler-currency-riyal')
                                    ->schema([
                                        Grid::make(1)
                                            ->schema([
                                                TextEntry::make('unit_price')
                                                    ->money('SAR')
                                                    ->label('Price'),
                                                TextEntry::make('property_tax')
                                                    ->money('SAR')
                                                    ->label('Property Tax'),
                                                TextEntry::make('total_amount')
                                                    ->money('SAR')
                                                    ->label('Total Amount')
                                                    ->weight('bold'),
                                            ])->columns(3),
                                    ]),

                                Section::make('Additional Information')
                                    ->icon('heroicon-o-information-circle')
                                    ->schema([
                                        RepeatableEntry::make('afterSalesServices')
                                            ->label('After Sales Services')
                                            ->schema([
                                                TextEntry::make('title'),
                                                TextEntry::make('description'),
                                            ]),

                                        RepeatableEntry::make('additionalFeatures')
                                            ->label('Additional Features')
                                            ->schema([
                                                TextEntry::make('title'),
                                                TextEntry::make('description'),
                                            ]),
                                    ]),
                            ]),
                    ]),
            ]);
    }

}
