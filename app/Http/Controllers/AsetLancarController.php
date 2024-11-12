<?php

namespace App\Http\Controllers;

use App\Exceptions\ModelException;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\WarehouseItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\File;

class AsetLancarController extends Controller
{
    public function index(Request $request)
    {

        $dataList = Item::with('group')->where('type',Item::TYPE_ASSET_LANCAR)->orderBy('id','desc');

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

        return view('asset-lancar.index',compact('dataList'));
    }

    public function detail($id)
	{
		$data = Item::with('group','tags')->where('id',$id)->first();

		$urlImage = $data->getImageUrl();

		$whList = WarehouseItem::with('warehouse')->whereHas('warehouse', function (Builder $query) {
			$query->where('customers.type','=',Customer::TYPE_WAREHOUSE);
		})->where('item_id',$id)->get();

		// dd($whList);

		$tid=$id;

		return view('asset-lancar.detail',compact('data','urlImage','whList','tid'));
	}

    public function transaction($id, Request $request)
	{
		$dataList = TransactionDetail::with('transaction','transaction.receiver','transaction.sender')->where('item_id',$id)->orderBy('date','desc');

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

		$dataList = $dataList->paginate(20)->withQueryString();

		$tid=$id;

		return view('asset-lancar.transaction',compact('dataList','dataListPropCustomer','tid'));
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

		return view('asset-lancar.stats',[
			'dataListPropCustomer' => $dataListPropCustomer,
			'dataList' => $data, 
			'tid'=>$id,
			'sellCode' => Transaction::TYPE_SELL,
			'moveCode' => Transaction::TYPE_MOVE,
			'returnCode' => Transaction::TYPE_RETURN,
			'prodCode' => Transaction::TYPE_PRODUCTION,
		]);

		
	}

	public function create()
	{
		return view('asset-lancar.create');
	}

	public function postCreate(Request $request)
	{
		try {
		DB::beginTransaction();

		
		$item = new Item();
		$item->name = $request->name;
		$item->code = $request->code;
		$item->pcode = strtoupper($item->code); 
		$item->price = $request->price;
		$item->cost = $request->cost;
		$item->description = $request->description;
		$item->description2 = $request->description2;
		$item->group_id = 0;
		

		$item->type = Item::TYPE_ASSET_LANCAR;
		$item->code = $item->pcode = strtoupper($item->code);
		$item->name = strtoupper($item->name);

		if(!$item->save())
			throw new ModelException($item->getErrors(), __LINE__);

		$file = $request->file;
		if(!empty($file))
		{
			$manager = new ImageManager(new Driver()); // atau 'imagick' jika diinginkan

			// Ambil file gambar yang diunggah
			$image = $file;

			$folder = "";
			$pathFile = $item->id.'.jpg';

			$filename =  $pathFile;
			
			// Buat instance gambar dari file yang diunggah
			// $img = $manager->make($image->getRealPath());
			$img = $manager->read($image->getRealPath());
	
			
			// Tentukan kualitas awal dan path tujuan
			$quality = 85; // Mulai dengan kualitas 85%
			$path = env('CDN_PATH', '/laragon/www/core-nation/public/asset/');
			$filePath = $path . $filename;

			// Buat direktori jika belum ada
			if (!File::exists($path.$folder)) {
				File::makeDirectory($path.$folder, 0755, true); // Membuat direktori dengan izin 755
			}

			// dd($filePath);
			// Simpan gambar dan kompresi hingga ukurannya di bawah 100KB
			do {
				// Simpan gambar ke path dengan kualitas yang ditentukan
				$img->save($filePath, $quality);
				
				// Hitung ukuran file
				$size = filesize($filePath);
				$quality -= 5; // Kurangi kualitas jika ukuran file masih lebih dari 100KB
			} while ($size > 100 * 1024 && $quality > 10); // Teruskan hingga ukuran file di bawah 100KB

			// $file = $file->move($item->getUploadPath(), $item->id.'.jpg');
			// Image::read($file)->resize(300, null, function($constraint) {
			// 	$constraint->aspectRatio();
			// });
		}

		DB::commit();

		return redirect()->route('asetLancar.index')->with('success', 'Asset created.');

		

		} catch(ModelException $e) {
			DB::rollBack();

			return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);
		} catch(\Exception $e) {
			DB::rollBack();

			dd($e);

			return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());
		}
	}

	public function edit($id)
	{
		$item = Item::find($id);

		return view('asset-lancar.edit',compact('item'));
	}

	public function postEdit(Request $request, $id)
	{
		try {
		DB::beginTransaction();

		
		$item = Item::find($id);
		$item->name = $request->name;
		$item->code = $request->code;
		$item->pcode = strtoupper($item->code); 
		$item->price = $request->price;
		$item->cost = $request->cost;
		$item->description = $request->description;
		$item->description2 = $request->description2;

		$item->code = $item->pcode = strtoupper($item->code);
		$item->name = strtoupper($item->name);

		if(!$item->save())
			throw new ModelException($item->getErrors(), __LINE__);

		$file = $request->file;
		if(!empty($file))
		{
			$file = $file->move($item->getUploadPath(), $item->id.'.jpg');
			Image::read($file)->resize(300, null, function($constraint) {
				$constraint->aspectRatio();
			});
		}

		DB::commit();

		return redirect()->route('asetLancar.detail',$id)->with('success', 'Asset edited.');

		

		} catch(ModelException $e) {
			DB::rollBack();

			return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);
		} catch(\Exception $e) {
			DB::rollBack();

			dd($e);

			return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());
		}
	}

}
