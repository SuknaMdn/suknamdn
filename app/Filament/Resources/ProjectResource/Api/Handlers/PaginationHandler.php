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

    protected $selectedFields = [
        'id', 'title', 'is_active', 'is_featured', 'purpose',
        'city_id', 'state_id', 'developer_id', 'area_range_from',
        'area_range_to', 'images', 'property_type_id',
    ];

    public function handler()
    {
        $query = $this->buildBaseQuery();
        $query = $this->applyFilters($query);
        $paginatedResults = $this->paginateResults($query);
        $this->transformResults($paginatedResults);

        return static::getApiTransformer()::collection($paginatedResults);
    }

    protected function buildBaseQuery()
    {
        return QueryBuilder::for(static::getEloquentQuery())
            ->select($this->selectedFields)
            ->where('is_active', true)
            ->with([
                'developer:id,name,logo',
                'city:id,name',
                'state:id,name',
                'propertyType:id,name'
            ]);
    }

    protected function applyFilters($query)
    {
        return $query
            ->allowedFields($this->getAllowedFields() ?? [])
            ->allowedSorts($this->getAllowedSorts() ?? [])
            ->allowedFilters($this->getAllowedFilters() ?? [])
            ->allowedIncludes($this->getAllowedIncludes() ?? [])
            ->when(request()->hasAny(['price_min', 'price_max', 'space_min', 'space_max']), function ($query) {
                $query->whereHas('units', function ($subQuery) {
                    $this->applyUnitFilters($subQuery);
                });
            });
    }

    protected function applyUnitFilters($query)
    {
        $filters = [
            'price_min' => ['total_amount', '>='],
            'price_max' => ['total_amount', '<='],
            'space_min' => ['total_area', '>='],
            'space_max' => ['total_area', '<='],
        ];

        foreach ($filters as $param => [$column, $operator]) {
            if (request()->filled($param)) {
                $query->where($column, $operator, request($param));
            }
        }
    }

    protected function paginateResults($query)
    {
        return $query->paginate(request()->query('per_page'))
                    ->appends(request()->query());
    }

    protected function transformResults($paginatedResults)
    {
        $paginatedResults->getCollection()->transform(function ($item) {

            // Get unit counts
            $totalUnits = $item->units()->count();
            $availableUnits = $item->units()->where('status', 1)->where('case', 0)->count();

            // Add counts to the item
            $item->available_units = $availableUnits;
            $item->total_units = $totalUnits;

            $item->starting_from = $this->getFormattedStartingPrice($item);
            $item->is_favorite = $this->checkIfFavorite($item);
            $item->images = $this->transformImages($item->images);
            $item->property_type = $item->propertyType?->name;
            if ($item->developer && $item->developer->logo) {
                $item->developer->logo = asset('storage/' . $item->developer->logo);
            }
            return $item;
        });
    }

    protected function getFormattedStartingPrice($item)
    {
        $startingPrice = $item->units()->min('total_amount');
        return $startingPrice ? number_format($startingPrice, 2, '.', ',') : null;
    }

    protected function checkIfFavorite($item)
    {
        if (!auth('api')->check()) {
            return false;
        }

        return auth('api')->user()
            ->favoritesProjects()
            ->where('favoritable_id', $item->id)
            ->where('favoritable_type', Project::class)
            ->exists();
    }

    protected function transformImages($images)
    {
        return $images ? collect($images)->map(fn($image) => asset('storage/' . $image)) : null;
    }

    public function getAllowedSorts(): array
    {
        return ['id', 'title', 'is_active', 'is_featured', 'created_at'];
    }

    public function getAllowedFilters(): array
    {
        return [
            'title', 'purpose', 'city_id', 'property_type_id',
            'state_id', 'developer_id', 'price_min', 'price_max',
            'space_min', 'space_max'
        ];
    }
}