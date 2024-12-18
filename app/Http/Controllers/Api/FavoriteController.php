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
                ->map(function ($unit) use ($favorite) {
                    // $unit->images = collect($unit->images)->map(function ($image) {
                    //     return asset('storage/' . $image);
                    // });
                    $unit->favorite_id = $favorite->id;

                    return $unit;
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
                ->map(function ($project) use ($favorite) {
                    $project->images = collect($project->images)->map(function ($image) {
                        return asset('storage/' . $image);
                    });
                    $project->favorite_id = $favorite->id;
                    return $project;
                });
            }
        }

        return response()->json([
            'data' => [
                'units' => $units,
                'projects' => $projects
            ],
            'status' => true
        ]);
    }

    public function createFavorite(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'favoritable_id' => 'required',
                'favoritable_type' => 'required|string',
            ]);

            $favorite = Favorite::create([
                'user_id' => $request->user_id,
                'favoritable_id' => $request->favoritable_id,
                'favoritable_type' => $request->favoritable_type,
            ]);

            return response()->json([
                'data' => $favorite,
                'status' => true,
                'message' => 'Favorite created successfully.'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'error .' . $th->getMessage()
            ], 201);
        }
    }

    // deleteFavorite
    public function deleteFavorite(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:favorites,id',
                'user_id' => 'required|exists:users,id',
            ]);

            $favorite = Favorite::where('id', $request->id)->where('user_id', $request->user_id)->first();
            if (!$favorite) {
                return response()->json([
                    'status' => false,
                    'message' => 'Favorite not found.'
                ], 201);
            }
            $favorite->delete();
            return response()->json([
                'status' => true,
                'message' => 'Favorite deleted successfully.'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'error .' . $th->getMessage()
            ], 201);
        }
    }
}
