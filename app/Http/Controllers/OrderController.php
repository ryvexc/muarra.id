<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Order;
use App\Models\Staff;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = currentUser();

        $orders = Order::query()->where("tenant_id", $user->tenant->id);
        if ($request->filled('branch_id')) $orders->where("branch_id", $request->branch_id);
        if ($request->filled("cashier_id")) $orders->where("cashier_id", $request->cashier_id);
        if ($request->filled("payment_type")) $orders->where("payment_type", $request->payment_type);
        $orders = $orders->paginate(10)->withQueryString();

        $branches = Branch::all()->where('tenant_id', $user->tenant->id);
        $cashiers = Staff::all()->where("tenant_id", $user->tenant->id)->where("role_id", 4);

        return view("page.order.index", [
            "orders" => $orders,
            "branches" => $branches,
            "cashiers" => $cashiers
        ]);
    }
}
