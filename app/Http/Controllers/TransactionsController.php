<?php

namespace App\Http\Controllers;

use App\Exceptions\ModelException;
use App\Exports\SellItemExport;
use App\Helpers\AppSettingsHelper;
use App\Helpers\CCManagerHelper;
use App\Helpers\DateHelper;
use App\Helpers\DeleterHelper;
use App\Helpers\StatManagerHelper;
use App\Helpers\StockManagerHelpers;
use App\Helpers\TransactionsManagerHelper;
use App\Libraries\CCManager;

use App\Libraries\StatManager;
use App\Libraries\TransactionsManager;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Jubeliosync;
use App\Models\StatSell;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\UserSetting;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class TransactionsController extends Controller
{
	public function index(Request $request)
	{

		$allType = Transaction::$typesJSON;

		$dataList = Transaction::with('receiver','sender');

		if($request->from && $request->to){
			$dataList = $dataList->whereDate('date','>=',$request->from)->whereDate('date','<=',$request->to);
		}

		if($request->invoice){
			$dataList = $dataList->where('invoice',$request->invoice);
		}

		if($request->total){
			$dataList = $dataList->where('total',$request->total);
		}

		if($request->type){
			$dataList = $dataList->where('type',$request->type);
		}

		if($request->order_date){
			$dataList = $dataList->orderBy($request->order_date,'desc');
		}else{
			$dataList = $dataList->orderBy('date','desc');
		}

		$datalist = $dataList->orderBy('id','desc');

		if(Auth::user()->location_id > 0){

			$customers = Customer::whereHas('locations', function ($query) {
				$query->where('location_id', Auth::user()->location_id);
			})->pluck('id');

			$dataList = $dataList->whereIn('sender_id', $customers)->whereIn('receiver_id', $customers);

			// dd($customers);

			// $userLocationId = Auth::user()->location_id;

			// $dataList = $dataList->where(function ($query) use ($userLocationId) {
			// 	// Jika sender_type atau recaiver_type = 1, tampilkan semua data
			// 	$query->where('sender_type', Customer::TYPE_CUSTOMER)
			// 		->orWhere('receiver_type', Customer::TYPE_CUSTOMER);
			// })
			// ->orWhere(function ($query) use ($userLocationId) {
			// 	// Jika sender_type atau receiver_type adalah [2,3,4], cek lokasi sender/receiver
			// 	$query->whereIn('sender_type', [Customer::TYPE_BANK, Customer::TYPE_WAREHOUSE, Customer::TYPE_RESELLER])
			// 		->orWhereIn('receiver_type', [Customer::TYPE_BANK, Customer::TYPE_WAREHOUSE, Customer::TYPE_RESELLER])
			// 		->where(function ($q) use ($userLocationId) {
			// 			$q->whereHas('sender.locations', function ($subQuery) use ($userLocationId) {
			// 				$subQuery->whereIn('locations.id', [$userLocationId]);
			// 			})->orWhereHas('receiver.locations', function ($subQuery) use ($userLocationId) {
			// 				$subQuery->whereIn('locations.id', [$userLocationId]);
			// 			});
			// 		});
			// });

			
				

			// $datList = $dataList->filterLocation();

			// $dataList = $dataList->whereIn('sender_type',[Customer::TYPE_CUSTOMER, Customer::TYPE_BANK,Customer::TYPE_WAREHOUSE,Customer::TYPE_RESELLER])->orWhereIn('receiver_type', [Customer::TYPE_CUSTOMER, Customer::TYPE_BANK,Customer::TYPE_WAREHOUSE,Customer::TYPE_RESELLER]);

			// $dataList = $dataList->where(function ($query) {
			// 	$query->whereHas('sender', function ($q) {
			// 		$q->where(function ($q2) {
			// 			$q2->whereIn('type', [Customer::TYPE_BANK,Customer::TYPE_WAREHOUSE,Customer::TYPE_RESELLER])
			// 			   ->whereHas('locations', function ($q3) {
			// 				   $q3->where('location_id', Auth::user()->location_id);
			// 			   });
			// 		});
			// 	})->orWhereHas('receiver', function ($q) {
			// 		$q->where(function ($q2) {
			// 			$q2->whereIn('type', [Customer::TYPE_BANK,Customer::TYPE_WAREHOUSE,Customer::TYPE_RESELLER])
			// 			   ->whereHas('locations', function ($q3) {
			// 				   $q3->where('location_id', Auth::user()->location_id);
			// 			   });
			// 		});
			// 	});
			// });
			
		
			

		}

		$dataList = $dataList->paginate(20)->withQueryString();

		// dd($dataList);

		return view('transactions.index',compact('dataList','allType'));
	}

    public function sell()
    {
		$trType = 'sell';

		$userSetting = UserSetting::where('user_id')->where('name','default_sell_warehouse')->first();

		if($userSetting){
			  $wh = Customer::find($userSetting->value);

			if($wh){
				$defaultParam = $userSetting->value;
			}else{
				$defaultParam = null;
			}
		}else{
			$defaultParam = null;
		}

		$dataListPropRecaiver = [
			"label" => "Warehouse",
			"id" => "warehouse",
			"idList" => "datalistWh",
			"idOption" => "datalistOptionsWh",
			"type" => Customer::TYPE_WAREHOUSE,
			"default" => $defaultParam
			
		];

		$dataListPropSender = [
			"label" => "Customer",
			"id" => "customer",
			"idList" => "datalistSender",
			"idOption" => "datalistOptionsSender",
			"type" => Customer::TYPE_CUSTOMER.",".Customer::TYPE_RESELLER,
			
		];

		
		$bankList = Customer::where('type',Customer::TYPE_BANK)->orderBy('name','asc')->get();
        return view('transactions.sell',compact('bankList','trType','dataListPropRecaiver','dataListPropSender'));
    }

    public function postSell(Request $request)
	{
		return $this->createTransaction(Transaction::TYPE_SELL, $request);
	}

	public function sellBatch()
    {
		$trType = 'sell';

		$dataListPropRecaiver = [
			"label" => "Warehouse",
			"id" => "warehouse",
			"idList" => "datalistWh",
			"idOption" => "datalistOptionsWh",
			"type" => Customer::TYPE_WAREHOUSE,
			
		];

		$dataListPropSender = [
			"label" => "Customer",
			"id" => "customer",
			"idList" => "datalistSender",
			"idOption" => "datalistOptionsSender",
			"type" => Customer::TYPE_CUSTOMER.",".Customer::TYPE_RESELLER,
			
		];

		
		$bankList = Customer::where('type',Customer::TYPE_BANK)->orderBy('name','asc')->get();
        return view('transactions.sell-batch',compact('bankList','trType','dataListPropRecaiver','dataListPropSender'));
    }

	public function buyBatch()
    {
		$trType = 'buy';

		$userSetting = UserSetting::where('user_id')->where('name','default_buy_warehouse')->first();

		if($userSetting){
			  $wh = Customer::find($userSetting->value);

			if($wh){
				$defaultParam = $userSetting->value;
			}else{
				$defaultParam = null;
			}
		}else{
			$defaultParam = null;
		}

		$dataListPropRecaiver = [
			"label" => "Warehouse",
			"id" => "warehouse",
			"idList" => "datalistWh",
			"idOption" => "datalistOptionsWh",
			"type" => Customer::TYPE_WAREHOUSE,
			
		];

		$dataListPropSender = [
			"label" => "Supplier",
			"id" => "supplier",
			"idList" => "datalistSender",
			"idOption" => "datalistOptionsSender",
			"type" => Customer::TYPE_SUPPLIER,
			"default" => $defaultParam
			
		];

		
		$bankList = Customer::where('type',Customer::TYPE_BANK)->orderBy('name','asc')->get();
        return view('transactions.buy-batch',compact('bankList','trType','dataListPropRecaiver','dataListPropSender'));
    }

	public function postSellBatch(Request $request)
 	{
		try {

		
		//start transaction
		DB::beginTransaction();

		// dd($request);

		$customer = $request->customer;
		$warehouse = $request->warehouse;

		
		$transaction = new Transaction();
		
		$transaction->date = $request->date;
        $transaction->type = Transaction::TYPE_SELL;
		$transaction->sender_id = $warehouse;
		$transaction->receiver_id = $customer;
		$transaction->due = $request->due ?? '0000-00-00';
		$transaction->description = $request->note ?? ' ';
		$transaction->invoice = $request->invoice ?? ' ';
		$transaction->adjustment = $request->adjustment ?? 0;
		$transaction->discount = $request->disc ?? 0;
		$transaction->submit_type = 1;
		$transaction->detail_ids = ' ';

		//gets the transaction id
		if(!$transaction->save())
			throw new ModelException($transaction->getErrors(), __LINE__);

		if(!$details = $transaction->createDetails($request->addMoreInputFields))
			throw new ModelException($transaction->getErrors(), __LINE__);

		$transaction->checkPPN($transaction->sender, $transaction->receiver);

		//add to customer stat
		$sm = new StatManagerHelper;
		$transaction->setAttribute('total',0 - $transaction->total); //make negative

		//deduct balance from receiver(customer)
		$receiver_balance = $sm->deduct($transaction->receiver_id,$transaction,true);
		if($receiver_balance === false)
			throw new ModelException($sm->getErrors());

		$transaction->receiver_balance = $receiver_balance;
        if(empty(trim($transaction->invoice)))
            $transaction->invoice = $transaction->id;
		if(!$transaction->save())
			throw new ModelException($transaction->getErrors());

		$cc = new CCManagerHelper;
		$class['date'] = Carbon::parse($transaction->date)->startOfMonth()->toDateString();
		$class['type'] = Transaction::TYPE_SELL;
		$class['total'] = $transaction->total;
		$class['customer'] = $transaction->receiver;
		$cc->update($class);

		//commit db transaction
		DB::commit();

        // $request->session()->flash('success', 'Transaction # ' . $transaction->id. ' created.');

		return redirect()->route('transaction.index',$transaction->id)->with('success', 'Transaction # ' . $transaction->id. ' created.');

		
		} catch(ModelException $e) {
			DB::rollBack();

			dd($e);

			return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);
            // return response()->json($e->getErrors(), 500);
		} catch(\Exception $e) {
			DB::rollBack();

			dd($e);

			return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());
		}
	}

	public function postBuyBatch(Request $request)
 	{
		try {
		//start transaction
		DB::beginTransaction();

		// dd($request);

		$customer = $request->supplier;
		$warehouse = $request->warehouse;

		
		$transaction = new Transaction();

		$transaction->date = $request->date;
        $transaction->type = Transaction::TYPE_BUY;
		$transaction->sender_id = $warehouse;
		$transaction->receiver_id = $customer;
		$transaction->due = $request->due ?? '0000-00-00';
		$transaction->description = $request->note ?? ' ';
		$transaction->invoice = $request->invoice ?? ' ';
		$transaction->adjustment = $request->adjustment ?? 0;
		$transaction->discount = $request->disc ?? 0;
		$transaction->submit_type = 1;
		$transaction->detail_ids = ' ';

		

		//gets the transaction id
		if(!$transaction->save())
			throw new ModelException($transaction->getErrors(), __LINE__);

		if(!$details = $transaction->createDetails($request->addMoreInputFields))
			throw new ModelException($transaction->getErrors(), __LINE__);

		$transaction->checkPPN($transaction->sender, $transaction->receiver);

		//add to customer stat
		$sm = new StatManagerHelper;
		$transaction->setAttribute('total',0 - $transaction->total); //make negative

		//deduct balance from receiver(customer)
		$receiver_balance = $sm->deduct($transaction->receiver_id,$transaction,true);
		if($receiver_balance === false)
			throw new ModelException($sm->getErrors());

		$transaction->receiver_balance = $receiver_balance;
        if(empty(trim($transaction->invoice)))
            $transaction->invoice = $transaction->id;

        if(!$transaction->save())
			throw new ModelException($transaction->getErrors());

		$cc = new CCManagerHelper;
		$class['date'] = Carbon::parse($transaction->date)->startOfMonth()->toDateString();
		$class['type'] = Transaction::TYPE_BUY;
		$class['total'] = $transaction->total;
		$class['customer'] = $transaction->receiver;
		$cc->update($class);

		//commit db transaction
		DB::commit();

        // $request->session()->flash('success', 'Transaction # ' . $transaction->id. ' created.');

		return redirect()->route('transaction.index',$transaction->id)->with('success', 'Transaction # ' . $transaction->id. ' created.');

		
		} catch(ModelException $e) {
			DB::rollBack();

			dd($e);

			return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);
            // return response()->json($e->getErrors(), 500);
		} catch(\Exception $e) {
			DB::rollBack();

			dd($e);

			return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());
		}
	}



	public function buy()
    {
		$trType = 'buy';

		$dataListPropRecaiver = [
			"label" => "Warehouse",
			"id" => "warehouse",
			"idList" => "datalistWh",
			"idOption" => "datalistOptionsWh",
			"type" => Customer::TYPE_WAREHOUSE,
			"default" => Customer::$defaultWH,
		];

		$dataListPropSender = [
			"label" => "Sender",
			"id" => "customer",
			"idList" => "datalistSender",
			"idOption" => "datalistOptionsSender",
			"type" => Customer::TYPE_SUPPLIER,
			
		];

		
		
        return view('transactions.buy',compact('trType','dataListPropRecaiver','dataListPropSender'));
    }

	public function postBuy(Request $request)
	{
		return $this->createTransaction(Transaction::TYPE_BUY, $request);
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
		$transaction->due = $request->due ?? '0000-00-00';
		$transaction->description = $request->note ?? ' ';
		$transaction->invoice = $request->invoice ?? ' ';
		$transaction->adjustment = $request->adjustment ?? 0;
		$transaction->discount = $request->disc ?? 0;
		$transaction->submit_type = 1;
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

		if($type == Transaction::TYPE_SELL || $type == Transaction::TYPE_RETURN){

		

			// Query
			$result = DB::table('transaction_details')
			->where('transaction_details.transaction_id',$transaction->id)
			->join('items', 'transaction_details.item_id', '=', 'items.id')
			->whereIn('transaction_details.transaction_type', [Transaction::TYPE_SELL, Transaction::TYPE_RETURN]) // Filter transaction_type 2 dan 15
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

		// return redirect()->route('transaction.getDetail',$transaction->id)->with('success', 'Transaction # ' . $transaction->id. ' created.');

		return response()->json(['status' => 'ok','message' => 'Data berhasil disimpan', 'trx' =>$transaction->id]);
		
        // return response()->json([
        //     'url' => route('transaction.getDetail',$transaction->id,$transaction->date),
        // ]);


		} catch(ModelException $e) {
			
            DB::rollBack();

			return response()->json(['status' => 'error', 'message' => $e->getErrors()['error'][0]], 200);

			// return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);
            // return response()->json($e->getErrors(), 500);
		
		} catch(\Exception $e) {
            DB::rollBack();

			// dd($e);

			return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);

			// return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());

            // return response()->json($e->getMessage(), 500);
			
		}
	}

	public function updateOrCreateStatsalesOptimized(array $data)
	{
		foreach ($data as $entry) {
			$existing = DB::table('stat_sells')
				->where('group_id', $entry['group_id'])
				->where('bulan', $entry['bulan'])
				->where('tahun', $entry['tahun'])
				->where('sender_id', $entry['sender_id'])
				->first();

			if ($existing) {
				// Jika data ditemukan, update sum_qty dan sum_total
				DB::table('stat_sells')
					->where('id', $existing->id)
					->incrementEach([
						'sum_qty' => $entry['sum_qty'],
						'sum_total' => $entry['sum_total']
					]);
			} else {
				// Jika tidak ditemukan, insert data baru
				DB::table('stat_sells')->insert([
					'group_id' => $entry['group_id'],
					'bulan' => $entry['bulan'],
					'tahun' => $entry['tahun'],
					'sender_id' => $entry['sender_id'],
					'type' => $entry['type'],
					'sum_qty' => $entry['sum_qty'],
					'sum_total' => $entry['sum_total'],
					'created_at' => now(),
					'updated_at' => now(),
				]);
			}
		}

		return response()->json(['message' => 'Data processed successfully'], 200);
	}

    public function getDetail($id, Request $request)
    {

		$data = Transaction::with(['receiver','sender','user','submitByA','submitByB','transactionDetail','transactionDetail.item','transactionDetail.item.group'])->where('id',$id)->first();


		$fileName = 'invoice_' . $data->id . '.pdf';

		$path = env('INVOICE_PATH', '/home/u343060430/domains/invoice.corenationactive.com/');
		$filePath = $path . $fileName;
		$pdfFile = 0;
		if (File::exists($filePath)) {
			$pdfFile = 1; // Membuat direktori dengan izin 755
		}
			


		$dataSubmit = [
			'by_a' => $data->submitByA->username ?? null,
			'by_b' =>  $data->submitByB->username ?? null,
		];

		$notNullCount = count(array_filter($dataSubmit, function ($value) {
			return !is_null($value);
		}));
		
		$submitBy = implode(', ', array_filter([$dataSubmit['by_a'] ?? null, $dataSubmit['by_b'] ?? null]));

		$cekJubelio = 0;
		$countA = 0;
		$countB = 0;

		if($data->a_submit_by){
			$countA = 1;
		}

		if($data->b_submit_by){
			$countB = 1;
		}

		$countAll = $countA+$countB;

		$limitShow = 1;

		if($data->type == Transaction::TYPE_SELL || $data->type == Transaction::TYPE_RETURN_SUPPLIER){

			$cekJubelio = Jubeliosync::where('warehouse_id',$data->sender_id)->count();


		}else if($data->type == Transaction::TYPE_BUY || $data->type == Transaction::TYPE_RETURN){

			$cekJubelio = Jubeliosync::where('warehouse_id',$data->receiver_id)->count();

		}else if($data->type == Transaction::TYPE_MOVE){
			$cekJubelio = Jubeliosync::whereIn('warehouse_id', [$data->sender_id, $data->receiver_id])->count();

			$sjbA = Jubeliosync::with('warehouse')->where('warehouse_id',$data->sender_id)->exists();
			$sjbB = Jubeliosync::with('warehouse')->where('warehouse_id',$data->receiver_id)->exists();

			// dd($sjbA,$sjbB);

			if($sjbA && $sjbB){

				$limitShow = 2;
			}


		}

		$sortBy = request('kolom', 'id');   // default code
		$sortType = request('order', 'desc'); // default asc
		$transactionId = $data->id;

		$nameWh = StockManagerHelpers::$names;

		 $details = TransactionDetail::query()
            ->join('items', 'items.id', '=', 'transaction_details.item_id')
            ->with('item')
            ->when($transactionId, function($query) use ($transactionId) {
                $query->where('transaction_details.transaction_id', $transactionId);
            })
            ->orderBy('items.'.$sortBy, $sortType) // urutkan sesuai query string
            ->get();


		if($request->receipt == 1){

			return view('layouts.receipt',compact('data','nameWh','details'));
			

		}else{
			return view('transactions.detail',compact('details','data','nameWh','cekJubelio','countAll','limitShow','notNullCount','submitBy','pdfFile'));
		}

		

    }

	public function detailJubelioSync($id, Request $request)
    {

		$data = Transaction::with(['receiver','sender','user','submitByA','submitByB','transactionDetail','transactionDetail.item','transactionDetail.item.group'])->withCount([
			'transactionDetail as item_with_jubelio_count' => function ($query) {
				$query->whereHas('item', function ($q) {
					$q->where(function ($q) {
						$q->whereNull('jubelio_item_id')
						  ->orWhere('jubelio_item_id','<', 1);
					});
				});
			}
		])->where('id',$id)->first();		

		$JubelioA = [];
		$JubelioB = [];

		$adJustTypeA = 0;
		$adJustTypeB = 0;

		$whA = 0;
		$whB = 0;

		$whAName = '';
		$whBName = '';
		


		if($data->type == Transaction::TYPE_SELL || $data->type == Transaction::TYPE_RETURN_SUPPLIER){

			$adJustTypeA = 2;

			$dataJub = Jubeliosync::with('warehouse')->where('warehouse_id',$data->sender_id)->first();

			$JubelioA = $dataJub->warehouse->name ?? null;

			$whA = 2;

			$whAName = $data->sender->name;


			

		}else if($data->type == Transaction::TYPE_BUY || $data->type == Transaction::TYPE_RETURN){

			$adJustTypeA = 1;

			$dataJub = Jubeliosync::with('warehouse')->where('warehouse_id',$data->receiver_id)->first();

			$JubelioA = $dataJub->warehouse->name ?? null;

			$whA = 1;

			$whAName = $data->receiver->name;
			

		}else if($data->type == Transaction::TYPE_MOVE){
			
			$sjbA = Jubeliosync::with('warehouse')->where('warehouse_id',$data->sender_id)->first();
			$sjbB = Jubeliosync::with('warehouse')->where('warehouse_id',$data->receiver_id)->first();

			// dd($sjbA,$sjbB);

			if($sjbA && $sjbB){

				
				$whA = 2;
				$whB = 1;
				
				$adJustTypeA = 2;
				$adJustTypeB = 1;

				$JubelioA =  $sjbA->customer->name ?? null;
				$JubelioB = $sjbB->warehouse->name ?? null;

				$whAName = $data->sender->name;
				$whBName = $data->receiver->name;

			}else if($sjbA && is_null($sjbB)){
				$adJustTypeA = 2;

				$whA = 2;

				$JubelioA = $sjbA->customer->name ?? null;

				$whAName = $data->sender->name;

			}else if(is_null($sjbA) && $sjbB){

				$whA = 1;
				$adJustTypeA = 1;
				$JubelioA = $sjbB->warehouse->name ?? null;
				
				$whAName = $data->receiver->name;				
			}

		

		}

		

	

		return view('transactions.detail-jubelio-sync',compact('data','JubelioA','JubelioB','adJustTypeA','adJustTypeB','whA','whB','whAName','whBName'));

    }

	public function move()
    {
		$trType = 'move';

		$dataListPropRecaiver = [
			"label" => "Receiver",
			"id" => "recaiver",
			"idList" => "datalistRecaiver",
			"idOption" => "datalistOptionsRecaiver",
			"type" => Customer::TYPE_WAREHOUSE.",".Customer::TYPE_VWAREHOUSE,
			
		];

		$dataListPropSender = [
			"label" => "Sender",
			"id" => "sender",
			"idList" => "datalistSender",
			"idOption" => "datalistOptionsSender",
			"type" => Customer::TYPE_WAREHOUSE.",".Customer::TYPE_VWAREHOUSE,
			
		];

		
        return view('transactions.move',compact('trType', 'dataListPropRecaiver','dataListPropSender'));
    }

	public function moveBatch()
    {
		$trType = 'sell';

		$dataListPropRecaiver = [
			"label" => "Recaiver",
			"id" => "recaiver",
			"idList" => "datalistRecaiver",
			"idOption" => "datalistOptionsRecaiver",
			"type" => Customer::TYPE_WAREHOUSE.",".Customer::TYPE_VWAREHOUSE,
			
		];

		$dataListPropSender = [
			"label" => "Sender",
			"id" => "warehouse",
			"idList" => "datalistSender",
			"idOption" => "datalistOptionsSender",
			"type" => Customer::TYPE_WAREHOUSE.",".Customer::TYPE_VWAREHOUSE,
			
		];

		
		$bankList = Customer::where('type',Customer::TYPE_BANK)->orderBy('name','asc')->get();
        return view('transactions.move-batch',compact('bankList','trType','dataListPropRecaiver','dataListPropSender'));
    }

	public function postMove(Request $request)
	{

		try {

		$input = $request->query();
		$sender = Customer::find($request->sender);
		$receiver = Customer::find($request->recaiver);

	
		

		DB::beginTransaction();

		$transaction = new Transaction();
        $transaction->date = $request->date;
        $transaction->type = Transaction::TYPE_MOVE;
		$transaction->submit_type = 1;
        $transaction->invoice = $request->invoice ?? ' ';
     
		if($request->note){
			$transaction->description = $request->note;
		}else{
			$transaction->description = "";
		}

		$transaction->detail_ids = ' ';
		$transaction->due = '0000-00-00';
       

		$transaction->sender_id = $sender->id;
		$transaction->receiver_id = $receiver->id;

		 $transaction->save();

		//start transaction

		
	

		//gets the transaction id
		if(!$transaction->save())
			throw new ModelException($transaction->getErrors(), __LINE__);

		if(!$transaction->createDetails($request->addMoreInputFields))
			throw new ModelException($transaction->getErrors(), __LINE__);
        if(empty(trim($transaction->invoice)))
            $transaction->invoice = $transaction->id;
		//update the transaction
		if(!$transaction->save())
			throw new ModelException($transaction->getErrors(), __LINE__);

		//commit db transaction
		DB::commit();

		return response()->json(['status' => 'ok','message' => 'Data berhasil disimpan', 'trx' =>$transaction->id]);
		// return redirect()->route('transaction.getDetail',$transaction->id)->with('success', 'Transaction # ' . $transaction->id. ' created.');

		} catch(ModelException $e) {
			DB::rollBack();

			return response()->json(['status' => 'error', 'message' => $e->getErrors()['error'][0]], 200);

			// return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);

		} catch(\Exception $e) {
			DB::rollBack();

			return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);

			// return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());
		}
	}

	public function postMoveBatch(Request $request)
 	{
		try {
		//start transaction
		DB::beginTransaction();

		// dd($request);

		$customer = $request->recaiver;
		$warehouse = $request->warehouse;

		
		$transaction = new Transaction();

		$transaction->date = $request->date;
        $transaction->type = Transaction::TYPE_MOVE;
        $transaction->description = ' ';
		$transaction->detail_ids = ' ';
		$transaction->receiver_id = ' ';
		$transaction->due = '0000-00-00';
		$transaction->submit_type = 1;
        $transaction->save();

		$transaction->sender_id = $warehouse;
		$transaction->receiver_id = $customer;
		$transaction->init(TRANSACTION::TYPE_MOVE);

		//gets the transaction id
		if(!$transaction->save())
			throw new ModelException($transaction->getErrors(), __LINE__);

		if(!$details = $transaction->createDetails($request->addMoreInputFields))
			throw new ModelException($transaction->getErrors(), __LINE__);
        if(empty(trim($transaction->invoice)))
            $transaction->invoice = $transaction->id;
		//update the transaction
		if(!$transaction->save())
		throw new ModelException($transaction->getErrors(), __LINE__);



		//commit db transaction
		DB::commit();

        // $request->session()->flash('success', 'Transaction # ' . $transaction->id. ' created.');

		return redirect()->route('transaction.index',$transaction->id)->with('success', 'Transaction # ' . $transaction->id. ' created.');

		
		} catch(ModelException $e) {
			DB::rollBack();

			dd($e);

			return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);
            // return response()->json($e->getErrors(), 500);
		} catch(\Exception $e) {
			DB::rollBack();

			dd($e);

			return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());
		}
	}

	public function use()
    {
		$trType = 'use';

		$dataListPropSender = [
			"label" => "From Warehouse",
			"id" => "warehouse",
			"idList" => "datalistWh",
			"idOption" => "datalistOptionsWh",
			"type" => Customer::TYPE_WAREHOUSE,
			
		];
		
        return view('transactions.use',compact('trType','dataListPropSender'));
    }

	public function postUse(Request $request)
	{
		try {

		$warehouse = Customer::find($request->warehouse);
	

		//start transaction
		DB::beginTransaction();

		$transaction = new Transaction();
        $transaction->date = $request->date;
        $transaction->type = Transaction::TYPE_USE;
        $transaction->description = ' ';
		$transaction->detail_ids = ' ';
		$transaction->receiver_id = ' ';
		$transaction->due = '0000-00-00';
		$transaction->submit_type = 1;
        $transaction->save();

		$transaction->sender_id = $warehouse->id;


		//gets the transaction id
		if(!$transaction->save())
			throw new ModelException($transaction->getErrors(), __LINE__);

		if(!$transaction->createDetails($request->addMoreInputFields))
			throw new ModelException($transaction->getErrors(), __LINE__);

		//update the transaction
		if(!$transaction->save())
			throw new ModelException($transaction->getErrors(), __LINE__);

		//commit db transaction
		DB::commit();

		return redirect()->route('transaction.getDetail',$transaction->id)->with('success', 'Transaction # ' . $transaction->id. ' created.');

		} catch(ModelException $e) {
			DB::rollBack();

			return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);
		} catch(\Exception $e) {
			DB::rollBack();

			return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());
		}
	}


	public function cashIn()
    {
		$userSetting = UserSetting::where('user_id')->where('name','default_income_account')->first();

		$bankList = Customer::where('type',Customer::TYPE_BANK)->orderBy('name','asc')->get();

		if($userSetting){
			  $wh = Customer::find($userSetting->value);

			if($wh){
				$defaultParam = $userSetting->value;
			}else{
				$defaultParam = 2704;
			}
		}else{
			$defaultParam = 2704;
		}
		
        return view('transactions.ci',compact('bankList','defaultParam'));
    }

	public function postCashIn(Request $request)
	{
		return $this->create_payment(Transaction::TYPE_CASH_IN,$request);
	}

	
	public function cashOut()
    {
		$userSetting = UserSetting::where('user_id',Auth::id())->where('name','default_expense_account')->first();

		if($userSetting){
			$wh = Customer::find($userSetting->value);

			  	// dd($wh,$userSetting->value);

			if($wh){
				$defaultParam = $userSetting->value;
			}else{
				$defaultParam = 2704;
			}
		}else{
			$defaultParam = 2704;
		}

	
		$bankList = Customer::where('type',Customer::TYPE_BANK)->orderBy('name','asc')->get();
		
        return view('transactions.co',compact('bankList','defaultParam'));
    }

	public function postCashOut(Request $request)
	{
		return $this->create_payment(Transaction::TYPE_CASH_OUT,$request);
	}

	protected function create_payment($type = null, $request)
	{
		try {

		//start transaction
		DB::beginTransaction();
		$details = collect($request->addMoreInputFields);

		if(empty($details))
			throw new \Exception('cash in/out cannot be empty');

		$account = $request->account;
		if(empty($account))
			throw new \Exception('account cannot be empty');

		//init
		$bank = Customer::find($account);
		$date =  $request->date;
		$sm = new StatManagerHelper;
		$cc = new CCManagerHelper;
		// $date = Carbon::createFromFormat(DateHelper::$format,$inputDate)->startOfMonth()->toDateString();

		//get the customers
		// $ids = array();
		// foreach ($details as $value) {
		// 	if(isset($value['customer']))
		// 		$ids[] = $value['customer'];
		// }

		$ids = $details->pluck('customer')->toArray();

		
		$db = Customer::whereIn('id', $ids)->pluck('type', 'id');

	

		$urls = array();
		foreach($details as $c)
		{
			
			//skip invalid customers
			if(!isset($db[$c['customer']])) //impossible, but who knows
				continue;
			elseif($db[$c['customer']] == Customer::TYPE_WAREHOUSE || $db[$c['customer']] == Customer::TYPE_VWAREHOUSE)
				continue;

			$transaction = new Transaction();
			$transaction->date = $date;
			$transaction->submit_type = 1;
			$transaction->invoice = isset($c['invoice']) ? $c['invoice'] : null;
			$transaction->description = isset($c['description']) ? $c['description'] : '';
			$transaction->total = $c['total'];

			switch($type)
			{
				case Transaction::TYPE_CASH_OUT:
					$transaction->sender_id = $bank->id;
					$transaction->init($type, $c['customer']);

					//make negative
					if($transaction->total > 0)
						$transaction->total = 0 - $transaction->total;
					$sender_balance = $sm->deduct($transaction->sender_id,$transaction);
					if($sender_balance === false)
						throw new \Exception($sm->getErrors()->first());
					$receiver_balance = $sm->deduct($transaction->receiver_id,$transaction);
					if($receiver_balance === false)
						throw new \Exception($sm->getErrors()->first());

					//update monthly stats
					$cc->update(array(
						'date' => $date,
						'type' => Transaction::TYPE_CASH_OUT,
						'customer' => $transaction->receiver,
						'total' => $transaction->total,
					));
					break;
				case Transaction::TYPE_CASH_IN:
					$transaction->receiver_id = $bank->id;
					$transaction->init($type, $c['customer']);

					//make positive
					$transaction->total = abs($transaction->total);
					$sender_balance = $sm->add($transaction->sender_id,$transaction);
					if($sender_balance === false)
						throw new \Exception($sm->getErrors()->first());
					$receiver_balance = $sm->add($transaction->receiver_id,$transaction);
					if($receiver_balance === false)
						throw new \Exception($sm->getErrors()->first());
					

					//update monthly stats
					$cc->update(array(
						'date' => $date,
						'type' => Transaction::TYPE_CASH_IN,
						'customer' => $transaction->sender,
						'total' => $transaction->total,
					));
					break;
			}

			//update the balances
			$transaction->receiver_balance = $receiver_balance;
			$transaction->sender_balance = $sender_balance;

			//gets the transaction id
			if(!$transaction->save())
				throw new ModelException($transaction->getErrors());

			if(!$transaction->invoice)
			{
				$transaction->invoice = $transaction->id;
				$transaction->save();
			}

			//save urls for links
			$urls[] = '#'.$transaction->id;
		}

		DB::commit();

		return redirect()->route('transaction.index')->with('success','Transactions ' . implode(', ', $urls) . ' created.');

		} catch(ModelException $e) {
			DB::rollBack();

			return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);
		} catch(\Exception $e) {

			dd($e);
			DB::rollBack();

			return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());
		}
	}

	public function adjust()
    {
      return view('transactions.adjust');
    }

	public function postAdjust(Request $request)
	{
		try {

		//start transaction
		DB::beginTransaction();

		$sender = $request->sender;
		$receiver = $request->receiver;

		// dd($sender, $receiver);

		if(empty($sender))
			throw new \Exception('sender (-) cannot be empty');

		if(empty($receiver))
			throw new \Exception('receiver (+) cannot be empty');

		//init
	
		$sm = new StatManagerHelper;
		$cc = new CCManagerHelper;

		$date = $request->date;

		//get the customers
		$customerTypes = Customer::whereIn('id', array($sender, $receiver))->pluck('type', 'id');

		// dd($customerTypes[$receiver]);

		//adjust only allows account to customer/reseller/bank or vice versa
		if(in_array($customerTypes[$receiver], Customer::$notAdjustable) || in_array($customerTypes[$sender], Customer::$notAdjustable))
			throw new \Exception('cannot adjust warehouse or virtual accounts');
		//if none of them are account
		if($customerTypes[$receiver] != Customer::TYPE_ACCOUNT && $customerTypes[$sender] != Customer::TYPE_ACCOUNT)
			throw new \Exception('adjust requires at least 1 journal account');
		//if both are account
		if($customerTypes[$receiver] == Customer::TYPE_ACCOUNT && $customerTypes[$sender] == Customer::TYPE_ACCOUNT)
			throw new \Exception('cannot adjust 2 journal accounts');

		$transaction = new Transaction();
		$transaction->date = $request->date;
		$transaction->submit_type = 1;
		$transaction->type = Transaction::TYPE_ADJUST;
		
		if($request->description){
			$transaction->description = $request->description;
		}else{
			$transaction->description = "";
		}

		$transaction->detail_ids = ' ';
		
		$transaction->sender_id = $sender;
		$transaction->receiver_id = $receiver;
		$transaction->total = $request->total;
		
		$transaction->due = '0000-00-00';
		$transaction->save();
	
		$transaction->total = abs($transaction->total);

		//make negative - for stats
		if($customerTypes[$sender] == Customer::TYPE_ACCOUNT)
			$transaction->total = 0 - $transaction->total;

		$sender_balance = $sm->deduct($transaction->sender_id,$transaction,true);
		$receiver_balance = $sm->add($transaction->receiver_id,$transaction,true);
		if($receiver_balance === false || $sender_balance === false)
			throw new \Exception('error saving statistics');

		//update the balances
		$transaction->receiver_balance = $receiver_balance;
		$transaction->sender_balance = $sender_balance;

		//gets the transaction id
		if(!$transaction->save())
			throw new ModelException($transaction->getErrors());

		if(!$transaction->invoice)
		{
			$transaction->invoice = $transaction->id;
			$transaction->save();
		}

		//update monthly stats
		$cc->update(array(
			'date' => $date,
			'type' => Transaction::TYPE_ADJUST,
			'customer' => $transaction->receiver,
			'total' => $transaction->total,
		));

		$cc->update(array(
			'date' => $date,
			'type' => Transaction::TYPE_ADJUST,
			'customer' => $transaction->sender,
			'total' => 0 - $transaction->total,
		));

		DB::commit();

		return redirect()->route('transaction.getDetail',$transaction->id)->with('success', 'Adjust # ' . $transaction->id. ' created.');

		} catch(ModelException $e) {
			DB::rollBack();

			return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);

		} catch(\Exception $e) {
			DB::rollBack();

			return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());
		}

	}

	public function transfer()
  {
		$bankList = Customer::whereIn('type',[Customer::TYPE_BANK,Customer::TYPE_VACCOUNT])->orderBy('name','asc')->get();
      	return view('transactions.transfer',compact('bankList'));
  }

	public function postTransfer(Request $request)
	{
		try
		{

		$sender = $request->sender;
		$receiver = $request->receiver;

		if(empty($sender))
			throw new \Exception('sender (-) cannot be empty');

		if(empty($receiver))
			throw new \Exception('receiver (+) cannot be empty');

		DB::beginTransaction();

		$transaction = new Transaction();
		$transaction->date = $request->date;
		$transaction->submit_type = 1;
		$transaction->type = Transaction::TYPE_TRANSFER;
		if($request->description){
			$transaction->description = $request->description;
		}else{
			$transaction->description = '';
		}
		$transaction->detail_ids = ' ';
		
		$transaction->sender_id = $sender;
		$transaction->receiver_id = $receiver;
		$transaction->total = $request->total;
		
		$transaction->due = '0000-00-00';
		$transaction->save();

		$transaction->total = abs($transaction->total);

		if(!$senderData = Customer::find($sender))
			throw new \Exception('Sender does not exist');

		if(!$receiverData = Customer::find($receiver))
			throw new \Exception('Receiver does not exist');

		$transaction->sender_type = $senderData->type;
		$transaction->receiver_type = $receiverData->type;

		// if(!$transaction->validate())
		// 	throw new ModelException($transaction->getErrors());

		//add to receiver
		$sm = new StatManagerHelper;
		$receiver_balance = $sm->add($transaction->receiver_id,$transaction, true);
		if($receiver_balance === false)
			throw new ModelException($sm->getErrors()->first());

		//deduct from sender
		$sender_balance = $sm->deduct($transaction->sender_id,$transaction, true);
		if($sender_balance === false)
			throw new ModelException($sm->getErrors()->first());


		// dd($receiver_balance, $sender_balance);

		$transaction->sender_balance = $sender_balance;
		$transaction->receiver_balance = $receiver_balance;

		if(!$transaction->save())
			throw new ModelException($transaction->getErrors());

		if(!$transaction->invoice)
		{
			$transaction->invoice = $transaction->id;
			$transaction->save();
		}

		$date = $request->date;
		$cc = new CCManagerHelper;
		$cc->update(array(
			'date' => $date,
			'type' => Transaction::TYPE_TRANSFER,
			'customer' => $transaction->sender,
			'total' => 0 - $transaction->total,
		));
		$cc->update(array(
			'date' => $date,
			'type' => Transaction::TYPE_TRANSFER,
			'customer' => $transaction->receiver,
			'total' => $transaction->total,
		));

		//commit db transaction
		DB::commit();

		return redirect()->route('transaction.getDetail',$transaction->id)->with('success', 'Transfer # ' . $transaction->id. ' created.');

		} catch(ModelException $e) {
			DB::rollBack();

			return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);
		} catch(\Exception $e) {
			DB::rollBack();

			return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());
		}
	}

	public function return()
    {
		$trType = 'buy';

		$dataListPropSender = [
			"label" => "Sender",
			"id" => "customer",
			"idList" => "datalistSender",
			"idOption" => "datalistOptionsSender",
			"type" => Customer::TYPE_CUSTOMER.",".Customer::TYPE_RESELLER,
			
		];

		$dataListPropRecaiver = [
			"label" => "Warehouse",
			"id" => "warehouse",
			"idList" => "datalistWh",
			"idOption" => "datalistOptionsWh",
			"type" => Customer::TYPE_WAREHOUSE,
		];
        return view('transactions.return',compact('trType','dataListPropRecaiver','dataListPropSender'));
    }

	public function postReturn(Request $request)
	{
		return $this->createTransaction(Transaction::TYPE_RETURN, $request);
	}

	public function returnSupplier()
    {
		$trType = 'buy';


		$dataListPropResaller = [
			"label" => "Resaller",
			"id" => "customer",
			"idList" => "datalistResaller",
			"idOption" => "datalistOptionsResaller",
			"type" => Customer::TYPE_SUPPLIER,
			
		];

		$dataListPropRecaiver = [
			"label" => "Warehouse",
			"id" => "warehouse",
			"idList" => "datalistWh",
			"idOption" => "datalistOptionsWh",
			"type" => Customer::TYPE_WAREHOUSE,
			"default" => Customer::$defaultWH,
		];
        return view('transactions.return-supplier',compact('trType','dataListPropRecaiver','dataListPropResaller'));
    }

	public function postReturnSupplier(Request $request)
	{
		return $this->createTransaction(Transaction::TYPE_RETURN_SUPPLIER, $request);
	}

	public function postDelete($id)
	{
		$t = Transaction::where('id','=',$id)->first();

		if(!$t){
			return App::abort(404);
		}
		
		DB::beginTransaction();

		// Query
		$result = DB::table('transaction_details')
		->where('transaction_details.transaction_id',$id)
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

		$this->deleteStatSales($insertData);


		$deleter = new DeleterHelper;
		$deleted = $deleter->delete($t);
		

		
		DB::commit();

		return redirect()->route('transaction.getDetailDelete',$id)->with('success', 'Transaction # ' . $deleted->id. ' deleted.');
	}

	public function deleteStatSales(array $data)
	{
		foreach ($data as $entry) {
			$existing = DB::table('stat_sells')
				->where('group_id', $entry['group_id'])
				->where('bulan', $entry['bulan'])
				->where('tahun', $entry['tahun'])
				->where('sender_id', $entry['sender_id'])
				->first();

			if ($existing) {
				// Jika data ditemukan, update sum_qty dan sum_total
				DB::table('stat_sells')
					->where('id', $existing->id)
					->decrementEach([
						'sum_qty' => $entry['sum_qty'],
						'sum_total' => $entry['sum_total']
					]);
			}
		}

		return response()->json(['message' => 'Data processed successfully'], 200);
	}

	public function jubelioReturn($id){

		$data = Transaction::with(['receiver','sender','user','transactionDetail','transactionDetail.item','transactionDetail.item.group'])->where('id',$id)->first();

		return view('transactions.jubelio_return',compact('data'));
	}

	public function jubelioReturnPost($id, Request $request){

		$transactionData = Transaction::with(['receiver','sender','user','transactionDetail','transactionDetail.item','transactionDetail.item.group'])->where('id',$id)->first();

		
		$item = TransactionDetail::with('item')->where('transaction_id',$id)->whereIn('item_id', $request->return_item)->get();

	


		$moreItem = [];
		

		foreach ($item as $data) {
			$moreItem[] = [
				'itemId'   => $data->item_id,
				'code'     => $data->item->code,
				'name'     => $data->item->name,
				'quantity' => $data->quantity,
				'price'    => $data->price,
				'discount' => 0,
				'subtotal' => $data->quantity*$data->price,
			];
		}

		$dataJubelio = [
			"date" => Carbon::now()->toDateString(),
			"due" => null,
			"warehouse" => $transactionData->sender_id,
			"customer" => $transactionData->receiver_id,
			"invoice" => $transactionData->invoice,
			"note" => " ",
			"account" => "7204",
			"amount" => null,
			"paid" => null,
			"addMoreInputFields" => $moreItem,
			"disc" => "0",
			"adjustment" =>  $request->adjustment,
			"ongkir" => "0"
		];

		$dataCollect =  (object) $dataJubelio;

		

		$transactionData->jubelio_return = 2;

		$transactionData->save();
		
		$this->createTransaction(Transaction::TYPE_RETURN, $dataCollect);

		return redirect()->route('transaction.index')->with('success', 'Return created.');


	}

	private function checkAndDeleteFile($filePath)
	{
		if (file_exists($filePath)) {
			unlink($filePath); // Hapus file lama
		}
	}

	public function generateInvoice($id)
	{
		$invoice = Transaction::with(['receiver','sender','user','transactionDetail','transactionDetail.item','transactionDetail.item.group'])->where('id',$id)->first();

		$typeInvoice = Transaction::$types[$invoice->type];


		$folderPath = public_path('invoices');
		if (!file_exists($folderPath)) {
			mkdir($folderPath, 0777, true);
		}
	
		// Nama file statis berdasarkan ID
		$fileName = 'invoice_' . $invoice->id . '.pdf';
		// $fullPath = $folderPath . '/' . $fileName;
		// $url = asset('invoices/' . $fileName);

		$path = env('INVOICE_PATH', '/laragon/www/core-nation/public/asset/inv/');
		$filePath = $path . $fileName;

		// Buat direktori jika belum ada
		if (!File::exists($path)) {
			File::makeDirectory($path, 0755, true); // Membuat direktori dengan izin 755
		}

		if (File::exists($filePath)) {
			unlink($filePath); // Membuat direktori dengan izin 755
		}

		$view = view('pdf.invoice', compact('invoice','typeInvoice'))->render();

		// preg_match('/name="docHeight" value="(\d+)"/', $view, $matches);
		// $docHeight = isset($matches[1]) ? (int)$matches[1] : 842; // default A4 height kalau gagal

		$pdf = Pdf::loadView('pdf.invoice', compact('invoice','typeInvoice'))->setPaper('A4','portrait')
		->setOptions([
			'isHtml5ParserEnabled' => true,
			'isRemoteEnabled' => true,
			'isPhpEnabled' => true,
		]);
		$pdf->save($filePath);
		
		// // Kirim ke WhatsApp
		// $this->sendToWhatsapp($invoice->phone, $url);

		return redirect()->route('transaction.getDetail',$invoice->id)->with('success', 'Transaction # ' . $invoice->id. ' PDF created.');
	}

	public function generateReceipt($id)
	{
		$data = Transaction::with(['receiver','sender','user','transactionDetail','transactionDetail.item','transactionDetail.item.group'])->where('id',$id)->first();


		$folderPath = public_path('receipts');
		if (!file_exists($folderPath)) {
			mkdir($folderPath, 0777, true);
		}
	
		// Nama file statis berdasarkan ID
		$fileName = 'receipt_' . $data->id . '.pdf';
		// $fullPath = $folderPath . '/' . $fileName;
		// $url = asset('invoices/' . $fileName);

		$path = env('INVOICE_PATH', '/laragon/www/core-nation/public/asset/inv/');
		$filePath = $path . $fileName;

		// Buat direktori jika belum ada
		if (!File::exists($path)) {
			File::makeDirectory($path, 0755, true); // Membuat direktori dengan izin 755
		}

		if (File::exists($filePath)) {
			unlink($filePath); // Membuat direktori dengan izin 755
		}

		$view = view('pdf.receipt', compact('data'))->render();

		// preg_match('/name="docHeight" value="(\d+)"/', $view, $matches);
		// $docHeight = isset($matches[1]) ? (int)$matches[1] : 842; // default A4 height kalau gagal

		$pdf = Pdf::loadView('pdf.receipt', compact('data'))->setPaper('A4','portrait')
		->setOptions([
			'isHtml5ParserEnabled' => true,
			'isRemoteEnabled' => true,
			'isPhpEnabled' => true,
		]);
		$pdf->save($filePath);
		
		// // Kirim ke WhatsApp
		// $this->sendToWhatsapp($invoice->phone, $url);

		$url = env('INVOICE_URL', 'https://invoice.corenationactive.com/');
		
		$fileUrl = $url.$fileName;

		return redirect($fileUrl);
	}

	public function sendToWhatsapp($id,Request $request)
	{

		$fileName = 'invoice_' . $id . '.pdf';

		$url = env('INVOICE_URL', 'https://invoice.corenationactive.com/');
		
		$fileUrl = $url.$fileName;

		$message = urlencode("Terimakasih telah belanja di CoreNation! Berikut invoice anda:\n\n$fileUrl");
		$waLink = "https://wa.me/" . $request->wa . "?text=" . $message;

		return redirect()->away($waLink);
	}

	public function warning($id)
    {
	
		$tid = $id;
		
     	return view('transactions.warning',compact('tid'));
    }

	public function warningKonfirmasi(Request $request, $id)
	{

		   $trans = Transaction::with(['receiver','sender','user','transactionDetail','transactionDetail.item','transactionDetail.item.group'])->where('id', $id)->first();

		   if ($request->side == 1) {
				$trans->a_submit_by = Auth::user()->id;
				
			} elseif ($request->side == 2) {
				$trans->b_submit_by = Auth::user()->id;
			
			}
        
            $trans->save();

			return redirect()->route('transaction.detailJubelioSync', $id)->with('success', 'Jubelio adjustment confirmed');

	}

	public function clearWarning(Request $request, $id)
	{

		   $trans = Transaction::with(['receiver','sender','user','transactionDetail','transactionDetail.item','transactionDetail.item.group'])->where('id', $id)->first();

		   if ($request->side == 1) {
				$trans->submit_a_count = 0;
				
			} elseif ($request->side == 2) {
				$trans->submit_b_count = 0;
			
			}
        
            $trans->save();

			return redirect()->route('transaction.detailJubelioSync', $id)->with('success', 'Jubelio adjustment warning cleared');

	}

	public function editNote($id){

		$data = Transaction::find($id);

		return view('transactions.edit-desc',compact('data'));

	}

	
    public function updateNote(Request $request,$id){

    
        try{
            DB::beginTransaction();

			$data = Transaction::find($id);

			$data->description = $request->note ?? " ";

			$data->save();

            DB::commit();


            return redirect()->route('transaction.getDetail',$id)->with('success',  'Note updated');

        

        } catch(ModelException $e) {
            DB::rollBack();
			return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);
        } catch(\Exception $e) {
            DB::rollBack();
			return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());
        }
    }

	public function transactionSync(Request $request){

		$types = [
			Transaction::TYPE_SELL => 'SELL',
			Transaction::TYPE_RETURN_SUPPLIER => 'RETURN SUPLIER',
			Transaction::TYPE_BUY => 'BUY',
			Transaction::TYPE_RETURN => 'RETURN',
			Transaction::TYPE_MOVE => 'MOVE',
		];

		$transactions = Transaction::with(['sender', 'receiver'])
			->where('submit_type', 1);

		if($request->display){
			$transactions = $transactions->where('sync_hide',$request->display);
		}else{
			$transactions = $transactions->where('sync_hide','N');
		}


		if($request->date){
			$transactions = $transactions->whereDate('date','=',$request->date);
		}

		if($request->invoice){
			$transactions = $transactions->where('invoice','=',$request->invoice);
		}
		
		if($request->type){
			$transactions =	$transactions->where('type',$request->type);
		}

		// if($request->sender == 'success'){
		// 	$transactions =	$transactions->whereNotNull('a_submit_by');
		// }else{
		// 	$transactions =	$transactions->whereNull('a_submit_by');
		// }

		// if($request->receiver == 'success'){
		// 	$transactions =	$transactions->whereNotNull('b_submit_by');
		// }else{
		// 	$transactions =	$transactions->whereNull('b_submit_by');
		// }

		if(!$request->invoice){
			$transactions =	$transactions->where(function ($query) {
				$query
					// TYPE_SELL atau TYPE_RETURN_SUPPLIER → cocokkan dengan sender_id di warehouse_id
				->where(function ($q) {
					$q->whereIn('type', [
						Transaction::TYPE_SELL,
						Transaction::TYPE_RETURN_SUPPLIER
					])
					->whereNull('a_submit_by')
					->whereIn('sender_id', function ($sub) {
						$sub->select('warehouse_id')->from('jubeliosyncs');
					});
				})

				// TYPE_BUY atau TYPE_RETURN → cocokkan dengan receiver_id di warehouse_id
				->orWhere(function ($q) {
					$q->whereIn('type', [
						Transaction::TYPE_BUY,
						Transaction::TYPE_RETURN
					])
					->whereNull('b_submit_by')
					->whereIn('receiver_id', function ($sub) {
						$sub->select('warehouse_id')->from('jubeliosyncs');
					});
				})
				->orWhere(function ($q) {
					$q->where('type', Transaction::TYPE_MOVE)
					->where(function ($qq) {
						$qq->where(function ($w) {
							$w->whereIn('sender_id', function ($sub) {
								$sub->select('warehouse_id')->from('jubeliosyncs');
							})
							->whereNull('a_submit_by');
						})
						->orWhere(function ($w) {
							$w->whereIn('receiver_id', function ($sub) {
								$sub->select('warehouse_id')->from('jubeliosyncs');
							})
							->whereNull('b_submit_by');
						});
					});
				});

			/*
				// TYPE_MOVE → cocokkan sender_id atau receiver_id di warehouse_id
				->orWhere(function ($q) {
					$q->where('type', Transaction::TYPE_MOVE)
					->where(function ($qq) {
						$qq->whereIn('sender_id', function ($sub) {
							$sub->select('warehouse_id')->from('jubeliosyncs');
						})->orWhereIn('receiver_id', function ($sub) {
							$sub->select('warehouse_id')->from('jubeliosyncs');
						});
					});
				});
			*/
			});
		}
		// dd($transactions->toSql(), $transactions->getBindings());

		$transactions = $transactions->orderBy('id', 'desc')->paginate(200);

		// dd($transactions);
        
		

		return view('transactions.sync',compact('transactions','types'));

	}

	public function transactionSyncDisplay(Request $request, $id){

		$transactions = Transaction::find($id);

		if($transactions->sync_hide == 'N'){
			$transactions->sync_hide = 'Y';
		}else{
			$transactions->sync_hide = 'N';
		} 

		$transactions->save();


		 return redirect()->route('transaction.transactionSync')->with('success',  'Updated');
	}



	
	
}
