<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashFlowController extends Controller
{
    public function index(){
        $currentYear = 2024;

        $transactions = DB::table('transactions')
            ->select(
                DB::raw("CASE 
                    WHEN sender_type IN ('1', '7', '3','8') THEN sender_type
                    WHEN receiver_type IN ('1', '7', '3','8') THEN receiver_type
                END as type"),
                DB::raw("SUM(CASE WHEN type = '9' THEN total ELSE 0 END) as total_cash_in"),
                DB::raw("SUM(CASE WHEN type = '7' THEN total ELSE 0 END) as total_cas_out"),
                DB::raw("SUM(CASE WHEN type = '2' THEN total ELSE 0 END) as total_sell"),
                DB::raw("SUM(CASE WHEN type = '15' THEN total ELSE 0 END) as total_return"),
                DB::raw("SUM(CASE WHEN type = '1' THEN total ELSE 0 END) as total_buy"),
                DB::raw("SUM(CASE WHEN type = '17' THEN total ELSE 0 END) as total_return_supplier"),
            )
        ->groupBy('type')
        ->get();

        dd($transactions);

        $groupByReceiver = Transaction::select('type', 'receiver_type', DB::raw('SUM(total) as total_sum'))
            ->whereYear('created_at', $currentYear) // Filter hanya tahun ini
            ->whereIn('type', [1, 2, 7, 9, 15, 17]) // Filter type 1,2,3,4
            ->where(function($query) {
                $query->where(function($q) {
                    $q->whereIn('type', [2, 15])
                    ->whereIn('receiver_type', [1,7]);
                })->orWhere(function($q) {
                    $q->whereIn('type', [1, 17])
                    ->whereIn('receiver_type', [4]);
                })->orWhere(function($q) {
                    $q->whereIn('type', [7, 9])
                    ->whereIn('receiver_type', [1,4,7,8,3]);
                });
            })
            ->groupBy('type', 'receiver_type')
             ->orderByDesc('total_sum') // Urutkan dari total terbesar
            ->get();


        $groupBySender = Transaction::select('type', 'sender_type', DB::raw('SUM(total) as total_sum'))
            ->whereYear('created_at', $currentYear) // Filter hanya tahun ini
            ->whereIn('type', [1, 2, 7, 9, 15, 17]) // Filter type 1,2,3,4
            ->where(function($query) {
                $query->where(function($q) {
                    $q->whereIn('type', [2, 15])
                    ->whereIn('sender_type', [1,7]);
                })->orWhere(function($q) {
                    $q->whereIn('type', [1, 17])
                    ->whereIn('sender_type', [4]);
                })->orWhere(function($q) {
                    $q->whereIn('type', [7, 9])
                    ->whereIn('sender_type', [1,4,7,8,3]);
                });
            })
            ->groupBy('type', 'sender_type')
            ->orderByDesc('total_sum') // Urutkan dari total terbesar
            ->get();

       
        return view('cashflow.index',compact('groupByReceiver','groupBySender'));

        
    }
}
