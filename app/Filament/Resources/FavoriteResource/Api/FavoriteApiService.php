<?php
namespace App\Filament\Resources\FavoriteResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\FavoriteResource;
use Illuminate\Routing\Router;


class FavoriteApiService extends ApiService
{
    protected static string | null $resource = FavoriteResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            // Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            // Handlers\DetailHandler::class
        ];

    }
}
