<?php

namespace App\Http\Controllers;

use App\Models\DestyPayload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DestyApiController extends Controller
{
    public function handleWebhook(Request $request)
    {
        try {
           
            // Simpan payload ke database
            $destyPayload = DestyPayload::create([
                'payload' => $request->all()
            ]);

            // Log success (opsional)
            Log::info('Webhook payload received and saved', [
                'id' => $destyPayload->id,
                'orderId' => $request->input('orderId'),
                'timestamp' => now()
            ]);

            // Return HTTP 200 dengan response success
            return response()->json([
                'status' => 'success',
                'message' => 'Webhook payload received successfully',
                'data' => [
                    'id' => $destyPayload->id,
                    'received_at' => $destyPayload->created_at
                ]
            ], 200);

        } catch (\Exception $e) {
            // Log error
            Log::error('Webhook processing failed: ' . $e->getMessage(), [
                'payload' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            // Tetap return 200 untuk webhook meskipun ada error
            // (untuk menghindari retry dari pihak pengirim webhook)
            return response()->json([
                'status' => 'error',
                'message' => 'Payload received but processing failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 200);
        }
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
