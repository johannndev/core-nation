<?php

namespace App\Http\Controllers;

use App\Exceptions\ModelException;
use App\Helpers\StockManagerHelpers;
use App\Models\Po;
use App\Models\PoDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PoController extends Controller
{
    public function index(Request $request)
	{
		

		$dataList = Po::with('customer')->withSum('transactionDetail', 'available_quantity')->orderBy('date','desc')->orderBy('id','desc');

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
}
