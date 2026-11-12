<?php

namespace App\Http\Controllers;

use App\Helpers\JournalHelper;
use App\Models\Branch;
use App\Models\Ingredient;
use App\Models\Inventory;
use App\Models\InventoryHistory;
use App\Models\Supply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplyController extends Controller
{
    public function index()
    {
        $supplies = Supply::all()->whereNull("stock_report_id");
        $ingredients = Ingredient::all()->whereNull("deleted_at");
        $branches = Branch::all();

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
        $history->branch_id = $user->branch->id;
        $history->ingredient_id = $request->ingredient_id;
        $history->type = "in";
        $history->quantity = $request->quantity;
        $history->stock_before = $stock_before;
        $history->stock_after = $inventory->stock;
        $history->reference_type = "supply";
        $history->reference_id = $supply->id;
        $history->notes = "Incoming supply #" . $supply->normalizedId();
        $history->created_by_staff_id = $user->id;
        $history->save();

        $inventory->save();

        return redirect()->back();
    }
}
