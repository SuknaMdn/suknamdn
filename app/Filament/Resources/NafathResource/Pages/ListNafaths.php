<?php

namespace App\Filament\Resources\NafathResource\Pages;

use App\Filament\Resources\NafathResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNafaths extends ListRecords
{
    protected static string $resource = NafathResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
