<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductIngredient;
use App\Models\ProductSubCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all()->whereNull("deleted_at");
        $categories = ProductCategory::all()->whereNull("deleted_at");
        $subcategories = ProductSubCategory::all()->whereNull("deleted_at");

        return view("page.product.index", [
            "products" => $products,
            "categories" => $categories,
            "subcategories" => $subcategories
        ]);
    }

    public function create()
    {
        $categories = ProductCategory::all()->whereNull("deleted_at");
        $subcategories = ProductSubCategory::all()->whereNull("deleted_at");
        $ingredients = Ingredient::all()->whereNull("deleted_at");

        return view("page.product.create", [
            "categories" => $categories,
            "subcategories" => $subcategories,
            "ingredients" => $ingredients
        ]);
    }

    public function post(Request $request)
    {
        DB::transaction(function () use ($request) {

            $product = new Product();
            $product->name = $request->name;
            $product->product_category_id = $request->product_category_id;
            $product->product_subcategory_id = $request->product_subcategory_id;
            $product->harga_jual = str_replace(".", "", $request->harga_jual);
            $product->harga_modal = str_replace(".", "", $request->harga_modal);
            $product->save();

            foreach ($request->ingredients ?? [] as $item) {

                $productIngredient = new ProductIngredient();
                $productIngredient->product_id = $product->id;
                $productIngredient->ingredient_id = $item['ingredient_id'];
                $productIngredient->quantity = $item['quantity'];
                $productIngredient->save();
            }
        });

        return redirect()->route("product.index");
    }

    public function editForm(Request $request, string $id)
    {
        $product = Product::find($id);
        $categories = ProductCategory::all()->whereNull("deleted_at");
        $subcategories = ProductSubCategory::all()->whereNull("deleted_at");
        $ingredients = Ingredient::all()->whereNull("deleted_at");

        return view("page.product.edit", [
            "product" => $product,
            "categories" => $categories,
            "subcategories" => $subcategories,
            "ingredients" => $ingredients
        ]);
    }

    public function edit(Request $request, string $id)
    {
        DB::transaction(function () use ($request, $id) {
            $product = Product::findOrFail($id);
            $product->name = $request->name;
            $product->product_category_id = $request->product_category_id;
            $product->product_subcategory_id = $request->product_subcategory_id;
            $product->harga_jual = str_replace(".", "", $request->harga_jual);
            $product->harga_modal = str_replace(".", "", $request->harga_modal);
            $product->save();

            ProductIngredient::where(
                'product_id',
                $product->id
            )->delete();

            foreach ($request->ingredients ?? [] as $item) {
                $productIngredient = new ProductIngredient();
                $productIngredient->product_id = $product->id;
                $productIngredient->ingredient_id = $item['ingredient_id'];
                $productIngredient->quantity = $item['quantity'];
                $productIngredient->save();
            }
        });

        return redirect()->route("product.index");
    }

    public function delete(Request $request, string $id)
    {
        $product = Product::find($id);
        $product->deleted_at = Carbon::now();
        $product->save();

        return redirect()->back();
    }
}
