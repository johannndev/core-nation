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
        Schema::create('restocks', function (Blueprint $table) {
            $table->id();
            $table->integer('item_id');
            $table->date('date');
            $table->integer('status');
            $table->integer('restocked_quantity')->nullable();
            $table->integer('in_production_quantity')->nullable();
            $table->integer('shipped_quantity')->nullable();
            $table->integer('missing_quantity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restocks');
    }
};
