<?php

namespace App\Filament\Resources\StateResource\Pages;

use App\Filament\Resources\StateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Excel;
use App\Imports\StatesImport;
use YOS\FilamentExcel\Actions\Import;
class ListStates extends ListRecords
{
    protected static string $resource = StateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            Import::make()
                ->import(StatesImport::class)
                ->type(Excel::XLSX)
                ->label('Import States from excel')
                ->hint('Upload xlsx type')
                ->color('success'),
        ];
    }
}
