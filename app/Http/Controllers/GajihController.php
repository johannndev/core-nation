<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GajihController extends Controller
{
    public function create($id){

        $limitTahunan = 6;
        $limitSakit = 6;

        $now = Carbon::now();
        $lastmonth = Carbon::now()->subMonth();     
       

        $karyawan = Karyawan::with(['gajih' => function($query) {
            $query->where('tahun', 2024)
                  ->select('karyawan_id', DB::raw('SUM(cuti_sakit) as total_cuti_sakit'), DB::raw('SUM(cuti_tahunan) as total_cuti_tahunan'))
                 
                  ->groupBy('karyawan_id');
        }])->where('id',$id)->first();

        $totalCuti = Cuti::select('karyawan_id', DB::raw('SUM(sakit) as total_cuti_sakit'), DB::raw('SUM(tahunan) as total_cuti_tahunan'),DB::raw('SUM(mendadak) as total_cuti_mendadak'))
                     ->whereYear('tgl_mulai', 2024)
                     ->whereMonth('tgl_mulai', 8)
                     ->where('karyawan_id',$id)
                     ->get()
                     ->toArray();

        $gajihArray = $karyawan->gajih->toArray()[0];

        // dd($totalCuti->toArray()[0]['total_cuti_sakit']);

        $kemarinTahunan = (int)$gajihArray['total_cuti_tahunan'];
        $bulaniniTahunan = (int)$totalCuti[0]['total_cuti_tahunan'];

        $kemarinSakit = (int)$gajihArray['total_cuti_sakit'];
        $bulaniniSakit = (int)$totalCuti[0]['total_cuti_sakit'];

        $bulaniniMendadak = (int)$totalCuti[0]['total_cuti_mendadak'];

        $totalTahunan = $kemarinTahunan+$bulaniniTahunan;
        $totalSakit = $kemarinSakit+$bulaniniSakit;

        if($totalTahunan > $limitTahunan ){
            if($kemarinTahunan > $limitTahunan){
                $dendaCutiTahunan = $bulaniniTahunan;
            }else{
                $sisaKemarinTahunan = $limitTahunan-$kemarinTahunan;

                $dendaCutiTahunan = abs($sisaKemarinTahunan-$bulaniniTahunan);
            }
        }else{
            $dendaCutiTahunan = 0;
        }

        if($totalSakit > $limitSakit ){
            if($kemarinSakit > $limitSakit){
                $dendaCutiSakit = $bulaniniSakit;
            }else{
                $sisaKemarinSakit = $limitSakit-$kemarinSakit;

                $dendaCutiSakit = abs($sisaKemarinSakit-$bulaniniSakit);
            }
        }else{
            $dendaCutiSakit = 0;
        }

        $rupiahDendaTahunan = $karyawan->harian*$dendaCutiTahunan;
        $rupiahDendaSakit = $karyawan->harian*$dendaCutiSakit;
        $rupiahDendaMendadak = $karyawan->harian*$bulaniniMendadak;

        $grandTotalCuti = $bulaniniTahunan+$bulaniniSakit+$bulaniniMendadak;
        if($grandTotalCuti > 0){
            $potongPremi = $karyawan->premi; 
        }else{
            $potongPremi = 0;
        }

        $grandTotalDendaCuti = $dendaCutiTahunan+$dendaCutiSakit+$bulaniniMendadak;
        $grandTotalDendaCutiRupiah = $rupiahDendaTahunan+$rupiahDendaSakit+$rupiahDendaMendadak;

        // dd($rupiahDendaTahunan,$rupiahDendaSakit,$rupiahDendaMendadak);
        


        return view('gajih.create',compact('karyawan','now','totalCuti','gajihArray','dendaCutiTahunan','dendaCutiSakit','grandTotalCuti','potongPremi','grandTotalDendaCuti','grandTotalDendaCutiRupiah'));
    }
}
