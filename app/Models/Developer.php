<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Developer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'description',
        'logo',
        'is_active',
        'user_id',
        'address',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($developer) {
            $developer->slug = Str::slug($developer->name);
        });

        static::updating(function ($developer) {
            $developer->slug = Str::slug($developer->name);
        });
    }

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
