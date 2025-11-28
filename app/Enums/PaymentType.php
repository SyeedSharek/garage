<?php

namespace App\Enums;

enum PaymentType: string
{
    case CARD = 'card';
    case CASH = 'cash';
    case PAY_LATER = 'pay_later';

    public function label(): string
    {
        return match($this) {
            self::CARD => 'Card',
            self::CASH => 'Cash',
            self::PAY_LATER => 'Pay Later',
        };
    }

    public function labelAr(): string
    {
        return match($this) {
            self::CARD => 'بطاقة',
            self::CASH => 'نقد',
            self::PAY_LATER => 'دفع لاحقاً',
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

