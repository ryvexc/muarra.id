<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $user = currentUser();

        $discounts = Discount::query()->where("tenant_id", $user->tenant->id)->where("deleted_at", NULL)->paginate(10)->withQueryString();

        return view("page.discount.index", [
            "discounts" => $discounts
        ]);
    }

    public function post(Request $request)
    {
        $discount = new Discount();
        $discount->tenant_id = Auth::user()->tenant->id;
        $discount->name = $request->name;
        $discount->value = str_replace(".", "", $request->value);
        $discount->type = $request->type;
        $discount->expiry_date = $request->expiry_date ?? NULL;
        $discount->description = $request->description ?? NULL;
        $discount->save();

        return redirect()->back();
    }

    public function edit(Request $request, string $id)
    {
        $discount = Discount::find($id);
        $discount->name = $request->name;
        $discount->value = str_replace(".", "", $request->value);
        $discount->type = $request->type;
        $discount->expiry_date = $request->expiry_date ?? NULL;
        $discount->description = $request->description ?? NULL;
        $discount->save();

        return redirect()->back();
    }
}
