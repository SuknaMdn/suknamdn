<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
class SearchController extends Controller
{
    public function searchProjects(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|min:2'
        ]);
        dd($request->name);
        // $name = $request->query('name');
        // if($name != null){
        //     // Perform the search
        //     $projects = Project::where('title', 'LIKE', '%' . $request->query('name') . '%')
        //         ->orWhere('description', 'LIKE', '%' . $request->query('name') . '%')
        //         ->orWhere('address', 'LIKE', '%' . $request->query('name') . '%')
        //         ->select('id','title', 'slug','description','address')
        //         ->limit(10)
        //         ->get();
        // }
        // else{
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Name is required',
        //     ]);
        // }

        // Return the results
        return response()->json([
            'status' => true,
            'data' => $projects,
        ]);
    }
}
