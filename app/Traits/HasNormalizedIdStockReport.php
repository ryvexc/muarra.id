<?php

namespace App\Traits;

trait HasNormalizedIdStockReport
{
    public function normalizedId(): string
    {
        $prefix = defined('static::ID_PREFIX') ? static::ID_PREFIX : 'ID';
        $length = defined('static::ID_NUMBER_LENGTH') ? static::ID_NUMBER_LENGTH : 6;

        $datetime = ($this->created_at ?? now())->format('Ymd-His');

        return sprintf(
            '%s-%s-%0' . $length . 'd',
            $prefix,
            $datetime,
            $this->id
        );
    }
}
