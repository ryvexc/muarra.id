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
        Schema::create('inventory_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId("tenant_id")->nullable()->references("id")->on("tenants")->onDelete("SET NULL");
            $table->foreignId("branch_id")->nullable()->references("id")->on("branches")->onDelete("SET NULL");
            $table->foreignId("ingredient_id")->nullable()->references("id")->on("ingredients")->onDelete("SET NULL");
            $table->enum("type", ["in", "out", "adjustment"]);
            $table->integer("quantity")->nullable();
            $table->integer("stock_before");
            $table->integer("stock_after");
            $table->string("reference_type");
            $table->integer("reference_id");
            $table->longText("notes");
            $table->foreignId("created_by_staff_id")->nullable()->references("id")->on("staffs")->onDelete("SET NULL");
            $table->foreignId("created_by_user_id")->nullable()->references("id")->on("users")->onDelete("SET NULL");
            $table->foreignId("stock_report_id")->nullable()->references("id")->on("stock_reports")->onDelete("SET NULL");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_histories');
    }
};
