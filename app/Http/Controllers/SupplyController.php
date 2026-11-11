<?php

namespace App\Http\Controllers;

use App\Helpers\JournalHelper;
use App\Models\Branch;
use App\Models\Ingredient;
use App\Models\Inventory;
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
        $supply = new Supply;
        $supply->tenant_id = Auth::user()->tenant->id;
        $supply->ingredient_id = $request->ingredient_id;
        $supply->quantity = $request->quantity;
        $supply->branch_id = $request->branch_id;
        $supply->save();

        JournalHelper::event('SUPPLY_RECEIVED', [
            'message' => "#" . $supply->normalizedId() . "\t" . $supply->ingredient->name . "\t" . $supply->quantity
        ]);

        $inventory = Inventory::firstOrNew([
            'tenant_id' => Auth::user()->tenant->id,
            'branch_id' => $request->branch_id,
            'ingredient_id' => $request->ingredient_id,
        ]);

        $inventory->stock = ($inventory->stock ?? 0) + $supply->quantity;

        $inventory->save();

        // $ingredient = Ingredient::find($request->ingredient_id);
        // $old_ingredient_stock = $ingredient->stock;

        // $ingredient->stock = $ingredient->stock + $supply->quantity;
        // $ingredient->save();

        // JournalHelper::event('INGREDIENT_STOCK_UPDATED', [
        //     'message' => "#" . $ingredient->normalizedId() . "\t" . $ingredient->name . "\t" . $old_ingredient_stock . "+" . $supply->quantity . "\t" . $ingredient->stock
        // ]);

        return redirect()->back();
    }
}
