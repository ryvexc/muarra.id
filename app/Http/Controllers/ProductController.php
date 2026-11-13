<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductIngredient;
use App\Models\ProductSubCategory;
use App\Models\Staff;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function filteredOrders(Request $request, Staff | User $user)
    {
        $products = Product::query()->where("tenant_id", $user->tenant->id)->where('deleted_at', NULL);
        if ($request->filled('branch_id')) $products->where("branch_id", $request->branch_id);
        if ($request->filled("cashier_id")) $products->where("cashier_id", $request->cashier_id);
        if ($request->filled("payment_type")) $products->where("payment_type", $request->payment_type);
        if ($request->filled("category_id")) $products->where("product_category_id", $request->category_id);
        if ($request->filled("subcategory_id")) $products->where("product_subcategory_id", $request->subcategory_id);
        if ($request->filled("start_date")) $products->whereDate("created_at", ">=", $request->start_date);
        if ($request->filled("end_date")) $products->whereDate("created_at", "<=", $request->end_date);

        return $products;
    }

    public function index(Request $request)
    {
        $user = currentUser();

        $products = $this->filteredOrders($request, $user);

        if ($request->filled("per_page"))
            $products = $products->paginate($request->per_page == "All" ? $products->count() : $request->per_page)->withQueryString();
        else $products = $products->paginate(10)->withQueryString();

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
            $product->tenant_id = Auth::user()->tenant->id;
            $product->name = $request->name;
            $product->product_category_id = $request->product_category_id;
            $product->product_subcategory_id = $request->product_subcategory_id ?? NULL;
            $product->harga_jual = str_replace(".", "", $request->harga_jual);
            $product->harga_modal = str_replace(".", "", $request->harga_modal);
            $product->save();

            foreach ($request->ingredients ?? [] as $item) {

                $productIngredient = new ProductIngredient();
                $productIngredient->tenant_id = Auth::user()->tenant->id;
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

    public function export(Request $request)
    {
        $user = currentUser();
        $products = $this->filteredOrders($request, $user);

        return Excel::download(
            new ProductsExport($products),
            'products-' . now()->format('Y-m-d-His') . '.xlsx'
        );
    }
}
