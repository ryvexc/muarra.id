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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use SebastianBergmann\CodeCoverage\Util\Percentage;

class OrderController extends Controller
{
    public function post(Request $request)
    {
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

            $product = Product::find($orderItemRequest["product"]->id);

            foreach ($product->ingredients as $product_ingredient) {
                $inventory = Inventory::where("branch_id", $user->branch->id)->where("tenant_id", $user->tenant->id)->where("ingredient_id", $product_ingredient->id)->first();
                $stock_before = $inventory->stock;
                $quantity = $product_ingredient->quantity * $orderItemRequest["quantity"];

                $inventory->stock -= $quantity;
                $inventory->save();

                $history = new InventoryHistory();
                $history->tenant_id = $user->tenant->id;
                $history->branch_id = $user->branch->id;
                $history->ingredient_id = $product_ingredient->id;
                $history->type = "out";
                $history->quantity = $quantity;
                $history->stock_before = $stock_before;
                $history->stock_after = $inventory->stock;
                $history->reference_type = "order";
                $history->reference_id = $order->id;
                $history->notes = "Ingredient used for order #" . $order->normalizedId();
                $history->created_by_staff_id = $user->id;
                $history->save();
            }
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

        return Response::json([
            "message" => "Order created",
            "data" => $order
        ]);
    }
}
