<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Inventory;
use App\Models\InventoryHistory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Staff;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use SebastianBergmann\CodeCoverage\Util\Percentage;
use Illuminate\Support\Str;
use App\Services\MidtransService;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function cancelOrder(Request $request, string $id)
    {
        $user = Staff::find($request->user()->id);

        $order = Order::where("id", $id)->where("tenant_id", $user->tenant->id)->where("branch_id", $user->branch->id)->first();

        if (!$order) return Response::json([
            "message" => "Order not found",
            "data" => null
        ], 404);

        $order->payment_status = "cancelled";
        $order->save();

        return Response::json([
            "message" => "Order cancelled",
            "data" => null
        ]);
    }

    public function getStatus(Request $request, string $id)
    {
        $user = Staff::find($request->user()->id);

        $order = Order::where('id', $id)
            ->where('tenant_id', $user->tenant->id)
            ->where('branch_id', $user->branch->id)
            ->first();

        if (!$order) {
            return Response::json([
                'message' => 'Order not found',
                'data' => null
            ], 404);
        }

        return Response::json([
            'data' => [
                'id' => $order->id,
                'payment_status' => $order->payment_status,
            ]
        ]);
    }

    public function getOrders(Request $request)
    {
        $user = Staff::find($request->user()->id);

        $order = Order::where('tenant_id', $user->tenant->id)
            ->where('branch_id', $user->branch->id)
            ->whereDate('created_at', now()->toDateString())
            ->latest()
            ->get();

        return Response::json([
            "message" => "Get orders success",
            "data" => $order
        ]);
    }

    public function post(Request $request)
    {
        DB::beginTransaction();

        $user = Staff::find($request->user()->id);

        $validator = Validator::make($request->all(), [
            "payment_type" => "required|in:card,cash,qris"
        ]);

        $orderItemsRequest = [];

        foreach ($request->orderItems as $orderItem) {
            $product = Product::find($orderItem["product_id"]);

            $orderItemsRequest[] = [
                "product" => $product,
                "quantity" => $orderItem["quantity"]
            ];
        }

        $discount = NULL;
        if ($request->discount_code) $discount = Discount::where("tenant_id", $user->tenant->id)->where("name", $request->discount_code)->first();

        $order = new Order();
        $order->tenant_id = $user->tenant->id;
        $order->branch_id = $user->branch->id;
        $order->cashier_id = $user->id;
        $order->cust_name = $request->cust_name ?? "";
        $order->subtotal = 0;
        $order->total = 0;
        $order->payment_type = $request->payment_type;
        $order->payment_status = $request->payment_type == "qris"
            ? "pending"
            : "paid";
        $order->save();

        $order_subtotal = 0;

        foreach ($orderItemsRequest as $orderItemRequest) {
            $total_price = $orderItemRequest["quantity"] * $orderItemRequest["product"]->harga_jual;
            $order_subtotal += $total_price;

            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $orderItemRequest["product"]->id;
            $orderItem->quantity = $orderItemRequest["quantity"];
            $orderItem->item_price = $orderItemRequest["product"]->harga_jual;
            $orderItem->total_price = $total_price;
            $orderItem->save();
        }

        $order->subtotal = $order_subtotal;
        if ($discount) {
            $order->discount_id = $discount->id;

            if ($discount->type == "percentage") $order->total = ((100 - $discount->value) / 100) * $order_subtotal;
            else $order->total = $order_subtotal - $discount->value;
        } else {
            $order->total = $order_subtotal;
        }

        $order->save();

        $qrisData = null;

        // QRIS: generate QR, dont do inventory / wallet
        if ($request->payment_type == "qris") {
            try {
                $midtrans = new MidtransService();

                $transaction = $midtrans->createQris(
                    $order->normalizedId(),
                    $order->total
                );

                $qrAction = collect($transaction->actions ?? [])
                    ->firstWhere('name', 'generate-qr-code');

                $qrisData = [
                    'transaction_id' => $transaction->transaction_id,
                    'transaction_status' => $transaction->transaction_status,
                    'qr_url' => $qrAction?->url,
                ];
            } catch (\Exception $e) {
                DB::rollBack();

                return Response::json([
                    'message' => 'Failed to create QRIS transaction',
                    'error' => $e->getMessage(),
                ], 500);
            }
        } else {
            $this->processInventory($order, $user);

            $wallet = Wallet::where("tenant_id", $user->tenant->id)
                ->where("branch_id", $user->branch->id)
                ->where("type", $request->payment_type == "cash" ? "cash" : "cashless")
                ->firstOrFail();

            $walletTransaction = new WalletTransaction();
            $walletTransaction->wallet_id = $wallet->id;
            $walletTransaction->type = "debit";
            $walletTransaction->amount = $order->total;
            $walletTransaction->balance = $wallet->balance + $order->total;

            if ($request->payment_type == "cash")
                $walletTransaction->description = "Pembayaran Tunai";
            else
                $walletTransaction->description = "Pembayaran Non-Tunai";

            $walletTransaction->subdescription = "dari Penjualan " . $order->normalizedId();
            $walletTransaction->salt = strtoupper(Str::random(4));
            $walletTransaction->save();
        }

        DB::commit();

        return Response::json([
            "message" => "Order created",
            "data" => $order,
            "qris" => $qrisData,
        ]);
    }

    private function processInventory(Order $order, Staff $user)
    {
        foreach ($order->orderItems as $orderItem) {
            $product = Product::find($orderItem->product_id);

            foreach ($product->ingredients as $product_ingredient) {
                $inventory = Inventory::where("branch_id", $order->branch_id)
                    ->where("tenant_id", $order->tenant_id)
                    ->where("ingredient_id", $product_ingredient->ingredient_id)
                    ->firstOrFail();

                $stock_before = $inventory->stock;

                $quantity = $product_ingredient->quantity * $orderItem->quantity;

                $inventory->stock -= $quantity;
                $inventory->save();

                $history = new InventoryHistory();
                $history->tenant_id = $order->tenant_id;
                $history->branch_id = $order->branch_id;
                $history->ingredient_id = $product_ingredient->ingredient_id;
                $history->type = "out";
                $history->quantity = -$quantity;
                $history->stock_before = $stock_before;
                $history->stock_after = $inventory->stock;
                $history->reference_type = "order";
                $history->reference_id = $order->id;
                $history->notes = "Ingredient used for order #" . $order->normalizedId();
                $history->created_by_staff_id = $user->id;
                $history->save();
            }
        }
    }
}
