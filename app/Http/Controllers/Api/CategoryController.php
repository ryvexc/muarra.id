<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CategoryController extends Controller
{
    public function indexCategory(Request $request)
    {
        $user = Staff::find($request->user()->id);

        $categories = ProductCategory::all()
            ->where("tenant_id", $user->tenant->id)
            ->whereNull("deleted_at");

        return Response::json([
            "message" => "Get categories success",
            "data" => $categories
        ])->setStatusCode(200);
    }

    public function indexSubCategory(Request $request)
    {
        $user = Staff::find($request->user()->id);

        $subCategories = ProductSubCategory::all()
            ->where("tenant_id", $user->tenant->id)
            ->whereNull("deleted_at");

        return Response::json([
            "message" => "Get subCategories success",
            "data" => $subCategories
        ])->setStatusCode(200);
    }
}
