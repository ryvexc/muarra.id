<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasNormalizedIdTransaction
{
    public function normalizedId(): string
    {
        $prefix = defined('static::ID_PREFIX') ? static::ID_PREFIX : 'ID';

        return $prefix . $this->created_at->format('YmdHis') . $this->salt;
    }
}
