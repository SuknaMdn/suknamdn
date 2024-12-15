<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectLandmark extends Model
{
    use HasFactory;

    protected $fillable = ['icon', 'title', 'distance', 'project_id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getIconAttribute($value)
    {
        return asset('storage/' . $value);
    }
}
