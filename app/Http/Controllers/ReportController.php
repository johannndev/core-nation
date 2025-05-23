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
}
