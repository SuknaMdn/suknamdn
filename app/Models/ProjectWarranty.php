<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ProjectWarranty extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // public function getIconAttribute($value)
    // {
    //     return asset('storage/' . $value);
    // }
}
