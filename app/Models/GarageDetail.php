<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GarageDetail extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'name_ar',
        'logo',
        'slogan',
        'slogan_ar',
        'mobile',
        'phone',
        'email',
        'cr_number',
        'tax_number',
        'po_box',
        'street_number',
        'street_number_ar',
        'zone',
        'zone_ar',
        'building_number',
        'building_number_ar',
        'wakalat_street',
        'wakalat_street_ar',
        'shop_number',
        'shop_number_ar',
        'area',
        'area_ar',
        'city',
        'city_ar',
        'country',
        'country_ar',
        'address',
        'address_ar',
        'notes',
        'notes_ar',
    ];

    /**
     * Get the garage details (singleton pattern since only one garage)
     */
    public static function getDetails(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }

    // Accessors for bilingual fields
    public function getNameAttribute($value)
    {
        return app()->getLocale() === 'ar' && $this->attributes['name_ar'] ?? null
            ? $this->attributes['name_ar']
            : $value;
    }

    public function getSloganAttribute($value)
    {
        return app()->getLocale() === 'ar' && $this->attributes['slogan_ar'] ?? null
            ? $this->attributes['slogan_ar']
            : $value;
    }

    public function getStreetNumberAttribute($value)
    {
        return app()->getLocale() === 'ar' && $this->attributes['street_number_ar'] ?? null
            ? $this->attributes['street_number_ar']
            : $value;
    }

    public function getZoneAttribute($value)
    {
        return app()->getLocale() === 'ar' && $this->attributes['zone_ar'] ?? null
            ? $this->attributes['zone_ar']
            : $value;
    }

    public function getBuildingNumberAttribute($value)
    {
        return app()->getLocale() === 'ar' && $this->attributes['building_number_ar'] ?? null
            ? $this->attributes['building_number_ar']
            : $value;
    }

    public function getWakalatStreetAttribute($value)
    {
        return app()->getLocale() === 'ar' && $this->attributes['wakalat_street_ar'] ?? null
            ? $this->attributes['wakalat_street_ar']
            : $value;
    }

    public function getShopNumberAttribute($value)
    {
        return app()->getLocale() === 'ar' && $this->attributes['shop_number_ar'] ?? null
            ? $this->attributes['shop_number_ar']
            : $value;
    }

    public function getAreaAttribute($value)
    {
        return app()->getLocale() === 'ar' && $this->attributes['area_ar'] ?? null
            ? $this->attributes['area_ar']
            : $value;
    }

    public function getCityAttribute($value)
    {
        return app()->getLocale() === 'ar' && $this->attributes['city_ar'] ?? null
            ? $this->attributes['city_ar']
            : $value;
    }

    public function getCountryAttribute($value)
    {
        return app()->getLocale() === 'ar' && $this->attributes['country_ar'] ?? null
            ? $this->attributes['country_ar']
            : $value;
    }

    public function getAddressAttribute($value)
    {
        return app()->getLocale() === 'ar' && $this->attributes['address_ar'] ?? null
            ? $this->attributes['address_ar']
            : $value;
    }

    public function getNotesAttribute($value)
    {
        return app()->getLocale() === 'ar' && $this->attributes['notes_ar'] ?? null
            ? $this->attributes['notes_ar']
            : $value;
    }

    // Get raw values (bypass accessors)
    public function getNameEn(): ?string
    {
        return $this->attributes['name'] ?? null;
    }

    public function getNameAr(): ?string
    {
        return $this->attributes['name_ar'] ?? null;
    }
}
