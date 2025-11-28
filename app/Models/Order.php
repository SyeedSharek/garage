<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\OrderStatus;
use App\Enums\OrderPaymentType;
use App\Helpers\CurrencyHelper;
use App\Traits\HasNotes;
use App\Traits\GeneratesUniqueNumber;

class Order extends Model
{
    use SoftDeletes, HasNotes, GeneratesUniqueNumber;
    protected $fillable = [
        'order_number',
        'customer_id',
        'user_id',
        'status',
        'order_date',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'order_date' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class)->orderBy('serial_number');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(OrderPayment::class)->orderBy('payment_date');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get subtotal payments
     */
    public function subtotalPayments(): HasMany
    {
        return $this->hasMany(OrderPayment::class)
            ->where('payment_type', OrderPaymentType::SUBTOTAL);
    }

    /**
     * Get tax payments
     */
    public function taxPayments(): HasMany
    {
        return $this->hasMany(OrderPayment::class)
            ->where('payment_type', OrderPaymentType::TAX);
    }

    /**
     * Get discount payments
     */
    public function discountPayments(): HasMany
    {
        return $this->hasMany(OrderPayment::class)
            ->where('payment_type', OrderPaymentType::DISCOUNT);
    }

    /**
     * Get total payments
     */
    public function totalPayments(): HasMany
    {
        return $this->hasMany(OrderPayment::class)
            ->where('payment_type', OrderPaymentType::TOTAL);
    }

    /**
     * Get subtotal from payments
     */
    public function getSubtotalAttribute(): float
    {
        return (float) $this->subtotalPayments()->sum('amount');
    }

    /**
     * Get tax amount from payments
     */
    public function getTaxAmountAttribute(): float
    {
        return (float) $this->taxPayments()->sum('amount');
    }

    /**
     * Get discount amount from payments
     */
    public function getDiscountAmountAttribute(): float
    {
        return (float) $this->discountPayments()->sum('amount');
    }

    /**
     * Get total amount from payments
     */
    public function getTotalAmountAttribute(): float
    {
        return (float) $this->totalPayments()->sum('amount');
    }

    /**
     * Get subtotal from order items
     */
    public function getSubtotalFromItemsAttribute(): float
    {
        return (float) $this->items->sum('amount');
    }

    /**
     * Add a payment entry
     */
    public function addPayment(OrderPaymentType $type, float $amount, ?string $notes = null, ?\DateTime $paymentDate = null): OrderPayment
    {
        return $this->payments()->create([
            'payment_type' => $type,
            'amount' => $amount,
            'notes' => $notes,
            'payment_date' => $paymentDate ?? now(),
            'user_id' => auth()->id() ?? null,
        ]);
    }

    public function getCustomerNameAttribute(): ?string
    {
        return $this->customer?->name;
    }

    public function getCustomerPhoneAttribute(): ?string
    {
        return $this->customer?->phone;
    }

    public function getCustomerAddressAttribute(): ?string
    {
        return $this->customer?->address;
    }

    // Currency formatting
    public function getFormattedSubtotalAttribute(): string
    {
        return CurrencyHelper::format($this->subtotal);
    }

    public function getFormattedTaxAmountAttribute(): string
    {
        return CurrencyHelper::format($this->tax_amount);
    }

    public function getFormattedDiscountAmountAttribute(): string
    {
        return CurrencyHelper::format($this->discount_amount);
    }

    public function getFormattedTotalAmountAttribute(): string
    {
        return CurrencyHelper::format($this->total_amount);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', OrderStatus::PENDING);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', OrderStatus::CONFIRMED);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', OrderStatus::DRAFT);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', OrderStatus::CANCELLED);
    }

    public function scopeForCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }
}
