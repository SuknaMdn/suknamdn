<?php

namespace App\Filament\Resources\UnitResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filament\Resources\UnitResource;
use App\Models\Unit;

class PaginationHandler extends Handlers
{
    public static string | null $uri = '/';
    public static string | null $resource = UnitResource::class;
    public static bool $public = true;

    public function handler()
    {
        $query = static::getEloquentQuery();
        $model = static::getModel();
        
        $query = QueryBuilder::for($query)
            ->with(['images' => function ($query) {
                $query->oldest()->limit(1);
            }])
            ->select('id', 'title', 'slug', 'case', 'building_number', 'floor', 'unit_type',
                    'unit_number', 'bedrooms', 'bathrooms', 'status', 'project_id',
                    'created_at', 'unit_price','total_amount', 'total_area')
            ->allowedFields($this->getAllowedFields() ?? [])
            ->allowedSorts($this->getAllowedSorts() ?? [])
            ->allowedFilters([
                // Text-based filters with partial matching
                AllowedFilter::callback('title', function ($query, $value) {
                    $query->where('title', 'LIKE', "%{$value}%");
                }),
                AllowedFilter::callback('status', function ($query, $value) {
                    $query->where('status', 'LIKE', "%{$value}%");
                }),
                AllowedFilter::callback('case', function ($query, $value) {
                    $query->where('case', 'LIKE', "%{$value}%");
                }),
                // Regular filters
                'unit_type',
                'project_id',
                'floor',
                'bedrooms',
                'bathrooms',
                'building_number',
                // Range filters
                AllowedFilter::callback('price_min', function ($query, $value) {
                    $query->where('unit_price', '>=', $value);
                }),
                AllowedFilter::callback('price_max', function ($query, $value) {
                    $query->where('unit_price', '<=', $value);
                }),
                AllowedFilter::callback('space_min', function ($query, $value) {
                    $query->where('total_area', '>=', $value);
                }),
                AllowedFilter::callback('space_max', function ($query, $value) {
                    $query->where('total_area', '<=', $value);
                }),
            ])
            ->allowedIncludes($this->getAllowedIncludes() ?? [])
            ->paginate(request()->query('per_page'))
            ->appends(request()->query());
                
        $query->getCollection()->transform(function ($item) {
            if ($item->images->isNotEmpty()) {
                $item->images = $item->images->map(function ($image) {
                    $image->image_path = asset('storage/' . $image->image_path);
                    return $image;
                });
            }
            $item->is_favorite = $this->checkIfFavorite($item);
            return $item;
        });

        return static::getApiTransformer()::collection($query);
    }

    protected function checkIfFavorite($item)
    {
        if (!auth('api')->check()) {
            return false;
        }
        
        return auth('api')->user()
            ->favoritesUnits()
            ->where('favoritable_id', $item->id)
            ->where('favoritable_type', Unit::class)
            ->exists();
    }
    public function getAllowedSorts(): array
    {
        return ['id', 'total_amount', 'unit_price','total_area', 'created_at'];
    }

    public function getAllowedFilters(): array
    {
        return [];
    }
}
