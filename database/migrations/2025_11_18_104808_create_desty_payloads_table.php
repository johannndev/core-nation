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
        Schema::create('desty_payloads', function (Blueprint $table) {
            $table->id();

            $table->date('date')->nullable(); // orderCreateTime (tanggal saja)

            $table->string('platform_warehouse_id')->nullable();
            $table->string('platform_warehouse_name')->nullable();

            $table->string('store_id')->nullable();
            $table->string('store_name')->nullable();
            $table->string('platform_name')->nullable();

            $table->string('invoice')->nullable();

            $table->decimal('adjustment', 15, 2)->default(0);
            $table->decimal('total_sales', 15, 2)->default(0);

            $table->string('order_status_list')->nullable();  // Completed / Returns
            $table->string('status')->nullable();             // pending dll

            $table->text('info')->nullable();

            $table->json('item_list')->nullable(); // SIMPAN JSON itemList    
            $table->string('json_path')->nullable();    // path file JSON disimpan
      
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desty_payloads');
    }
};
