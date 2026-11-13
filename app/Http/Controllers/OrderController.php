<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use App\Models\Branch;
use App\Models\Order;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function filteredOrders(Request $request, Staff | User $user)
    {
        $orders = Order::query()->where("tenant_id", $user->tenant->id);
        if ($request->filled('branch_id')) $orders->where("branch_id", $request->branch_id);
        if ($request->filled("cashier_id")) $orders->where("cashier_id", $request->cashier_id);
        if ($request->filled("payment_type")) $orders->where("payment_type", $request->payment_type);
        if ($request->filled("start_date")) $orders->whereDate("created_at", ">=", $request->start_date);
        if ($request->filled("end_date")) $orders->whereDate("created_at", "<=", $request->end_date);

        return $orders;
    }

    public function index(Request $request)
    {
        $user = currentUser();

        $orders = $this->filteredOrders($request, $user);

        if ($request->filled("per_page"))
            $orders = $orders->paginate($request->per_page == "All" ? $orders->count() : $request->per_page)->withQueryString();
        else $orders = $orders->paginate(10)->withQueryString();

        $branches = Branch::all()->where('tenant_id', $user->tenant->id);
        $cashiers = Staff::all()->where("tenant_id", $user->tenant->id)->where("role_id", 4);

        return view("page.order.index", [
            "orders" => $orders,
            "branches" => $branches,
            "cashiers" => $cashiers
        ]);
    }

    public function export(Request $request)
    {
        $user = currentUser();
        $orders = $this->filteredOrders($request, $user);

        return Excel::download(
            new OrdersExport($orders),
            'orders-' . now()->format('Y-m-d-His') . '.xlsx'
        );
    }
}
