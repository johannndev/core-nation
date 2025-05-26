<?php

namespace App\Http\Controllers;

use App\Models\Jubelioorder;
use App\Models\Jubelioreturn;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JubelioController extends Controller
{
    public function order(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {

                $secret = 'corenation2025';
                $content = trim($request->getContent());

                $sign = hash_hmac('sha256',$content . $secret, $secret, false);

                $signature = $request->header('Sign');

                if ($signature !== $sign) {
                    throw new \Exception('Invalid signature');
                }

                $dataApi = $request->all(); 

                $tanggal = Carbon::parse($dataApi['transaction_date']);
                $threshold = Carbon::parse('2025-03-06');

                $limitTime = $tanggal->lessThan($threshold) ? 0 : 1;

                if($limitTime == 1){
                    throw new \Exception('transaksi sebelum tanggal 03/03/25 tidak dibuat, tangggal transaksi'.$dataApi['transaction_date']);
                }

                if($dataApi['status'] == "SHIPPED"){

                    $exists = Jubelioorder::where('jubelio_order_id',$dataApi['salesorder_id'])->where('type','SELL')->where('order_status',$dataApi['status'])->exists();


                    if ($exists) {
                        // Jika sudah ada, throw error agar langsung masuk ke catch
                        throw new \Exception('Data already exists');
                    }

                    DB::table('jubelioorders')->insert([
                        'jubelio_order_id' => $dataApi['salesorder_id'],
                        'invoice' => $dataApi['salesorder_no'],
                        'type' => 'SELL',
                        'order_status' => $dataApi['status'],
                        'run_count' => 0,
                        'error_type' => null,
                        'error' => null,
                        'payload' => json_encode($request->all()),
                        'execute_by' => null,
                        'status' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                }else if($dataApi['status'] == "CANCELED"){

                    $dataTransaksi = Transaction::where('type',Transaction::TYPE_SELL)->where('invoice',$dataApi['salesorder_no'])->exists();

                    if($dataTransaksi){
                        if($dataTransaksi->jubelio_return > 0){

                            throw new \Exception('Transaksi sudah return');

                        }
                    }

                    $returnData = new Jubelioreturn();
                            
                    $returnData->order_id = $dataApi['salesorder_id'];
                    $returnData->transaction_id = $dataTransaksi->id;
                    $returnData->method_pay = $dataApi['payment_method'];
                    $returnData->invoice = $dataApi['salesorder_no'];
                    $returnData->pesan = $dataApi['cancel_reason_detail'];
                    $returnData->location_name = $dataApi['location_name'];
                    $returnData->store_name = $dataApi['source_name'];

                    $returnData->save();
                }

                return response()->json([
                    'success' => 'ok',
                    'message' => 'Data saved successfully',
                ], 200);
                
            });

            return response()->json([
                'success' => 'ok',
                'message' => 'Data saved successfully',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => 'error',
                'message' => $e->getMessage(),
            ], 200);
        }
    }
}
