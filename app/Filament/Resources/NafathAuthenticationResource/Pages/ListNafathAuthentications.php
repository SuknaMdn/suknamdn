<?php

namespace App\Filament\Resources\NafathAuthenticationResource\Pages;

use App\Filament\Resources\NafathAuthenticationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNafathAuthentications extends ListRecords
{
    protected static string $resource = NafathAuthenticationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
