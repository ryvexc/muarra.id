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
        Schema::create('stock_report_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId("stock_report_id")->references("id")->on("stock_reports");
            $table->foreignId("ingredient_id")->references("id")->on("ingredients");
            $table->bigInteger("unit_price");
            $table->integer("opening_stock");
            $table->integer("outgoing_stock");
            $table->integer("incoming_stock");
            $table->integer("expected_stock");
            $table->integer("actual_stock");
            $table->integer("difference");
            $table->bigInteger("difference_value");
            $table->longText("notes")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_report_items');
    }
};
