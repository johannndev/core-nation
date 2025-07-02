<?php

namespace App\Http\Controllers;

use App\Exceptions\ModelException;
use App\Models\Item;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $dataList = Tag::whereIn('type',[Tag::TYPE_TYPE,Tag::TYPE_SIZE,Tag::TYPE_JAHIT,Tag::TYPE_WARNA])->orderBy('id','desc');

        if($request->name){
			$dataList = $dataList->where('name', 'like', '%'.$request->name.'%');
		}


        $dataList = $dataList->paginate(20)->withQueryString();

        return view('tag.index',compact('dataList'));

    }

     public function create(Request $request)
    {
        $typeList = Tag::$types; 
        $itemType = Item::$types;

        return view('tag.create',compact('typeList','itemType'));

    }

     public function store(Request $request){

        try
		{
            DB::beginTransaction();

            $data = new Tag;
            $data->name = $request->name;
            $data->code = $request->code;
            $data->type = $request->type;
            $data->item_type = $request->item_type;
    
            $data->save();
     

            DB::commit();

        
            return redirect()->route('tag.index')->with('success',  'Tag '.$data->name.' created');

            
		} catch(ModelException $e) {
			DB::rollBack();
			return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);
		} catch(\Exception $e) {
			DB::rollBack();
			return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());
		}
	}

}
