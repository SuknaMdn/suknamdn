<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nafath extends Model
{
    protected $fillable = [
        'user_id', 'transaction_id', 'national_id', 'id_type',
        'full_name', 'date_of_birth', 'gender', 'nationality',
        'status', 'response_data', 'verified_at', 'expires_at'
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
}
