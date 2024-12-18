<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
class SearchController extends Controller
{
    public function searchProjects(Request $request)
    {
        // Check if a search query is provided
        if ($request->has('name') && !empty($request->query('name'))) {
            // Perform search when name is provided
            $projects = Project::where('title', 'LIKE', '%' . $request->query('name') . '%')
                ->orWhere('description', 'LIKE', '%' . $request->query('name') . '%')
                ->orWhere('address', 'LIKE', '%' . $request->query('name') . '%')
                ->select('id', 'title', 'slug', 'description', 'address')
                ->get();
        } else {
            // Get all projects when no search query is provided
            $projects = Project::select('id', 'title', 'slug', 'description', 'address')
                ->get();
        }

        return response()->json([
            'status' => true,
            'data' => $projects,
            'total' => $projects->count(),
            'message' => $request->has('name') && !empty($request->query('name'))
                ? 'Projects found successfully'
                : 'All projects retrieved successfully'
        ]);
    }
}
