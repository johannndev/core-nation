<?php

namespace App\Http\Controllers;

use App\Models\Borongan;
use App\Models\BoronganDetail;
use App\Models\Worker;
use Illuminate\Http\Request;

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

	public function detail($id){
		$borongan = Borongan::findOrFail($id);

		$detaiList = BoronganDetail::with('item')->where('borongan_id','=',$id)->get();

		return view('borongan.detail',compact('borongan','detaiList'));
	}

   
}
