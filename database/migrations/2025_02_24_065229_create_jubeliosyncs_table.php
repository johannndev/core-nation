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
        Schema::create('jubeliosyncs', function (Blueprint $table) {
            $table->id();
            $table->integer('jubelio_store_id');
            $table->string('jubelio_store_name');
            $table->integer('jubelio_location_id');
            $table->string('jubelio_location_name');
            $table->integer('warehouse_id');
            $table->integer('customer_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jubeliosyncs');
    }
};
