<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
class MapController extends Controller
{
    public function index()
    {
        $projects = Project::where('is_active', true)->select('id', 'title', 'latitude', 'longitude')->get();
        return response()->json($projects);
    }
}
