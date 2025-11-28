<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GarageBranch extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'name_ar',
        'mobile',
        'phone',
        'email',
        'cr_number',
        'unit',
        'unit_ar',
        'zone',
        'zone_ar',
        'street',
        'street_ar',
        'landmark',
        'landmark_ar',
        'road',
        'road_ar',
        'city',
        'city_ar',
        'country',
        'country_ar',
        'address',
        'address_ar',
        'notes',
        'notes_ar',
        'is_main',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Accessors for all bilingual fields
    public function getNameAttribute($value)
    {
        return app()->getLocale() === 'ar' && $this->attributes['name_ar'] ?? null
            ? $this->attributes['name_ar']
            : $value;
    }

    public function getUnitAttribute($value)
    {
        return app()->getLocale() === 'ar' && $this->attributes['unit_ar'] ?? null
            ? $this->attributes['unit_ar']
            : $value;
    }

    public function getZoneAttribute($value)
    {
        return app()->getLocale() === 'ar' && $this->attributes['zone_ar'] ?? null
            ? $this->attributes['zone_ar']
            : $value;
    }

    public function getStreetAttribute($value)
    {
        return app()->getLocale() === 'ar' && $this->attributes['street_ar'] ?? null
            ? $this->attributes['street_ar']
            : $value;
    }

    public function getLandmarkAttribute($value)
    {
        return app()->getLocale() === 'ar' && $this->attributes['landmark_ar'] ?? null
            ? $this->attributes['landmark_ar']
            : $value;
    }

    public function getRoadAttribute($value)
    {
        return app()->getLocale() === 'ar' && $this->attributes['road_ar'] ?? null
            ? $this->attributes['road_ar']
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

    public function scopeMain($query)
    {
        return $query->where('is_main', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function getMainBranch()
    {
        return static::where('is_main', true)->first();
    }
}
