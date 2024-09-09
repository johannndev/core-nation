<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class KaryawanController extends Controller
{
    public function index(){

        $now = Carbon::now();

        $dataList = Karyawan::with(['gajihSingle','gajih' => function($query) use($now) {
            $query->where('tahun', $now->year)
                  ->select('karyawan_id', DB::raw('SUM(cuti_sakit) as total_cuti_sakit'), DB::raw('SUM(cuti_tahunan) as total_cuti_tahunan'), DB::raw('SUM(cuti_mendadak) as total_cuti_mendadak'))
                  ->groupBy('karyawan_id');
        }])->orderBy('nama','asc');;

        if(Request('name')) {
			$name = str_replace(' ', '%', Request('name'));
			$dataList = $dataList->where('nama','LIKE',"%$name%");
		}

        $dataList = $dataList->paginate(50)->withQueryString();
  

        return view('karyawan.index',compact('dataList','now'));
    }

    public function create(){
        return view('karyawan.create');
    }

    public function store(Request $request){
        $rules = [
            'address'  => ['required'],
            'name'  => ['required'],
            'phone'  => ['required'],
            'bulanan'  => ['required'],
            'harian'  => ['required'],
            'premi'  => ['required'],
            
		];

        $attributes = [
            'address'  => 'Address',
            'name'  => 'Name',
            'phone' => 'Phone',
            'bulanan' => 'Bulanan',
            'harian' => 'Harian',
            'premi' => 'Premi',
        ];

        $this->validate($request, $rules, [], $attributes);

        $data = new Karyawan();

        $data->nama =$request->name;
        $data->alamat =$request->address;
        $data->no_telp =$request->phone;
        $data->bulanan =$request->bulanan;
        $data->harian =$request->harian;
        $data->premi =$request->premi;

        $data->save();

        return redirect()->route('karyawan.index')->with('success', 'Karyawan '.$data->nama.' created.');
    }

    public function edit($id){

        $data = Karyawan::find($id);

        return view('karyawan.edit',compact('data'));
    }

    public function update(Request $request,$id){
        $rules = [
            'address'  => ['required'],
            'name'  => ['required'],
            'phone'  => ['required'],
            'bulanan'  => ['required'],
            'harian'  => ['required'],
            'premi'  => ['required'],
            
		];

        $attributes = [
            'address'  => 'Address',
            'name'  => 'Name',
            'phone' => 'Phone',
            'bulanan' => 'Bulanan',
            'harian' => 'Harian',
            'premi' => 'Premi',
        ];

        $this->validate($request, $rules, [], $attributes);

        $data = Karyawan::find($id);

        $data->nama =$request->name;
        $data->alamat =$request->address;
        $data->no_telp =$request->phone;
        $data->bulanan =$request->bulanan;
        $data->harian =$request->harian;
        $data->premi =$request->premi;

        $data->save();

        return redirect()->route('karyawan.index')->with('success', 'Karyawan '.$data->nama.' updated.');
    }

    public function detail($id){

        $cid = $id;

        $data = Karyawan::find($id);

        return view('karyawan.detail',compact('data','cid'));
    }

    public function delete($id){

        $data = Karyawan::find($id);
        $data->delete();

        return redirect()->route('karyawan.index')->with('success', 'Karyawan '.$data->nama.' deleted.');
    }

}
