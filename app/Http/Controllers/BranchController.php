<?php

namespace App\Http\Controllers;

use App\Exports\BranchesExport;
use App\Models\Branch;
use App\Models\Ingredient;
use App\Models\Inventory;
use App\Models\Staff;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class BranchController extends Controller
{
    public function filteredOrders(Request $request, Staff | User $user)
    {
        $branches = Branch::query()->where("tenant_id", $user->tenant->id)->where('deleted_at', NULL);
        if ($request->filled('branch_id')) $branches->where("branch_id", $request->branch_id);
        if ($request->filled("cashier_id")) $branches->where("cashier_id", $request->cashier_id);
        if ($request->filled("payment_type")) $branches->where("payment_type", $request->payment_type);
        if ($request->filled("start_date")) $branches->whereDate("created_at", ">=", $request->start_date);
        if ($request->filled("end_date")) $branches->whereDate("created_at", "<=", $request->end_date);

        return $branches;
    }

    public function index(Request $request)
    {
        $user = currentUser();

        $branches = $this->filteredOrders($request, $user);

        if ($request->filled("per_page"))
            $branches = $branches->paginate($request->per_page == "All" ? $branches->count() : $request->per_page)->withQueryString();
        else $branches = $branches->paginate(10)->withQueryString();

        $managers = Staff::all()->where("tenant_id", $user->tenant->id)->where("role_id", 3)->whereNull("deleted_at");

        return view("page.branch.index", [
            "branches" => $branches,
            "managers" => $managers
        ]);
    }

    public function post(Request $request)
    {
        $user = currentUser();

        $branch = new Branch();
        $branch->tenant_id = Auth::user()->tenant->id;
        $branch->manager_id = $request->manager_id ?? NULL;
        $branch->name = $request->name;
        $branch->address = $request->address;
        $branch->save();

        $ingredients = Ingredient::all();

        foreach ($ingredients as $ingredient) {
            $inventory = new Inventory();
            $inventory->branch_id = $branch->id;
            $inventory->tenant_id = currentUser()->tenant->id;
            $inventory->ingredient_id = $ingredient->id;
            $inventory->stock = 0;
            $inventory->save();
        }

        $cashWallet = new Wallet();
        $cashWallet->uuid = Str::uuid();
        $cashWallet->type = "cash";
        $cashWallet->tenant_id = $user->tenant->id;
        $cashWallet->branch_id = $branch->id;
        $cashWallet->balance = 0;
        $cashWallet->save();

        $cashlessWallet = new Wallet();
        $cashlessWallet->uuid = Str::uuid();
        $cashlessWallet->type = "cashless";
        $cashlessWallet->tenant_id = $user->tenant->id;
        $cashlessWallet->branch_id = $branch->id;
        $cashlessWallet->balance = 0;
        $cashlessWallet->save();

        if ($request->manager_id) {
            $manager = Staff::all()->where("role_id", 3)->where("id", $request->manager_id)->first();

            if (!$manager) {
                return redirect()->back()->withErrors([
                    "message" => "Manager isn't valid."
                ]);
            }

            $manager->branch_id = $branch->id;
            $manager->save();
        }

        return redirect()->back();
    }

    public function edit(Request $request, string $id)
    {
        $manager = Staff::all()->where("role_id", 3)->where("id", $request->manager_id)->first();

        if (!$manager) {
            return redirect()->back()->withErrors([
                "message" => "Manager isn't valid."
            ]);
        }

        $branch = Branch::find($id);
        $branch->manager_id = $manager->id ?? NULL;
        $branch->name = $request->name;
        $branch->address = $request->address;
        $branch->save();

        $manager->branch_id = $branch->id;
        $manager->save();

        return redirect()->back();
    }

    public function delete(Request $request, string $id)
    {
        $branch = Branch::find($id);
        $branch->deleted_at = Carbon::now();
        $branch->save();

        if ($branch)
            Staff::where("branch_id", $branch->id)->update([
                "branch_id" => NULL
            ]);

        return redirect()->back();
    }

    public function export(Request $request)
    {
        $user = currentUser();
        $orders = $this->filteredOrders($request, $user);

        return Excel::download(
            new BranchesExport($orders),
            'branches-' . now()->format('Y-m-d-His') . '.xlsx'
        );
    }
}
