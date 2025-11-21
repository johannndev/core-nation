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
        // LOG AWAL SEMUA STATUS YANG DITERIMA
        // ======================================
        Log::info('Received orderStatusList', [
            'orderStatusList' => $payload['orderStatusList'] ?? null,
            'order_id'        => $payload['orderId'] ?? null
        ]);

        // ======================================
        // FILTER ORDER STATUS (STRING)
        // ======================================

        // Jika hanya satu string status (misal: "Completed")
        $orderStatus = $payload['orderStatusList'] ?? null;

        if (!in_array($orderStatus, ['Completed', 'Returned', 'Returns'])) {
            Log::info('Order status tidak diproses (string)', [
                'received_status' => $orderStatus,
                'order_id'        => $payload['orderId'] ?? null
            ]);

            return response()->json([
                'message' => 'Status tidak diproses'
            ], 200);
        }

        // ======================================
        // FILTER ORDER STATUS (ARRAY)
        // ======================================
        $orderStatusList = $payload['orderStatusList'] ?? [];

        // pastikan berupa array
        if (!is_array($orderStatusList)) {
            $orderStatusList = [$orderStatus];
        }

        $allowedStatus = ['Completed', 'Returns'];

        $matched = array_intersect($orderStatusList, $allowedStatus);

        if (empty($matched)) {

            Log::info('Order status tidak cocok (array)', [
                'received_status' => $orderStatusList,
                'order_id'        => $payload['orderId'] ?? null
            ]);

            return response()->json([
                'message' => 'Status tidak diproses'
            ], 200);
        }

        // ======================================
        // PREPARE DATA
        // ======================================

        $date = Carbon::parse($payload['orderCreateTime'])->format('Y-m-d');

        $itemList = collect($payload['items'])->map(function ($item) {
            return [
                'code' => $item['itemExternalCode'],
                'quantity' => $item['quantity'],
                'price' => $item['sellPrice'],
            ];
        })->toArray();

        $adjustment = $payload['totalInvoice'] - $payload['totalSales'];

        $orderId = $payload['orderId'];

        // --- Simpan file JSON ---
        $jsonFileName = $orderId . '.json';
        $jsonPath = public_path('desty/' . $jsonFileName);

        if (!file_exists(public_path('desty'))) {
            mkdir(public_path('desty'), 0777, true);
        }

        file_put_contents($jsonPath, json_encode($payload, JSON_PRETTY_PRINT));
        $publicPath = 'desty/' . $jsonFileName;

        // Data untuk insert
        $dataRaw = [
            "date" => $date,
            "platformWarehouseId" => $payload['storeName'],
            "platformWarehouseName" => $payload['platformName'],
            "storeId" => $payload['storeId'],
            "storeName" => $payload['storeName'],
            "platformName" => $payload['platformName'],
            "invoice" => $payload['orderId'],
            "adjustment" => $adjustment,
            "totalSales" => $payload['totalSales'],
            "orderStatusList" => $orderStatus,
            "status" => 'pending',
            "info" => null,
            "itemList" => $itemList,
            "json_path" => $publicPath
        ];

        // ======================================
        // SIMPAN WAREHOUSE (JIKA BELUM ADA)
        // ======================================

        $cekWarehouse = DestyWarehouse::where('platformWarehouseId', $payload['storeName'])
            ->where('storeId', $payload['storeId'])
            ->first();

        if (!$cekWarehouse) {
            DestyWarehouse::create([
                "platformWarehouseId" => $payload['storeName'],
                "platformWarehouseName" => $payload['platformName'],
                "storeId" => $payload['storeId'],
                "storeName" => $payload['storeName'],
                "platformName" => $payload['platformName'],
            ]);
        }

        // ======================================
        // SIMPAN ORDER
        // ======================================
        DestyPayload::create($dataRaw);

        return response()->json([
            'message' => 'Order tersimpan',
            'data' => $dataRaw
        ], 200);
    }
}
