<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Services\MoyasarPaymentService;

class Payment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'currency',
        'status',
        'payment_method',
        'transaction_id',
        'payable_type',
        'payable_id',
        'payment_type',
        'invoice_number',
        'description',
        'user_id',
        'metadata',
        'paid_at',
        'due_date'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'paid_at' => 'datetime',
        'due_date' => 'datetime',
    ];

    /**
     * Get the user associated with the payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent payable model.
     */
    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include payments with a specific status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Check if the payment is paid.
     *
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Generate a unique invoice number.
     *
     * @return string
     */
    public static function generateInvoiceNumber(): string
    {
        return 'INV-' . now()->format('YmdHis') . '-' . random_int(1000, 9999);
    }


    /**
     * Create a Moyasar payment.
     *
     * @param array $data
     * @return \Moyasar\Payment
     * @throws \Exception
     */
    public function processPayment(array $data)
    {
        $paymentService = new MoyasarPaymentService();

        // Create a payment using the Moyasar service
        $moyasarPayment = $paymentService->createPayment($data, $this);

        // Access the array keys correctly
        $this->transaction_id = $moyasarPayment['id'];
        $this->status = $moyasarPayment['status'];
        $this->save();

        return $moyasarPayment;
    }

    /**
     * Get the related payable model's name.
     *
     * @return string
     */
    public function getPayableNameAttribute()
    {
        if ($this->payable_type && $this->payable_id) {
            $payableModel = $this->payable; // This will return the related model (Unit or SubscriptionPlan)
            return $payableModel ? $payableModel->title : 'Unknown';
        }
        return 'Unknown';
    }

}
