<?php

namespace App\Http\Controllers;

use App\Helpers\JournalHelper;
use App\Models\Ingredient;
use App\Models\Supply;
use Illuminate\Http\Request;

class SupplyController extends Controller
{
    public function index()
    {
        $supplies = Supply::all()->whereNull("stock_report_id");
        $ingredients = Ingredient::all()->whereNull("deleted_at");

        return view("page.supply.index", [
            "supplies" => $supplies,
            "ingredients" => $ingredients
        ]);
    }

    public function post(Request $request)
    {
        $supply = new Supply;
        $supply->ingredient_id = $request->ingredient_id;
        $supply->quantity = $request->quantity;
        $supply->save();

        JournalHelper::event('SUPPLY_RECEIVED', [
            'message' => "#" . $supply->normalizedId() . "\t" . $supply->ingredient->name . "\t" . $supply->quantity
        ]);


        $ingredient = Ingredient::find($request->ingredient_id);
        $old_ingredient_stock = $ingredient->stock;

        $ingredient->stock = $ingredient->stock + $supply->quantity;
        $ingredient->save();

        JournalHelper::event('INGREDIENT_STOCK_UPDATED', [
            'message' => "#" . $ingredient->normalizedId() . "\t" . $ingredient->name . "\t" . $old_ingredient_stock . "+" . $supply->quantity . "\t" . $ingredient->stock
        ]);

        return redirect()->back();
    }
}
