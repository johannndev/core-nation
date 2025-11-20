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
        try {

            $payload = $request->all();
            
            // ==========================
            // FILTER ORDER STATUS
            // ==========================
            $orderStatus = $payload['orderStatusList'] ?? null;

            if (!in_array($orderStatus, ['Completed', 'Returned', 'Returns'])) {
                return response()->json([
                    'message' => 'Status tidak diproses'
                ], 200);
            }

            // ==========================
            // PREPARE DATA
            // ==========================

            $date = Carbon::parse($payload['orderCreateTime'])->format('Y-m-d');

            $itemList = collect($payload['items'])->map(function ($item) {
                return [
                    'code' => $item['itemExternalCode'],
                    'quantity' => $item['quantity'],
                    'price' => $item['sellPrice'],
                ];
            })->toArray();

            $adjustment = $payload['totalInvoice'] - $payload['totalSales']; // minus

            $orderId = $payload['orderId'];

            // --- Step 1: Generate JSON file per-order ---
            $jsonFileName = $orderId . '.json';
            $jsonPath = public_path('desty/' . $jsonFileName);

            // pastikan folder ada
            if (!file_exists(public_path('desty'))) {
                mkdir(public_path('desty'), 0777, true);
            }

            // simpan JSON asli ke file
            file_put_contents($jsonPath, json_encode($payload, JSON_PRETTY_PRINT));

            // path untuk database (public path)
            $publicPath = 'desty/' . $jsonFileName;

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

            // ==========================
            // SIMPAN TO DestyWarehouse
            // ==========================

            $platformWarehouseId = $payload['storeName'];
            $storeId = $payload['storeId'];

            $cekWarehouse = DestyWarehouse::where('platformWarehouseId', $platformWarehouseId)
                ->where('storeId', $storeId)
                ->first();

            /**
             * Simpan jika:
             * - platformWarehouseId TIDAK ADA, atau
             * - storeId TIDAK ADA
             */
            if (!$cekWarehouse) {
                DestyWarehouse::create([
                    "platformWarehouseId" => $payload['storeName'],
                    "platformWarehouseName" => $payload['platformName'],
                    "storeId" => $payload['storeId'],
                    "storeName" => $payload['storeName'],
                    "platformName" => $payload['platformName'],
                ]);
            }

            // ==========================
            // SIMPAN ORDER
            // ==========================

            DestyPayload::create($dataRaw);

            

            return response()->json([
                'message' => 'Order tersimpan',
                'data' => $dataRaw
            ], 200);

        } catch (\Exception $e) {

            Log::error("Webhook Error: ".$e->getMessage());

            return response()->json([
                'message' => 'Error',
                'error' => $e->getMessage()
            ], 500);
        }

        
    }
}
