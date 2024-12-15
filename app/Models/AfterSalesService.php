<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AfterSalesService extends Model
{
    protected $fillable = ['title', 'description', 'icon'];

    public function units(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class);
    }

    public function getIconAttribute($value)
    {
        return asset('storage/' . $value);
    }
}
