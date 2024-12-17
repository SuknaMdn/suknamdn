<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Unit;
use App\Models\Project;

class FavoriteController extends Controller
{
    public function getUserFavorite($user_id)
    {
        $favorites = Favorite::where('user_id', $user_id)->with('unit', 'project')->get();

        $units = [];
        $projects = [];

        foreach ($favorites as $favorite) {

            // get unit
            if ($favorite->favoritable_type == Unit::class) {
                $units = Unit::find($favorite->favoritable_id)->select('id', 'title', 'slug', 'images', 'building_number', 'unit_number', 'total_area', 'bedrooms', 'bathrooms', 'unit_price')
                ->get()
                ->map(function ($project) {
                    $project->images = collect($project->images)->map(function ($image) {
                        return asset('storage/' . $image);
                    });
                    return $project;
                });
            }

            // get project with units
            if ($favorite->favoritable_type == Project::class) {
                $projects = Project::find($favorite->favoritable_id)->select('id', 'title', 'slug', 'images', 'area_range_from', 'area_range_to')
                    ->withCount('units')
                    ->with(['units' => function ($unitQuery) {
                        $unitQuery->selectRaw('project_id, MIN(unit_price) as min_price, MAX(unit_price) as max_price')
                                  ->groupBy('project_id');
                    }])
                ->get()
                ->map(function ($project) {
                    $project->images = collect($project->images)->map(function ($image) {
                        return asset('storage/' . $image);
                    });
                    return $project;
                });
            }
        }

        return response()->json([
            'data' => [
                'units' => $units,
                'projects' => $projects
            ],
            'success' => true
        ]);
    }
}
