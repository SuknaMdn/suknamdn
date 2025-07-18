<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Developer;
use App\Models\Project;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class FilterController extends Controller
{
    public function getProjectsFilterParameters() {
        try {
            // Get Cities & Developers
            $cities = City::whereHas('projects')
                ->where('status', 1)
                ->select('id', 'name', 'status')
                ->get();

            $developers = Developer::whereHas('projects')
                ->where('is_active', 1)
                ->select('id', 'name', 'is_active', 'logo')
                ->get()
                ->map(function ($developer) {
                    $developer->logo = $developer->logo ? asset('storage/' . $developer->logo)  : null;
                    return $developer;
                });

            return response()->json([
                'cities' => $cities,
                'developers' => $developers,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong!',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getUnitsFilterParameters($project_id)
    {
        // Get the project
        $project = Project::findOrFail($project_id);

        if ($project == null) {
            return response()->json([
                'message' => "Not Found",
                'status'  => 'error',
            ], 200);
        }

        // Get related units and retrieve unique values for filtering
        $floors = $project->units()->pluck('floor')->unique()->toArray();
        $buildingNumbers = $project->units()->pluck('building_number')->unique()->toArray();

        return response()->json([
            'floors' => $floors,
            'building_numbers' => $buildingNumbers,
        ], 200);
    }

}
