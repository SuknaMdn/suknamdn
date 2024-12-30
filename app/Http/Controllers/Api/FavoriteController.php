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
        $favorites = Favorite::where('user_id', $user_id)->get();

        $units = collect();
        $projects = collect();

        foreach ($favorites as $favorite) {

            // get unit
            if ($favorite->favoritable_type == Unit::class) {
                $unit = Unit::where('id', $favorite->favoritable_id)
                    ->select('id', 'title', 'slug','case', 'building_number', 'floor', 'unit_type', 'unit_number', 'bedrooms', 'bathrooms', 'status', 'project_id', 'created_at', 'total_amount', 'total_area')
                    ->with(['images' => function ($query) {
                        $query->select('id', 'unit_id','image_path');
                    }])
                    ->first();

                if ($unit) {
                    // Transform images
                    if ($unit->images->isNotEmpty()) {
                        $firstImage = $unit->images->first();
                        $unit->image = asset('storage/' . $firstImage->image_path);
                    }
                    $unit->favorite_id = $favorite->id;
                    $unit->makeHidden('images'); // Hide the field

                    $units->push($unit);
                }
            }
            // get project
            if ($favorite->favoritable_type == Project::class) {

                $project = Project::where('id', $favorite->favoritable_id)
                    ->select('id', 'title', 'slug', 'images', 'area_range_from', 'area_range_to', 'is_active', 'is_featured', 'purpose', 'city_id', 'state_id', 'developer_id', 'property_type_id')
                    ->withCount('units')
                    ->with([
                        'units' => function ($unitQuery) {
                            $unitQuery->selectRaw('project_id, MIN(unit_price) as min_price, MAX(unit_price) as max_price')
                                    ->groupBy('project_id');
                        },
                        'propertyType' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'developer' => function ($query) {
                            $query->select('id', 'name', 'logo');
                        },
                        'city' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'state' => function ($query) {
                            $query->select('id', 'name');
                        }
                    ])
                    ->first();

                if ($project) {
                    $project->images = collect($project->images)->map(function ($image) {
                        return asset('storage/' . $image);
                    });
                    $project->favorite_id = $favorite->id;
                    $project->makeHidden('units'); // Hide the units field
                    $projects->push($project);
                }
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

    // toggle Favorite add, remove
    public function toggleFavorite(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'favoritable_id' => 'required',
                'favoritable_type' => 'required|string',
            ]);

            // Validate that favoritable_type is either Unit or Project
            if (!in_array($request->favoritable_type, ['App\\Models\\Unit', 'App\\Models\\Project'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid favoritable type. Must be either Unit or Project.'
                ], 422);
            }

            // Check if the favoritable model exists (Unit or Project)
            $model = $request->favoritable_type;
            $favoritable = $model::find($request->favoritable_id);

            if (!$favoritable) {
                return response()->json([
                    'status' => false,
                    'message' => 'Favoritable item not found.'
                ], 404);
            }

            // Check if favorite already exists
            $favorite = Favorite::where([
                'user_id' => $request->user_id,
                'favoritable_id' => $request->favoritable_id,
                'favoritable_type' => $request->favoritable_type,
            ])->first();

            if ($favorite) {
                // If favorite exists, delete it
                $favorite->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Favorite removed successfully.',
                    'is_favorited' => false
                ], 200);
            }

            // If favorite doesn't exist, create it
            $favorite = Favorite::create([
                'user_id' => $request->user_id,
                'favoritable_id' => $request->favoritable_id,
                'favoritable_type' => $request->favoritable_type,
            ]);

            return response()->json([
                'data' => $favorite,
                'status' => true,
                'message' => 'Favorite added successfully.',
                'is_favorited' => true
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $th->getMessage()
            ], 500);
        }
    }
}
