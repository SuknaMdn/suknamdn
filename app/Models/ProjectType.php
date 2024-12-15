<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProjectType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'property_type_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ptype) {
            $ptype->slug = Str::slug($ptype->name);
        });

        static::updating(function ($ptype) {
            $ptype->slug = Str::slug($ptype->name);
        });
    }

}
