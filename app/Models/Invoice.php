<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Helpers\CurrencyHelper;
use App\Traits\HasNotes;
use App\Traits\GeneratesUniqueNumber;

class Invoice extends Model
{
    use HasFactory, SoftDeletes, HasNotes, GeneratesUniqueNumber;
    protected $fillable = [
        'invoice_number',
        'order_id',
        'customer_id',
        'user_id',
        'payment_gateway',
        'status',
        'total_amount',
        'paid_amount',
        'invoice_date',
        'due_date',
        'paid_at',
    ];

    protected $casts = [
        'payment_gateway' => PaymentGateway::class,
        'status' => PaymentStatus::class,
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'invoice_date' => 'datetime',
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(OrderPayment::class)->orderBy('payment_date');
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

    /**
     * Get remaining amount (calculated)
     */
    public function getRemainingAmountAttribute(): float
    {
        return (float) ($this->total_amount - $this->paid_amount);
    }

    public function updatePaymentStatus(): void
    {
        $this->paid_amount = (float) $this->payments()->sum('amount');

        if ($this->payment_gateway === PaymentGateway::CASH) {
            $this->status = $this->paid_amount >= $this->total_amount
                ? PaymentStatus::PAID
                : PaymentStatus::PENDING;
        } else {
            // For card payments, check payment status
            if ($this->paid_amount >= $this->total_amount) {
                $this->status = PaymentStatus::PAID;
                $this->paid_at = now();
            } elseif ($this->paid_amount > 0) {
                $this->status = PaymentStatus::PARTIAL;
            } elseif ($this->due_date && \Carbon\Carbon::parse($this->due_date)->isPast()) {
                $this->status = PaymentStatus::OVERDUE;
            } elseif ($this->due_date && \Carbon\Carbon::parse($this->due_date)->isToday()) {
                $this->status = PaymentStatus::DUE;
            } else {
                $this->status = PaymentStatus::PENDING;
            }
        }

        $this->save();
    }

    // Currency formatting
    public function getFormattedTotalAmountAttribute(): string
    {
        return CurrencyHelper::format((float) $this->total_amount);
    }

    public function getFormattedPaidAmountAttribute(): string
    {
        return CurrencyHelper::format((float) $this->paid_amount);
    }

    public function getFormattedRemainingAmountAttribute(): string
    {
        return CurrencyHelper::format($this->remaining_amount);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', PaymentStatus::PENDING);
    }

    public function scopeDue($query)
    {
        return $query->where('status', PaymentStatus::DUE);
    }

    public function scopePaid($query)
    {
        return $query->where('status', PaymentStatus::PAID);
    }

    public function scopePartial($query)
    {
        return $query->where('status', PaymentStatus::PARTIAL);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', PaymentStatus::OVERDUE);
    }

    public function scopeCard($query)
    {
        return $query->where('payment_gateway', PaymentGateway::CARD);
    }

    public function scopeCash($query)
    {
        return $query->where('payment_gateway', PaymentGateway::CASH);
    }

    public function scopeForCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeFromOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }
}
