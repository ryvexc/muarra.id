<?php

namespace App\Http\Controllers;

use App\Exports\InventoryHistoryExport;
use App\Models\Branch;
use App\Models\Ingredient;
use App\Models\InventoryHistory;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InventoryHistoryController extends Controller
{
    public function filteredOrders(Request $request, Staff | User $user)
    {
        $inventory_histories = [];

        if ($user instanceof User) {
            $inventory_histories = InventoryHistory::query()->where("tenant_id", $user->tenant->id)->orderBy("created_at", "desc");
        } elseif ($user instanceof Staff) {
            $inventory_histories = InventoryHistory::query()->where("tenant_id", $user->tenant->id)->where("branch_id", $user->branch->id)->orderBy("created_at", "desc");
        }

        if ($request->filled('branch_id')) $inventory_histories->where("branch_id", $request->branch_id);
        if ($request->filled("cashier_id")) $inventory_histories->where("cashier_id", $request->cashier_id);
        if ($request->filled("payment_type")) $inventory_histories->where("payment_type", $request->payment_type);
        if ($request->filled("ingredient_id")) $inventory_histories->where("ingredient_id", $request->ingredient_id);
        if ($request->filled("type")) $inventory_histories->where("type", $request->type);
        if ($request->filled("start_date")) $inventory_histories->whereDate("created_at", ">=", $request->start_date);
        if ($request->filled("end_date")) $inventory_histories->whereDate("created_at", "<=", $request->end_date);

        return $inventory_histories;
    }

    public function index(Request $request)
    {
        $user = currentUser();

        $inventory_histories = $this->filteredOrders($request, $user);

        if ($request->filled("per_page"))
            $inventory_histories = $inventory_histories->paginate($request->per_page == "All" ? $inventory_histories->count() : $request->per_page)->withQueryString();
        else $inventory_histories = $inventory_histories->paginate(10)->withQueryString();

        $branches = Branch::all()->where("tenant_id", $user->tenant->id)->whereNull("deleted_at");
        $ingredients = Ingredient::all()->where("tenant_id", $user->tenant->id)->whereNull("deleted_at");

        return view("page.inventory_history.index", [
            "inventory_histories" => $inventory_histories,
            "branches" => $branches,
            "ingredients" => $ingredients
        ]);
    }

    public function export(Request $request)
    {
        $user = currentUser();
        $products = $this->filteredOrders($request, $user);

        return Excel::download(
            new InventoryHistoryExport($products),
            'inventory-histories-' . now()->format('Y-m-d-His') . '.xlsx'
        );
    }
}
