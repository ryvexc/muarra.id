<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Staff;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\InventoryHistory;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->all();

        // Validasi payload dasar
        if (
            !isset($data['order_id']) ||
            !isset($data['status_code']) ||
            !isset($data['gross_amount']) ||
            !isset($data['signature_key']) ||
            !isset($data['transaction_status'])
        ) {
            return response()->json([
                'message' => 'Invalid notification payload',
            ], 400);
        }

        // Verifikasi signature Midtrans
        $serverKey = config('services.midtrans.server_key');

        $signature = hash(
            'sha512',
            $data['order_id'] .
                $data['status_code'] .
                $data['gross_amount'] .
                $serverKey
        );

        if (!hash_equals($signature, $data['signature_key'])) {
            Log::warning('Invalid Midtrans signature', [
                'order_id' => $data['order_id'],
            ]);

            return response()->json([
                'message' => 'Invalid signature',
            ], 403);
        }

        // Ambil ID database dari normalized ID, contoh ORD-0005
        if (!preg_match('/^ORD-(\d+)$/', $data['order_id'], $matches)) {
            return response()->json([
                'message' => 'Invalid order ID format',
            ], 400);
        }

        $orderId = (int) $matches[1];

        try {
            DB::transaction(function () use ($data, $orderId) {

                // Lock order untuk mencegah notification diproses bersamaan
                $order = Order::with('orderItems')
                    ->where('id', $orderId)
                    ->lockForUpdate()
                    ->first();

                if (!$order || $order->normalizedId() !== $data['order_id']) {
                    throw new \Exception('Order not found');
                }

                if ((float) $data['gross_amount'] != (float) $order->total) {
                    throw new \Exception('Payment amount mismatch');
                }

                $status = $data['transaction_status'];

                // Jika sudah paid, jangan proses inventory dan wallet lagi.
                if ($order->payment_status === 'paid') {
                    return;
                }

                // Mapping status Midtrans
                if ($status === 'settlement') {
                    $this->processPaidOrder($order);

                    $order->payment_status = 'paid';
                    $order->save();
                } elseif ($status === 'capture') {
                    // Capture dianggap berhasil jika fraud status accept
                    if (($data['fraud_status'] ?? null) === 'accept') {
                        $this->processPaidOrder($order);

                        $order->payment_status = 'paid';
                        $order->save();
                    }
                } elseif ($status === 'pending') {
                    $order->payment_status = 'pending';
                    $order->save();
                } elseif ($status === 'expire') {
                    $order->payment_status = 'cancelled';
                    $order->save();
                } elseif ($status === 'cancel') {
                    $order->payment_status = 'cancelled';
                    $order->save();
                } elseif ($status === 'deny') {
                    $order->payment_status = 'cancelled';
                    $order->save();
                }
            });

            Log::info('Midtrans notification processed', [
                'order_id' => $data['order_id'],
                'transaction_status' => $data['transaction_status'],
            ]);

            return response()->json([
                'message' => 'Notification processed',
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Midtrans webhook processing failed', [
                'order_id' => $data['order_id'],
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Failed to process notification',
            ], 500);
        }
    }

    private function processPaidOrder(Order $order)
    {
        $user = Staff::findOrFail($order->cashier_id);

        // Proses pengurangan inventory
        foreach ($order->orderItems as $orderItem) {
            $product = Product::findOrFail($orderItem->product_id);

            foreach ($product->ingredients as $productIngredient) {
                $inventory = Inventory::where('branch_id', $order->branch_id)
                    ->where('tenant_id', $order->tenant_id)
                    ->where('ingredient_id', $productIngredient->ingredient_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $stockBefore = $inventory->stock;
                $quantity = $productIngredient->quantity * $orderItem->quantity;

                $inventory->stock -= $quantity;
                $inventory->save();

                $history = new InventoryHistory();
                $history->tenant_id = $order->tenant_id;
                $history->branch_id = $order->branch_id;
                $history->ingredient_id = $productIngredient->ingredient_id;
                $history->type = 'out';
                $history->quantity = -$quantity;
                $history->stock_before = $stockBefore;
                $history->stock_after = $inventory->stock;
                $history->reference_type = 'order';
                $history->reference_id = $order->id;
                $history->notes = 'Ingredient used for order #' . $order->normalizedId();
                $history->created_by_staff_id = $user->id;
                $history->save();
            }
        }

        // Proses wallet
        $wallet = Wallet::where('tenant_id', $order->tenant_id)
            ->where('branch_id', $order->branch_id)
            ->where('type', 'cashless')
            ->lockForUpdate()
            ->firstOrFail();

        $walletTransaction = new WalletTransaction();
        $walletTransaction->wallet_id = $wallet->id;
        $walletTransaction->type = 'debit';
        $walletTransaction->amount = $order->total;
        $walletTransaction->balance = $wallet->balance + $order->total;
        $walletTransaction->description = 'Pembayaran QRIS';
        $walletTransaction->subdescription = 'dari Penjualan ' . $order->normalizedId();
        $walletTransaction->salt = strtoupper(Str::random(4));
        $walletTransaction->save();
    }
}
