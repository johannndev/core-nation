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
        Schema::create('gajihs', function (Blueprint $table) {
            $table->id();
            $table->integer('karyawan_id');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->integer('bulanan');
            $table->integer('harian');
            $table->integer('premi');
            $table->integer('cuti_sakit')->default(0);
            $table->integer('cuti_tahunan')->default(0);
            $table->integer('cuti_mendadak')->default(0);
            $table->integer('total_cuti')->default(0);
            $table->integer('potongan_cuti_bulanan')->default(0);
            $table->integer('potongan_cuti_premi')->default(0);
            $table->integer('total_potongan')->default(0);
            $table->integer('flag')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gajihs');
    }
};
