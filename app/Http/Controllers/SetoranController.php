<?php

namespace App\Http\Controllers;

use App\Exceptions\ModelException;
use App\Helpers\ItemsManagerHelper;
use App\Models\Item;
use App\Models\Produksi;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetoranController extends Controller
{
	const WAREHOUSE = 2874;
	
    public function index(Request $request, $id = false)
    {
		$jahitList = Worker::jahit()->get();
		$potongList = Worker::potong()->get();
        $statusList = Produksi::$statusJSON;

        $itemList = Item::all();


		// dd($itemList);

        $from = $request->from;
		$to = $request->to;

        $defaultStatus = Produksi::STATUS_SETOR;

		//init the query
		$query = Produksi::with('item','potong','size','jahit');
		//dates are set!
		if($from && $to)
		{
			// $from = DateHelper::toSQL($from);
			// $to = DateHelper::toSQL($to);
			$query = $query->where(function($query) use($from,$to)
			{
				$query->where(function($query) use($from,$to)
				{
					$query->where('potong_date','>=',$from)->where('potong_date','<=',$to);
				});
				$query = $query->orWhere(function($query) use($from,$to)
				{
					$query->where('jahit_date','>=',$from)->where('jahit_date','<=',$to);
				});
			});
		}

		//if pid is set
		if($id && $id > 0)
			$query = $query->where('id','=',$id);

		//SEARCH
		if($request->potong_id)
			$query = $query->where('potong_id','=',$request->potong_id);
		if($request->jahit_id)
			$query = $query->where('jahit_id','=',$request->jahit_id);
		if($request->customer)
			$query = $query->where('customer','LIKE',"%$request->customer%");
		if($request->warna)
			$query = $query->where('warna','LIKE',"%$request->warna%");
		if($request->kode)
			$query = $query->where('temp_name','LIKE',"%$request->kode%");
		if($request->surat_jalan_potong)
			$query = $query->where('surat_jalan_potong','LIKE',"$request->surat_jalan_potong%");
		if($request->serial) {
            $serial = $request->serial;
			$query = $query->where(function($query) use($serial) {
				$query->where('id','=',Produksi::fromSerial($serial))->orWhere('original_id','=',Produksi::fromSerial($serial));
			});
		}

		
        if($defaultStatus == Produksi::STATUS_PRODUKSI)
            $query = $query->where('status', '=', $defaultStatus);
        else {
            if($status = $request->status) {
                if($status > 0)
                    $query = $query->where('status','=',$status);
                else
                    $query = $query->where('status', '!=', Produksi::STATUS_PRODUKSI);
            }
            else
                $query = $query->where('status', '!=', Produksi::STATUS_PRODUKSI);
        }



		if($request->invoice)
			$query = $query->where('invoice','=', $request->invoice);

		$produksi = $query->orderBy('id','desc')->paginate(30);


        $sg = Produksi::STATUS_GUDANG;
        $sb = Produksi::STATUS_BOTH;
		
		

        return view('setoran.index',compact('produksi','jahitList','potongList','statusList','sg','sb'));
    }

    public function postEditItem($id,Request $request)
	{
		
		try {
        DB::beginTransaction();
	

		//get the data from db
		$produksi = Produksi::with(array('item','potong','size','jahit'))->find($id);

		//save only the edited parts
		if(!$produksi)
			throw new ModelException('Setoran tidak ada');

		//also update siblings
		$siblingsIds = array();
		if(!empty($request->code)) {
			$produksi->item_id = $request->code;
			//check for item
			$item = Item::find($produksi->item_id);
			if(!$item)
				throw new ModelException('item salah');
			$produksi->temp_name = $item->name;

			//check for original
			if($produksi->original_id > 0) {
				//find siblings
				$siblings = Produksi::where('original_id','=',$produksi->original_id)->where('id', '!=', $produksi->id)->get();
				if(!empty($siblings)) {
					foreach ($siblings as $s) {
						//check, sudah turun, skip
						if(!empty($s->invoice)) continue;
						$s->item_id = $produksi->item_id;
						$s->temp_name = $produksi->temp_name;
						if(!$s->save())
							throw new ModelException($s->getErrors());
						$siblingsIds[] = Produksi::toSerial($s->id);
					}
				}
			}
		}

		if(!$produksi->save())
			throw new ModelException($produksi->getErrors());

		//response
		
		$msg = 'serial: '.$produksi->serial().' updated';
		
		if(count($siblingsIds) > 0)
			$msg .= ', '.implode(', ', $siblingsIds).' juga di update';

        DB::commit();

		return redirect()->route('setoran.index')->with('success',$msg);

		} catch(ModelException $e) {
			DB::rollBack();

           

            return redirect()->back()->with('errorMessage',$e->getErrors()[0]);
		} catch(\Exception $e) {
            DB::rollBack();

			return redirect()->back()->with('errorMessage',$e->getMessage());
		}
	}

    public function detail(Request $request, $id)
	{

		$data = Produksi::findOrFail($id);

		$jahitList = Worker::jahit()->get();

		return view('setoran.detail',compact('data','jahitList'));

	}

    public function postEdit($id, Request $request){

		DB::beginTransaction();

		$p = Produksi::findOrFail($id);
		$warna = $request->warna;
		$customer = $request->customer;
		$surat_jalan_potong = $request->surat_jalan_potong;

		if($p->original_id > 0) {
			$setorans = Produksi::where('original_id','=',$p->original_id)->get();
			foreach ($setorans as $s) {
				$s = Produksi::findOrFail($s->id);
				$s->warna = strtoupper($warna);
				$s->customer = strtoupper($customer);
				$s->surat_jalan_potong = $surat_jalan_potong;

				if(!$s->save()) {
					DB::rollBack();
					return redirect()->back()->withInput()->withErrors($s->getErrors());
				}
			}
		}
		else {
			$p->warna = strtoupper($warna);
			$p->customer = strtoupper($customer);
			$p->surat_jalan_potong = $surat_jalan_potong;
			if(!$p->save()) {
				DB::rollBack();
				return redirect()->back()->withInput()->withErrors($p->getErrors());
			}
		}

		DB::commit();

		return redirect()->route('setoran.detail',$id)->with('success', $p->serial().' edited');
	}

    public function postGantiJahit($id, Request $request)
	{
		$produksi = Produksi::findOrFail($id);

		$jahit_id = $request->jahit_id;
		if(!$jahit_id || empty($jahit_id)) {
			
			return redirect()->route('setoran.detail',$id)->with('error', 'bukan penjahit 1.');
		}

		//check if valid penjahit
		$valid = Worker::where('type', '=', Worker::TYPE_JAHIT)->where('id', '=', $jahit_id)->first();
		if(!$valid) {

			return redirect()->route('setoran.detail',$id)->with('error', 'bukan penjahit 1.');
			
			
		}

		DB::beginTransaction();

		$produksi->jahit_id = $jahit_id;
		if(!$produksi->save()) {
			DB::rollBack();

			return redirect()->route('setoran.detail',$id)->with('error', 'error saving old produksi');
			
			
		}


		
		DB::commit();

		return redirect()->route('setoran.index')->with('success', 'produksi edited.');
	}

    public function postEditStatus($id)
	{
		$p = Produksi::findOrFail($id);
		if($p->status != Produksi::STATUS_SETOR)
			return redirect()->back()->withInput()->with('error', 'Harus yang masih putih');

		$p->status = Produksi::STATUS_PRODUKSI;
		if(!$p->save())
			return redirect()->back()->withInput()->withErrors($p->getErrors());

		return redirect()->route('setoran.index')->with('success', $p->serial().' kembali ke Produksi');
	}

	public function postGudang($id, Request $request)
	{
		try {
		DB::beginTransaction();
		$produksiId = $id;
		$invoice = $request->invoice;

		if(!$invoice)
			throw new ModelException('Invoice tidak boleh kosong');

		//get the data from db
		$produksi = Produksi::with(array('item','potong','size','jahit'))->find($produksiId);

		//save only the edited parts
		if(!$produksi)
			throw new ModelException('Kitir tidak ada');

		if(empty($produksi->item_id))
			throw new ModelException('Belum ada item');

		if($produksi->transaction_id > 0)
			throw new \Exception('Sudah masuk invoice');

		if($produksi->detail_id > 0)
			throw new \Exception('Sudah ada di gudang');

		if($produksi->status == Produksi::STATUS_GUDANG)
			throw new \Exception('Error Processing Request');

		if(!empty($produksi->invoice))
			throw new \Exception('sudah ada invoice');

		//save to transaction
		if(!$transaction = Transaction::where('invoice','=',$invoice)->where('type','=',Transaction::TYPE_PRODUCTION)->first())
		{
			$transaction = new Transaction();
			$transaction->date = Carbon::now()->toDateString();
			$transaction->init(Transaction::TYPE_PRODUCTION);
			$transaction->receiver_id = self::WAREHOUSE; //gudang new
			$transaction->invoice = $invoice;
			$transaction->total_items = 0;
			$transaction->detail_ids = '';

			//gets the transaction id
			if(!$transaction->save())
				throw new ModelException($transaction->getErrors());
		}

		//save to transaction detail
		$detail = new TransactionDetail();
		$detail->transaction_id = $transaction->id;
		$detail->item_id = $produksi->item->id;
		$detail->quantity = $produksi->quantity;
		$detail->date = $transaction->date;
		$detail->transaction_type = Transaction::TYPE_PRODUCTION;
		$detail->sender_id = 0;
		$detail->receiver_id = self::WAREHOUSE;
		if(!$detail->save())
			throw new ModelException('Error saving transaction detail');

		//store transaction id
		$produksi->status = Produksi::STATUS_GUDANG;
		$produksi->transaction_id = $transaction->id;
		$produksi->detail_id = $detail->id;
		$produksi->invoice = $invoice;
		$produksi->gudang_date = Carbon::now()->toDateString();
		if(!$produksi->save())
			throw new ModelException($produksi->getErrors());

		//update transaction quantity
		$transaction->total_items += $produksi->quantity;
		$detail_ids = array_filter(explode(',', $transaction->detail_ids));
		$detail_ids[] = $detail->id;
		$transaction->detail_ids = trim(implode(',',$detail_ids));
		if(!$transaction->save())
			throw new ModelException($transaction->getErrors());

		//add to warehouse item
		ItemsManagerHelper::add($detail->receiver_id, $produksi->item, $produksi->quantity);

		//response

		DB::commit();

		return redirect()->route('transaction.getDetail',$transaction->id)->with('success', 'serial: '.$produksi->serial().' sudah masuk transaksi');

		
		} catch(ModelException $e) {
			DB::rollBack();

            return redirect()->back()->with('errorMessage',$e->getErrors()[0]);
		} catch(\Exception $e) {
			DB::rollBack();

			return redirect()->back()->with('errorMessage',$e->getMessage());
		}
	}
}
