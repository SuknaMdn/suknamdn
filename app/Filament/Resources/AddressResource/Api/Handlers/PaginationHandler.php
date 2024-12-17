<?php
namespace App\Filament\Resources\AddressResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filament\Resources\AddressResource;

class PaginationHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = AddressResource::class;


    public function handler()
    {
        $query = static::getEloquentQuery();
        $model = static::getModel();

        $query = QueryBuilder::for($query)
            ->where('user_id', request()->user()->id)
            ->with(['city:id,name', 'state:id,name'])
            ->allowedFields($this->getAllowedFields() ?? [])
            ->allowedSorts($this->getAllowedSorts() ?? [])
            ->allowedFilters($this->getAllowedFilters() ?? [])
            ->allowedIncludes($this->getAllowedIncludes() ?? [])
            ->get()
            ->map(function ($item) {
                $item->city_name = $item->city->name;
                $item->state_name = $item->state->name;
                return $item;
            });

        return static::getApiTransformer()::collection($query);
    }
}
