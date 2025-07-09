<?php
namespace App\Filament\Resources\FavoriteResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filament\Resources\FavoriteResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Project;
use App\Models\Unit;

class PaginationHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = FavoriteResource::class;

    public function handler()
    {
        $query = static::getEloquentQuery();
        $query = $query->where('user_id', Auth::id());

        // Clone the query for units and projects
        $unitsQuery = clone $query;
        $projectsQuery = clone $query;

        // Filter for units
        $unitsQuery = $unitsQuery->where('favoritable_type', Unit::class);

        // Filter for projects
        $projectsQuery = $projectsQuery->where('favoritable_type', Project::class);

        // Get paginated units with relationships (matching main units API)
        $units = QueryBuilder::for($unitsQuery)
            ->allowedFields($this->getAllowedFields() ?? [])
            ->allowedSorts($this->getAllowedSorts() ?? [])
            ->allowedFilters($this->getAllowedFilters() ?? [])
            ->with(['favoritable' => function($query) {
                $query->select('id', 'title', 'slug', 'case', 'building_number', 'floor', 'unit_type',
                    'unit_number', 'bedrooms', 'bathrooms', 'status', 'project_id',
                    'created_at', 'unit_price', 'total_amount', 'total_area')
                    ->with(['images' => function($imageQuery) {
                        $imageQuery->oldest()->limit(1);
                    }]);
            }])
            ->paginate(request()->query('per_page', 15))
            ->through(function($favorite) {
                $unit = $favorite->favoritable;
                
                if (!$unit) return null;
                
                if ($unit->images->isNotEmpty()) {
                    $unit->images = $unit->images->map(function($image) {
                        $image->image_path = asset('storage/' . $image->image_path);
                        return $image;
                    });
                }
                
                $unit->is_favorite = true;
                return $unit;
            })
            ->filter();

        // Get paginated projects with relationships (matching main projects API)
        $projects = QueryBuilder::for($projectsQuery)
            ->allowedFields($this->getAllowedFields() ?? [])
            ->allowedSorts($this->getAllowedSorts() ?? [])
            ->allowedFilters($this->getAllowedFilters() ?? [])
            ->with(['favoritable' => function($query) {
                $query->select([
                    'id', 'title', 'is_active', 'is_featured', 'purpose',
                    'city_id', 'state_id', 'developer_id', 'area_range_from',
                    'area_range_to', 'images', 'property_type_id', 'completion_percentage',
                    'enables_payment_plan', 'created_at', 'updated_at'
                ])
                ->with([
                    'developer:id,name,logo',
                    'city:id,name',
                    'state:id,name',
                    'propertyType:id,name',
                    'units' => function($unitQuery) {
                        $unitQuery->selectRaw('project_id, MIN(unit_price) as min_price, MAX(unit_price) as max_price')
                                  ->groupBy('project_id');
                    }
                ]);
            }])
            ->paginate(request()->query('per_page', 15))
            ->through(function($favorite) {
                $project = $favorite->favoritable;
                
                if (!$project) return null;
                
                // Transform images
                $images = is_string($project->images) 
                    ? json_decode($project->images, true) 
                    : $project->images;
                
                $project->images = $images ? collect($images)->map(fn($image) => asset('storage/' . $image)) : null;
                
                // Get unit counts
                $totalUnits = $project->units()->count();
                $availableUnits = $project->units()->where('status', 1)->where('case', 0)->count();
                
                // Add counts to the project
                $project->available_units = $availableUnits;
                $project->total_units = $totalUnits;
                
                // Add starting price
                $startingPrice = $project->units()->min('unit_price');
                $project->starting_from = $startingPrice ? number_format($startingPrice, 0, '.', ',') : null;
                
                // Set favorite status
                $project->is_favorite = true;
                
                // Transform developer logo
                if ($project->developer && $project->developer->logo) {
                    $logo = $project->developer->logo;
                    if (!str_starts_with($logo, 'http://') && !str_starts_with($logo, 'https://')) {
                        $logo = asset('storage/' . $logo);
                    }
                    $project->developer->logo = $logo;
                }
                
                // Add property type name
                $project->property_type = $project->propertyType?->name;
                
                return $project;
            })
            ->filter();

        // Return structured response matching the main API format
        return response()->json([
            'data' => [
                'units' => [
                    'data' => $units->items(),
                    'links' => [
                        'first' => $units->url(1),
                        'last' => $units->url($units->lastPage()),
                        'prev' => $units->previousPageUrl(),
                        'next' => $units->nextPageUrl(),
                    ],
                    'meta' => [
                        'current_page' => $units->currentPage(),
                        'from' => $units->firstItem(),
                        'last_page' => $units->lastPage(),
                        'links' => $this->paginationLinks($units),
                        'path' => $units->path(),
                        'per_page' => $units->perPage(),
                        'to' => $units->lastItem(),
                        'total' => $units->total(),
                    ]
                ],
                'projects' => [
                    'data' => $projects->items(),
                    'links' => [
                        'first' => $projects->url(1),
                        'last' => $projects->url($projects->lastPage()),
                        'prev' => $projects->previousPageUrl(),
                        'next' => $projects->nextPageUrl(),
                    ],
                    'meta' => [
                        'current_page' => $projects->currentPage(),
                        'from' => $projects->firstItem(),
                        'last_page' => $projects->lastPage(),
                        'links' => $this->paginationLinks($projects),
                        'path' => $projects->path(),
                        'per_page' => $projects->perPage(),
                        'to' => $projects->lastItem(),
                        'total' => $projects->total(),
                    ]
                ]
            ],
            'success' => true
        ]);
    }

    protected function paginationLinks($paginator)
    {
        return [
            [
                'url' => $paginator->previousPageUrl(),
                'label' => '&laquo; Previous',
                'active' => false
            ],
            [
                'url' => $paginator->url($paginator->currentPage()),
                'label' => $paginator->currentPage(),
                'active' => true
            ],
            [
                'url' => $paginator->nextPageUrl(),
                'label' => 'Next &raquo;',
                'active' => false
            ]
        ];
    }
}