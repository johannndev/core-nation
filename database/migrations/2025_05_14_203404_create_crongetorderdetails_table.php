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
        Schema::create('crongetorderdetails', function (Blueprint $table) {
            $table->id();
            $table->integer('get_order_id');
            $table->integer('order_id');
            $table->string('invoice');
            $table->string('location_id');
            $table->string('store_id');
            $table->string('status');
            $table->string('is_canceled')->default(10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crongetorderdetails');
    }
};
