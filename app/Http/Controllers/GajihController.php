<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Cuti;
use App\Models\Gajih;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class GajihController extends Controller
{
    public function index(Request $request){

        $now = Carbon::now();

        $bulanSelect = $now->month;
        $yearSelect = $now->year;

        $roleName = Auth::user()->getRoleNames()[0];

        $gajihList = Gajih::with('karyawan','bankSingle')->orderBy('tahun','desc')->orderBy('bulan','desc');

        if( $roleName != "superadmin"){
            $gajihList = $gajihList->whereHas('karyawan', function (Builder $query) {
                $query->where('flag', 1);
            });
        }

         
		if($request->bulan && $request->tahun){
			$gajihList = $gajihList->where('bulan',$request->bulan)->where('tahun',$request->tahun);

            $bulanSelect = $request->bulan;
            $yearSelect = $request->tahun;
		}else{

            $gajihList = $gajihList->where('bulan',$bulanSelect)->where('tahun',$yearSelect);

        }

        if($request->karyawan){

            $kSearch = $request->karyawan;

            $gajihList = $gajihList->whereHas('karyawan', function (Builder $query) use($kSearch) {
                $query->where('nama', 'like', $kSearch.'%');
            });

        }

        if($request->tipe){
            $gajihList = $gajihList->where('tipe',$request->tipe);
        }
        
        $gajihList = $gajihList->paginate(20)->withQueryString();

      

        $gajiPerBank = Gajih::with('bank');

        if($request->bulan && $request->tahun){
			$gajiPerBank = $gajiPerBank->where('bulan',$request->bulan)->where('tahun',$request->tahun);

            $bulanSelect = $request->bulan;
            $yearSelect = $request->tahun;
		}else{

            $gajiPerBank = $gajiPerBank->where('bulan',$bulanSelect)->where('tahun',$yearSelect);

        }

        $gajiPerBank =  $gajiPerBank->selectRaw('bank_id, SUM(total_gajih) as total_gaji')
        ->groupBy('bank_id')
        ->get();


       

        return view('gaji.index',compact('gajihList','bulanSelect','yearSelect','gajiPerBank'));

    }

    public function create($id){

        $setting = AppSetting::all()->pluck('value','name')->toArray();

     
        $role = Auth::user()->getRoleNames()[0];

        if($role != 'superadmin'){

            $cekK = Karyawan::find($id);
            
            if($cekK->flag = 2){
                return abort(404);
            }
        }




        $limitTahunan = (int)$setting['batas_cuti_tahunan'];
        $limitSakit = (int)$setting['batas_cuti_sakit'];

        $now = Carbon::now();
        $lastmonth = Carbon::now()->subMonth();

        $gajihData = Gajih::where('karyawan_id',$id)->where('bulan',$now->month)->where('tahun',$now->year)->first();
        

        $karyawan = Karyawan::with(['gajih' => function($query) use($lastmonth) {
            $query->where('tahun', $lastmonth->year)
                  ->select('karyawan_id', DB::raw('SUM(cuti_sakit) as total_cuti_sakit'), DB::raw('SUM(cuti_tahunan) as total_cuti_tahunan'))
                 
                  ->groupBy('karyawan_id');
        }])->where('id',$id)->first();

        $totalCuti = Cuti::select('karyawan_id', DB::raw('SUM(sakit) as total_cuti_sakit'), DB::raw('SUM(tahunan) as total_cuti_tahunan'),DB::raw('SUM(mendadak) as total_cuti_mendadak'))
                     ->whereYear('tgl_mulai', $lastmonth->year)
                     ->whereMonth('tgl_mulai', $lastmonth->month)
                     ->where('karyawan_id',$id)
                     ->get()
                     ->toArray();

      
        if(count($karyawan->gajih) > 0){
            $gajihArray = $karyawan->gajih->toArray()[0];
        }else{
            $gajihArray = [
                'total_cuti_tahunan' => 0,
                'total_cuti_sakit' => 0,
                

            ];
        }

       

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
        


        return view('gaji.create',compact('karyawan','now','totalCuti','gajihArray','dendaCutiTahunan','dendaCutiSakit','grandTotalCuti','potongPremi','grandTotalDendaCuti','grandTotalDendaCutiRupiah','gajihData'));
    }

    public function store($id, Request $request){
        $role = Auth::user()->getRoleNames()[0];
        $karyawan = Karyawan::where('id',$id)->first();

        if($role != 'superadmin'){
            
            if($karyawan->flag = 2){
                return abort(404);
            }
        }

       

        $totalCuti = $request->total_cuti_tahunan+$request->total_cuti_mendadak+$request->total_cuti_sakit;
        $totalPotongan = $request->potong_bulanan+$request->potong_premi;

        $rupiahHarian = $karyawan->harian*26;
        $totalGajih = $rupiahHarian + $karyawan->bulanan + $karyawan->premi + $request->bonus;
        $totalSanksi = $request->potong_bulanan + $request->potong_premi + $request->sanksi;
        $gajih = $totalGajih-$totalSanksi;
        
        $data = new Gajih();

        $data->karyawan_id = $id;
        $data->bulan = $request->bulan;
        $data->tahun = $request->tahun;
        $data->bulanan = $karyawan->bulanan;
        $data->harian = $rupiahHarian;
        $data->premi = $karyawan->premi;
        $data->cuti_sakit = $request->total_cuti_sakit;
        $data->cuti_tahunan = $request->total_cuti_tahunan;
        $data->cuti_mendadak = $request->total_cuti_mendadak;
        $data->total_cuti = $totalCuti;
        $data->potongan_cuti_bulanan = $request->potong_bulanan;
        $data->potongan_cuti_premi = $request->potong_premi;
        $data->total_potongan = $totalPotongan;
        $data->bonus = $request->bonus;
        $data->sanksi = $request->sanksi;
        $data->total_gajih = $gajih;
        $data->bank_id = $karyawan->bank_id;
        $data->flag = $request->privasi;

        $data->save();

        return redirect()->route('gajih.list',$id)->with('success','Gaji '.$karyawan->nama.' created');

    }

    public function list($id, Request $request){
        $karyawan = Karyawan::find($id);

        $roleName = Auth::user()->getRoleNames()[0];

        $gajihList = Gajih::where('karyawan_id',$id)->orderBy('tahun','desc')->orderBy('bulan','desc');

        if( $roleName != "superadmin"){

            if($karyawan->flag == 2){
                return abort(404);
            }
          
        }

         
		if($request->bulan && $request->tahun){
			$gajihList = $gajihList->where('bulan',$request->bulan)->where('tahun',$request->tahun);
		}

        if($request->tipe){
            $gajihList = $gajihList->where('tipe',$request->tipe);
        }
        
        $gajihList = $gajihList->paginate(20)->withQueryString();

        $cid = $id;

        return view('gaji.gajiList',compact('karyawan','gajihList','cid'));
    }

    public function delete($id){
        $gajih = Gajih::find($id);

        $gajih->delete();

        return redirect()->route('gaji.index')->with('success','Gaji deleted');
    }
}

