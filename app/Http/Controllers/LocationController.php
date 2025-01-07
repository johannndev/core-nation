<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class LocationController extends Controller
{
    public function index(){
        $dataList = Location::query();

       

        if(Request('name')) {
			$name = str_replace(' ', '%', Request('name'));
			$dataList = $dataList->where('name','LIKE',"%$name%");
		}
		if($id = Request('id')) {
			$dataList = $dataList->where('memberId','=', $id);
		}

		
        
        $dataList = $dataList->orderBy('name','asc')->paginate(50)->withQueryString();

        return view('location.index',compact('dataList'));
    }

    public function create(){
        return view('location.create');
    }

    public function store(Request $request){
        $rules = [
        
            'name'  => 'required',
          
           
            
		];

        $attributes = [
        
            'name'  => 'Name',
            
            
        ];

        $this->validate($request, $rules, [], $attributes);

        $user = new Location();
        $user->name = $request->name;
        $user->child_ids = "";
        $user->parent_ids = "";
      
        $user->save();

        return redirect()->route('location.index')->with('success', 'Location  created.');
    }

    public function edit($id){

        $location = Location::findOrFail($id);

        return view('location.edit',compact('location'));
    }

    public function update(Request $request,$id){
        $rules = [
        
            'name'  => 'required',
          
           
            
		];

        $attributes = [
        
            'name'  => 'Name',
            
            
        ];

        $this->validate($request, $rules, [], $attributes);

        $user = Location::findOrFail($id);
        $user->name = $request->name;
       
      
        $user->save();

        return redirect()->route('location.index')->with('success', 'Location  updated.');
    }


    public function locationDetail($id){

        $uid = $id;

        $location = Location::findOrFail($id);

        $dataList = Customer::whereIn('type',[2,3,7]);

        
        if(Request('name')) {
			$name = str_replace(' ', '%', Request('name'));
			$dataList = $dataList->where('name','LIKE',"%$name%");
		}

        $dataList = $dataList->whereHas('locations', function (Builder $query) use($uid) {
            $query->where('id', $uid);
        });

        $dataList = $dataList->orderBy('name','asc')->paginate(50)->withQueryString();

        return view('location.detail',compact('dataList','location'));

    }
}