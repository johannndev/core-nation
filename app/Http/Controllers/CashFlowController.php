<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashFlowController extends Controller
{
    public function index(Request $request){



        $groupBySender = Transaction::whereIn('type', [1, 2, 7, 9, 15, 17]);

        if($request->tahun){
            $groupBySender = $groupBySender->whereYear('created_at', $request->tahun);
            $currentYear = $request->tahun;
        }else{

            $currentYear = carbon::now()->year;;
            $groupBySender = $groupBySender->whereYear('created_at', $currentYear);
        }

        if($request->bulan){
            $groupBySender = $groupBySender->whereMonth('created_at', $request->bulan);
        }


        $groupBySender = $groupBySender->whereIn('sender_type', [1, 7, 3, 8])
        ->select(
            'sender_type',
            DB::raw('SUM(CASE WHEN type = "9" THEN total ELSE 0 END) AS cash_in_total'),
            DB::raw('SUM(CASE WHEN type = "7" THEN total ELSE 0 END) AS cash_out_total'),
            DB::raw('SUM(CASE WHEN type = "2" THEN total ELSE 0 END) AS sell_total'),
            DB::raw('SUM(CASE WHEN type = "15" THEN total ELSE 0 END) AS return_total'),
            DB::raw('SUM(CASE WHEN type = "1" THEN total ELSE 0 END) AS buy_total'),
            DB::raw('SUM(CASE WHEN type = "17" THEN total ELSE 0 END) AS return_suplier'),
        )
        ->groupBy('sender_type');

        if($request->type && $request->sort){
            $groupBySender = $groupBySender->orderBy($request->type,$request->sort);
        }

        $groupBySender = $groupBySender->get();

        $groupByReceiver = Transaction::whereIn('type', [1, 2, 7, 9, 15, 17]);

        if($request->tahun){
            $groupByReceiver = $groupByReceiver->whereYear('created_at', $request->tahun);
            $currentYear = $request->tahun;
        }else{

            $currentYear = carbon::now()->year;;
            $groupByReceiver = $groupByReceiver->whereYear('created_at', $currentYear);
        }

        if($request->bulan){
            $groupByReceiver = $groupByReceiver->whereMonth('created_at', $request->bulan);
        }


        $groupByReceiver = $groupByReceiver->whereIn('receiver_type', [1, 7, 3, 8])
        ->select(
            'receiver_type',
            DB::raw('SUM(CASE WHEN type = "9" THEN total ELSE 0 END) AS cash_in_total'),
            DB::raw('SUM(CASE WHEN type = "7" THEN total ELSE 0 END) AS cash_out_total'),
            DB::raw('SUM(CASE WHEN type = "2" THEN total ELSE 0 END) AS sell_total'),
            DB::raw('SUM(CASE WHEN type = "15" THEN total ELSE 0 END) AS return_total'),
            DB::raw('SUM(CASE WHEN type = "1" THEN total ELSE 0 END) AS buy_total'),
            DB::raw('SUM(CASE WHEN type = "17" THEN total ELSE 0 END) AS return_suplier'),
        )
        ->groupBy('receiver_type');
        if($request->type && $request->sort){
            $groupByReceiver = $groupByReceiver->orderBy($request->type,$request->sort);
        }

        $groupByReceiver = $groupByReceiver->get();

        // dd($groupByReceiver);


    

       
        return view('cashflow.index',compact('groupByReceiver','groupBySender','currentYear'));

        
    }

    public function book(Request $request){


        $transactions = Transaction::whereIn('type', [1, 2, 7, 9, 15, 17]);
        
        if ($request->type == 'sender') {

            $transactions == $transactions->select(
                'sender_id',
                
    
                DB::raw('SUM(CASE WHEN type = "9" THEN total ELSE 0 END) AS cash_in_total'),
                DB::raw('SUM(CASE WHEN type = "7" THEN total ELSE 0 END) AS cash_out_total'),
                DB::raw('SUM(CASE WHEN type = "2" THEN total ELSE 0 END) AS sell_total'),
                DB::raw('SUM(CASE WHEN type = "15" THEN total ELSE 0 END) AS return_total'),
                DB::raw('SUM(CASE WHEN type = "1" THEN total ELSE 0 END) AS buy_total'),
                DB::raw('SUM(CASE WHEN type = "17" THEN total ELSE 0 END) AS return_suplier'),
            );

            $transactions = $transactions->where('sender_type',$request->book)
            ->groupBy('sender_id')
            ->with('sender');
    
        } else if($request->type == 'receiver'){

            $transactions == $transactions->select(
                'receiver_id',
                
    
                DB::raw('SUM(CASE WHEN type = "9" THEN total ELSE 0 END) AS cash_in_total'),
                DB::raw('SUM(CASE WHEN type = "7" THEN total ELSE 0 END) AS cash_out_total'),
                DB::raw('SUM(CASE WHEN type = "2" THEN total ELSE 0 END) AS sell_total'),
                DB::raw('SUM(CASE WHEN type = "15" THEN total ELSE 0 END) AS return_total'),
                DB::raw('SUM(CASE WHEN type = "1" THEN total ELSE 0 END) AS buy_total'),
                DB::raw('SUM(CASE WHEN type = "17" THEN total ELSE 0 END) AS return_suplier'),
            );

            $transactions = $transactions->where('receiver_type',$request->book)
            ->groupBy('receiver_id')
            ->with('receiver');

        } else {
            # code...
        }
        

        

        if($request->tahun){
            $transactions = $transactions->whereYear('created_at', $request->tahun);
            $currentYear = $request->tahun;
        }else{

            $currentYear = carbon::now()->year;;
            $transactions = $transactions->whereYear('created_at', $currentYear);
        }

        if($request->bulan > 0){
            $transactions = $transactions->whereMonth('created_at', $request->bulan);
        }

        if($request->sort_type && $request->sort){
            $transactions = $transactions->orderBy($request->sort_type,$request->sort);
        }

    
        // ->get()->toArray(); // Eager Load Sender
        $transactions = $transactions->paginate(100)->withQueryString();

        // dd($transactions);

        return view('cashflow.book-grub',compact('currentYear','transactions'));

    }
}
