<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        $tenants = Tenant::all()->whereNull("deleted_at");
        $users = User::all()->where("id", ">", 1)->whereNull("deleted_at");

        return view("page.tenants.index", [
            "tenants" => $tenants,
            "users" => $users
        ]);
    }

    public function post(Request $request)
    {
        $tenant = new Tenant();
        $tenant->name = $request->name;
        $tenant->user_id = $request->user_id;
        $tenant->save();

        return redirect()->back();
    }

    public function edit(Request $request, string $id)
    {
        $tenant = Tenant::find($id);
        $tenant->name = $request->name;
        $tenant->user_id = $request->user_id;
        $tenant->save();

        return redirect()->back();
    }

    public function delete(Request $request, string $id)
    {
        $tenant = Tenant::find($id);
        $tenant->deleted_at = Carbon::now();
        $tenant->save();

        return redirect()->back();
    }
}
