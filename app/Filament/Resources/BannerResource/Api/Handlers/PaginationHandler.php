<?php
namespace App\Filament\Resources\BannerResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filament\Resources\BannerResource;

class PaginationHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = BannerResource::class;
    public static bool $public = true;


    public function handler()
    {
        $query = static::getEloquentQuery();
        $model = static::getModel();

        $query = QueryBuilder::for($query)
        ->select('id', 'title', 'description', 'is_visible', 'sort','click_url_target', 'click_url','bannerable_type', 'bannerable_id')
        ->with('media')
        // ->with(['media' => function ($query) {
        //     $query->select('id', 'disk', 'model_type', 'model_id', 'conversions_disk', 'generated_conversions', 'file_name', 'collection_name');
        // }])
        ->allowedFields($this->getAllowedFields() ?? [])
        ->allowedSorts($this->getAllowedSorts() ?? [])
        ->allowedFilters($this->getAllowedFilters() ?? [])
        ->allowedIncludes($this->getAllowedIncludes() ?? [])
        ->paginate(request()->query('per_page'))
        ->appends(request()->query());

        return static::getApiTransformer()::collection($query);
    }
}
