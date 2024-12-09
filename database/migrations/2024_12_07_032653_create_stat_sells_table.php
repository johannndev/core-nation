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
        Schema::create('stat_sells', function (Blueprint $table) {
            $table->id();
            $table->integer('group_id');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->integer('sender_id');
            $table->integer('type');
            $table->integer('sum_qty');
            $table->decimal('sum_total', 30, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stat_sells');
    }
};
