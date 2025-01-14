<?php
namespace App\Filament\Resources\UnitOrderResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filament\Resources\UnitOrderResource;
use Illuminate\Support\Facades\Auth;

class PaginationHandler extends Handlers {
    public static string | null $uri = '/{uuid}/orders';
    public static string | null $resource = UnitOrderResource::class;


    public function handler()
    {
        $query = static::getEloquentQuery();
        $model = static::getModel();
        $user = Auth::user();

        $query = QueryBuilder::for($query)
        ->where('user_id', $user->id)
        ->with('unit')
        ->allowedFields($this->getAllowedFields() ?? [])
        ->allowedSorts($this->getAllowedSorts() ?? [])
        ->allowedFilters($this->getAllowedFilters() ?? [])
        ->allowedIncludes($this->getAllowedIncludes() ?? [])
        ->paginate(request()->query('per_page'))
        ->appends(request()->query());

        // change some fields
        $query->getCollection()->transform(function ($item) {
            $item->unit_title = $item->unit->title;
            $item->unit_building_number = $item->unit->building_number;
            $item->project_name = $item->unit->project->title;
            return $item;
        });
        // hide some fields
        $query->getCollection()->makeHidden(['user_id', 'unit_id','created_at','unit']);

        return static::getApiTransformer()::collection($query);
    }
}
