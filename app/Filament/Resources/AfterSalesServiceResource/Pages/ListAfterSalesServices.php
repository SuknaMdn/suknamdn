<?php

namespace App\Filament\Resources\AfterSalesServiceResource\Pages;

use App\Filament\Resources\AfterSalesServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAfterSalesServices extends ListRecords
{
    protected static string $resource = AfterSalesServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
