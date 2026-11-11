<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Staff;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::all()->whereNull("deleted_at");
        $managers = Staff::all()->where("role_id", 3)->whereNull("deleted_at");

        return view("page.branch.index", [
            "branches" => $branches,
            "managers" => $managers
        ]);
    }

    public function post(Request $request)
    {
        $manager = Staff::all()->where("role_id", 3)->where("user_id", $request->manager_id)->first();

        if (!$manager) {
            return redirect()->back()->withErrors([
                "message" => "Manager isn't valid."
            ]);
        }

        $branch = new Branch();
        $branch->tenant_id = Auth::user()->tenant->id;
        $branch->manager_id = $manager->id;
        $branch->name = $request->name;
        $branch->address = $request->address;
        $branch->save();

        $manager->branch_id = $branch->id;
        $manager->save();

        return redirect()->back();
    }

    public function edit(Request $request, string $id)
    {
        $manager = Staff::all()->where("role_id", 3)->where("user_id", $request->manager_id)->first();

        if (!$manager) {
            return redirect()->back()->withErrors([
                "message" => "Manager isn't valid."
            ]);
        }

        $branch = Branch::find($id);
        $branch->manager_id = $manager->id;
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

        return redirect()->back();
    }
}
