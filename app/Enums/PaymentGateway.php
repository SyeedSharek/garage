<?php

namespace App\Enums;

enum PaymentGateway: string
{
    case CARD = 'card';
    case CASH = 'cash';

    public function label(): string
    {
        return match($this) {
            self::CARD => 'Card',
            self::CASH => 'Cash',
        };
    }

    public function labelAr(): string
    {
        return match($this) {
            self::CARD => 'بطاقة',
            self::CASH => 'نقد',
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

