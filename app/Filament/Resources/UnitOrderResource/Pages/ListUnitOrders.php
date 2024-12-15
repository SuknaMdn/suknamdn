<?php

namespace App\Filament\Resources\UnitOrderResource\Pages;

use App\Filament\Resources\UnitOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\UnitOrderResource\Widgets\UnitOrderStat;
class ListUnitOrders extends ListRecords
{
    protected static string $resource = UnitOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            UnitOrderStat::class
        ];
    }
}
