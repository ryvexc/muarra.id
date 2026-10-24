<?php

namespace App\Support;

class Currency
{
  protected static array $currencies = [
    'IDR' => [
      'symbol' => 'Rp',
      'decimal' => 0,
      'decimal_separator' => ',',
      'thousand_separator' => '.',
      'symbol_position' => 'before',
    ],

    'USD' => [
      'symbol' => '$',
      'decimal' => 2,
      'decimal_separator' => '.',
      'thousand_separator' => ',',
      'symbol_position' => 'before',
    ],

    'EUR' => [
      'symbol' => '€',
      'decimal' => 2,
      'decimal_separator' => ',',
      'thousand_separator' => '.',
      'symbol_position' => 'before',
    ],
  ];

  public static function format(string $currency, int|float $value): string
  {
    $currency = strtoupper($currency);

    if (! isset(self::$currencies[$currency])) {
      throw new \InvalidArgumentException(
        "Currency [{$currency}] is not supported."
      );
    }

    $config = self::$currencies[$currency];

    $formatted = number_format(
      $value,
      $config['decimal'],
      $config['decimal_separator'],
      $config['thousand_separator']
    );

    return $config['symbol_position'] === 'before'
      ? $config['symbol'] . '' . $formatted
      : $formatted . ' ' . $config['symbol'];
  }
}
