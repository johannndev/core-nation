<?php

namespace App\Http\Controllers;

use App\Helpers\RecordManagerHelper;
use App\Models\Customer;
use App\Models\Operation;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ReportController extends Controller
{
    public function profitLoss(Request $request){
        $datesNow = Carbon::now()->subMonth(6);
        $datesLast = Carbon::now()->subMonth(7);


		$rm = new RecordManagerHelper();
		$pl = $rm->getProfitLoss($datesNow->month, $datesNow->year);
		$compare = $rm->getProfitLoss($datesNow->month, $datesNow->year);

        $ops = Operation::pluck('name','id');
		$operations = [];
		foreach($ops as $id => $name) {
			$operations[] = ['id' => $id, 'name' => $name];
		}

        dd($operations,$pl,$compare,$ops);

    }

    public function cash(Request $request){
        //cust ids
		$cids = array(
			2475, //shopee core
			2224, //shopee crystal
			2258, //toped
			1677, //lazada
			2768, //tiktok
			2446, //blibli
			2043, //zalora
			1868, //java yoga
			2073, //yoga light
			122, //acu
			98, //aling
			298, //angela
			756, //atiek
			117, //caroline
			2136, //desi
			142, //elisa
			2229, //erna
			2187, //fanny
			2588, //loretta
			2170, //maria malang
			2415, //merry
			296, //minarsih bali
			1678, //musdiani
			2218, //nurcahyani alwi
			205, //paula
			519, //rindu
			930, //rusmalina
			1070, //rustinawati
			1116, //susi
			80, //tutik
			2308, //yuli riau
			2710, //anytime
			2457, //roca
			2664, //cahya
			2742, //erwin
			2153, //fb cust
			2031, //fb dp
			2733, //reza
			2722, //heritage
			2686, //rizki nanda
			2781, //evy bantrang
			2717, //leon
			2727, //fitri
			1885, //yufika
			2791, //synergy
			2873, //alexander bali
			2872, //aden
			2091, //athaya
			2763, //arie
			2838, //bee
			2689, //caroline ang
			359, //dewi suryani
			2852, //erwin fithub
			2766, //febri
			2807, //finns
			2754, //keliling
			2847, //mike fithub
			2700, //nando
			2721, //novi bandung
			2656, //rangers
			2735, //rocky
			2850, //rosa
			2858, //vava
			2790, //william
			2703, //seko
            2546, //umum sby
		);

		//1. get month input

        $datesNow = Carbon::now();

        if($request->month){
            $month = $request->month;
        }else{
            $month = $datesNow->month;
        }

        if($request->year){
            $year = $request->year;
        }else{
            $year = $datesNow->year;
        }

		
		// $year = $request->year;
		$date = Carbon::createFromDate("$year","$month","01");
		$startDate = $date->startOfMonth()->toDateString();
		$endDate = $date->endOfMonth()->toDateString();

        $cashIn = Transaction::whereIn('sender_id',$cids)->whereBetween('date',[$startDate,$endDate])->where('type',Transaction::TYPE_CASH_IN)->groupBy('sender_id')->selectRaw('sender_id, sum(total) as st')->get()->pluck('st','sender_id')->toArray();

        $cashOut = Transaction::whereIn('receiver_id',$cids)->whereBetween('date',[$startDate,$endDate])->where('type',Transaction::TYPE_CASH_OUT)->groupBy('receiver_id')->selectRaw('receiver_id, sum(total) as st')->get()->pluck('st','receiver_id')->toArray();

        $sell = Transaction::whereIn('receiver_id',$cids)->whereBetween('date',[$startDate,$endDate])->where('type',Transaction::TYPE_SELL)->groupBy('receiver_id')->selectRaw('receiver_id, sum(total) as st')->get()->pluck('st','receiver_id')->toArray();

        $returnData = Transaction::whereIn('sender_id',$cids)->whereBetween('date',[$startDate,$endDate])->where('type',Transaction::TYPE_RETURN)->groupBy('sender_id')->selectRaw('sender_id, sum(total) as st')->get()->pluck('st','sender_id')->toArray();

        // dd($return);
        

        $dcList = Customer::whereIn('id',$cids)->get();


        $nettCash = array_sum($cashIn) + array_sum($cashOut);

        $nettSell = array_sum($sell) + array_sum($returnData);

        $yearList = [];

        for ($i = 2019; $i <= date('Y'); $i++){
            $yearList[] = $i;
        }

        $yearList = array_reverse($yearList);
                                            



        
		return view('report.cash',compact('dcList','cashIn','cashOut','returnData','sell','nettCash','nettSell','month','year','yearList','datesNow'));
	
    }

	public function income(Request $request){
		 // Ambil input dari request atau pakai default
		$month = $request->input('bulan');
		$year = $request->input('tahun');
		$periode = $request->input('type'); // 6 atau 12

		// Jika ada filter bulan & tahun, hitung ke depan
		if ($month && $year && $periode) {
			$startDate = Carbon::create($year, $month, 1)->startOfMonth();
			$endDate = Carbon::create($year, $month, 1)->addMonths($periode - 1)->endOfMonth();
		} else {
			// Default: 12 bulan ke belakang dari bulan ini
			$endDate = Carbon::now()->endOfMonth();
			$startDate = Carbon::now()->subMonths(11)->startOfMonth();
		}


		// Nilai constant
		$typeSell = Transaction::TYPE_SELL;
		$typeReturn = Transaction::TYPE_RETURN;
		$typeCashIn = Transaction::TYPE_CASH_IN;
		$typeCashOut = Transaction::TYPE_CASH_OUT;
		$receiverTypeCustomer = Customer::TYPE_CUSTOMER;
		$receiverTypeReseller = Customer::TYPE_RESELLER;
		$receiverTypeAccount = Customer::TYPE_ACCOUNT;

		// Query
		$rawData = DB::table('transactions')
			->join('customers', 'transactions.receiver_id', '=', 'customers.id')
			->selectRaw("
				YEAR(transactions.date) as year,
				MONTH(transactions.date) as month,

				SUM(CASE 
						WHEN transactions.type = ? 
							AND transactions.receiver_type IN (?, ?) 
							AND customers.is_online = 0 
						THEN transactions.total 
						ELSE 0 
					END) as sell_offline,

				SUM(CASE 
						WHEN transactions.type = ? 
							AND transactions.receiver_type IN (?, ?) 
							AND customers.is_online = 1 
						THEN transactions.total 
						ELSE 0 
					END) as sell_online,

				SUM(CASE 
						WHEN transactions.type = ? 
							AND transactions.receiver_type IN (?, ?) 
							AND customers.is_online = 0 
						THEN transactions.total 
						ELSE 0 
					END) as return_offline,

				SUM(CASE 
						WHEN transactions.type = ? 
							AND transactions.receiver_type IN (?, ?) 
							AND customers.is_online = 1 
						THEN transactions.total 
						ELSE 0 
					END) as return_online,

				SUM(CASE 
						WHEN transactions.type = ? 
							AND transactions.receiver_type IN (?, ?) 
							AND customers.is_online = 0 
						THEN transactions.total 
						ELSE 0 
					END) as cashin_offline,

				SUM(CASE 
						WHEN transactions.type = ? 
							AND transactions.receiver_type IN (?, ?) 
							AND customers.is_online = 1 
						THEN transactions.total 
						ELSE 0 
					END) as cashin_online,

				SUM(CASE 
						WHEN transactions.type = ? 
							AND transactions.receiver_type = ? 
						THEN transactions.total 
						ELSE 0 
					END) as cashin_journal,
					SUM(CASE 
						WHEN transactions.type = ? 
							AND transactions.receiver_type IN (?, ?) 
							AND customers.is_online = 0 
						THEN transactions.total 
						ELSE 0 
					END) as cashout_offline,

				SUM(CASE 
						WHEN transactions.type = ? 
							AND transactions.receiver_type IN (?, ?) 
							AND customers.is_online = 1 
						THEN transactions.total 
						ELSE 0 
					END) as cashout_online,

				SUM(CASE 
						WHEN transactions.type = ? 
							AND transactions.receiver_type = ? 
						THEN transactions.total 
						ELSE 0 
					END) as cashout_journal
			", [
				$typeSell, $receiverTypeCustomer, $receiverTypeReseller,
				$typeSell, $receiverTypeCustomer, $receiverTypeReseller,
				$typeReturn, $receiverTypeCustomer, $receiverTypeReseller,
				$typeReturn, $receiverTypeCustomer, $receiverTypeReseller,
				$typeCashIn, $receiverTypeCustomer, $receiverTypeReseller,
				$typeCashIn, $receiverTypeCustomer, $receiverTypeReseller,
				$typeCashIn, $receiverTypeAccount,
				$typeCashOut, $receiverTypeCustomer, $receiverTypeReseller,
				$typeCashOut, $receiverTypeCustomer, $receiverTypeReseller,
				$typeCashOut, $receiverTypeAccount,
			])
			->whereBetween('transactions.date', [$startDate, $endDate])
			->groupByRaw('YEAR(transactions.date), MONTH(transactions.date)')
			->orderByRaw('YEAR(transactions.date), MONTH(transactions.date)')
			->get();

			$results = [];

			// Inisialisasi seluruh bulan dari startDate ke endDate
			$period = Carbon::parse($startDate)->startOfMonth();
			$end = Carbon::parse($endDate)->startOfMonth();

			while ($period <= $end) {
				$key = $period->format('M-y'); // contoh: Jan-25
				$results[$key] = [
					'sell_offline' => 0,
					'sell_online' => 0,
					'return_offline' => 0,
					'return_online' => 0,
					'nett_revenue' => 0,
					'cash_in_offline' => 0,
					'cash_in_online' => 0,
					'cash_in_journal' => 0,
					'nett_cash_in' => 0,
					'cash_out_offline' => 0,
					'cash_out_online' => 0,
					'cash_out_journal' => 0,
					'nett_cash_out' => 0,
					'nett_cash' => 0,
				];
				$period->addMonth();
			}

			// Isi data dari query
			foreach ($rawData as $row) {

				$sellOffline = (float) abs($row->sell_offline);
				$sellOnline = (float) abs($row->sell_online);
				$returnOffline = (float) abs($row->return_offline);
				$returnOnline = (float) abs($row->return_online);
				$cashInOffline = (float) abs($row->cashin_offline);
				$cashInOnline = (float) abs($row->cashin_online);
				$cashInJournal = (float) abs($row->cashin_journal);
				$cashOutOffline = (float) abs($row->cashout_offline);
				$cashOutOnline = (float) abs($row->cashout_online);
				$cashOutJournal = (float) abs($row->cashout_journal);

				$totalCashIn = $cashInOffline + $cashInOnline + $cashInJournal;
				$totalCashOut = $cashOutOffline + $cashOutOnline + $cashOutJournal;

				$key = Carbon::create($row->year, $row->month)->format('M-y');
				$results[$key] = [
					'sell_offline' => $sellOffline,
					'sell_online' => $sellOnline,
					'return_offline' => $returnOffline,
					'return_online' => $returnOnline,
					'nett_revenue' => $sellOffline + $sellOnline + $returnOffline + $returnOnline,
					'cash_in_offline' => $cashInOffline,
					'cash_in_online' => $cashInOnline ,
					'cash_in_journal' => $cashInJournal,
					'nett_cash_in' =>  $totalCashIn,
					'cash_out_offline' => $cashOutOffline,
					'cash_out_online' => $cashOutOnline ,
					'cash_out_journal' => $cashOutJournal,
					'nett_cash_out' =>  $totalCashOut,
					'nett_cash' => $totalCashIn + $totalCashOut
				];
			}

			$dateList = [];
			foreach($results as $key => $row){

				$dateList[$key] = [
					'date' => $key,
				];

			}

			$income = [];
			foreach($results as $key => $row){

				$income[$key] = [
					'sell_offline' => $row['sell_offline'],
					'sell_online' => $row['sell_online'],
					'return_offline' => $row['return_offline'],
					'return_online' => $row['return_online'],
					'nett_revenue' => $row['nett_revenue']
				];

			}

			$cashIn = [];
			foreach($results as $key => $row){

				$cashIn[$key] = [
					'cash_in_offline' => $row['cash_in_offline'],
					'cash_in_online' => $row['cash_in_online'],
					'cash_in_journal' => $row['cash_in_journal'],
					'nett_cash_in' => $row['nett_cash_in'],
				];

			}

			$cashOut = [];
			foreach($results as $key => $row){

				$cashOut[$key] = [
					'cash_out_offline' => $row['cash_out_offline'],
					'cash_out_online' => $row['cash_out_online'],
					'cash_out_journal' => $row['cash_out_journal'],
					'nett_cash_out' => $row['nett_cash_out'],
				];

			}

			$cashTotal = [];
			foreach($results as $key => $row){

				$cashTotal[$key] = [
					'nett_cash' => $row['nett_cash'],
				];

			}

		return view('report.income',compact('results','dateList','income','cashIn','cashOut','cashTotal'));
	}	
}
