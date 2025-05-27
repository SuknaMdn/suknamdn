<?php

namespace App\Filament\Resources\UserResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\UserResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/user';
    public static string | null $resource = UserResource::class;

    public function handler(Request $request)
    {
        $user = $request->user();

        if (!$user) return static::sendNotFoundResponse();

        $query = QueryBuilder::for(
            static::getEloquentQuery()->where('id', $user->id)
        )
        ->with([
            'address' => function ($query) {
                $query->select('id', 'user_id', 'city_id', 'state_id', 'is_default')
                      ->with(['city:id,name', 'state:id,name']);
            }
        ])
        ->withCount(['favorites', 'orders'])
        ->first();

        if (!$query) return static::sendNotFoundResponse();

        $transformer = static::getApiTransformer();

        return new $transformer($query);
    }
}