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
        Schema::create('crongetorders', function (Blueprint $table) {
            $table->id();
            $table->date('from');
            $table->date('to');
            $table->integer('count')->default(0);
            $table->integer('total')->default(0);
            $table->integer('status')->default(0);
            $table->integer('step')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crongetorders');
    }
};
