<?php
namespace App\Filament\Resources\FavoriteResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filament\Resources\FavoriteResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaginationHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = FavoriteResource::class;


    public function handler()
    {
        $query = static::getEloquentQuery();

        // Filter to only show the current user's favorites
        $query = $query->where('user_id', Auth::id());

        $model = static::getModel();

        // Clone the query for units and projects
        $unitsQuery = clone $query;
        $projectsQuery = clone $query;

        // Filter for units
        $unitsQuery = $unitsQuery->where('favoritable_type', 'App\Models\Unit');

        // Filter for projects
        $projectsQuery = $projectsQuery->where('favoritable_type', 'App\Models\Project');

        // Get units with relationships
        $units = QueryBuilder::for($unitsQuery)
            ->select(['id', 'user_id', 'favoritable_type', 'favoritable_id'])
            ->allowedFields($this->getAllowedFields() ?? [])
            ->allowedSorts($this->getAllowedSorts() ?? [])
            ->allowedFilters($this->getAllowedFilters() ?? [])
            ->with(['favoritable' => function ($query) {
                $query->with(['images' => function ($imageQuery) {
                    $imageQuery->select(['id', 'unit_id', 'image_path'])
                        ->get()
                        ->map(function ($image) {
                            // Transform image path to full URL
                            $image->full_image_path = $image->image_path
                                ? Storage::url($image->image_path)
                                : null;
                            return $image;
                        });
                }])
                ->select([
                    'id',
                    'title',
                    'building_number',
                    'unit_number',
                    'unit_type',
                    'floor',
                    'total_area',
                    'bedrooms',
                    'bathrooms',
                    'unit_price'
                ]);
            }])
            ->get();

        // Get projects with relationships
        $projects = QueryBuilder::for($projectsQuery)
            ->select(['id', 'user_id', 'favoritable_type', 'favoritable_id'])
            ->allowedFields($this->getAllowedFields() ?? [])
            ->allowedSorts($this->getAllowedSorts() ?? [])
            ->allowedFilters($this->getAllowedFilters() ?? [])
            // ->with(['favoritable:id,title,slug,images,area_range_from,area_range_to'])
            ->with(['favoritable' => function ($query) {
                $query->select(['id', 'title', 'slug', 'images', 'area_range_from', 'area_range_to']) // Select only the required fields from project
                    ->withCount('units')
                    ->with(['units' => function ($unitQuery) {
                        // Fetch all units and get min and max price globally for the project
                        $unitQuery->selectRaw('project_id, MIN(unit_price) as min_price, MAX(unit_price) as max_price')
                                  ->groupBy('project_id'); // Group by project_id to get the min/max for all units related to the project
                    }]);
            }])
            ->get()
            ->map(function ($favorite) {
                // Transform project to include full image paths
                $project = $favorite->favoritable;

                // Check if images exist and is a JSON string or array
                $images = is_string($project->images)
                    ? json_decode($project->images, true)
                    : $project->images;

                // Generate full image URLs
                $fullImagePaths = $images ? array_map(function($image) {
                    return Storage::url($image); // Assuming images are stored using Laravel's Storage
                }, $images) : [];

                // Add full image paths to the project
                $project->images = $fullImagePaths;

                // Add units count and price range
                $project->units_count = $project->units_count ?? 0;
                $project->min_price = $project->units[0]->min_price ?? null;
                $project->max_price = $project->units[0]->max_price ?? null;

                return $favorite;


            });

        // Return structured response
        return response()->json([
            'data' => [
                'units' => static::getApiTransformer()::collection($units),
                'projects' => static::getApiTransformer()::collection($projects),
            ],
            'success' => true
        ]);
    }
}
