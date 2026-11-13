<?php

namespace App\Http\Controllers;

use App\Exports\StaffExport;
use App\Models\Branch;
use App\Models\Role;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class StaffController extends Controller
{
    public function filteredOrders(Request $request, Staff | User $user)
    {
        $staffs = Staff::query()->where("tenant_id", $user->tenant->id)->where('deleted_at', NULL);
        if ($request->filled('branch_id')) $staffs->where("branch_id", $request->branch_id);
        if ($request->filled("cashier_id")) $staffs->where("cashier_id", $request->cashier_id);
        if ($request->filled("payment_type")) $staffs->where("payment_type", $request->payment_type);
        if ($request->filled("assigned_in")) $staffs->where("branch_id", $request->assigned_in);
        if ($request->filled("start_date")) $staffs->whereDate("created_at", ">=", $request->start_date);
        if ($request->filled("end_date")) $staffs->whereDate("created_at", "<=", $request->end_date);

        return $staffs;
    }

    public function index(Request $request)
    {
        $user = currentUser();

        $staffs = $this->filteredOrders($request, $user);

        if ($request->filled("per_page"))
            $staffs = $staffs->paginate($request->per_page == "All" ? $staffs->count() : $request->per_page)->withQueryString();
        else $staffs = $staffs->paginate(10)->withQueryString();

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

    public function export(Request $request)
    {
        $user = currentUser();
        $orders = $this->filteredOrders($request, $user);

        return Excel::download(
            new StaffExport($orders),
            'staff-' . now()->format('Y-m-d-His') . '.xlsx'
        );
    }
}
