<?php

namespace App\Filament\Resources\AdditionalFeatureResource\Pages;

use App\Filament\Resources\AdditionalFeatureResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdditionalFeatures extends ListRecords
{
    protected static string $resource = AdditionalFeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
