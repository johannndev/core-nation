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
        $secret = 'corenation2025';
        $content = trim($request->getContent());
        $sign = hash_hmac('sha256', $content . $secret, $secret, false);
        $signature = $request->header('Sign');

        if ($signature !== $sign) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $dataApi = $request->all();

        if ($dataApi['status'] === "SHIPPED") {
            $tanggal = Carbon::parse($dataApi['transaction_date']);
            $threshold = Carbon::parse('2025-03-06');

            if ($tanggal->lessThan($threshold)) {
                return response()->json([
                    'status' => 'ok',
                    'message' => 'Transaksi sebelum tanggal 06 Maret 2025 tidak dibuat. Tanggal transaksi: ' . $tanggal->toDateTimeString(),
                ], 200);
            }

            $cekTransaksi = Transaction::where('type',Transaction::TYPE_SELL)->where('invoice',$dataApi['salesorder_no'])->first();

            $exists = Jubelioorder::where('invoice',$dataApi['salesorder_no'])
                ->where('type', 'SELL')
                ->where('order_status', $dataApi['status'])
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => 'ok',
                    'message' => 'Data already exists',
                ], 200);
            }else{

                if($cekTransaksi){

                    DB::table('jubelioorders')->insert([
                        'jubelio_order_id'  => $dataApi['salesorder_id'],
                        'source'            => 1,
                        'invoice'           => $dataApi['salesorder_no'],
                        'type'              => 'SELL',
                        'order_status'      => $dataApi['status'],
                        'run_count'         => 0,
                        'error_type'        => null,
                        'error'             => null,
                        'payload'           => json_encode($dataApi),
                        'execute_by'        => null,
                        'status'            => 0,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ]);

                    return response()->json([
                        'status' => 'ok',
                        'message' => 'Data saved successfully',
                    ], 200);

                }else{

                    return response()->json([
                        'status' => 'ok',
                        'message' => 'Invoice sudah ada',
                    ], 200);
                    
                }

            }



        } elseif ($dataApi['status'] === "CANCELED") {
            $transaction = Transaction::where('type', Transaction::TYPE_SELL)
                ->where('invoice', $dataApi['salesorder_no'])
                ->first();

            if ($transaction) {
                if ($transaction->jubelio_return > 0) {
                    return response()->json([
                        'status' => 'ok',
                        'message' => 'Transaksi sudah return',
                    ], 200);
                }

                $returnData = new Jubelioreturn();
                $returnData->order_id       = $dataApi['salesorder_id'];
                $returnData->transaction_id = $transaction->id;
                $returnData->method_pay     = $dataApi['payment_method'];
                $returnData->invoice        = $dataApi['salesorder_no'];
                $returnData->pesan          = $dataApi['cancel_reason_detail'];
                $returnData->location_name  = $dataApi['location_name'];
                $returnData->store_name     = $dataApi['source_name'];
                $returnData->save();

                return response()->json([
                    'status' => 'ok',
                    'message' => 'Data saved successfully',
                ], 200);
            }

            return response()->json([
                'status' => 'ok',
                'message' => 'Transaksi tidak ditemukan',
            ], 200);
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'Status ' . $dataApi['status'] . ' ok',
        ], 200);
    }


    public function index(Request $request){
        $dataList = Jubelioorder::orderBy('created_at','desc');

        if($request->invoice){
			$dataList = $dataList->where('invoice', 'like', '%'.$request->invoice.'%');
		}

        if($request->status == 'warning'){
            $dataList = $dataList->where('status',2)->where('error_type',2);
        }elseif($request->status == 'success'){
            $dataList = $dataList->where('status',2)->where('error_type',10);
        }elseif($request->status == 'error'){
            $dataList = $dataList->where('status',1)->where('error_type',1);
        }else{
            $dataList = $dataList->where('status',0);
        }

        $dataList = $dataList->paginate(20)->withQueryString();

        // dd($allRolesInDatabase);

        return view('jubelio.webhook.index',compact('dataList'));
    }

    public function warning(Request $request){
        $dataList = Jubelioorder::where('status',2)->where('error_type',2)->orderBy('created_at','desc');

        if($request->invoice){
			$dataList = $dataList->where('invoice', 'like', '%'.$request->invoice.'%');
		}

        $dataList = $dataList->paginate(20)->withQueryString();

        // dd($allRolesInDatabase);

        return view('jubelio.webhook.index',compact('dataList'));
    }

    public function success(Request $request){
        $dataList = Jubelioorder::where('status',2)->where('error_type',10)->orderBy('created_at','desc');

        if($request->invoice){
			$dataList = $dataList->where('invoice', 'like', '%'.$request->invoice.'%');
		}

        $dataList = $dataList->paginate(20)->withQueryString();

        // dd($allRolesInDatabase);

        return view('jubelio.webhook.index',compact('dataList'));
    }

     public function detail($id){
        $data = Jubelioorder::find($id);

        $jsonData = json_decode($data->payload, true); // pastikan jadi array/objek PHP


        return view('jubelio.webhook.detail',compact('data','jsonData'));
    }

}
