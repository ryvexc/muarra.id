<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\CoreApi;

class MidtransService
{
  public function __construct()
  {
    Config::$serverKey = config('services.midtrans.server_key');
    Config::$isProduction = config('services.midtrans.is_production');
    Config::$isSanitized = true;
    Config::$is3ds = true;
  }

  public function createQris($orderId, $amount, $items = [])
  {
    $payload = [
      'payment_type' => 'qris',
      'transaction_details' => [
        'order_id' => $orderId,
        'gross_amount' => (int) $amount,
      ],
      'qris' => [
        'acquirer' => 'gopay',
      ],
    ];

    if (!empty($items)) {
      $payload['item_details'] = $items;
    }

    return CoreApi::charge($payload);
  }
}
