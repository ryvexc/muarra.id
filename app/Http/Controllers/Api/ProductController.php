<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $user = Staff::find($request->user()->id);

        $products = Product::all()
            ->where("tenant_id", $user->tenant->id)
            ->whereNull("deleted_at");

        return Response::json([
            "message" => "Get products success",
            "data" => $products
        ])->setStatusCode(200);
    }
}
