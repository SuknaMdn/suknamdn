<?php
namespace App\Filament\Resources\UserResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\UserResource;
use Illuminate\Routing\Router;

class UserApiService extends ApiService
{
    protected static string | null $resource = UserResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\UpdateHandler::class,
            Handlers\DetailHandler::class,
        ];

    }
}
