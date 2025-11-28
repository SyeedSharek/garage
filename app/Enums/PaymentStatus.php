<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case DUE = 'due';
    case PAID = 'paid';
    case PARTIAL = 'partial';
    case OVERDUE = 'overdue';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Draft',
            self::PENDING => 'Pending',
            self::DUE => 'Due',
            self::PAID => 'Paid',
            self::PARTIAL => 'Partial',
            self::OVERDUE => 'Overdue',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function labelAr(): string
    {
        return match($this) {
            self::DRAFT => 'مسودة',
            self::PENDING => 'قيد الانتظار',
            self::DUE => 'مستحق',
            self::PAID => 'مدفوع',
            self::PARTIAL => 'جزئي',
            self::OVERDUE => 'متأخر',
            self::CANCELLED => 'ملغي',
        };
    }

    public function getLabel(): string
    {
        return app()->getLocale() === 'ar' ? $this->labelAr() : $this->label();
    }

    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'gray',
            self::PENDING => 'yellow',
            self::DUE => 'orange',
            self::PAID => 'green',
            self::PARTIAL => 'blue',
            self::OVERDUE => 'red',
            self::CANCELLED => 'gray',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

