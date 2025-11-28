<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\OrderPaymentType;
use App\Helpers\CurrencyHelper;

class OrderPayment extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'order_id',
        'invoice_id',
        'user_id',
        'payment_type',
        'amount',
        'is_paid',
        'is_payable',
        'payment_date',
    ];

    protected $casts = [
        'payment_type' => OrderPaymentType::class,
        'amount' => 'decimal:2',
        'is_paid' => 'boolean',
        'is_payable' => 'boolean',
        'payment_date' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get formatted amount with currency
     */
    public function getFormattedAmountAttribute(): string
    {
        return CurrencyHelper::format((float) $this->amount);
    }

    // Scopes
    public function scopeSubtotal($query)
    {
        return $query->where('payment_type', OrderPaymentType::SUBTOTAL);
    }

    public function scopeTax($query)
    {
        return $query->where('payment_type', OrderPaymentType::TAX);
    }

    public function scopeDiscount($query)
    {
        return $query->where('payment_type', OrderPaymentType::DISCOUNT);
    }

    public function scopeTotal($query)
    {
        return $query->where('payment_type', OrderPaymentType::TOTAL);
    }

    public function scopeForOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }

    public function scopeForInvoice($query, $invoiceId)
    {
        return $query->where('invoice_id', $invoiceId);
    }

    public function scopePaid($query)
    {
        return $query->where('is_paid', true);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('is_paid', false);
    }

    public function scopePayable($query)
    {
        return $query->where('is_payable', true);
    }

    public function scopeNotPayable($query)
    {
        return $query->where('is_payable', false);
    }
}
