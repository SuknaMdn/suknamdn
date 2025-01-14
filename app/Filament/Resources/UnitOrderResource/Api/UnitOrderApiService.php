<?php
namespace App\Filament\Resources\UnitOrderResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\UnitOrderResource;
use Illuminate\Routing\Router;


class UnitOrderApiService extends ApiService
{
    protected static string | null $resource = UnitOrderResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class
        ];

    }
}
