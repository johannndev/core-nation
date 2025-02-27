<?php

namespace App\Http\Controllers;

use App\Exceptions\ModelException;
use App\Helpers\AppSettingsHelper;
use App\Helpers\CCManagerHelper;
use App\Helpers\HashManagerHelper;
use App\Helpers\InvoiceTrackerHelpers;
use App\Helpers\StatManagerHelper;
use App\Helpers\TransactionsManagerHelper;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Jubeliosync;
use App\Models\Logjubelio;
use App\Models\Notmatcheditem;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiJubelioController extends Controller
{
    public function order(Request $request){
        $secret = 'corenation2025';
        $content = trim($request->getContent());

        $sign = hash_hmac('sha256',$content . $secret, $secret, false);

        $signature = $request->header('Sign');

        if ($signature !== $sign) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $dataApi = $request->all(); 

        $dataJubelio = [];

        $matched = 0;
        $notMatched = 0;
        $store = [];
        $location = [];


        if($dataApi['status'] == "SHIPPED"){

            $jubelioSync = Jubeliosync::where('jubelio_store_id',$dataApi['store_id'])->where('jubelio_location_id',$dataApi['location_id'])->first();

            if($jubelioSync){

                // $produkIds = collect($dataApi['items'])->pluck('item_code')->unique(); // Hilangkan duplikasi ID
                $itemCodes = collect($dataApi['items'])->pluck('item_code')->unique();

                // Ambil hanya kolom yang diperlukan
                $existingProducts = Item::whereIn('code', $itemCodes)
                    ->get(['id', 'code', 'name'])
                    ->keyBy('code'); // Index berdasarkan 'code' agar pencarian lebih cepat
                
                // Proses matching dengan map agar lebih efisien
                $groupedData = collect($dataApi['items'])->partition(fn($item) => isset($existingProducts[$item['item_code']]));
                
                $matched = $groupedData[0]->map(fn($item) => [
                    'itemId'   => $existingProducts[$item['item_code']]->id,
                    'code'     => $existingProducts[$item['item_code']]->code,
                    'name'     => $existingProducts[$item['item_code']]->name,
                    'quantity' => $item['qty'],
                    'price'    => $item['price'],
                    'discount' => $item['disc_amount'],
                    'subtotal' => $item['amount'],
                ])->values(); // Reset indeks array
                
                $notMatched = $groupedData[1]->values(); // Reset indeks array

                $createData = [];

                if($matched->count() > 0){

                    $cekTransaksi = Transaction::where('invoice')->first();

                    if($cekTransaksi){

                        return response()->json([
                            'status' => 'ok',
                            'pesan' => 'Invoice transaksi sudah ada',
                        ], 200);

                    }else{

                        $dataJubelio = [
                            "date" => Carbon::now()->toDateString(),
                            "due" => null,
                            "warehouse" => $jubelioSync->warehouse_id,
                            "customer" => $jubelioSync->customer_id,
                            "invoice" => $dataApi['salesorder_no'],
                            "note" => "generated by jubelio",
                            "account" => "7204",
                            "amount" => null,
                            "addMoreInputFields" => $matched,
                            "disc" => "0",
                            "adjustment" => "0",
                            "ongkir" => "0"
                        ];

                        $createData =  $this->createTransaction(Transaction::TYPE_SELL, $dataJubelio);

                        if($createData['status'] == "200" ){

                            $dataLog = new Logjubelio();
                            
                            $dataLog->transaction_id = $createData['transaction_id'];
                            $dataLog->invoice_id = $dataApi['salesorder_no'];
                            $dataLog->total_matched_item = $matched->count();
                            $dataLog->total_not_matched = $notMatched->count();
                            $dataLog->desc =  $createData['message'];
        
                            $dataLog->save();
        
                            if($notMatched->count() > 0){
        
                                $notMactheArray = [];
        
                                foreach ($notMatched as $data) {
                                    $notMactheArray[] = [
                                        'transaction_list' => $createData['transaction_id'],
                                        'item_code' => $data['item_code'],
                                        'item_name' =>  $data['item_name'],
                                        'channel' =>  $data['item_code'],
                                        'loc_name' =>  $dataApi['source_name'],
                                        'thumbnail' =>  $data['thumbnail'],
                                        'created_at' => Carbon::now(),
                                        'updated_at' => Carbon::now(),
                                    ];
                                }
        
                                DB::table('notmatcheditems')->insert($notMactheArray);
        
                             
        
        
                            }
        
                        }

                    }

                    

                   
                   

                }

               

                $matched = $matched->count();
                $notMatched = $notMatched->count();


               

            }else{
                return response()->json([
                    'status' => 'ok',
                    'pesan' => 'Data sync dengan aria tidak ditemukan',
                ], 200);
            }

           

        }else{

            return response()->json([
                'status' => 'ok',
                'pesan' => $dataApi['status'],
            ], 200);

        }


     


       

        return response()->json([
            'status' => 'ok',
            'status_jubelio' => $dataApi['status'],
            'total_matched' => $matched,
            'total_not_matched' => $notMatched,
            'store' => $dataApi['store_id'],
            'location' => $dataApi['location_id'],
            'data' => $dataJubelio
        ], 200);
    }

    public function retur(Request $request){
        $secret = 'corenation2025';
        $content = trim($request->getContent());

        $sign = hash_hmac('sha256',$content . $secret, $secret, false);

        $signature = $request->header('Sign');

        // $data = new Logjubelio();

        // $data->log = $request->items;

        // $data->save();

        $data = $request->all(); 

        return response()->json([
            'status' => 'ok',
            'signature' => $signature,
            'received_data' => $data
        ], 200);
    }

    protected function createTransaction($type = null, $request)
    {
       try {

       $class = array();

       
       //start transaction
       DB::beginTransaction();

       $customer = Customer::find($request->customer);
       $warehouse = Customer::find($request->warehouse);

       // dd($customer,$warehouse);

       // $input = $request;
       $transaction = new Transaction();
       $transaction->date = $request->date;
       $transaction->type = $type;

       if($request->note){
           $transaction->description = $request->note;
       }else{
           $transaction->description = "";
       }

       if($request->due){
           $transaction->due = $request->due;
       }else{
           $transaction->due = '0000-00-00';
       }

       $transaction->description = ' ';
       $transaction->detail_ids = ' ';
       
       $transaction->save();
       switch($type)
       {
           case Transaction::TYPE_BUY:
           case Transaction::TYPE_RETURN:
               $transaction->sender_id = $customer->id;
               $transaction->receiver_id = $warehouse->id;
               break;
           case Transaction::TYPE_SELL:
           case Transaction::TYPE_RETURN_SUPPLIER:
               $transaction->sender_id = $warehouse->id;
               $transaction->receiver_id = $customer->id;
               break;
           default: //don't update stats for move, production
               break;
       }
       
       $transaction->init($type);

       // dd($request->addMoreInputFields);
       //gets the transaction id
       if(!$transaction->save())

           
           throw new ModelException($transaction->getErrors(), __LINE__);

       if(!$details = $transaction->createDetails($request->addMoreInputFields))
           throw new ModelException($transaction->getErrors(), __LINE__);
       

       //check ppn first
       $transaction->checkPPN($transaction->sender, $transaction->receiver);

   


       //add to customer stat
       // $sm = new StatManager;

       $sm = new StatManagerHelper();
       switch($type)
       {
           case Transaction::TYPE_BUY:
           case Transaction::TYPE_RETURN:
               //add balance to sender(supplier)
               $sender_balance = $sm->add($transaction->sender_id,$transaction,true); //skip 1 because the transaction is already created?
               if($sender_balance === false)
                   throw new ModelException($sm->getErrors());

               $transaction->sender_balance = $sender_balance;
               break;
           case Transaction::TYPE_SELL:
           case Transaction::TYPE_RETURN_SUPPLIER:
               $transaction->setAttribute('total',0 - $transaction->total); //make negative

               //deduct balance from receiver(customer)
               $receiver_balance = $sm->deduct($transaction->receiver_id,$transaction,true);
               if($receiver_balance === false)
                   throw new ModelException($sm->getErrors());

               $transaction->receiver_balance = $receiver_balance;

               // $transaction->save();

               // dd($receiver_balance,$transaction, $transaction->receiver_balance);
               break;
           default: //don't update stats for move, production
               break;
       }

       

       if(!$transaction->save())
           throw new $transaction->getErrors();

       $paid = $request->paid;
       //special case: paid is checked
       if($type == Transaction::TYPE_SELL && isset($paid) && $paid)
       {
           //calculate total
           $amount = isset($request->amount) ? $request->amount : 0;
           if($amount <= 0) $amount = abs($transaction->total);

           $payment = $transaction->attachIncome($transaction->date, $transaction->receiver_id, $request->account,$amount);
           $class['income'] = $payment->total;

           //another special case, ongkir is filled, create journal
           $settingApp = new AppSettingsHelper;
           $ongkir = isset($request->ongkir) ? $request->ongkir : null;
           if(!empty($ongkir))
               $transaction->attachOngkir($transaction->date, $payment->receiver_id, abs($ongkir), $settingApp->getAppSettings('ongkir') );
       }

       


       InvoiceTrackerHelpers::flag($transaction);

       // dd($details);
       
       TransactionsManagerHelper::checkSell($transaction, $details);

       
       HashManagerHelper::save($transaction);
       $cc = new CCManagerHelper;
       $class['date'] = Carbon::createFromFormat('Y-m-d',$transaction->date)->startOfMonth()->toDateString();
       //update customer class
       switch ($transaction->type) {
           case Transaction::TYPE_SELL:
               $class['type'] = Transaction::TYPE_SELL;
               $class['total'] = $transaction->total;
               $class['customer'] = $transaction->receiver;
               $cc->update($class);
               break;
           case Transaction::TYPE_RETURN:
               $class['type'] = Transaction::TYPE_RETURN;
               $class['total'] = $transaction->total;
               $class['customer'] = $transaction->sender;
               $cc->update($class);
               break;
           default:
               break;
       }

       if($type == 2 || $type == 15){

       

           // Query
           $result = DB::table('transaction_details')
           ->where('transaction_details.transaction_id',$transaction->id)
           ->join('items', 'transaction_details.item_id', '=', 'items.id')
           ->whereIn('transaction_details.transaction_type', [2, 15]) // Filter transaction_type 2 dan 15
           ->selectRaw('
               items.group_id,
               MONTH(transaction_details.date) as bulan,
               YEAR(transaction_details.date) as tahun,
               transaction_details.sender_id,
               transaction_details.transaction_type,
               SUM(transaction_details.quantity) as sum_qty,
               SUM(transaction_details.total) as sum_total
           ')
           ->groupBy('items.group_id', DB::raw('MONTH(transaction_details.date)'), DB::raw('YEAR(transaction_details.date)'), 'transaction_details.sender_id', 'transaction_details.transaction_type')
           ->orderBy('items.group_id') // Optional: Untuk urutan hasil
           ->get();
   
           $insertData = [];
           foreach ($result as $row) {
               $insertData[] = [
                   'group_id' => $row->group_id,
                   'bulan' => $row->bulan,
                   'tahun' => $row->tahun,
                   'sender_id' => $row->sender_id,
                   'type' => $row->transaction_type,
                   'sum_qty' => (int)$row->sum_qty,
                   'sum_total' => (int)$row->sum_total,
                   'created_at' => now(),
                   'updated_at' => now(),
               ];
           }

           // dd($insertData);

           $this->updateOrCreateStatsalesOptimized($insertData);

       }

       //commit db transaction
       DB::commit();

       // $request->session()->flash('success', 'Transaction # ' . $transaction->id. ' created.');

        return response()->json([
            'status' => '200',
            'message' => 'ok',
            'transaction_id' => $transaction->id,
        ], 200);

       return redirect()->route('transaction.getDetail',$transaction->id)->with('success', 'Transaction # ' . $transaction->id. ' created.');
       
       // return response()->json([
       //     'url' => route('transaction.getDetail',$transaction->id,$transaction->date),
       // ]);


       } catch(ModelException $e) {
           
           DB::rollBack();

             return response()->json([
                'status' => '422',
                'message' => $e->getErrors()['error'][0],
            ], 200);

          
           // return response()->json($e->getErrors(), 500);
       
       } catch(\Exception $e) {
           DB::rollBack();

           return response()->json([
                'status' => '422',
                'message' => $e->getMessage(),
            ], 200);

        //    return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());

           // return response()->json($e->getMessage(), 500);
           
       }
   }
}
