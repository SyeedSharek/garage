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
     *
     * @param string|null $prefix
     * @param int $length
     * @return string
     */
    public function generateUniqueNumber(?string $prefix = null, int $length = 8): string
    {
        $prefix = $prefix ?? $this->getDefaultPrefix();
        $column = $this->getUniqueNumberColumn();

        do {
            // Generate number: PREFIX + YEAR + MONTH + RANDOM
            $year = date('Y');
            $month = date('m');
            $random = str_pad((string) mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

            $number = $prefix . $year . $month . $random;

            // If length is specified, truncate or pad
            if ($length > 0) {
                $number = substr($number, 0, $length);
            }

        } while ($this->where($column, $number)->exists());

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

