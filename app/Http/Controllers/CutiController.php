<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CutiController extends Controller
{
    public function create($id){

        $karyawan = Karyawan::find($id);

        return view('cuti.create',compact('karyawan'));
    }

    public function store($id, Request $request){
        $rules = [
            'tm'  => ['required'],
            'ta'  => ['required'],
            'tipe'  => ['required'],
            
            
		];

        $attributes = [
            'tm'  => 'Tanggal Mulai',
            'ta'  => 'Tanggal Akhir',
            'tipe' => 'Tipe',
            
        ];

        $this->validate($request, $rules, [], $attributes);

        $startDate = Carbon::parse($request->tm); // Tanggal mulai
        $endDate = Carbon::parse($request->ta);   // Tanggal akhir

        $days = $startDate->diffInDays($endDate) + 1;


        $data = new Cuti();

        $data->karyawan_id = $id;
        $data->tipe = $request->tipe;
        $data->tgl_mulai = $request->tm;
        $data->tgl_akhir = $request->ta;

        if($request->tipe == 1){
            $data->tahunan = $days;
        }elseif($request->tipe == 2){
            $data->sakit = $days;
        }elseif($request->tipe == 3){
            $data->mendadak = $days;
        }else{

        }

        $data->save();
    }

    public function cutiList($id, Request $request){

        $karyawan = Karyawan::find($id);

        $cutiList = Cuti::where('karyawan_id',$id)->orderBy('tgl_mulai','desc');

        
		if($request->bulan && $request->tahun){
			$cutiList = $cutiList->whereMonth('tgl_mulai',$request->bulan)->whereYear('tgl_mulai',$request->tahun);
		}

        if($request->tipe){
            $cutiList = $cutiList->where('tipe',$request->tipe);
        }

        $cutiList = $cutiList->paginate(20)->withQueryString();


        $cid = $id;

        return view('cuti.cutiList',compact('karyawan','cutiList','cid'));

    }
}
