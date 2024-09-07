<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index(){
        $dataList = Karyawan::orderBy('nama','asc');;

        if(Request('name')) {
			$name = str_replace(' ', '%', Request('name'));
			$dataList = $dataList->where('nama','LIKE',"%$name%");
		}

        $dataList = $dataList->paginate(50)->withQueryString();

        return view('karyawan.index',compact('dataList'));
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
