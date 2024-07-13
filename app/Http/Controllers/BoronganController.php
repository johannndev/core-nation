<?php

namespace App\Http\Controllers;

use App\Exceptions\ModelException;
use App\Models\Borongan;
use App\Models\BoronganDetail;
use App\Models\Produksi;
use App\Models\Tag;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BoronganController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from;
		$to = $request->to;
        $jahitList = Worker::jahit()->get();

		$query = Borongan::with(array('jahit'));
		//dates are set!
		if($from && $to)
		{
			$query = $query->where('from','>=',$from)->where('to','<=',$to);
		}
		if($jahit_id = $request->jahit_id)
			$query = $query->where('jahit_id','=',$jahit_id);

		$dataList = $query->orderBy('id','DESC')->paginate(30);

        return view('borongan.index',compact('dataList','jahitList'));
    }

	public function create()
    {
		$from = Carbon::now()->subDay(7)->toDateString();
		$to = Carbon::now()->toDateString();

		

        $jahitList = Worker::jahit()->get();

        return view('borongan.create',compact('jahitList','from','to'));
    }

	public function detail($id){
		$borongan = Borongan::findOrFail($id);

		$detaiList = BoronganDetail::with('item')->where('borongan_id','=',$id)->get();

		return view('borongan.detail',compact('borongan','detaiList'));
	}

	protected function findBorongan($from, $to,$jahit_id)
	{
		$from = $from;
		$to = $to;
		if(!$from || !$to)
			throw new \Exception('Tanggal error', 1);

		if(!$jahit_id)
			throw new \Exception('Jahit tidak boleh kosong', 1);

		
		//find date
		$query = Produksi::with(array('item'))->where('gudang_date','>=',$from)->where('gudang_date','<=',$to)->where('status','=',Produksi::STATUS_GUDANG);
		//find jahit
		$query = $query->where('jahit_id','=',$jahit_id);
		//fail-safe: make sure item is set
		$data = $query->where('item_id','>',0)->get();
		$borongan = [];
		foreach ($data as $key => $val) {

			

			$borongan[$key] = $val->toArray();
			$borongan[$key]['serial'] = $val->serial();
			$borongan[$key]['edit_link'] = route('setoran.index',['id'=>$val->id]);

			$borongan[$key]['ongkos'] = $this->ongkos($val->item);
			$borongan[$key]['produksi_id'] = $val->id;
			$borongan[$key]['total'] = (float)bcmul($val->quantity,$borongan[$key]['ongkos']);

			
			if ($val->item_id > 0){

				$borongan[$key]['code'] = $val->item->getItemCode();
			}else{
				$borongan[$key]['code'] = $val->temp_name;
			}
		}
		return $borongan;
	}

	public function getAjaxBorongan(Request $request){
		$data = $this->findBorongan($request->from,$request->to,$request->jahit);

		// dd($data);

		return response()->json($data);
	}

	public function postAdd(Request $request)
	{
		try {
		DB::beginTransaction();
		$borongan = $this->findBorongan($request->from,$request->to,$request->jahit);
		//try saving
		$b = new Borongan;
		$b->date = Carbon::now()->toDateString();
		$b->user_id = Auth::user()->id;
		$b->permak = $request->permak;
		$b->tres =  $request->tres;
		$b->lain2 =  $request->lain2;
		$b->jahit_id =  $request->jahit;
		$b->total_items = 0;
		$b->total = bcadd($b->permak, bcadd($b->tres, $b->lain2));
		$from = $request->from;
		if(!$from)
			throw new \Exception('From date salah', 1);
		$b->from = $from;
		$to = $request->to;
		if(!$to)
			throw new \Exception('To date salah', 1);
		$b->to = $to;
		if(!$b->save())
			throw new ModelException($b->getErrors());

		foreach ($borongan as $value) {
			$detail = new BoronganDetail;
			$detail->borongan_id = $b->id;
			$detail->item_id = $value['item']['id'];
			$detail->ongkos = $value['ongkos'];
			$detail->quantity = $value['quantity'];
			$detail->produksi_id = $value['produksi_id'];
			$detail->total = $value['total'];
			if(!$detail->save())
				throw new ModelException($detail->getErrors());

			//update produksi status
			$produksi = Produksi::find($value['produksi_id']);
			$produksi->status = Produksi::STATUS_BOTH;
			if(!$produksi->save())
				throw new ModelException($produksi->getErrors());

			$b->total = bcadd($b->total, $value['total']);
			$b->total_items += $value['quantity'];
		}

		if(!$b->save())
			throw new ModelException($b->getErrors());

			DB::commit();

		return redirect()->route('borongan.index')->with('success', 'Borongan created.');


		} catch(ModelException $e) {
			DB::rollBack();

			return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);
		} catch(\Exception $e) {
			DB::rollBack();

			return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());
		}
	}

	protected function ongkos($item)
	{
		if(!$item)
			return 0;

		//crystalsports_items
		$ongkos = $item->tags()->where(Tag::table().'.type','=',Tag::TYPE_JAHIT)->first();
		if(!$ongkos)
			return 0;

		return $ongkos->price;
	}

   
}
