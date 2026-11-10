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
        Schema::create('staffs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("tenant_id")->nullable()->references("id")->on("tenants")->onDelete("SET NULL");
            $table->foreignId("branch_id")->nullable()->references("id")->on("branches")->onDelete("SET NULL");
            $table->foreignId("role_id")->nullable()->references("id")->on("roles")->onDelete("SET NULL");
            $table->string("name");
            $table->string("email")->unique();
            $table->string("phone_number")->nullable()->unique();
            $table->string("password");
            $table->timestamp("deleted_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staffs');
    }
};
