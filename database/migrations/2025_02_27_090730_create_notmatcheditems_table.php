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
        Schema::create('notmatcheditems', function (Blueprint $table) {
            $table->id();
            $table->integer('transaction_list');
            $table->integer('item_code');
            $table->string('item_name');
            $table->string('channel');
            $table->string('loc_name');
            $table->string('thumbnail');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notmatcheditems');
    }
};
