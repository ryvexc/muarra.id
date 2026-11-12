<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $user = currentUser();

        $orders = Order::all()
            ->where("tenant_id", $user->tenant->id);

        return view("page.order.index", [
            "orders" => $orders
        ]);
    }
}
