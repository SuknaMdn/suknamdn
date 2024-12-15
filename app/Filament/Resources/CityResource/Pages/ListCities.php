<?php

namespace App\Filament\Resources\CityResource\Pages;

use App\Filament\Resources\CityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use YOS\FilamentExcel\Actions\Import;
use App\Imports\CityImport;
use Maatwebsite\Excel\Excel;
use App\Imports\StatesImport;
class ListCities extends ListRecords
{
    protected static string $resource = CityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Import::make()
                ->import(CityImport::class)
                ->type(\Maatwebsite\Excel\Excel::XLSX)
                ->label('Import Cities from excel')
                ->hint('Upload xlsx type')
                ->color('success'),
        ];
    }

}
