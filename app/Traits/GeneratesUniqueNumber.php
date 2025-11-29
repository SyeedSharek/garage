<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait GeneratesUniqueNumber
{
    /**
     * Boot the trait and set up model events
     */
    protected static function bootGeneratesUniqueNumber(): void
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getUniqueNumberColumn()})) {
                $model->{$model->getUniqueNumberColumn()} = $model->generateUniqueNumber();
            }
        });
    }

    /**
     * Generate a unique number for the model
     * Based on latest number for current month + 1
     *
     * @param string|null $prefix
     * @param int $length (ignored - kept for backward compatibility)
     * @return string
     */
    public function generateUniqueNumber(?string $prefix = null, int $length = 0): string
    {
        $prefix = $prefix ?? $this->getDefaultPrefix();
        $column = $this->getUniqueNumberColumn();
        $year = date('Y');
        $month = date('m');
        $yearMonth = $year . $month;
        $pattern = $prefix . $yearMonth;

        // Get the latest number for the current month by querying the number column
        $lastNumber = static::where($column, 'like', $pattern . '%')
            ->orderBy($column, 'desc')
            ->value($column);

        if ($lastNumber && str_starts_with($lastNumber, $pattern)) {
            // Extract the sequence part (last 6 digits)
            $sequence = (int) substr($lastNumber, -6) + 1;
        } else {
            // No previous records for this month, start from 1
            $sequence = 1;
        }

        // Format: PREFIX + YEAR + MONTH + SEQUENCE (6 digits)
        // Example: ORD202511000001, INV202511000001
        $number = $prefix . $yearMonth . str_pad((string) $sequence, 6, '0', STR_PAD_LEFT);

        return $number;
    }

    /**
     * Get the column name for unique number
     * Override this method in your model if needed
     *
     * @return string
     */
    protected function getUniqueNumberColumn(): string
    {
        // Try common column names
        if (isset($this->uniqueNumberColumn)) {
            return $this->uniqueNumberColumn;
        }

        // Auto-detect based on model name
        $modelName = class_basename($this);

        return match($modelName) {
            'Order' => 'order_number',
            'Invoice' => 'invoice_number',
            default => 'number',
        };
    }

    /**
     * Get the default prefix for the unique number
     * Override this method in your model if needed
     *
     * @return string
     */
    protected function getDefaultPrefix(): string
    {
        // Try to get from model property
        if (isset($this->uniqueNumberPrefix)) {
            return $this->uniqueNumberPrefix;
        }

        // Auto-generate prefix based on model name
        $modelName = class_basename($this);

        return match($modelName) {
            'Order' => 'ORD',
            'Invoice' => 'INV',
            default => strtoupper(substr($modelName, 0, 3)),
        };
    }

    /**
     * Generate a sequential number (alternative method)
     * Format: PREFIX-YYYYMM-000001
     *
     * @param string|null $prefix
     * @return string
     */
    public function generateSequentialNumber(?string $prefix = null): string
    {
        $prefix = $prefix ?? $this->getDefaultPrefix();
        $column = $this->getUniqueNumberColumn();
        $yearMonth = date('Ym');

        // Get the last number for this month
        $lastNumber = static::where($column, 'like', $prefix . $yearMonth . '%')
            ->orderBy($column, 'desc')
            ->value($column);

        if ($lastNumber) {
            // Extract the sequence number and increment
            $sequence = (int) substr($lastNumber, -6) + 1;
        } else {
            $sequence = 1;
        }

        $number = $prefix . $yearMonth . str_pad((string) $sequence, 6, '0', STR_PAD_LEFT);

        return $number;
    }
}

