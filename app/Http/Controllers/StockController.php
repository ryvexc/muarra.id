<?php

namespace App\Http\Controllers;

use App\Helpers\JournalHelper;
use App\Models\Branch;
use App\Models\Ingredient;
use App\Models\Inventory;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function index()
    {
        $user = currentUser();

        $ingredients = Ingredient::all()->where("tenant_id", $user->tenant->id)->whereNull("deleted_at");
        $units = Unit::all();
        $branches = Branch::all()->where("tenant_id", $user->tenant->id);

        $inventories = Inventory::all()->where("tenant_id", $user->tenant->id);

        return view("page.stock.index", [
            "ingredients" => $ingredients,
            "units" => $units,
            "inventories" => $inventories,
            "branches" => $branches
        ]);
    }

    public function post(Request $request)
    {
        $ingredient = new Ingredient;
        $ingredient->tenant_id = currentUser()->tenant->id;
        $ingredient->name = $request->name;
        $ingredient->unit_of_measurement_id = $request->unit_of_measurement_id;
        $ingredient->harga_satuan = str_replace(".", "", $request->harga_satuan);
        $ingredient->save();

        $branches = Branch::all()->where("tenant_id", currentUser()->tenant->id);

        foreach ($branches as $branch) {
            $inventory = new Inventory();
            $inventory->branch_id = $branch->id;
            $inventory->tenant_id = currentUser()->tenant->id;
            $inventory->ingredient_id = $ingredient->id;
            $inventory->stock = 0;
            $inventory->save();
        }

        JournalHelper::event('INGREDIENT_CREATED', [
            'message' => "#" . $ingredient->normalizedId() . "\t" . $ingredient->name . "\t" . $ingredient->stock . "\t" . $ingredient->unit_of_measurement
        ]);

        return redirect()->back();
    }

    public function edit(Request $request, string $id)
    {
        $ingredient = Ingredient::find($id);
        $ingredient->name = $request->name;
        $ingredient->updated_at = Carbon::now();
        $ingredient->save();

        return redirect()->back();
    }

    public function delete(Request $request, string $id)
    {
        $ingredient = Ingredient::find($id);
        $ingredient->deleted_at = Carbon::now();
        $ingredient->save();

        return redirect()->back();
    }
}
