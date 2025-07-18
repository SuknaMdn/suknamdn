<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nafath extends Model
{
    protected $fillable = [
        'user_id',
        'national_id',
        'id_type',
        'full_name',
        'date_of_birth',
        'gender',
        'nationality',
        'status',
        'response_data',
        'verified_at',
        'expires_at',
        'request_id',
        'transaction_id',
        'random_number',
    ];

    protected $casts = [
        'response_data' => 'array',
        'verified_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function authentications(): HasMany
    {
        return $this->hasMany(NafathAuthentication::class, 'nafath_request_id', 'request_id');
    }

    public function nationalAddresses(): HasMany
    {
        return $this->hasMany(NafathNationalAddress::class, 'nafath_request_id', 'request_id');
    }
}
