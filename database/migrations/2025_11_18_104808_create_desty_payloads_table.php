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

            $table->string('order_id');                 // orderId
            $table->string('item_order_id');            // itemOrderId
            $table->string('item_code');                // itemCode
            $table->string('item_external_code');       // itemExternalCode
            $table->string('item_name');                // itemName
            $table->string('location_id')->nullable();  // locationId
            $table->string('location_name')->nullable();// locationName

            $table->string('store_id');                 // storeId
            $table->string('store_name');               // storeName

            $table->string('platform_order_status');    // platformOrderStatus
            $table->integer('quantity');                // quantity
            $table->integer('sell_price');              // sellPrice

            $table->string('json_path')->nullable();    // path file JSON disimpan
            $table->integer('status');      
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
