<?php

namespace App\Http\Controllers;

use App\Exports\StocksExport;
use App\Helpers\JournalHelper;
use App\Models\Branch;
use App\Models\Ingredient;
use App\Models\Inventory;
use App\Models\Staff;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class StockController extends Controller
{
    public function filteredOrders(Request $request, Staff | User $user)
    {
        $inventories = Inventory::query()->where("tenant_id", $user->tenant->id);
        if ($request->filled('branch_id')) $inventories->where("branch_id", $request->branch_id);
        if ($request->filled("cashier_id")) $inventories->where("cashier_id", $request->cashier_id);
        if ($request->filled("payment_type")) $inventories->where("payment_type", $request->payment_type);
        if ($request->filled("assigned_in")) $inventories->where("branch_id", $request->assigned_in);
        if ($request->filled("start_date")) $inventories->whereDate("created_at", ">=", $request->start_date);
        if ($request->filled("end_date")) $inventories->whereDate("created_at", "<=", $request->end_date);

        return $inventories;
    }

    public function index(Request $request)
    {
        $user = currentUser();

        $ingredients = Ingredient::all()->where("tenant_id", $user->tenant->id)->whereNull("deleted_at");
        $units = Unit::all();
        $branches = Branch::all()->where("tenant_id", $user->tenant->id);

        $inventories = $this->filteredOrders($request, $user);

        if ($request->filled("per_page"))
            $inventories = $inventories->paginate($request->per_page == "All" ? $inventories->count() : $request->per_page)->withQueryString();
        else $inventories = $inventories->paginate(10)->withQueryString();

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
        $user = currentUser();

        $ingredient = Ingredient::find($id);

        if ($ingredient)
            Inventory::where('tenant_id', $user->tenant->id)->where("ingredient_id", $ingredient->id)->delete();

        $ingredient->deleted_at = Carbon::now();
        $ingredient->save();

        return redirect()->back();
    }

    public function export(Request $request)
    {
        $user = currentUser();

        return Excel::download(
            new StocksExport($user->tenant->id),
            'stocks-' . now()->format('Y-m-d-His') . '.xlsx'
        );
    }
}
