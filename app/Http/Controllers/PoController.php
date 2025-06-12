<?php

namespace App\Http\Controllers;

use App\Exceptions\ModelException;
use App\Helpers\StockManagerHelpers;
use App\Models\Customer;
use App\Models\Po;
use App\Models\PoDetail;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\WarehouseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class PoController extends Controller
{
    public function index(Request $request)
	{
		

		$dataList = Po::with('customer')->withSum('transactionDetail', 'available_quantity')->orderBy('date','desc')->orderBy('id','desc');

		if($request->from && $request->to){
			$dataList = $dataList->whereDate('date','>=',$request->from)->whereDate('date','<=',$request->to);
		}

        if($request->customer){

            $user = $request->customer;

            $dataList = $dataList->whereHas('user', function (Builder $query) use($user) {
                $query->where('username', 'like', $user);
            });

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

		// dd($dataList);

		return view('transactions.po-index',compact('dataList'));
	}

    public function getDetail($id, Request $request)
    {

		$data = Po::with(['customer','user','transactionDetail','transactionDetail.item','transactionDetail.item.group'])->where('id',$id)->first();

        $detailC = PoDetail::where('transaction_id',$id)->where('status','=',1)->count();

        // dd($data);

		$nameWh = StockManagerHelpers::$names;

		if($request->receipt == 1){

			return view('layouts.receipt',compact('data','nameWh'));
			

		}else{
			return view('transactions.po-detail',compact('data','nameWh','detailC'));
		}

		

    }

    public function updateItemQty(Request $request, $id){

        //start transaction
        try {
            DB::beginTransaction();
            $item = PoDetail::findOrFail($id);

            $item->available_quantity = $request->qty;
            $item->status = 2;
           

            if(!$item->save())
                throw new ModelException($item->getErrors());

            $po = Po::findorFail($item->transaction_id);

            $po->status = 1;

            if(!$po->save())
                throw new ModelException($item->getErrors());

           

            DB::commit();

            return redirect()->route('transaction.Podetail',$item->transaction_id)->with('success', 'Item # ' . $item->id. ' updated.');

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

    public function updateItemKosong(Request $request, $id){

        
        //start transaction
        try {
            DB::beginTransaction();
            $item = PoDetail::findOrFail($id);

            $item->status = 3;
            
           

            if(!$item->save())
                throw new ModelException($item->getErrors());

            $po = Po::findorFail($item->transaction_id);

            $po->status = 1;

            if(!$po->save())
                throw new ModelException($item->getErrors());

           

            DB::commit();

            return redirect()->route('transaction.Podetail',$item->transaction_id)->with('success', 'Item # ' . $item->id. ' updated.');

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

    public function success($id){

        $po = Po::findorFail($id);

        $po->status = 2;

        $po->save();

        

        return redirect()->route('transaction.Podetail',$id)->with('success', 'Item # ' . $po->id. ' updated.');

    }

    public function delete(Request $request, $id){

        //start transaction
        try {
            DB::beginTransaction();

            $po = Po::findOrFail($id);

            $item = PoDetail::where('transaction_id',$id)->delete();

            $po->delete();

            DB::commit();


            return redirect()->route('transaction.Poindex')->with('success', 'Item #' . $id. ' deleted.');

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

    public function poMove($id, Request $request){

        $data = Po::with(['customer','user','transactionDetail','transactionDetail.item','transactionDetail.item.group'])->where('id',$id)->first();

		$dataListPropSender = [
			"label" => "Sender",
			"id" => "sender",
			"idList" => "datalistSender",
			"idOption" => "datalistOptionsSender",
			"type" => Customer::TYPE_WAREHOUSE.",".Customer::TYPE_VWAREHOUSE,
            "default" => $request->sender,
			
		];

        $dataListPropRecaiver = [
			"label" => "Receiver",
			"id" => "recaiver",
			"idList" => "datalistRecaiver",
			"idOption" => "datalistOptionsRecaiver",
			"type" => Customer::TYPE_WAREHOUSE.",".Customer::TYPE_VWAREHOUSE,
            "default" => $request->recaiver,
			
		];

        $whItem = [];
        $zeroCount = 0;

        if($request->sender){

            $item = $data->transactionDetail->pluck('item_id')->toArray();

            $whItem = WarehouseItem::where('warehouse_id',$request->sender)->whereIn('item_id',$item)->pluck('quantity','item_id')->toArray();

            $whItem = WarehouseItem::where('warehouse_id', $request->sender)
                ->whereIn('item_id', $item)
                ->pluck('quantity', 'item_id')
                ->toArray();

            // Isi item yang tidak ditemukan dengan 0
            foreach ($item as $id) {
                if (!isset($whItem[$id])) {
                    $whItem[$id] = 0;
                }
            }

            // Hitung jumlah item yang quantity-nya 0
            $zeroCount = collect($whItem)->filter(fn($qty) => $qty == 0)->count();

        }

    

        return view('transactions.po-move',compact('data','dataListPropSender','whItem','dataListPropRecaiver'));


    }

    public function updateQty($id, Request $request)
    {

        $details = $request->input('detail');

        // dd($details);
        

        try {
            DB::transaction(function () use ($details) {
                $ids = collect($details)->pluck('id')->unique()->toArray();

                // Ambil ID yang tersedia di database
                $existingIds = PoDetail::whereIn('id', $ids)->pluck('id')->toArray();

                // Cek jika ada ID yang tidak ditemukan
                $missing = array_diff($ids, $existingIds);
                if (!empty($missing)) {
                    throw new \Exception('Beberapa ID tidak ditemukan: ' . implode(', ', $missing));
                }

                // Lanjutkan upsert jika semua ID valid
                PoDetail::upsert(
                    $details,
                    ['id'],   // kolom untuk pencocokan (update berdasarkan id)
                    ['quantity']   // kolom yang akan diupdate
                );
            });

            return back()->with('success', 'Qty berhasil diupdate.');
        } catch (\Exception $e) {
            return back()->with('errorMessage', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function postMove(Request $request,$id)
	{
		try {

        $data = Po::with(['customer','user','transactionDetail','transactionDetail.item','transactionDetail.item.group'])->where('id',$id)->first();

		$input = $request->query();
		$sender = Customer::find($request->sender);
		$receiver = Customer::find($request->recaiver);

		DB::beginTransaction();

		$transaction = new Transaction();
        $transaction->date = $data->date;
        $transaction->type = Transaction::TYPE_MOVE;
		$transaction->submit_type = 1;
        $transaction->invoice = $data->invoice ?? ' ';
        $transaction->description = $data->description ?? '';
		

		$transaction->detail_ids = ' ';
		$transaction->due = '0000-00-00';
        $transaction->save();

		$transaction->sender_id = $sender->id;
		$transaction->receiver_id = $receiver->id;

		//start transaction

        $detail = [];

        foreach ($data->transactionDetail as $item) {
            $detail[] = [
                'itemId'   => $item->item_id,
                'code'     => $item->item->code,
                'name'     => $item->item->name,
                'quantity' => $item->quantity,
                'price'    => $item->price,
                'discount' => 0,
                'subtotal' => $item->quantity*$item->price,
            ];
        }
   
		

		//gets the transaction id
		if(!$transaction->save())
			throw new ModelException($transaction->getErrors(), __LINE__);

		if(!$transaction->createDetails($detail))
			throw new ModelException($transaction->getErrors(), __LINE__);
        if(empty(trim($transaction->invoice)))
            $transaction->invoice = $transaction->id;
		//update the transaction
		if(!$transaction->save())
			throw new ModelException($transaction->getErrors(), __LINE__);

        PoDetail::where('transaction_id',$id)->delete();
        $data->delete();
        
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

}
