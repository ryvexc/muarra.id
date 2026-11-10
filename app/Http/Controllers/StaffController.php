<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Role;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $staffs = Staff::all()->whereNull("deleted_at");
        $branches = Branch::all()->whereNull("deleted_at");
        $roles = Role::all()->where("id", ">", 2)->whereNull("deleted_at");

        return view("page.staff.index", [
            "staffs" => $staffs,
            "branches" => $branches,
            "roles" => $roles
        ]);
    }

    public function post(Request $request)
    {

        $staff = new Staff;

        $staff->tenant_id = Auth::user()->tenant->id;
        $staff->role_id = $request->role_id;
        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->phone_number = $request->phone_number;

        if ($request->password) {
            $staff->password = Hash::make($request->password);
        }

        $staff->branch_id = $request->branch_id;
        $staff->save();

        return redirect()->back();
    }

    public function edit(Request $request, string $id)
    {
        $staff = Staff::find($id);

        $staff->role_id = $request->role_id;
        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->phone_number = $request->phone_number;

        if ($request->password) {
            $staff->password = Hash::make($request->password);
        }

        $staff->branch_id = $request->branch_id;
        $staff->save();

        return redirect()->back();
    }

    public function delete(Request $request, string $id)
    {
        $staff = Staff::find($id);

        $staff->deleted_at = $request->branch_id;
        $staff->save();

        return redirect()->back();
    }
}
