<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::all()->whereNull("deleted_at");
        $subCategories = ProductSubCategory::all()->whereNull("deleted_at");

        return view("page.category.index", [
            "subCategories" => $subCategories,
            "categories" => $categories
        ]);
    }

    public function postCategory(Request $request)
    {
        $product_category = new ProductCategory();
        $product_category->name = $request->name;
        $product_category->save();

        return redirect()->back();
    }

    public function editCategory(Request $request, string $id)
    {
        $product_category = ProductCategory::find($id);
        $product_category->name = $request->name;
        $product_category->save();

        return redirect()->back();
    }

    public function deleteCategory(Request $request, string $id)
    {
        $product_category = ProductCategory::find($id);
        $product_category->deleted_at = Carbon::now();
        $product_category->save();

        return redirect()->back();
    }

    public function postSubCategory(Request $request)
    {
        $product_sub_category = new ProductSubCategory;
        $product_sub_category->product_category_id = $request->product_category_id;
        $product_sub_category->name = $request->name;
        $product_sub_category->save();

        return redirect()->back();
    }

    public function editSubCategory(Request $request, string $id)
    {
        $product_sub_category = ProductSubCategory::find($id);
        $product_sub_category->product_category_id = $request->product_category_id;
        $product_sub_category->name = $request->name;
        $product_sub_category->save();

        return redirect()->back();
    }

    public function deleteSubCategory(Request $request, string $id)
    {
        $product_sub_category = ProductSubCategory::find($id);
        $product_sub_category->deleted_at = Carbon::now();
        $product_sub_category->save();

        return redirect()->back();
    }
}
