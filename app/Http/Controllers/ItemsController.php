<?php

namespace App\Http\Controllers;

use App\Exceptions\ModelException;
use App\Helpers\ItemsManagerHelper;
use App\Helpers\LocalHelper;
use App\Libraries\ItemsManager;
use App\Models\Customer;
use App\Models\Item;
use App\Models\ItemGroup;
use App\Models\Tag;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\WarehouseItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ItemsController extends Controller
{
    public function index(Request $request)
    {

        $dataList = Item::with('group')->where('type',Item::TYPE_ITEM)->orderBy('id','desc');

        if($request->code){

            if(is_numeric($request->code)){
                $dataList = $dataList->where('id','=', $request->code);
            }else{
                $dataList = $dataList->where('code','LIKE',"%$request->code%");
            }

        }

        if($request->name) {
			$name = str_replace(' ', '%', $request->name);
			$dataList = $dataList->where('name','LIKE',"%$name%");
		}
		if($request->desc) {
			$desc = str_replace(' ', '%', $request->desc);
			$dataList = $dataList->whereHas('group', function($q) use($desc) {
				$q->where('description','LIKE',"%$desc%");
			});
		}
		if($request->alias) {
			$alias = str_replace(' ', '%', $request->alias);
			$dataList = $dataList->whereHas('group', function($q) use($alias) {
				$q->where('alias','LIKE',"%$alias%");
			});
		}


        $dataList = $dataList->paginate(20)->withQueryString();

		// dd($dataList);

        return view('items.index',compact('dataList'));
    }

	public function create()
	{
		$tags = ItemsManagerHelper::loadTagsJSON(Tag::$types);

		return view('items.create',compact('tags'));
	}

	public function postCreate(Request $request)
	{
		try {
		$input = $request;
		$tags = $request->tags;
		

		DB::beginTransaction();

		$itemManager = new ItemsManagerHelper;

		// dd($request->tags);

		if(!$itemManager->createItems($input, $tags, $request->file))
			throw new ModelException($itemManager->getErrors(), __LINE__);

		DB::commit();

		return redirect()->route('item.index')->with('success', 'Item(s) created.');

		

		} catch(ModelException $e) {
			DB::rollBack();

			return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);
		} catch(\Exception $e) {
			DB::rollBack();

			dd($e);

			return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());
		}
	}
	

	public function detail($id)
	{
		$data = Item::with('group','tags')->where('id',$id)->first();

		// dd($data);

		$urlImage = $data->item_image_path;

		

		$whList = WarehouseItem::with('warehouse')->whereHas('warehouse', function (Builder $query) {
			$query->where('customers.type','=',Customer::TYPE_WAREHOUSE);
		})->where('item_id',$id)->get();

		// dd($whList);

		$tid=$id;
		

		return view('items.detail',compact('data','urlImage','whList','tid'));
	}

	public function jubelio($id)
	{
		$data = Item::with('group','tags')->where('id',$id)->first();

	

		$urlImage = $data->item_image_path;

		

		$message = "";
		$dataJubelio = [];

		if($data->jubelio_item_id > 0){

			$body = [
				'ids' => [$data->jubelio_item_id],
			];

			$response = Http::withHeaders([ 
				'Content-Type'=> 'application/json', 
				'authorization'=> Cache::get('jubelio_data')['token'], 
			]) 
			->post('https://api2.jubelio.com/inventory/items/all-stocks/',$body); 
	
			$result = json_decode($response->body(), true);

			if (!isset($result['data']) || is_null($result['data'])) {
				$message = "Item tidak ada";
				// dd('Data is null or not set', $result);
			} else {
				$message = "ok";
				$dataJubelio = $result['data'][0];
				
			}

			
		}

		

		$tid=$id;
		

		return view('items.jubelio',compact('data','urlImage','tid','dataJubelio','message'));
	}

	public function edit($id)
	{
		$item = Item::with('group')->where('id',$id)->first();

		$tags = ItemsManagerHelper::loadTagsJSON(Tag::$types);

		// dd($tags);

		foreach($tags as $key => $value) {
			if($value['type_id'] == Tag::TYPE_SIZE || $value['type_id'] == Tag::TYPE_TYPE){
				unset($tags[$key]);
				// $tags[$key] = array();
			}

			
				
		}

		$array = explode(',', $item->tag_ids);

		$selected = array_flip($array);

		// array_key_exists($t->id,$selected)

		// dd($tags, $selected);


		return view('items.edit',compact('tags','selected','item'));
	}

	public function postEdit($id,Request $request)
	{
		try{

		$input = $request;
		$tags = $request->tags;

		DB::beginTransaction();

		$itemManager = new ItemsManagerHelper;

		if(!$item = $itemManager->updateItem($id, $input, $tags, $request->file))
			throw new ModelException($itemManager->getError(), __LINE__);

		DB::commit();

		return redirect()->route('item.detail',$id)->with('success', 'Item edited.');

		

		} catch(ModelException $e) {
			DB::rollBack();

			return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);
		} catch(\Exception $e) {
			
			DB::rollBack();

			dd($e);

			return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());
		}
	}

	public function transaction($id, Request $request)
	{
		$dataList = TransactionDetail::with('transaction','transaction.receiver','transaction.sender')->where('item_id',$id)->orderBy('date','desc')->orderBy('transaction_id','desc');

		if($request->addr){
			$default = $request->addr;
			$dataList = $dataList->whereAny(['sender_id','receiver_id'],$request->addr);
		}else{
			$default = null;
		}

		$dataListPropCustomer = [
			"label" => "Addr Book",
			"id" => "addr",
			"idList" => "datalistSender",
			"idOption" => "datalistOptionsSender",
			"type" => Customer::TYPE_CUSTOMER.",".Customer::TYPE_RESELLER.",".Customer::TYPE_WAREHOUSE,
			"default" => $default,
			
			
		];

		// dd($dataList);

		$dataList = $dataList->paginate(200)->withQueryString();

		$tid=$id;

		return view('items.transaction',compact('dataList','dataListPropCustomer','tid'));
	}

	public function stat($id, Request $request)
	{
		if($request->from){
			$from = $request->from;
		}else{
			$from = Carbon::now()->subMonths(11)->startOfMonth()->toDateString();
		}
	

		if($request->to){
			$to = $request->to;
		}else{
			$to = Carbon::now()->endOfMonth()->toDateString();
		}

		$data = TransactionDetail::select(array(
			"transaction_type",
			DB::raw("DATE_FORMAT(date,'%M %Y') AS showdate"),
			DB::raw("DATE_FORMAT(date,'%m') AS bulan"),
			DB::raw("DATE_FORMAT(date,'%Y') AS tahun"),
		))->where('item_id','=',$id)->whereIn('transaction_type',[Transaction::TYPE_SELL, Transaction::TYPE_MOVE, Transaction::TYPE_RETURN, Transaction::TYPE_PRODUCTION])->whereDate('date','>=',$from)->whereDate('date','<=',$to)->groupBy('showdate');

		if($request->addr){
			$default = $request->addr;
			$data = $data->whereAny(['sender_id','receiver_id'],$request->addr);
		}else{
			$default = null;
		}

		

		$dataListPropCustomer = [
			"label" => "Addr Book",
			"id" => "addr",
			"idList" => "datalistSender",
			"idOption" => "datalistOptionsSender",
			"type" => Customer::TYPE_CUSTOMER.",".Customer::TYPE_RESELLER.",".Customer::TYPE_WAREHOUSE,
			"default" => $default,
			
			
		];

		$data = $data->orderBy("date", 'DESC')->get();

		// dd($dataTotal,$data);

		// dd($from,$to, $data);

		return view('items.stats',[
			'dataListPropCustomer' => $dataListPropCustomer,
			'dataList' => $data, 
			'tid'=>$id,
			'sellCode' => Transaction::TYPE_SELL,
			'moveCode' => Transaction::TYPE_MOVE,
			'returnCode' => Transaction::TYPE_RETURN,
			'prodCode' => Transaction::TYPE_PRODUCTION,
		]);

		
	}
	

	public function group(Request $request)
	{
		$group = ItemGroup::with('items');

		if($request->kode) {
			$name = str_replace(' ', '%', $request->kode);
			$group = $group->where('name','LIKE',"%$request->kode%");
		}
		if($request->desc) {
			$desc = str_replace(' ', '%', $request->desc);
			$group = $group->where('description','LIKE',"%$request->desc%");
		}
		if($request->alias) {
			$alias = str_replace(' ', '%', $request->alias);
			$group = $group->where('alias','LIKE',"%$request->alias%");
		}

		$group = $group->orderBy('id','desc')->paginate(20)->withQueryString();

		return view('items.group',compact('group'));
	}

	public function groupDetail($id)
	{
		$data = ItemGroup::findOrFail($id);
		$items = Item::where('group_id','=',$data->id)->orderBy('variant','asc')->get();
		if(!$items) $items = array();
		$warehouse = array();
		foreach ($items as $i) {
			$warehouse[$i->id] = WarehouseItem::with('warehouse')->where('item_id','=',$i->id)->leftJoin('customers','customers.id','=','warehouse_item.warehouse_id')->where('customers.type','=',Customer::TYPE_WAREHOUSE)->where(function($query) {
				$query->where('customers.deleted_at','=',null)->orWhere('customers.deleted_at','=','0000-00-00 00:00:00');
			})->get();
			if(!$warehouse[$i->id]) $warehouse[$i->id] = array();
		}

		$urlImage = $data->getImageUrl();

		$tid = $id;


		return view('items.group-detail',compact('data','items','warehouse','urlImage','tid'));

	}

	public function groupStat($id, Request $request)
	{
		if($request->from){
			$from = $request->from;
		}else{
			$from = Carbon::now()->subMonths(11)->startOfMonth()->toDateString();
		}
	

		if($request->to){
			$to = $request->to;
		}else{
			$to = Carbon::now()->endOfMonth()->toDateString();
		}


		$dataTotal = TransactionDetail::select(array(
			"transaction_type",
			DB::raw("DATE_FORMAT(date,'%M %Y') AS showdate"),
			DB::raw("DATE_FORMAT(date,'%m') AS bulan"),
			DB::raw("DATE_FORMAT(date,'%Y') AS tahun"),
		));
		
		$dataTotal = $dataTotal->whereHas('item', function (Builder $query) use($id) {
			$query->where('group_id',$id);
		});

	

		$dataTotal = $dataTotal->whereIn('transaction_type',[Transaction::TYPE_SELL, Transaction::TYPE_MOVE, Transaction::TYPE_RETURN, Transaction::TYPE_PRODUCTION])->whereDate('date','>=',$from)->whereDate('date','<=',$to)->groupBy('showdate');

        if(Request('addr')){
			$dataTotal = $dataTotal->whereAny(['sender_id','receiver_id'],Request('addr'));
        }

		if($request->addr){
			$default = $request->addr;
			$dataTotal = $dataTotal->whereAny(['sender_id','receiver_id'],$request->addr);
		}else{
			$default = null;
		}

        $dataTotal = $dataTotal->orderBy("date", 'DESC')->get();

		// dd($dataTotal);
		
		$dataListPropCustomer = [
			"label" => "Addr Book",
			"id" => "addr",
			"idList" => "datalistSender",
			"idOption" => "datalistOptionsSender",
			"type" => Customer::TYPE_CUSTOMER.",".Customer::TYPE_RESELLER.",".Customer::TYPE_WAREHOUSE,
			"default" => $default,
			
			
		];

		return view('items.group-stats',[
			'dataListPropCustomer' => $dataListPropCustomer,
			'dataList' => $dataTotal, 
			'tid'=>$id,
			'sellCode' => Transaction::TYPE_SELL,
			'moveCode' => Transaction::TYPE_MOVE,
			'returnCode' => Transaction::TYPE_RETURN,
			'prodCode' => Transaction::TYPE_PRODUCTION,
			'typeStat' => 'grub'
		]);
	}
}
