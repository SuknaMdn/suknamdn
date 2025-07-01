<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
class UnitOrder extends Model
{
    protected $guarded = [];

    public function installments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderInstallment::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the payment associated with the order
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    
    // يستخدم لحساب نسبة شريط التقدم
    protected function paidInstallmentsCount(): Attribute
    {
        return Attribute::make(get: fn () => $this->installments->where('status', 'paid')->count());
    }
    
    // يستخدم لحساب نسبة شريط التقدم
    protected function totalInstallmentsCount(): Attribute
    {
        return Attribute::make(get: fn () => $this->installments->count());
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
                'pending' => 'في الانتظار',
                'processing' => 'قيد المعالجة',
                'completed' => 'مكتمل',
                'cancelled' => 'ملغي',
                default => $attributes['status'],
            }
        );
    }

    /**
     * Generate a timeline of events for the order.
     *
     * @return array
     */
    public function getTimeline(): array
    {
        $events = [];

        if ($this->istisna_contract_url) {
            $events[] = ['date' => $this->updated_at, 'text' => 'تم رفع عقد الاستصناع', 'icon' => 'ki-document'];
        }
        if ($this->price_quote_url) {
            $events[] = ['date' => $this->updated_at, 'text' => 'تم رفع عرض السعر', 'icon' => 'ki-price-tag'];
        }

        foreach ($this->installments->where('status', 'paid') as $installment) {
            $events[] = [
                'date' => $installment->paid_at,
                'text' => 'تم دفع ' . $installment->milestone?->name . ' - ' . number_format($installment->amount) . ' ر.س',
                'icon' => 'ki-financial-schedule'
            ];
        }

        // Sort events by date
        usort($events, fn($a, $b) => $a['date'] <=> $b['date']);

        return $events;
    }
}
