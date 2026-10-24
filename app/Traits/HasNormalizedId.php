<?php

namespace App\Traits;

trait HasNormalizedId
{
  public function normalizedId(): string
  {
    $prefix = defined('static::ID_PREFIX') ? static::ID_PREFIX : 'ID';
    $length = defined('static::ID_NUMBER_LENGTH') ? static::ID_NUMBER_LENGTH : 3;

    return sprintf('%s-%0' . $length . 'd', $prefix, $this->id);
  }
}
