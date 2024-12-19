<?php
namespace App\Filament\Resources\ProjectResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filament\Resources\ProjectResource;
use App\Models\Project;

class PaginationHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = ProjectResource::class;
    public static bool $public = true;

    public function handler()
    {
        $query = static::getEloquentQuery();
        $model = static::getModel();

        $query = QueryBuilder::for($query)
            ->select([
                'id',
                'title',
                'is_active',
                'is_featured',
                'purpose',
                'city_id',
                'state_id',
                'developer_id',
                'images',
                'property_type_id',
            ])
            ->where('is_active', true)
            ->with(['developer:id,name,logo', 'city:id,name', 'state:id,name', 'propertyType:id,name'])
            ->allowedFields($this->getAllowedFields() ?? [])
            ->allowedSorts($this->getAllowedSorts() ?? [])
            ->allowedFilters($this->getAllowedFilters() ?? [])
            // Apply min/max filters for price and space based on related 'units' table
            ->whereHas('units', function ($query) {
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
            })
            ->allowedIncludes($this->getAllowedIncludes() ?? [])
            ->paginate(request()->query('per_page'))
            ->appends(request()->query());

            $query->getCollection()->transform(function ($item) {


                // Add starting price to the project
                $startingPrice = $item->units()->min('total_amount');
                $item->starting_from = $startingPrice ? number_format($startingPrice, 2, '.', ',') : null;

                // Check if the user is authenticated via API using Bearer token
                if (auth('api')->check()) {
                    $user = auth('api')->user();
                    // Check if the item is in the user's favorites using polymorphic relationship
                    $item->is_favorite = $user->favoritesProjects()
                        ->where('favoritable_id', $item->id)
                        ->where('favoritable_type', Project::class)
                        ->exists();
                } else {
                    $item->is_favorite = false;
                }

                if ($item->images) {
                    $item->images = collect($item->images)->map(function ($image) {
                        return asset('storage/' . $image);
                    });
                }

                $item->property_type = $item->propertyType ? $item->propertyType->name : null;

                return $item;
            });

        return static::getApiTransformer()::collection($query);
    }

    public function getAllowedSorts(): array
    {
        return ['id', 'title', 'is_active', 'is_featured', 'created_at'];
    }

    public function getAllowedFilters(): array
    {
        return ['title', 'purpose', 'city_id', 'property_type_id', 'state_id', 'developer_id', 'price_min', 'price_max', 'space_min', 'space_max'];
    }
}
