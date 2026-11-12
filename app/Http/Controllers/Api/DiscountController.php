<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $user = Staff::find($request->user()->id);

        $discounts = Discount::all()
            ->where("tenant_id", $user->tenant->id)
            ->whereNull("deleted_at");

        return Response::json([
            "message" => "Get discounts success",
            "data" => $discounts
        ])->setStatusCode(200);
    }
}
