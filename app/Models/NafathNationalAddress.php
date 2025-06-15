<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NafathNationalAddress extends Model
{
    protected $fillable = [
        'nafath_request_id',
        'street_name',
        'city',
        'additional_number',
        'district',
        'unit_number',
        'building_number',
        'post_code',
        'location_coordinates',
        'is_primary_address',
        'city_id',
        'region_id',
        'district_id',
        'region_name_l2',
        'city_l2',
        'street_l2',
        'district_l2',
        'region_name',
    ];

    protected $casts = [
        'is_primary_address' => 'boolean',
    ];

    public function nafath(): BelongsTo
    {
        return $this->belongsTo(Nafath::class, 'nafath_request_id', 'request_id');
    }
}
