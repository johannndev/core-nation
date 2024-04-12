<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
