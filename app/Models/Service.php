<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use App\Helpers\CurrencyHelper;
use App\Traits\HasNotes;

class Service extends Model
{
    use HasFactory, SoftDeletes, HasTranslations, HasNotes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'unit_price',
        'unit',
        'is_active',
        'is_custom',
        'sort_order',
    ];

    /**
     * Fields that should be translatable
     */
    public $translatable = ['name', 'description'];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_custom' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Get translated unit name from language files
     */
    public function getUnitTranslatedAttribute(): string
    {
        return trans("units.{$this->unit}", [], app()->getLocale());
    }

    /**
     * Get formatted price with currency
     */
    public function getFormattedPriceAttribute(): string
    {
        return CurrencyHelper::format((float) $this->unit_price);
    }

    /**
     * Get formatted price in Arabic format
     */
    public function getFormattedPriceArAttribute(): string
    {
        return CurrencyHelper::formatAr((float) $this->unit_price);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function scopeSystem($query)
    {
        return $query->where('is_custom', false);
    }

    // Get raw values
    public function getNameEn(): ?string
    {
        return $this->getTranslation('name', 'en');
    }

    public function getNameAr(): ?string
    {
        return $this->getTranslation('name', 'ar');
    }

    public function getDescriptionEn(): ?string
    {
        return $this->getTranslation('description', 'en');
    }

    public function getDescriptionAr(): ?string
    {
        return $this->getTranslation('description', 'ar');
    }

    public function getUnitKey(): string
    {
        return $this->attributes['unit'] ?? 'pcs';
    }
}
