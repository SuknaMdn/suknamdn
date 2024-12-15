<?php
namespace App\Filament\Resources\ProjectTypeResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\ProjectTypeResource;
use Illuminate\Routing\Router;


class ProjectTypeApiService extends ApiService
{
    protected static string | null $resource = ProjectTypeResource::class;

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
