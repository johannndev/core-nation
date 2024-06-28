<?php

namespace App\Http\Controllers;

use App\Helpers\DateHelper;
use App\Models\Produksi;
use App\Models\Tag;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exceptions\ModelException;
use Carbon\Carbon;

class ProduksiController extends Controller
{
    public function index(Request $request, $id = false)
    {
		$jahitList = Worker::jahit()->get();
		$potongList = Worker::potong()->get();
        $statusList = Produksi::$statusJSON;

        $from = $request->from;
		$to = $request->to;

        $defaultStatus = Produksi::STATUS_PRODUKSI;

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

		$produksi = $query->orderBy('id','desc')->paginate(30)->withQueryString();

		

        return view('produksi.index',compact('produksi','jahitList','potongList','statusList'));
    }

	public function detail(Request $request, $id)
	{

		$data = Produksi::findOrFail($id);

		$jahitList = Worker::jahit()->get();

		return view('produksi.detail',compact('data','jahitList'));

	}

	public function create(){

		$sizeList = Tag::loadSizes();
		$potongList = Worker::potong()->get();
	
		return view('produksi.create',compact('sizeList','potongList'));
	}

	public function storeProduksi(Request $request)
	{
		// dd($request);

		$date = $request->date;
		
		$potong = $request->potong;
		$suratJalanPotong = $request->surat_jalan_potong;

		//start transaction
		DB::beginTransaction();

		$data = $request->addMoreInputFields;

		foreach($data as $d)
		{
			//skip empty code, quantity < 1
			if($d['qty'] < 1 || empty($d['name']))
					continue;

			$produksi = new Produksi();
			$produksi->temp_name = $d['name'];
			$produksi->size_id = $d['size'];
			$produksi->quantity = $d['qty'];
			$produksi->customer = $d['customer'];
			$produksi->warna = $d['warna'];
			$produksi->potong_id = $potong;
			$produksi->potong_date = $date;
			$produksi->warna = strtoupper($produksi->warna);
			$produksi->customer = strtoupper($produksi->customer);
			$produksi->surat_jalan_potong = $suratJalanPotong;

			if(!$produksi->save())
				throw new ModelException($produksi->getErrors(), __LINE__);
		}

		//commit db transaction
		DB::commit();

		return redirect()->route('produksi.index')->with('success', 'Produksi created.');

	}

	public function postSaveRow($id, Request $request)
	{
	
		$data = $request->jahitUpdate;

		//get the data from db
		$produksi = Produksi::findOrFail($id);


		if($data){

		
			$produksi->jahit_date = Carbon::now();
			
			$produksi->jahit_id = $data;

			$produksi->save();

		}

		return redirect()->route('produksi.index')->with('success', 'serial: '.$produksi->serial().' updated');

	
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

		return redirect()->route('produksi.detail',$id)->with('success', $p->serial().' edited');
	}

	public function postPisahJahit($id, Request $request)
	{
		$produksi = Produksi::findOrFail($id);

		$split_q = $request->split_q;
		if(!$split_q || empty($split_q)) {

			return redirect()->route('produksi.detail',$id)->with('error', 'Tidak ada yang dipisah.');

		}

		if($split_q >= $produksi->quantity) {
			return redirect()->route('produksi.detail',$id)->with('error', 'Jumlah produksi salah.');
			
		}

		DB::beginTransaction();

		//create new instance
		$new = $produksi->replicate();
		$new->quantity = $split_q;
		$new->jahit_id = 0;

		//save original id
		if($produksi->original_id)
			$new->original_id = $produksi->original_id;
		else {
			$new->original_id = $produksi->id;
			$produksi->original_id = $produksi->id;
		}

		$produksi->quantity = $produksi->quantity - $new->quantity;

		if(!$new->save()) {
			DB::rollBack();

			return redirect()->route('produksi.detail',$id)->with('error', 'error saving new produksi');
		}

		if(!$produksi->save()) {
			DB::rollBack();

			return redirect()->route('produksi.detail',$id)->with('error', 'error saving old produksi');

		}

		DB::commit();

		return redirect()->route('produksi.detail',$id)->with('success', $new->serial().' created.');

		
	}

	public function postGantiJahit($id, Request $request)
	{
		$produksi = Produksi::findOrFail($id);

		$jahit_id = $request->jahit_id;
		if(!$jahit_id || empty($jahit_id)) {
			
			return redirect()->route('produksi.detail',$id)->with('error', 'bukan penjahit 1.');
		}

		//check if valid penjahit
		$valid = Worker::where('type', '=', Worker::TYPE_JAHIT)->where('id', '=', $jahit_id)->first();
		if(!$valid) {

			return redirect()->route('produksi.detail',$id)->with('error', 'bukan penjahit 1.');
			
			
		}

		DB::beginTransaction();

		$produksi->jahit_id = $jahit_id;
		if(!$produksi->save()) {
			DB::rollBack();

			return redirect()->route('produksi.detail',$id)->with('error', 'error saving old produksi');
			
			
		}


		
		DB::commit();

		return redirect()->route('produksi.index')->with('success', 'produksi edited.');
	}

	public function postSetor($id)
	{

		
		try {
			DB::beginTransaction();
	
		$produksi = Produksi::find($id);
		$produksi->setor_date = Carbon::now()->toDateString();
		$produksi->status = Produksi::STATUS_SETOR;

		if(!$produksi->save())
			throw new ModelException($produksi->getErrors());

		DB::commit();

		return redirect()->route('produksi.index')->with('success','serial: '.$produksi->serial().' masuk setoran');
		
		} catch(ModelException $e) {
			DB::rollBack();
			return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);
		} catch(\Exception $e) {
			DB::rollBack();
			return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());
		}
	}


	public function getPotongList()
	{
		
		$dataList = Worker::potong()->withTrashed()->paginate();
		
		return view('produksi.potong',compact('dataList'));
	}

	public function getPotongCreate()
	{

		return view('produksi.potong-create');
	}

	public function createPotong(Request $request)
	{
		$input = $request->name;

		$w = new Worker;
		$w->name = trim($input);
		$w->type = Worker::TYPE_POTONG;
		if(!$w->save())
		{
			pre($w->getErrors());exit;
			return redirect()->back()->with('errorMessage',$w->getMessage());
		}

		return redirect()->route('produksi.getPotongList')->with('success', $w->name.' created');
	}

	public function getPotongEdit($id)
	{
		$data = Worker::findOrfail($id);

		return view('produksi.potong-edit',compact('data'));
	}

	public function updatePotong(Request $request,$id)
	{
		$input = $request->name;

		$w = Worker::findOrFail($id);
		$w->name = trim($input);
		$w->type = Worker::TYPE_POTONG;
		if(!$w->save())
		{
			pre($w->getErrors());exit;
			return redirect()->back()->with('errorMessage',$w->getMessage());
		}

		return redirect()->route('produksi.getPotongList')->with('success', $w->name.' created');
	}

	public function postDeletePotong($id)
	{
		$w = Worker::where('id', '=', $id)->where('type', '=', Worker::TYPE_POTONG)->first();
		
		if(!$w)
			return abort(404);
		$w->delete();

		return redirect()->route('produksi.getPotongList')->with('success', $w->name.' deleted');
	}

	public function getJahitList()
	{
		
		$dataList = Worker::jahit()->withTrashed()->paginate();
		
		return view('produksi.jahit',compact('dataList'));
	}

	public function getJahitCreate()
	{

		return view('produksi.jahit-create');
	}

	public function createJahit(Request $request)
	{
		$input = $request->name;

		$w = new Worker;
		$w->name = trim($input);
		$w->type = Worker::TYPE_JAHIT;
		if(!$w->save())
		{
			pre($w->getErrors());exit;
			return redirect()->back()->with('errorMessage',$w->getMessage());
		}

		return redirect()->route('produksi.getJahitList')->with('success', $w->name.' created');
	}

	public function getJahitEdit($id)
	{
		$data = Worker::findOrfail($id);

		return view('produksi.jahit-edit',compact('data'));
	}

	public function updateJahit(Request $request,$id)
	{
		$input = $request->name;

		$w = Worker::findOrFail($id);
		$w->name = trim($input);
		$w->type = Worker::TYPE_JAHIT;
		if(!$w->save())
		{
			pre($w->getErrors());exit;
			return redirect()->back()->with('errorMessage',$w->getMessage());
		}

		return redirect()->route('produksi.getJahitList')->with('success', $w->name.' created');
	}

	public function postDeleteJahit($id)
	{
		$w = Worker::where('id', '=', $id)->where('type', '=', Worker::TYPE_JAHIT)->first();
		
		if(!$w)
			return abort(404);
		$w->delete();

		return redirect()->route('produksi.getJahitList')->with('success', $w->name.' deleted');
	}




}
