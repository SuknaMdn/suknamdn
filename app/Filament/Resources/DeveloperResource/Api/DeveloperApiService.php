<?php
namespace App\Filament\Resources\DeveloperResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\DeveloperResource;
use Illuminate\Routing\Router;


class DeveloperApiService extends ApiService
{
    protected static string | null $resource = DeveloperResource::class;

    public static function handlers() : array
    {
        return [
            // Handlers\CreateHandler::class,
            // Handlers\UpdateHandler::class,
            // Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            // Handlers\DetailHandler::class
        ];

    }
}
