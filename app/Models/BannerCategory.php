<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BannerCategory extends Model
{
    use HasFactory, HasUlids;

    /**
     * @var string
     */
    protected $table = 'banner_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'parent_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($banner) {
            $banner->slug = Str::slug($banner->name);
        });

        static::updating(function ($banner) {
            $banner->slug = Str::slug($banner->name);
        });
    }

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(BannerCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(BannerCategory::class, 'parent_id');
    }

    public function banners()
    {
        return $this->hasMany(Banner::class, 'banner_category_id');
    }
}
