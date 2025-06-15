<?php
namespace App\Filament\Resources\DeveloperResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filament\Resources\DeveloperResource;
use Illuminate\Support\Facades\Storage;

class PaginationHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = DeveloperResource::class;
    public static bool $public = true;


    public function handler()
    {
        $query = static::getEloquentQuery();
        $model = static::getModel();

        $paginated = QueryBuilder::for($query)
            ->allowedFields($this->getAllowedFields() ?? [])
            ->allowedSorts($this->getAllowedSorts() ?? [])
            ->allowedFilters($this->getAllowedFilters() ?? [])
            ->allowedIncludes($this->getAllowedIncludes() ?? [])
            ->paginate(request()->query('per_page'))
            ->appends(request()->query());

        // Mutate each item to add full logo URL
        $transformed = $paginated->getCollection()->transform(function ($item) {
            if (!empty($item->logo)) {
                $item->logo = asset(Storage::url($item->logo));
            }
            return $item;
        });

        // Set transformed collection back
        $paginated->setCollection($transformed);

        return static::getApiTransformer()::collection($paginated);
    }
}
