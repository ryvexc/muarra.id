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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId("branch_id")->nullable()->references("id")->on("branches")->onDelete("SET NULL");
            $table->foreignId("tenant_id")->nullable()->references("id")->on("tenants")->onDelete("SET NULL");
            $table->foreignId("ingredient_id")->nullable()->references("id")->on("ingredients")->onDelete("SET NULL");
            $table->integer("stock");
            $table->timestamp("deleted_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
