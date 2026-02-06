<?php

namespace App\Http\Controllers;

use App\Exceptions\ModelException;
use App\Helpers\ItemsManagerHelper;
use App\Models\Customer;
use App\Models\Item;
use App\Models\ItemTag;
use App\Models\Tag;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\WarehouseItem;
use App\View\Components\Customer\Items;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

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

		dd( $data->lancar_image_path);

		$urlImage = $data->lancar_image_path;

		$whList = WarehouseItem::with('warehouse')->whereHas('warehouse', function (Builder $query) {
			$query->where('customers.type','=',Customer::TYPE_WAREHOUSE);
		})->where('item_id',$id)->get();

		// dd($whList);

		$tid=$id;

		return view('asset-lancar.detail',compact('data','urlImage','whList','tid'));
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

			// dd($result);

			if (!isset($result['data'][0]) || is_null($result['data'][0])) {
				$message = "Item tidak ada";
				// dd('Data is null or not set', $result);
			} else {
				$message = "ok";
				$dataJubelio = $result['data'][0];
				
			}

			
		}

		

		$tid=$id;
		

		return view('asset-lancar.jubelio',compact('data','urlImage','tid','dataJubelio','message'));
	}

	public function getItem($id){

        $itemProd = Item::find($id);

        $response = Http::withHeaders([ 
            'Content-Type'=> 'application/json', 
            'authorization'=> Cache::get('jubelio_data')['token'], 
        ]) 
        ->get('https://api2.jubelio.com/inventory/items/to-stock/',[
            'q' => $itemProd->code,
        ]); 

        $result = json_decode($response->body(), true);
		$dataList = $result['data'];

		// dd($dataList);

        return view('asset-lancar.jubelio-item',compact('dataList','itemProd'));

    }

	public function updateJubelioId($id, Request $request){

		$data = Item::find($id);

		$data->jubelio_item_id = $request->jubelio_item_id ?? null;

		$data->save();


		return redirect()->route('asetLancar.jubelio',$id)->with('success', 'Item updated');

	}

    public function transaction($id, Request $request)
	{
        $data = Item::where('id',$id)->first();
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

		return view('asset-lancar.transaction',compact('data','dataList','dataListPropCustomer','tid'));
	}

    public function stat($id, Request $request)
	{
        $itemData = Item::where('id',$id)->first();
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
            'itemData' => $itemData,
		]);

		
	}

	public function create()
	{
		$tags = ItemsManagerHelper::loadTagsJSON(Item::TYPE_ASSET_LANCAR,Tag::$asetLancarCreate);

		$type = Item::TYPE_ASSET_LANCAR;
		
		return view('asset-lancar.create',compact('tags','type'));
	}

	public function postCreate(Request $request)
	{
		try {
			$input = $request;
			$tags = $request->tags;
            $input->type = ITEM::TYPE_ASSET_LANCAR;

			DB::beginTransaction();
			$itemManager = new ItemsManagerHelper;
			if(!$itemManager->createItems($input, $tags, $request->file))
				throw new ModelException($itemManager->getErrors(), __LINE__);

			DB::commit();
			return redirect()->route('asetLancar.index')->with('success', 'Item(s) created.');
		} catch(ModelException $e) {
			DB::rollBack();
			return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);
		} catch(\Exception $e) {
			DB::rollBack();

			dd($e);

			return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());
		}
	}

	public function postsCreate(Request $request)
	{
		try {
		DB::beginTransaction();
		
		$item = new Item();
		$item->name = $request->name;
		$item->pcode = $item->code = strtoupper($request->code); 
		$item->price = $request->price;
		$item->cost = $request->cost;
		$item->description = $request->description ?? "";
		$item->description2 = $request->description2 ?? "";
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

			$maxSize = 1000;
			$originalWidth = $img->width();
			$originalHeight = $img->height();

			if ($originalWidth > $originalHeight) {
				$scale = $maxSize / $originalWidth; // Skala berdasarkan lebar
			} else {
				$scale = $maxSize / $originalHeight; // Skala berdasarkan tinggi
			}
			
			// Hitung dimensi baru
			$maxWidth = round($originalWidth * $scale);
			$maxHeight = round($originalHeight * $scale);

			// Hitung ukuran baru (50% lebih kecil)
			// $maxWidth = $originalWidth * 0.5;
			// $maxHeight = $originalHeight * 0.5;
			if ($img->width() > $maxWidth || $img->height() > $maxHeight) {
				$img->resize($maxWidth, $maxHeight, function ($constraint) {
					$constraint->aspectRatio(); // Menjaga rasio asli
					$constraint->upsize();      // Mencegah gambar menjadi lebih besar dari ukuran aslinya
				});
			}
		
	
			
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

	public function duplicate($id)
	{
		$item = Item::find($id);
		$type = Item::TYPE_ASSET_LANCAR;
		$tags = ItemsManagerHelper::loadTagsJSON(Item::TYPE_ASSET_LANCAR,Tag::$asetLancarCreate);
		$dataType = $item->tags->where('type',Tag::TYPE_TYPE)->first();

		return view('asset-lancar.duplicate',compact('item','type','tags','dataType'));
	}


	public function edit($id)
	{
		$item = Item::find($id);

		$dataWarna = $item->tags->where('type',Tag::TYPE_WARNA)->first();
		$dataType = $item->tags->where('type',Tag::TYPE_TYPE)->first();

		$type = Item::TYPE_ASSET_LANCAR;

		$tags = ItemsManagerHelper::loadTagsJSON(Item::TYPE_ASSET_LANCAR,Tag::$asetLancarCreate);

		$tagSize = ItemTag::with('tag')->whereHas('tag', function (Builder $query) {
			$query->where('type', Tag::TYPE_SIZE);
		})->where('item_id',$id)->pluck('id','tag_id')->toArray();




		return view('asset-lancar.edit',compact('item','dataWarna','type','tags','dataType','tagSize'));
	}

	
	public function postEdit(Request $request, $id)
	{		
		try{

			$input = $request;
			$tags = array_values(array_filter(array_merge(...array_values($request->tags))));

			DB::beginTransaction();
			$itemManager = new ItemsManagerHelper;


			$item = Item::find($id);
			$item->name = $request->name;
			$item->pcode = $item->code = strtoupper($request->pcode); 
			$item->price = $request->price;
			$item->description = $input->description;
			$item->cost = $input->cost;
			$item->description = $input->description;
			$item->description2 = $input->description2;
			$item->save();

			$item->tags()->sync($tags);


			DB::commit();

			return redirect()->route('asetLancar.detail',$id)->with('success', 'Item edited.');

		} catch(ModelException $e) {
			DB::rollBack();

			return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);
		} catch(\Exception $e) {
			
			DB::rollBack();

			dd($e);

			return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());
		}

		// try {
		// DB::beginTransaction();

		
		// $item = Item::find($id);
		// $item->name = $request->name;
		// $item->code = $request->code;
		// $item->pcode = strtoupper($item->code); 
		// $item->price = $request->price;
		// $item->cost = $request->cost;
		// $item->description = $request->description;
		// $item->description2 = $request->description2;

		// $item->code = $item->pcode = strtoupper($item->code);
		// $item->name = strtoupper($item->name);

		// if(!$item->save())
		// 	throw new ModelException($item->getErrors(), __LINE__);

		// $file = $request->file;
		// if(!empty($file))
		// {
		// 	$file = $file->move($item->getUploadPath(), $item->id.'.jpg');
		// 	Image::read($file)->resize(300, null, function($constraint) {
		// 		$constraint->aspectRatio();
		// 	});
		// }

		// DB::commit();

		// return redirect()->route('asetLancar.detail',$id)->with('success', 'Asset edited.');

		

		// } catch(ModelException $e) {
		// 	DB::rollBack();

		// 	return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);
		// } catch(\Exception $e) {
		// 	DB::rollBack();

		// 	dd($e);

		// 	return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());
		// }
	}

}
