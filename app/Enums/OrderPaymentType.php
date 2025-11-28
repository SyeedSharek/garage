<?php

namespace App\Enums;

enum OrderPaymentType: string
{
    case SUBTOTAL = 'subtotal';
    case TAX = 'tax';
    case DISCOUNT = 'discount';
    case TOTAL = 'total';

    public function label(): string
    {
        return match($this) {
            self::SUBTOTAL => 'Subtotal',
            self::TAX => 'Tax',
            self::DISCOUNT => 'Discount',
            self::TOTAL => 'Total',
        };
    }

    public function labelAr(): string
    {
        return match($this) {
            self::SUBTOTAL => 'المجموع الفرعي',
            self::TAX => 'الضريبة',
            self::DISCOUNT => 'الخصم',
            self::TOTAL => 'الإجمالي',
        };
    }

    public function getLabel(): string
    {
        return app()->getLocale() === 'ar' ? $this->labelAr() : $this->label();
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

