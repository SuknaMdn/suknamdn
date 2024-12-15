<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\IconColumn;
use Filament\Infolists\Components\IconEntry;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    public function getSubheading(): string
    {
        return 'Project - ' . $this->record->title;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
