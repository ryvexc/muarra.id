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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId("tenant_id")->nullable()->references("id")->on("tenants")->onDelete("SET NULL");
            $table->foreignId("branch_id")->nullable()->references("id")->on("branches")->onDelete("SET NULL");
            $table->foreignId("cashier_id")->nullable()->references("id")->on("staffs")->onDelete("SET NULL");
            $table->string("cust_name")->nullable();
            $table->bigInteger("subtotal");
            $table->foreignId("discount_id")->nullable()->references("id")->on("discounts")->onDelete("SET NULL");
            $table->bigInteger("total");
            $table->enum("payment_type", ["qris", "card", "cash"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
