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
        Schema::create('desty_warehouses', function (Blueprint $table) {
            $table->id();

            $table->string('platform_warehouse_id')->nullable(); 
            $table->string('platform_warehouse_name')->nullable();

            $table->string('store_id')->nullable();
            $table->string('store_name')->nullable();

            $table->string('platform_name')->nullable();

            $table->timestamps();

            // kombinasi unik, hanya simpan jika salah satu tidak ada
            $table->unique(['platform_warehouse_id', 'store_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desty_warehouses');
    }
};
