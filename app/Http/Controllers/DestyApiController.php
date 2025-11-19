<?php

namespace App\Http\Controllers;

use App\Models\DestyPayload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DestyApiController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $data = $request->all();

        $orderId = $data['orderId'];
        $items = $data['itemList'];

        // --- Step 1: Generate JSON file per-order ---
        $jsonFileName = $orderId . '.json';
        $jsonPath = public_path('desty/' . $jsonFileName);

        // pastikan folder ada
        if (!file_exists(public_path('desty'))) {
            mkdir(public_path('desty'), 0777, true);
        }

        // simpan JSON asli ke file
        file_put_contents($jsonPath, json_encode($data, JSON_PRETTY_PRINT));

        // path untuk database (public path)
        $publicPath = 'desty/' . $jsonFileName;


        // --- Step 2: Simpan batch item ke DB ---
        $batchInsert = [];

        foreach ($items as $item) {
            $batchInsert[] = [
                'order_id' => $orderId,
                'item_order_id' => $item['itemOrderId'],
                'item_code' => $item['itemCode'],
                'item_external_code' => $item['itemExternalCode'],
                'item_name' => $item['itemName'],
                'location_id' => $item['locationId'],
                'location_name' => $item['locationName'],
                'store_id' => $data['storeId'],
                'store_name' => $data['storeName'],
                'platform_order_status' => $item['platformOrderStatus'],
                'quantity' => $item['quantity'],
                'sell_price' => $item['sellPrice'],
                'json_path' => $publicPath,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('order_items')->insert($batchInsert);

        return response()->json([
            'success' => true,
            'message' => 'Order items saved successfully',
            'json_path' => $publicPath
        ]);
    }

    /**
     * Get all stored payloads (untuk testing/debugging)
     */
    public function getPayloads()
    {
        $payloads = DestyPayload::latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => $payloads
        ], 200);
    }

    /**
     * Get specific payload by ID
     */
    public function getPayload($id)
    {
        $payload = DestyPayload::find($id);

        if (!$payload) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payload not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $payload
        ], 200);
    }
}
