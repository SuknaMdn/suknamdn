<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NafathAuthentication extends Model
{
    protected $fillable = [
        'nafath_request_id',
        'nafath_trans_id',
        'user_type',
        'authentication_status',
        'authenticated_at',
        'first_name',
        'second_name',
        'third_name',
        'last_name',
        'father_name',
        'grandfather_name',
        'family_name',
        'english_first_name',
        'english_second_name',
        'english_third_name',
        'english_last_name',
        'gender',
        'nationality',
        'nationality_code',
        'nationality_desc',
        'date_of_birth_g',
        'date_of_birth_h',
        'national_id',
        'id_version_number',
        'id_issue_place',
        'id_issue_date_g',
        'id_issue_date_h',
        'id_expiry_date_g',
        'id_expiry_date_h',
        'iqama_number',
        'iqama_version_number',
        'iqama_expiry_date_g',
        'iqama_expiry_date_h',
        'iqama_issue_date_g',
        'iqama_issue_date_h',
        'iqama_issue_place_code',
        'iqama_issue_place_desc',
        'sponsor_name',
        'legal_status',
        'social_status_code',
        'social_status_desc',
        'occupation_code',
        'place_of_birth',
        'passport_number',
        'is_minor',
        'total_dependents',
        'raw_data',
    ];

    protected $casts = [
        'authenticated_at' => 'datetime',
        'date_of_birth_g' => 'date',
        'id_issue_date_g' => 'date',
        'id_expiry_date_g' => 'date',
        'iqama_expiry_date_g' => 'date',
        'iqama_issue_date_g' => 'date',
        'is_minor' => 'boolean',
        'total_dependents' => 'integer',
        'raw_data' => 'array',
    ];

    public function nafath(): BelongsTo
    {
        return $this->belongsTo(Nafath::class, 'nafath_request_id', 'request_id');
    }
}