<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class FilterQueryController extends Controller
{
    private function getQuery($queryParams){
        $nullQueries = [];
        foreach($queryParams as $key => $value){
            if(!is_null($value)){
               $nullQueries[$key] = $value;
            }
        }

        return $nullQueries;
    }

    public function transactionFilter(Request $request)
    {
       
        $queryFilter = $this->getQuery($request->query()); 
       
       return redirect()->route('transaction.index',$queryFilter);

    }

    public function transactionFilterDelete(Request $request)
    {
       
        $queryFilter = $this->getQuery($request->query()); 
       
       return redirect()->route('transaction.delete',$queryFilter);

    }


    public function itemFilter(Request $request)
    {
       
        $queryFilter = $this->getQuery($request->query()); 
       
       return redirect()->route('item.index',$queryFilter);

    }

    public function itemStatFilter(Request $request,$id)
    {
       
        $queryFilter = $this->getQuery($request->query());

        $array = Arr::add($queryFilter,'id',$id);
      
       
       return redirect()->route('item.stat',$array);

    }

    public function itemTransFilter(Request $request,$id)
    {
       
        $queryFilter = $this->getQuery($request->query());

        $array = Arr::add($queryFilter,'id',$id);
      
       
       return redirect()->route('item.transaction',$array);

    }

    public function itemGroupTransFilter(Request $request)
    {
       
        $queryFilter = $this->getQuery($request->query());

       
      
       
       return redirect()->route('item.group',$queryFilter);

    }

    public function itemGroupStatFilter(Request $request,$id)
    {
       
        $queryFilter = $this->getQuery($request->query());

        $array = Arr::add($queryFilter,'id',$id);
      
       
       return redirect()->route('item.statGroup',$array);

    }

    public function assetLancarFilter(Request $request)
    {
       
        $queryFilter = $this->getQuery($request->query()); 
       
       return redirect()->route('asetLancar.index',$queryFilter);

    }

    public function assetLancarTransFilter(Request $request,$id)
    {
       
        $queryFilter = $this->getQuery($request->query());

        $array = Arr::add($queryFilter,'id',$id);
      
       
       return redirect()->route('asetLancar.transaction',$array);

    }

    public function assetLancarStatFilter(Request $request,$id)
    {
       
        $queryFilter = $this->getQuery($request->query());

        $array = Arr::add($queryFilter,'id',$id);
      
       
       return redirect()->route('asetLancar.stat',$array);

    }

    public function contributorFilter(Request $request)
    {
       
        $queryFilter = $this->getQuery($request->query());

      
       
       return redirect()->route('contributor.index',$queryFilter);

    }

    private function getQueryWithId($queryParams){

        $coll =  collect($queryParams);

        $queryParams = $coll->except(['_token', 'action'])->toArray();

        $nullQueries = [];
        foreach($queryParams as $key => $value){
            if(!is_null($value)){
               $nullQueries[$key] = $value;
            }
        }

        return $nullQueries;
    }


    public function getFilter(Request $request)
    {
       
        $queryFilter = $this->getQueryWithId($request->input());
       
        return redirect()->route($request->action,$queryFilter);

    }
}
