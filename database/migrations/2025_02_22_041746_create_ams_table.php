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
        Schema::create('ams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('sk')->nullable();
            $table->longText('pk')->nullable();
            $table->longText('ok')->nullable();
            $table->dateTime('expDate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ams');
    }
};
