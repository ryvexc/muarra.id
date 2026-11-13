<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\InventoryHistoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\WalletTransactionController;
use App\Http\Middleware\AnyAuthentication;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\StaffAuthentication;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, "index"])->name("dashboard.index")->middleware(AnyAuthentication::class);

Route::prefix("/orders")->group(function () {
  Route::get("/", [OrderController::class, 'index'])->name("order.index");
  Route::get("/export", [OrderController::class, 'export'])->name("order.export");
})->middleware(AnyAuthentication::class);

Route::prefix("/branch")->group(function () {
  Route::get("/", [BranchController::class, 'index'])->name("branch.index");
  Route::post("/", [BranchController::class, "post"])->name("branch.post");
  Route::put("/{id}", [BranchController::class, 'edit'])->name("branch.edit");
  Route::delete("/{id}", [BranchController::class, 'delete'])->name("branch.delete");
  Route::get("/export", [BranchController::class, 'export'])->name("branch.export");
})->middleware(Authenticate::class);

Route::prefix("/staff")->group(function () {
  Route::get("/", [StaffController::class, 'index'])->name("staff.index");
  Route::post("/", [StaffController::class, 'post'])->name("staff.post");
  Route::put("/{id}", [StaffController::class, 'edit'])->name("staff.edit");
  Route::delete("/{id}", [StaffController::class, 'delete'])->name("staff.delete");
  Route::get("/export", [StaffController::class, 'export'])->name("staff.export");
})->middleware(AnyAuthentication::class);

Route::prefix("/products")->group(function () {
  Route::get("/", [ProductController::class, 'index'])->name("product.index");
  Route::post("/", [ProductController::class, 'post'])->name("product.post");
  Route::get("/create", [ProductController::class, "create"])->name("product.create");
  Route::get("/edit/{id}", [ProductController::class, "editForm"])->name("product.edit.form");
  Route::put("/{id}", [ProductController::class, 'edit'])->name("product.edit");
  Route::delete("/{id}", [ProductController::class, 'delete'])->name("product.delete");
  Route::get("/export", [ProductController::class, 'export'])->name("product.export");
})->middleware(Authenticate::class);

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
})->middleware(Authenticate::class);

Route::prefix("/discount")->group(function () {
  Route::get("/", [DiscountController::class, "index"])->name('discount.index');
  Route::post("/", [DiscountController::class, "post"])->name('discount.post');
  Route::put("/{id}", [DiscountController::class, "edit"])->name('discount.edit');
})->middleware(Authenticate::class);

Route::prefix("/stocks")->group(function () {
  Route::get("/", [StockController::class, 'index'])->name("stock.index");
  Route::post("/", [StockController::class, 'post'])->name("stock.post");
  Route::put("/{id}", [StockController::class, 'edit'])->name("stock.edit");
  Route::delete("/{id}", [StockController::class, 'delete'])->name("stock.delete");
  Route::get("/export", [StockController::class, 'export'])->name('stock.export');

  Route::prefix("/report")->group(function () {
    Route::get("/", [StockOpnameController::class, 'index'])->name("stock-report.index");
    Route::get("/view/{id}", [StockOpnameController::class, 'view'])->name("stock-report.view");
    Route::get("/new", [StockOpnameController::class, 'new'])->name("stock-report.new");
    Route::post("/new", [StockOpnameController::class, 'createReport'])->name("stock-report.post");
  });
})->middleware(Authenticate::class);

Route::prefix("/auth")->group(function () {
  Route::get("/signin", [AuthController::class, 'signin'])->name("auth.signin");
  Route::post("/login", [AuthController::class, 'login'])->name("login");
  Route::get("/logout", [AuthController::class, 'logout'])->name("logout");
})->middleware(Authenticate::class);

Route::prefix("/finance")->group(function () {
  Route::prefix("/wallet")->group(function () {
    Route::get('/main', [WalletController::class, 'main'])->name("wallet.main.index");
    Route::get('/history', [WalletTransactionController::class, 'index'])->name("wallet.history.index");
    Route::get('/history/get-statement', [WalletTransactionController::class, 'exportStatement'])->name("wallet.history.get-statement");
    Route::get('/tenant', [WalletController::class, 'tenant'])->name("wallet.tenant");
  });
})->middleware(Authenticate::class);

Route::prefix("/tenants")->group(function () {
  Route::get("/", [TenantController::class, 'index'])->name("tenant.index");
  Route::post("/", [TenantController::class, 'post'])->name("tenant.post");
  Route::put("/{id}", [TenantController::class, 'edit'])->name("tenant.edit");
  Route::delete("/{id}", [TenantController::class, 'delete'])->name("tenant.delete");
})->middleware(Authenticate::class);

Route::prefix("/registered-users")->group(function () {
  Route::get("/", [RegisteredUserController::class, 'index'])->name("registered-user.index");
})->middleware(Authenticate::class);

Route::prefix("/supply")->group(function () {
  Route::get("/", [SupplyController::class, "index"])->name("supply.index");
  Route::post("/", [SupplyController::class, "post"])->name("supply.post");
})->middleware(Authenticate::class);

Route::prefix("/history")->group(function () {
  Route::get("/", [InventoryHistoryController::class, "index"])->name("inventory-history.index");
  Route::get("/export", [InventoryHistoryController::class, "export"])->name("inventory-history.export");
})->middleware(AnyAuthentication::class);

Route::get('/test-log', function () {
  \App\Helpers\JournalHelper::event('TEST', [
    'hello' => 'world'
  ]);

  return 'done';
})->middleware(Authenticate::class);

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
})->middleware(Authenticate::class);
