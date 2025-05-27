<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    public function index(){
        
        $tb = [3,4,5,6,7,8,9,10,20,28];

        $setting = AppSetting::all()->pluck('value','name')->toArray();

        $dataListSell = [
			"label" => "Account for 100% Discount",
			"id" => "sell_100",
			"idList" => "datalistSell",
			"idOption" => "datalistOptionsSell",
			"type" => Customer::TYPE_ACCOUNT,
			"default" => $setting['sell_100'],
		];

        $dataListOngkir = [
			"label" => "Account for Ongkir",
			"id" => "ongkir",
			"idList" => "datalistOngkir",
			"idOption" => "datalistOptionsOngkir",
			"type" => Customer::TYPE_ACCOUNT,
			"default" => $setting['ongkir'],
		];

        

        return view('settings.index',compact('dataListSell','dataListOngkir','setting','tb'));
    }

    public function update(Request $request){
        $sell = 'sell_100';

        $setting = AppSetting::all()->pluck('value','name')->toArray();

        DB::transaction( function() use($setting){

            foreach($setting as $index => $item){

                AppSetting::where(['name'=>$index])->update(['value'=>Request($index)]);

            }

        });

        return redirect()->back()->with('success','Settings Updated');
    }

    public function systemLog(){
        
        $logPath = storage_path('logs/laravel.log');

        if (File::exists($logPath)) {
            $logContent = File::get($logPath);
        } else {
            $logContent = 'Log file not found.';
        }

        return view('log.system', compact('logContent'));
    }
}
