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
        if (request()->has('price_min')) {
            $query->where('total_amount', '>=', request('price_min'));
        }
        if (request()->has('price_max')) {
            $query->where('total_amount', '<=', request('price_max'));
        }
        if (request()->has('space_min')) {
            $query->where('total_area', '>=', request('space_min'));
        }
        if (request()->has('space_max')) {
            $query->where('total_area', '<=', request('space_max'));
        }

        // Handle sorting by allowed sorts
        $sort = request()->query('sort'); // Assuming you send the sort option as a query parameter (e.g., 'sort=price_asc')

        if ($sort) {
            switch ($sort) {
                case 'price_asc':
                    $query->orderBy('total_amount', 'asc'); // Sort by lowest price
                    break;
                case 'price_desc':
                    $query->orderBy('total_amount', 'desc'); // Sort by highest price
                    break;
                case 'space_asc':
                    $query->orderBy('total_area', 'asc'); // Sort by smallest area
                    break;
                case 'space_desc':
                    $query->orderBy('total_area', 'desc'); // Sort by largest area
                    break;
                default:
                    // Handle any other sorting options that are not specifically defined
                    // You can add a default sorting option if needed
                    $query->orderBy('created_at', 'desc');
            }
        }

        $query = QueryBuilder::for($query)
            ->with(['images' => function ($query) {
                $query->oldest()->limit(1);
            }])
            ->select('id', 'title', 'slug','case', 'building_number', 'floor', 'unit_type', 'unit_number', 'bedrooms', 'bathrooms', 'status', 'project_id', 'created_at', 'total_amount', 'total_area')
            ->allowedFields($this->getAllowedFields() ?? [])
            ->allowedSorts($this->getAllowedSorts() ?? [])
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

            // // Handle additional features images
            // if ($item->additionalFeatures()->exists()) {
            //     $item->additionalFeatures = $item->additionalFeatures->map(function ($additionalFeature) {
            //         $additionalFeature->icon = asset('storage/' . $additionalFeature->icon);  // Adjust path as needed
            //         return $additionalFeature;
            //     });
            // }

            // // Handle after sales services images
            // if ($item->afterSalesServices()->exists()) {
            //     $item->afterSalesServices = $item->afterSalesServices->map(function ($afterSalesService) {
            //         $afterSalesService->icon = asset('storage/' . $afterSalesService->icon);  // Adjust path as needed
            //         return $afterSalesService;
            //     });
            // }

            return $item;
        });



        return static::getApiTransformer()::collection($query);
    }

    public function getAllowedSorts(): array
    {
        return ['id', 'title', 'status', 'project_id', 'created_at', 'price_asc', 'price_desc', 'space_asc', 'space_desc'];
    }

    public function getAllowedFilters(): array
    {
        return ['title', 'status', 'case', 'unit_type', 'project_id','floor', 'bedrooms', 'bathrooms', 'price_min', 'price_max', 'space_min', 'space_max'];
    }
}
