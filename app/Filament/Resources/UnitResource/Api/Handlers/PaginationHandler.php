<?php
namespace App\Filament\Resources\UnitResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filament\Resources\UnitResource;

class PaginationHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = UnitResource::class;
    public static bool $public = true;


    public function handler()
    {
        $query = static::getEloquentQuery();
        $model = static::getModel();

        // Apply min/max filters for price and space
        if (request()->has('price_min') && request()->price_min != null) {
            $query->where('total_amount', '>=', request('price_min'));
        }
        if (request()->has('price_max') && request()->price_max != null) {
            $query->where('total_amount', '<=', request('price_max'));
        }
        if (request()->has('space_min') && request()->space_min != null) {
            $query->where('total_area', '>=', request('space_min'));
        }
        if (request()->has('space_max') && request()->space_max != null) {
            $query->where('total_area', '<=', request('space_max'));
        }

        $query = QueryBuilder::for($query)
            ->with(['images' => function ($query) {
                $query->oldest()->limit(1);
            }])
            ->select('id', 'title', 'slug','case', 'building_number', 'floor', 'unit_type', 'unit_number', 'bedrooms', 'bathrooms', 'status', 'project_id', 'created_at', 'total_amount', 'total_area')
            ->allowedFields($this->getAllowedFields() ?? [])
            // ->allowedSorts($this->getAllowedSorts() ?? [])
            ->allowedFilters($this->getAllowedFilters() ?? [])
            ->allowedIncludes($this->getAllowedIncludes() ?? [])
            ->paginate(request()->query('per_page'))
            ->appends(request()->query());

        $query->getCollection()->transform(function ($item) {
            // Check if the 'images' relation is loaded and has at least one image
            if ($item->images->isNotEmpty()) {
                $firstImage = $item->images->first();
                $item->image = asset('storage/' . $firstImage->image_path); // Adjust path as needed
            }
            return $item;
        });



        return static::getApiTransformer()::collection($query);
    }

    public function getAllowedSorts(): array
    {
        return ['id', 'project_id', 'total_amount', 'total_area' , 'created_at'];
    }

    public function getAllowedFilters(): array
    {
        return ['title', 'status', 'case', 'unit_type', 'project_id','floor', 'bedrooms', 'bathrooms', 'price_min', 'price_max', 'space_min', 'space_max'];
    }
}
