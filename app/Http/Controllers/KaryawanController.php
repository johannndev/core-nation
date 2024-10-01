<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class KaryawanController extends Controller
{
    public function index(){

        $now = Carbon::now();

        $role = Auth::user()->getRoleNames()[0];

     

        $dataList = Karyawan::with(['gajihSingle','gajih' => function($query) use($now) {
            $query->where('tahun', $now->year)
                  ->select('karyawan_id', DB::raw('SUM(cuti_sakit) as total_cuti_sakit'), DB::raw('SUM(cuti_tahunan) as total_cuti_tahunan'), DB::raw('SUM(cuti_mendadak) as total_cuti_mendadak'))
                  ->groupBy('karyawan_id');
        }])->orderBy('nama','asc');;

        if(Request('name')) {
			$name = str_replace(' ', '%', Request('name'));
			$dataList = $dataList->where('nama','LIKE',"%$name%");
		}

        if($role != 'superadmin'){
            $dataList = $dataList->where('flag',1);
        }

        $dataList = $dataList->paginate(50)->withQueryString();
  

        return view('karyawan.index',compact('dataList','now'));
    }

    public function create(){

        $dataListPropRecaiver = [
			"label" => "Account Bank",
			"id" => "bank",
			"idList" => "datalistBank",
			"idOption" => "datalistOptionsBank",
			"type" => Customer::TYPE_BANK,
			
		];

        return view('karyawan.create',compact('dataListPropRecaiver'));
    }

    public function store(Request $request){
        $rules = [
            'address'  => ['required'],
            'name'  => ['required'],
            'phone'  => ['required'],
            'bulanan'  => ['required'],
            'harian'  => ['required'],
            'premi'  => ['required'],
            'bank'  => ['required'],
            
		];

        $attributes = [
            'address'  => 'Address',
            'name'  => 'Name',
            'phone' => 'Phone',
            'bulanan' => 'Bulanan',
            'harian' => 'Harian',
            'premi' => 'Premi',
            'bank' => 'Account bank',
        ];

        $this->validate($request, $rules, [], $attributes);

        $data = new Karyawan();

        $data->nama =$request->name;
        $data->alamat =$request->address;
        $data->no_telp =$request->phone;
        $data->bulanan =$request->bulanan;
        $data->harian =$request->harian;
        $data->premi =$request->premi;
        $data->bank_id =$request->bank;
        $data->flag =$request->privasi;

        $data->save();

        return redirect()->route('karyawan.index')->with('success', 'Karyawan '.$data->nama.' created.');
    }

    public function edit($id){

        $data = Karyawan::find($id);

        $role = Auth::user()->getRoleNames()[0];

        if($role != 'superadmin'){
            if($data->flag = 2){
                return abort(404);
            }
        }

        $dataListPropRecaiver = [
			"label" => "Account Bank",
			"id" => "bank",
			"idList" => "datalistBank",
			"idOption" => "datalistOptionsBank",
			"type" => Customer::TYPE_BANK,
            "default" => $data->bank_id,
			
		];

        return view('karyawan.edit',compact('data','dataListPropRecaiver'));
    }

    public function update(Request $request,$id){
        $rules = [
            'address'  => ['required'],
            'name'  => ['required'],
            'phone'  => ['required'],
            'bulanan'  => ['required'],
            'harian'  => ['required'],
            'premi'  => ['required'],
            'bank'  => ['required'],
            
		];

        $attributes = [
            'address'  => 'Address',
            'name'  => 'Name',
            'phone' => 'Phone',
            'bulanan' => 'Bulanan',
            'harian' => 'Harian',
            'premi' => 'Premi',
            'bank' => 'Account bank',
        ];

        $this->validate($request, $rules, [], $attributes);

        $data = Karyawan::find($id);

        $data->nama =$request->name;
        $data->alamat =$request->address;
        $data->no_telp =$request->phone;
        $data->bulanan =$request->bulanan;
        $data->harian =$request->harian;
        $data->premi =$request->premi;
        $data->bank_id =$request->bank;
        $data->flag =$request->privasi;

        $data->save();

        return redirect()->route('karyawan.index')->with('success', 'Karyawan '.$data->nama.' updated.');
    }

    public function detail($id){

        $cid = $id;

        $data = Karyawan::with('bank')->find($id);

        return view('karyawan.detail',compact('data','cid'));
    }

    public function delete($id){

        $data = Karyawan::find($id);
        $data->delete();

        return redirect()->route('karyawan.index')->with('success', 'Karyawan '.$data->nama.' deleted.');
    }

}
