<?php

namespace App\Filament\Resources\AfterSalesServiceResource\Pages;

use App\Filament\Resources\AfterSalesServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAfterSalesService extends EditRecord
{
    protected static string $resource = AfterSalesServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
