<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Item;
use App\Models\Produksi;
use App\Models\WarehouseItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;



class AjaxController extends Controller
{



    public function getCostumer(Request $request)
    {
        $search = str_replace(' ', '%', $request->search);

        $customer = Customer::where('name','like','%'.$search.'%')->whereIn('type',explode(",",$request->type));
        
        if($request->local > 0){

            $localId = $request->local;

            $customer = $customer->whereHas('locations', function (Builder $query) use($localId) {
                $query->where('location_id', $localId);
            });

        }

        
        $customer = $customer->paginate();

        // $customer = $customer->get(['id', 'name']);
        return response()->json($customer, 200);
    }

    public function getCostumerCash(Request $request)
    {
        $search = str_replace(' ', '%', $request->search);
       
        $customer = Customer::with('stat')->where('name','like','%'.$search.'%')->whereNotIn('type',[Customer::TYPE_BANK,Customer::TYPE_VWAREHOUSE,Customer::TYPE_WAREHOUSE,Customer::TYPE_VACCOUNT])->paginate();

        // $customer = $customer->get(['id', 'name']);
        return response()->json($customer, 200);
    }

    

    public function getCostumerSingle(Request $request)
    {
       
        $customer = Customer::find($request->idCust);
        return response()->json($customer, 200);
    }

    public function getItem(Request $request)
    {
        $search = str_replace(' ', '%', $request->search);
       
        $item = Item::where('name','like','%'.$search.'%')->orderBy('name','asc')->paginate();

        // dd($item);
        
        return response()->json($item, 200);
    }

    public function getItemId(Request $request)
    {
        $search = str_replace(' ', '%', $request->search);
       
        $item = Item::where('id','like','%'.$search.'%')->orderBy('name','asc')->skip(0)->take(10)->get();
        
        return response()->json($item, 200);
    }

    public function getItemSetoran(Request $request)
    {
        $search = str_replace(' ', '%', $request->search);

        $item = Item::where('name','like','%'.$search.'%')->orWhere('id','like','%'.$search.'%')->orderBy('name','asc')->paginate();
        
        return response()->json($item, 200);
    }

    public function getInvoice(Request $request)
    {
        $search = str_replace(' ', '%', $request->search);

        $setoran = Produksi::where('invoice','like','%'.$search.'%')->where('id',$request->id)->get();
        
        return response()->json($setoran, 200);
    }


    public function getItemAjax(Request $request)
	{

		$itemId = $request->item_id;

       

            $itemGet = Item::where('id',$itemId)->orWhere('code', $itemId)->orWhere('name', $itemId)->first();

        
            if(!$itemGet){
                return response()->json(["data" => 0,'error' => 1]);
            }

            
        

            if($request->wh_id){
    
                $wh= WarehouseItem::where('item_id',$itemGet->id)->where('warehouse_id',$request->wh_id)->first();
                
                if ($wh) {
                    
                    $whGet = $wh->quantity;
                   
                } else {
                    $whGet = 0;
                }
                
    
    
            }else{
                $whGet = 0;
            }
    
            $data = [
                'data' => $itemGet,
                'whQty' => $whGet,
            ];
    
            return response()->json(["data" => $data,'error' => 0]);

        

	
		
	}

    // public function sellBatch(Request $request){
    //     $whid = $request->whId;

    //     $data = $request->csvInput;

    //     $rows = preg_split('/[\/n\s]+/', trim($data));

    //     $array = array();

    //     foreach ($rows as $row) {
    //         // Memecah setiap baris menjadi elemen array
    //         $elements = explode(',', $row);
            
    //         // Menambah elemen ke dalam array
    //         $array[] = array(
    //             'id' => $elements[0],
    //             'qty' => $elements[1],
    //             'price' => $elements[2]
    //         );
    //     }

    //     $qtyPluck = collect($array)->pluck('qty','id')->toArray();
    //     $qtyPrice = collect($array)->pluck('price','id')->toArray();

        

    //     $collect = collect($array)->pluck('id');

    //     // $item = Item::whereIn('id',$collect)->orderBy('name','asc')->get();

    //     $items = Item::whereIn('id', $collect)
    //             ->with(['warehousesItemAlt' => function ($query) use ($whid) {
    //                 $query->where('warehouse_id', $whid);
    //             }])
    //             ->orderBy('name', 'asc')
    //             ->get();

    //     $dataList = [];

    //     foreach ($items as $i) {
    //         $warehouseQuantity = $i->warehousesItemAlt->first()->quantity ?? 0; // Ambil quantity warehouse terkait, atau 0 jika tidak ada
        
    //         $dataList[] = [
    //             'id' => $i->id,
    //             'code' => $i->code,
    //             'quantity' => (float)$qtyPluck[$i->id],
    //             'warehouse' => $warehouseQuantity,
    //             'price' => (float)$qtyPrice[$i->id]
    //         ];
    //     }

    //     $dataColl = collect($dataList);

        
    //     // dd($dataColl->sum('price'));

    //     // foreach($item as $i){
    //     //     $dataList[] = [
    //     //         'id' => $i->id,
    //     //         'code' => $i->code,
    //     //         'quantity'=>(float)$qtyPluck[$i->id],
    //     //         'warehouse'=> $i->getQtyWarehouse($i->id,$whid),
    //     //         'price' => (float)$qtyPrice[$i->id] 
    //     //     ];
    //     // }

        


    //     return response()->json(['data' => $dataList, 'totalQty'=> $dataColl->sum('quantity'),'totalPrice' => $dataColl->sum('price'),'total' => $dataColl->count()]);

    //     // dd($dataList);
    // }

    public function sellBatch(Request $request)
    {
        $whid = $request->whId;

        // dd($request);

        // Validate and store the uploaded file
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048'
        ]);

        $file = $request->file('csv_file');
        $filePath = $file->getRealPath();

        // Parse the CSV file to an array
        $array = $this->parseCsvToArray($filePath); // Use the parsing function from earlier

    

        // dd($array);

        // $qtyPluck = collect($array)->pluck('qty','id')->toArray();
        // $qtyPrice = collect($array)->pluck('price','id')->toArray();

        // $collect = collect($array)->pluck('id');


        $codes = collect($array)->pluck('id')->unique();

        $items = Item::whereIn('code', $codes)
            ->with(['warehousesItemAlt' => function ($query) use ($whid) {
                $query->where('warehouse_id', $whid);
            }])
            ->get()
            ->keyBy('code');

           

        // $items = Item::where(function ($query) use ($collect) {
        //     $query->whereIn('id', $collect)
        //             ->orWhereIn('code', $collect);
        //     })
        //     ->with(['warehousesItemAlt' => function ($query) use ($whid) {
        //         $query->where('warehouse_id', $whid);
        //     }])
        //     ->orderBy('name', 'asc')
        //     ->get();


            $dataList = [];

            foreach ($array as $row) {
                $code = $row['id'];
                $qty = (float) $row['qty'];
                $price = (int) $row['price'];

                $item = $items[$code] ?? null;

                if ($item) {
                    $warehouseQuantity = $item->warehousesItemAlt->first()->quantity ?? 0;

                    $dataList[] = [
                        'id' => $item->id,
                        'code' => $item->code,
                        'quantity' => $qty,
                        'warehouse' => $warehouseQuantity,
                        'price' => $price,
                    ];
                }
            }

      
      

        //tambah id dan warehaouse

        // foreach ($items as $i) {
        //     $warehouseQuantity = $i->warehousesItemAlt->first()->quantity ?? 0; // Ambil quantity warehouse terkait, atau 0 jika tidak ada
        
        //     // Use array indexing to get values, or default to 0
        //     $qty = isset($qtyPluck[$i->code]) ? (float) $qtyPluck[$i->code] : 0;
        //     $price = isset($qtyPrice[$i->code]) ? (float) $qtyPrice[$i->code] : 0;

        //     $dataList[] = [
        //         'id' => $i->id,
        //         'code' => $i->code,
        //         'quantity' => $qty,
        //         'warehouse' => $warehouseQuantity,
        //         'price' => (int)Str::replace('.', '', $price),
        //     ];
        // }

        $dataColl = collect($dataList);
        
        // dd($dataColl->sum('price'));

        // foreach($item as $i){
        //     $dataList[] = [
        //         'id' => $i->id,
        //         'code' => $i->code,
        //         'quantity'=>(float)$qtyPluck[$i->id],
        //         'warehouse'=> $i->getQtyWarehouse($i->id,$whid),
        //         'price' => (float)$qtyPrice[$i->id] 
        //     ];
        // }

        


        return response()->json(['data' => $dataList, 'totalQty'=> $dataColl->sum('quantity'),'totalPrice' => $dataColl->sum('price'),'total' => $dataColl->count()]);
    }

    private function parseCsvToArray($filePath)
    {
        $dataArray = [];

        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ';')) !== false) {
                $dataArray[] = [
                    'id' => $row[0],
                    'qty' => $row[1],
                    'price' => $row[2]
                ];
            }
            fclose($handle);
        }

   

        return $dataArray;
    }

    public function processBarcode(Request $request)
    {
        $barcode = $request->input('barcode');

        // Logika untuk memproses barcode di sini, misalnya mencari produk di database berdasarkan barcode
        // Contoh:
        // $product = Product::where('barcode', $barcode)->first();

        return response()->json(['message' => 'Barcode processed successfully: ' . $barcode]);
    }
}
