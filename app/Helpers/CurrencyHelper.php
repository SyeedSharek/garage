<?php

namespace App\Helpers;

class CurrencyHelper
{
    public static function code(): string
    {
        return config('currency.code', 'QAR');
    }

    public static function symbol(): string
    {
        return config('currency.symbol', 'QR');
    }

    public static function name(): string
    {
        $locale = app()->getLocale();
        $names = config('currency.name', ['en' => 'Qatar Rial', 'ar' => 'ريال قطري']);
        return $names[$locale] ?? $names['en'];
    }

    public static function format(float $amount, bool $showSymbol = true): string
    {
        $decimals = config('currency.decimals', 2);
        $decimalSep = config('currency.decimal_separator', '.');
        $thousandsSep = config('currency.thousands_separator', ',');

        $formatted = number_format($amount, $decimals, $decimalSep, $thousandsSep);
        return $showSymbol ? self::symbol() . ' ' . $formatted : $formatted;
    }

    public static function formatAr(float $amount, bool $showSymbol = true): string
    {
        $decimals = config('currency.decimals', 2);
        $decimalSep = config('currency.decimal_separator', '.');
        $thousandsSep = config('currency.thousands_separator', ',');

        $formatted = number_format($amount, $decimals, $decimalSep, $thousandsSep);
        return $showSymbol ? $formatted . ' ' . self::symbol() : $formatted;
    }
}

