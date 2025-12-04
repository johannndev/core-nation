<?php

namespace App\Http\Controllers;

use App\Models\DestyPayload;
use App\Models\DestyWarehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DestyApiController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->all();

        // ======================================
        // AMBIL orderStatusList SELALU SEBAGAI ARRAY
        // ======================================
        $orderStatusList = $payload['orderStatusList'] ?? [];

        if (!is_array($orderStatusList)) {
            $orderStatusList = [$orderStatusList];
        }

        // LOG STATUS
        Log::info('Received orderStatusList', [
            'orderStatusList' => $orderStatusList,
            'order_sn'        => $payload['orderSn'] ?? null
        ]);

        // ======================================
        // FILTER
        // ======================================

        $allowedStatus = ['Completed', 'Returned', 'Returns'];

        // cocokkan array kiriman dengan allowed
        $matched = array_intersect($orderStatusList, $allowedStatus);

        if (empty($matched)) {
            Log::info('Order status tidak cocok', [
                'received_status' => $orderStatusList,
                'order_sn'        => $payload['orderSn'] ?? null
            ]);

            return response()->json([
                'message' => 'Status tidak diproses'
            ], 200);
        }

        //jika invoice sudah ada jangan teruskan
        $existingOrder = DestyPayload::where('invoice', $payload['orderSn'])->first();
        if ($existingOrder) {
            Log::info('Order sudah ada, tidak disimpan ulang', [
                'invoice' => $payload['orderSn']
            ]); 
            return response()->json([
                'message' => 'Order sudah ada'
            ], 200);
        }

        // ======================================
        // DATA LANJUTAN
        // ======================================

        $date = Carbon::parse($payload['orderPaymentTime']);

        $itemListCollection = collect($payload['itemList'])->map(function ($item) {
            return [
                'code'     => $item['itemExternalCode'],
                'quantity' => $item['quantity'],
                'price'    => $item['price'] != 0 ? $item['price'] : $item['originalPrice'],
            ];
        });

        // SUM total sebelum toArray()
        $totalPrice = $itemListCollection->sum(function ($item) {
            return $item['quantity'] * $item['price'];
        });

        // Baru convert ke array
        $itemList = $itemListCollection->toArray();

        $adjustment = $payload['escrowAmount'] - $totalPrice;

        $orderId = $payload['orderId'];

        // --- Simpan file JSON ---
        $jsonFileName = $orderId . '.json';
        $jsonPath = public_path('desty/' . $jsonFileName);

        if (!file_exists(public_path('desty'))) {
            mkdir(public_path('desty'), 0777, true);
        }

        file_put_contents($jsonPath, json_encode($payload, JSON_PRETTY_PRINT));

        $publicPath = 'desty/' . $jsonFileName;

        // Ambil item pertama dari itemList
        $firstItem = $payload['itemList'][0] ?? null;

        // Ambil platform warehouse dari item pertama
        $platformWarehouseId = $firstItem['platformWarehouseId'] ?? null;
        $platformWarehouseName = $firstItem['platformWarehouseName'] ?? null;
        $locationName = " (".$firstItem['locationName'].")" ?? null;

        //totalsales = sum payload[itemList][originalPrice]


        $dataRaw = [
            "date" => $date,
            "platform_warehouse_id" => $platformWarehouseId,
            "platform_warehouse_name" => $platformWarehouseName.$locationName,
            "store_id" => $payload['storeId'],
            "store_name" => $payload['storeName'],
            "platform_name" => $payload['platformName'],
            "invoice" => $payload['orderSn'],
            "adjustment" => $adjustment,
            "total_sales" =>  $totalPrice,
            "order_status_list" => $orderStatusList[0], // simpan ARRAY asli
            "status" => 'pending',
            "info" => null,
            "item_list" => $itemList,
            "json_path" => $publicPath
        ];

        // SIMPAN WAREHOUSE
        $cekWarehouse = DestyWarehouse::where('platform_warehouse_id', $platformWarehouseId)
            ->where('store_id', $payload['storeId'])
            ->first();

        if (!$cekWarehouse) {
            DestyWarehouse::create([
                "platform_warehouse_id" => $platformWarehouseId,
                "platform_warehouse_name" => $platformWarehouseName.$locationName,
                "store_id" => $payload['storeId'],
                "store_name" => $payload['storeName'],
                "platform_name" => $payload['platformName'],
            ]);
        }

        // SIMPAN ORDER
        DestyPayload::create($dataRaw);

        return response()->json([
            'message' => 'Order tersimpan',
            'data' => $dataRaw
        ], 200);
    }
}
