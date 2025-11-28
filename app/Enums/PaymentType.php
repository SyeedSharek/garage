<?php

namespace App\Enums;

enum PaymentType: string
{
    case CASH = 'cash';
    case CREDIT = 'credit';

    public function label(): string
    {
        return match($this) {
            self::CASH => 'Cash',
            self::CREDIT => 'Credit',
        };
    }

    public function labelAr(): string
    {
        return match($this) {
            self::CASH => 'نقد',
            self::CREDIT => 'آجل',
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

