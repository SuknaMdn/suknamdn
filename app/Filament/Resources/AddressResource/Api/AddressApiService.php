<?php
namespace App\Filament\Resources\AddressResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\AddressResource;
use Illuminate\Routing\Router;


class AddressApiService extends ApiService
{
    protected static string | null $resource = AddressResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class
        ];

    }
}
