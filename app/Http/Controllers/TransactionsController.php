<?php

namespace App\Http\Controllers;

use App\Exceptions\ModelException;
use App\Helpers\AppSettingsHelper;
use App\Helpers\CCManagerHelper;
use App\Helpers\HashManagerHelper;
use App\Helpers\InvoiceTrackerHelpers;
use App\Helpers\StatManagerHelper;
use App\Helpers\StockManagerHelpers;
use App\Helpers\TransactionsManagerHelper;
use App\Libraries\CCManager;
use App\Libraries\HashManager;
use App\Libraries\InvoiceTracker;

use App\Libraries\StatManager;
use App\Libraries\TransactionsManager;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Database\Eloquent\Builder;


class TransactionsController extends Controller
{
	public function index(Request $request)
	{
		$allType = Transaction::$typesJSON;

		$dataList = Transaction::with('receiver','sender')->orderBy('date','desc')->orderBy('id','desc');

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

		$dataList = $dataList->paginate(20)->withQueryString();

		return view('transactions.index',compact('dataList','allType'));
	}

    public function sell()
    {
		$itemnameList = Item::select('id','name')->get();

		// dd($itemnameList);
		
		$bankList = Customer::where('type',Customer::TYPE_BANK)->orderBy('name','asc')->get();
        return view('transactions.sell',compact('bankList','itemnameList'));
    }

    public function postSell(Request $request)
	{
		// dd($request);

		return $this->createTransaction(Transaction::TYPE_SELL, $request);
	}


	public function buy()
    {
		$itemnameList = Item::select('id','name')->get();

		$defaultCust = Customer::where('id','2875')->first();

		// dd($itemnameList);
		
		$bankList = Customer::where('type',Customer::TYPE_BANK)->orderBy('name','asc')->get();
        return view('transactions.buy',compact('bankList','itemnameList','defaultCust'));
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
        $transaction->description = ' ';
		$transaction->detail_ids = ' ';
		$transaction->due = '0000-00-00';
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
                    // throw new ModelException($sm->getErrors());

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

		//commit db transaction
		DB::commit();

        // $request->session()->flash('success', 'Transaction # ' . $transaction->id. ' created.');

		return redirect()->route('transaction.getDetail',$transaction->id);
		
        // return response()->json([
        //     'url' => route('transaction.getDetail',$transaction->id,$transaction->date),
        // ]);


		} catch(ModelException $e) {
			
            DB::rollBack();

			return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);
            // return response()->json($e->getErrors(), 500);
		
		} catch(\Exception $e) {
            DB::rollBack();

			dd($e);

			return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());

            // return response()->json($e->getMessage(), 500);
			
		}
	}

    public function getDetail($id)
    {

		$data = Transaction::with(['receiver','sender','user','transactionDetail','transactionDetail.item','transactionDetail.item.group'])->where('id',$id)->first();

		$nameWh = StockManagerHelpers::$names;

		

		return view('transactions.detail',compact('data','nameWh'));

    }

}
