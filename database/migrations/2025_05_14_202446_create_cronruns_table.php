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
        Schema::create('cronruns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('command');      // e.g. 'cron:daily-clean'
            $table->string('schedule');     // e.g. 'daily', 'dailyAt:01:00'
            $table->boolean('status')->default(1); // 1 = aktif, 0 = nonaktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cronruns');
    }
};
