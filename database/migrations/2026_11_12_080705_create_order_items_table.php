<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId("order_id")->nullable()->references("id")->on("orders")->onDelete("SET NULL");
            $table->foreignId("product_id")->nullable()->references("id")->on("products")->onDelete("SET NULL");
            $table->integer("quantity");
            $table->bigInteger("item_price");
            $table->bigInteger("total_price");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
