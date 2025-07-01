<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderInstallment extends Model
{
    protected $guarded = [];

    public function unitOrder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(UnitOrder::class);
    }
    
    public function milestone(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProjectPaymentMilestone::class, 'project_payment_milestone_id');
    }

    /**
     * Get the status in a translated, human-readable format.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function statusTranslated(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn ($value, $attributes) => match ($attributes['status']) {
                'paid' => 'مدفوع',
                'due' => 'مستحق',
                'pending' => 'في الانتظار',
                'overdue' => 'متأخر',
                default => $attributes['status'],
            }
        );
    }

    /**
     * Get a color name for the status badge.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function statusColor(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn ($value, $attributes) => match ($attributes['status']) {
                'paid' => 'success',
                'due' => 'warning',
                'pending' => 'primary',
                'overdue' => 'danger',
                default => 'dark',
            }
        );
    }
}
