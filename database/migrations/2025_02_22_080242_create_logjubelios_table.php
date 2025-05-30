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
            $table->string('order_id')->nullable();
            $table->string('error')->nullable();
            $table->string('type')->nullable();
            $table->string('invoice')->nullable();
            $table->longText('pesan')->nullable();
            $table->string('location_name')->nullable();
            $table->string('store_name')->nullable();
            $table->longText('cron_failed')->nullable();
            $table->integer('cron_run')->default(0);
            $table->integer('status')->default(0);
            
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
