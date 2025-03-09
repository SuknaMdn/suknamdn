<?php

namespace App\Filament\Resources\NafathResource\Pages;

use App\Filament\Resources\NafathResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNafath extends EditRecord
{
    protected static string $resource = NafathResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
