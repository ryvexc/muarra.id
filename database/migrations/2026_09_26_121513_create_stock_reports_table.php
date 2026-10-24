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
        Schema::create('stock_reports', function (Blueprint $table) {
            $table->id();
            $table->string("cashier_name");
            $table->string("cabang");
            $table->integer("stock_matched_count");
            $table->integer("stock_surplus_count");
            $table->integer("stock_surplus_value");
            $table->integer("stock_shortage_count");
            $table->integer("stock_shortage_value");
            $table->enum("status", ["shortage", "surplus", "matched"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_reports');
    }
};
