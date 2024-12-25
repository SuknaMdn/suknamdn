<?php
namespace App\Filament\Resources\ProjectTypeResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filament\Resources\ProjectTypeResource;

class PaginationHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = ProjectTypeResource::class;
    public static bool $public = true;


    public function handler()
    {
        $query = static::getEloquentQuery();
        $model = static::getModel();

        $query = QueryBuilder::for($query)
        // ->whereHas('projects', function ($query) {
        //     $query->where('is_active', true);
        // })
        ->allowedFields($this->getAllowedFields() ?? [])
        ->allowedSorts($this->getAllowedSorts() ?? [])
        ->allowedFilters($this->getAllowedFilters() ?? [])
        ->allowedIncludes(array_merge($this->getAllowedIncludes() ?? [], ['projects']))
        ->get();

        return static::getApiTransformer()::collection($query);
    }
}
