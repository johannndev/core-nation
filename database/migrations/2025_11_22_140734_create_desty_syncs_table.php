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
        Schema::create('desty_syncs', function (Blueprint $table) {
            $table->id();
            $table->integer('desty_warehouse_id');
            $table->integer('warehouse_id');
            $table->integer('customer_id');
            $table->string('gudang_id')->nullable();
            $table->string('slot_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desty_syncs');
    }
};
