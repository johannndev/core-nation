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
        Schema::create('cutis', function (Blueprint $table) {
            $table->id();
            $table->integer('karyawan_id');
            $table->integer('tipe');
            $table->date('tgl_mulai');
            $table->date('tgl_akhir');
            $table->integer('tahunan')->default(0);
            $table->integer('sakit')->default(0);
            $table->integer('mendadak')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cutis');
    }
};
