<?php

namespace App\Http\Controllers;

use App\Models\StatSell;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class StatSellController extends Controller
{
    public function generet(Request $request){
        // Ambil parameter rentang waktu dari request
        $startDate = $request->input('start_date', '2024-01-01'); // Default awal tahun 2024
        $endDate = $request->input('end_date', '2024-12-31');     // Default akhir tahun 2024

        // Query

        $data = DB::table('transaction_details')
        ->join('items', 'transaction_details.item_id', '=', 'items.id')
        ->whereIn('transaction_details.transaction_type', [2, 15]) // Filter transaction_type 2 dan 15
        ->whereBetween('transaction_details.date', [$startDate, $endDate])// Filter rentang waktu
        ->get();


        $result = DB::table('transaction_details')
        ->join('items', 'transaction_details.item_id', '=', 'items.id')
        ->whereIn('transaction_details.transaction_type', [2, 15]) // Filter transaction_type 2 dan 15
        ->whereBetween('transaction_details.date', [$startDate, $endDate]) // Filter rentang waktu
        ->selectRaw('
            items.group_id,
            MONTH(transaction_details.date) as bulan,
            YEAR(transaction_details.date) as tahun,
            transaction_details.sender_id,
            transaction_details.transaction_type,
            SUM(transaction_details.quantity) as sum_qty,
            SUM(transaction_details.total) as sum_total
        ')
        ->groupBy('items.group_id', DB::raw('MONTH(transaction_details.date)'), DB::raw('YEAR(transaction_details.date)'), 'transaction_details.sender_id', 'transaction_details.transaction_type')
        ->orderBy('items.group_id') // Optional: Untuk urutan hasil
        ->get();

        // dd($result,$data);

        $insertData = [];
        foreach ($result as $row) {
            $insertData[] = [
                'group_id' => $row->group_id,
                'bulan' => $row->bulan,
                'tahun' => $row->tahun,
                'sender_id' => $row->sender_id,
                'type' => $row->transaction_type,
                'sum_qty' => $row->sum_qty,
                'sum_total' => $row->sum_total,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
    
        // Simpan data dalam satu query
        if (!empty($insertData)) {
            StatSell::insert($insertData);
        }


        return 'berhasil generete';

    }

    public function index(Request $request){
        $dataList = StatSell::with('group')->orderBy('bulan','desc')->orderBy('tahun','desc');

        if($request->bulan){
			$dataList = $dataList->where('bulan',$request->bulan);
		}

		if($request->tahun){
			$dataList = $dataList->where('tahun',$request->tahun);
		}

        if($request->group){
			$dataList = $dataList->where('group',$request->group);
		}

		if($request->type){
			$dataList = $dataList->where('type',$request->type);
		}

		$dataList = $dataList->paginate(100)->withQueryString();

        // dd($dataList->toArray());

        return view('report.itemsale',compact('dataList'));
    }
}
