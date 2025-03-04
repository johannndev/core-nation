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
        Schema::create('logjubelios', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('invoice')->nullable();
            $table->json('data')->nullable(); // Kolom untuk menyimpan array dalam bentuk JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logjubelios');
    }
};
