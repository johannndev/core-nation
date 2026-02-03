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
        Schema::create('restock_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('restock_id')->constrained()->cascadeOnDelete();
            $table->integer('item_id')->index();

            $table->string('step');
            // restocked | production | shipped | received | missing

            $table->string('action');
            // created | moved | edited | received | missing

            $table->integer('qty_before')->default(0);
            $table->integer('qty_after')->default(0);
            $table->integer('qty_changed')->default(0);

            $table->string('invoice')->nullable();

            $table->integer('user_id');
            $table->date('date');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restock_histories');
    }
};
