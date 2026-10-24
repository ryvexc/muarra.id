<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $discounts = Discount::all()->whereNull("deleted_at");

        return view("page.discount.index", [
            "discounts" => $discounts
        ]);
    }

    public function post(Request $request)
    {
        $discount = new Discount();
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
