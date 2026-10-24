<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, "index"])->name("dashboard.index");
Route::get("/orders", [OrderController::class, 'index'])->name("order.index");

Route::prefix("/products")->group(function () {
  Route::get("/", [ProductController::class, 'index'])->name("product.index");
  Route::post("/", [ProductController::class, 'post'])->name("product.post");
  Route::get("/create", [ProductController::class, "create"])->name("product.create");
  Route::get("/edit/{id}", [ProductController::class, "editForm"])->name("product.edit.form");
  Route::put("/{id}", [ProductController::class, 'edit'])->name("product.edit");
  Route::delete("/{id}", [ProductController::class, 'delete'])->name("product.delete");
});

Route::prefix("/stocks")->group(function () {
  Route::get("/", [StockController::class, 'index'])->name("stock.index");
  Route::post("/", [StockController::class, 'post'])->name("stock.post");
  Route::put("/{id}", [StockController::class, 'edit'])->name("stock.edit");
  Route::delete("/{id}", [StockController::class, 'delete'])->name("stock.delete");

  Route::prefix("/report")->group(function () {
    Route::get("/", [StockOpnameController::class, 'index'])->name("stock-report.index");
    Route::get("/view/{id}", [StockOpnameController::class, 'view'])->name("stock-report.view");
    Route::get("/new", [StockOpnameController::class, 'new'])->name("stock-report.new");
    Route::post("/new", [StockOpnameController::class, 'createReport'])->name("stock-report.post");
  });
});

Route::prefix("/finance")->group(function () {
  Route::prefix("/wallet")->group(function () {
    Route::get('/main', [WalletController::class, 'main'])->name("wallet.main.index");
    Route::get('/tenant', [WalletController::class, 'tenant'])->name("wallet.tenant");
  });
});

Route::prefix("/categories")->group(function () {
  Route::get("/", [CategoryController::class, 'index'])->name("category.index");
  Route::post("/", [CategoryController::class, 'postCategory'])->name('category.post');
  Route::put("/{id}", [CategoryController::class, 'editCategory'])->name('category.edit');
  Route::delete("/{id}", [CategoryController::class, 'deleteCategory'])->name('category.delete');

  Route::prefix("/sub")->group(function () {
    Route::post('/', [CategoryController::class, 'postSubCategory'])->name("category.sub.post");
    Route::put('/{id}', [CategoryController::class, 'editSubCategory'])->name("category.sub.edit");
    Route::delete('/{id}', [CategoryController::class, 'deleteSubCategory'])->name("category.sub.delete");
  });
});

Route::prefix("/supply")->group(function () {
  Route::get("/", [SupplyController::class, "index"])->name("supply.index");
  Route::post("/", [SupplyController::class, "post"])->name("supply.post");
});

Route::prefix("/discount")->group(function () {
  Route::get("/", [DiscountController::class, "index"])->name('discount.index');
  Route::post("/", [DiscountController::class, "post"])->name('discount.post');
  Route::put("/{id}", [DiscountController::class, "edit"])->name('discount.edit');
});

Route::get('/test-log', function () {
  \App\Helpers\JournalHelper::event('TEST', [
    'hello' => 'world'
  ]);

  return 'done';
});

Route::prefix("journal")->group(function () {
  Route::get("/event", function () {
    $path = storage_path('journals/event.log');

    $logs = collect();

    if (file_exists($path)) {
      $logs = collect(file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES))
        ->map(fn($line) => json_decode($line, true))
        ->reverse()
        ->values();
    }

    return view("page.journal.event", compact("logs"));
  });
});
