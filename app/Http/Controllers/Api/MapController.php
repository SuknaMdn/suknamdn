<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class MapController extends Controller
{
    public function index()
    {
        $projects = Project::where('is_active', true)
        ->select('id', 'title',  'latitude', 'longitude', 'slug', 'images', 'developer_id','area_range_from', 'area_range_to', 'is_active', 'is_featured', 'purpose', 'city_id', 'state_id', 'property_type_id')
        ->with([
            'developer' => function($developerQuery){
                $developerQuery->select('id', 'logo');
            }
            ,
            'units' => function ($unitQuery) {
                $unitQuery->selectRaw('project_id, MIN(unit_price) as min_price, MAX(unit_price) as max_price')
                          ->groupBy('project_id');
            },
            'propertyType' => function ($query) {
                $query->select('id', 'name');
            },
            'city' => function ($query) {
                $query->select('id', 'name');
            },
            'state' => function ($query) {
                $query->select('id', 'name');
            }
        ])
        ->get();

        // Transform the images to include the full path
        $projects->each(function ($project) {

            // Add starting price to the project
            // $startingPrice = $project->units()->min('unit_price');
            // $project->starting_from = $startingPrice ? number_format($startingPrice, 2, '.', ',') : null;

            $startingPrice = $project->units()->min('unit_price');
            $project->starting_from = $startingPrice ? formatToArabic($startingPrice) : null;


            // Check if the user is authenticated via API using Bearer token
            if (auth('api')->check()) {
                $user = auth('api')->user();
                // Check if the item is in the user's favorites using polymorphic relationship
                $project->is_favorite = $user->favoritesProjects()
                    ->where('favoritable_id', $project->id)
                    ->where('favoritable_type', Project::class)
                    ->exists();
            } else {
                $project->is_favorite = false;
            }

            $project->property_type = $project->propertyType ? $project->propertyType->name : null;

            $project->images = collect($project->images)->map(function ($image) {
                return asset('storage/' . $image);
            });
            // Merge latitude and longitude into latlong
            $project->latlong = "({$project->latitude},{$project->longitude})";

            // developer logo
            $project->developer_logo = $project->developer && $project->developer->logo 
                ? asset('storage/' . $project->developer->logo) 
                : null;
            $project->makeHidden('units','latitude', 'longitude','developer'); // Hide the units field

        });

        return response()->json(['data' => $projects]);
    }
}
