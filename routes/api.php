<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix("v1")->group(function () {
    Route::prefix("auth")->group(function () {
        Route::post("signin", [AuthController::class, "signin"]);
        Route::post("signout", [AuthController::class, "signout"])->middleware("auth:sanctum");
        Route::post("me", [AuthController::class, "me"])->middleware("auth:sanctum");
    });

    Route::prefix("discounts")->group(function () {
        Route::get("/", [DiscountController::class, "index"])->middleware("auth:sanctum");
    });

    Route::prefix("products")->group(function () {
        Route::get("/", [ProductController::class, "index"])->middleware("auth:sanctum");
    });

    Route::prefix("categories")->group(function () {
        Route::get("/", [CategoryController::class, "indexCategory"])->middleware("auth:sanctum");
    });

    Route::prefix("subcategories")->group(function () {
        Route::get("/", [CategoryController::class, "indexSubCategory"])->middleware("auth:sanctum");
    });
});
