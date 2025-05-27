<?php

namespace App\Http\Controllers;

use App\Helpers\CronHelper;
use App\Models\Cronrun;
use Illuminate\Http\Request;

class CronrunController extends Controller
{
    public function index(){
        $dataList = Cronrun::all();

        // dd($allRolesInDatabase);

        return view('cron.index',compact('dataList'));
    }

    public function edit($id){
        $data = Cronrun::find($id);

        // dd($allRolesInDatabase);

        return view('cron.edit',compact('data'));
    }

    public function update($id, Request $request){
        $data = Cronrun::find($id);

        $data->schedule = $request->schedule;
        $data->status = $request->status;

        $data->save();

        CronHelper::refreshCronCache();

        return redirect()->route('cronrunner.index')->with('success',  'Cron updated');

    }
}
