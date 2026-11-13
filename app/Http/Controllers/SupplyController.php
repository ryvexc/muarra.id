<?php

namespace App\Http\Controllers;

use App\Helpers\JournalHelper;
use App\Models\Branch;
use App\Models\Ingredient;
use App\Models\Inventory;
use App\Models\InventoryHistory;
use App\Models\Staff;
use App\Models\Supply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplyController extends Controller
{
    public function filteredOrders(Request $request, Staff | User $user)
    {
        $supplies = Supply::query()->where("tenant_id", $user->tenant->id)->where("stock_report_id", NULL);
        if ($request->filled('branch_id')) $supplies->where("branch_id", $request->branch_id);
        if ($request->filled("cashier_id")) $supplies->where("cashier_id", $request->cashier_id);
        if ($request->filled("payment_type")) $supplies->where("payment_type", $request->payment_type);
        if ($request->filled("start_date")) $supplies->whereDate("created_at", ">=", $request->start_date);
        if ($request->filled("end_date")) $supplies->whereDate("created_at", "<=", $request->end_date);

        return $supplies;
    }

    public function index(Request $request)
    {
        $user = currentUser();

        $supplies = $this->filteredOrders($request, $user);
        if ($request->filled("per_page"))
            $supplies = $supplies->paginate($request->per_page == "All" ? $supplies->count() : $request->per_page)->withQueryString();
        else $supplies = $supplies->paginate(10)->withQueryString();

        $ingredients = Ingredient::all()->where("tenant_id")->whereNull("deleted_at");
        $branches = Branch::all()->where("tenant_id", $user->tenant->id);

        return view("page.supply.index", [
            "supplies" => $supplies,
            "ingredients" => $ingredients,
            "branches" => $branches
        ]);
    }

    public function post(Request $request)
    {
        $user = currentUser();

        $supply = new Supply;
        $supply->tenant_id = currentUser()->tenant->id;
        $supply->ingredient_id = $request->ingredient_id;
        $supply->quantity = $request->quantity;
        $supply->branch_id = $request->branch_id ?? currentUser()->branch->id;
        $supply->save();

        $inventory = Inventory::firstOrNew([
            'tenant_id' => currentUser()->tenant->id,
            'branch_id' => $request->branch_id ?? currentUser()->branch->id,
            'ingredient_id' => $request->ingredient_id,
        ]);

        $stock_before = $inventory->stock;
        $inventory->stock = ($inventory->stock ?? 0) + $supply->quantity;

        $history = new InventoryHistory();
        $history->tenant_id = $user->tenant->id;
        $history->branch_id = $request->branch_id ?? currentUser()->branch->id;
        $history->ingredient_id = $request->ingredient_id;
        $history->type = "in";
        $history->quantity = $request->quantity;
        $history->stock_before = $stock_before;
        $history->stock_after = $inventory->stock;
        $history->reference_type = "supply";
        $history->reference_id = $supply->id;
        $history->notes = "Incoming supply #" . $supply->normalizedId();
        if ($user instanceof User) $history->created_by_user_id = $user->id;
        else $history->created_by_staff_id = $user->id;
        $history->save();

        $inventory->save();

        return redirect()->back();
    }
}
