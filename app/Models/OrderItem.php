<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use App\Helpers\CurrencyHelper;

class OrderItem extends Model
{
    use SoftDeletes, HasTranslations;

    protected $fillable = [
        'order_id',
        'service_id',
        'serial_number',
        'description',
        'part_number',
        'quantity',
        'unit',
        'unit_price',
        'amount',
        'sort_order',
    ];

    /**
     * Fields that should be translatable
     */
    public $translatable = ['description'];

    protected $casts = [
        'serial_number' => 'integer',
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'amount' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get translated unit name from language files
     */
    public function getUnitTranslatedAttribute(): string
    {
        return trans("units.{$this->unit}", [], app()->getLocale());
    }

    /**
     * Get formatted unit price with currency
     */
    public function getFormattedUnitPriceAttribute(): string
    {
        return CurrencyHelper::format((float) $this->unit_price);
    }

    /**
     * Get formatted amount with currency
     */
    public function getFormattedAmountAttribute(): string
    {
        return CurrencyHelper::format((float) $this->amount);
    }

    protected static function booted(): void
    {
        static::saving(function ($orderItem) {
            $orderItem->amount = $orderItem->quantity * $orderItem->unit_price;

            // Auto-increment serial number if not set
            if (!$orderItem->serial_number) {
                $maxSerial = OrderItem::where('order_id', $orderItem->order_id)
                    ->max('serial_number') ?? 0;
                $orderItem->serial_number = $maxSerial + 1;
            }

            // Auto-populate from service if service_id exists
            if ($orderItem->service_id && !$orderItem->description) {
                $service = Service::find($orderItem->service_id);
                if ($service) {
                    $orderItem->description = [
                        'en' => $service->getTranslation('name', 'en'),
                        'ar' => $service->getTranslation('name', 'ar'),
                    ];
                    if (!$orderItem->unit) {
                        $orderItem->unit = $service->unit;
                    }
                    if (!$orderItem->unit_price || $orderItem->unit_price == 0) {
                        $orderItem->unit_price = $service->unit_price;
                    }
                }
            }
        });

        // Note: Order amounts are calculated from order_payments, not stored in orders table
    }
}
