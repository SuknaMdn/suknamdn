<?php

namespace App\Filament\Resources\NafathAuthenticationResource\Pages;

use App\Filament\Resources\NafathAuthenticationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNafathAuthentication extends EditRecord
{
    protected static string $resource = NafathAuthenticationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
