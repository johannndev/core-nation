<?php

namespace App\Http\Controllers;

use App\Models\CronStatRun;
use App\Models\Item;
use App\Models\Jubeliosync;
use App\Models\WarehouseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CronController extends Controller
{
    public function itemEdit(){

        
        $jubelioSync = Jubeliosync::select('warehouse_id')->groupBy('warehouse_id')->pluck('warehouse_id')->toArray();

        $itemCount = WarehouseItem::whereIn('warehouse_id', $jubelioSync)->count();
      
        $data = CronStatRun::where('name','edit_item')->first();

        $whItem = WarehouseItem::with(['item' => function ($query) {
            $query->whereNull('jubelio_item_id');
        }])
        ->whereIn('warehouse_id', $jubelioSync)
        ->orderBy('id', 'asc')
        ->first();


        $itemCode = $whItem;
        dd($itemCode);

        return response()->json([

            'total_item' => $itemCount,
            'running' => $data->runner,
            'not_found' =>$data->failed,
            'last_run' =>$data->updated_at->format('d-m-Y h:m:s')
        ]);
    }

    public function itemCron(){

        // DB::table('items')->update([
        //     'jubelio_item_id' => null, // Kolom yang diperbarui
        // ]);
       

        $item = Item::whereNull('jubelio_item_id')->orderBy('id','asc')->first();

        // dd($item);

        if($item){

            $response = Http::withHeaders([ 
                'Content-Type'=> 'application/json', 
                'authorization'=> Cache::get('jubelio_data')['token'], 
            ]) 
            ->get('https://api2.jubelio.com/inventory/items/to-stock/',[
                'q' => $item->code,
            ]); 
    
            $data = json_decode($response->body(), true);

            if($data['totalCount'] == 0){
                DB::table('items')->where('code',$item->code)->update([
                    'jubelio_item_id' => 0, // Kolom yang diperbarui
                ]);
               
                DB::table('cron_stat_runs')->incrementEach([
                    'runner' => 1,
                    'failed' => 1,
                ]);
            }else{
                
                DB::table('items')->where('code',$item->code)->update([
                    'jubelio_item_id' => $data['data'][0]['item_id'], // Kolom yang diperbarui
                ]);

                DB::table('cron_stat_runs')->increment('runner');

           

               
            }
    
            dd($data['totalCount'],$item->code);

        }

   

    

        // $itemId = $data['data'][0]['item_id'];

        
    }
}
