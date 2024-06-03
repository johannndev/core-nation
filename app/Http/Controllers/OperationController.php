<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerStat;
use App\Models\Operation;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Colors\Rgb\Channels\Red;

class OperationController extends Controller
{
    public function index (Request $request)
    {
        $query = Operation::query();
		//dates are set!
		if($request->name)
			$query = $query->where('name','LIKE',"%$request->name%");

    	$data = $query->paginate(50);

        return view('operation.index',compact('data'));
    }

	public function create()
	{
		return view('operation.create');
	}

	public function store(Request $request)
	{
		$rules = [          
            'name'  => 'required',
            'description' => 'required',

		];

        $attributes = [

            'name'  => 'Address',
            'description' => 'Description',
        
        ];

        $this->validate($request, $rules, [], $attributes);

		$data = new Operation();
		$data->name = $request->name;
		$data->description = $request->description;

		$data->save();

		return redirect()->route('operation.index')->with('success', 'Operation Created'); 

	}

	public function edit($id)
	{
		$operation = Operation::findOrFail($id);
		return view('operation.edit',compact('operation'));
	}

	public function update($id,Request $request)
	{
		$rules = [          
            'name'  => 'required',
            'description' => 'required',

		];

        $attributes = [

            'name'  => 'Address',
            'description' => 'Description',
        
        ];

        $this->validate($request, $rules, [], $attributes);

		$data = Operation::findOrFail($id);
		$data->name = $request->name;
		$data->description = $request->description;

		$data->save();

		return redirect()->route('operation.index')->with('success', 'Operation Updated'); 

	}

    public function detail ($id)
    {
        $operation = Operation::findOrFail($id);
		//dates are set!
		$query = Customer::accounts()->where('parent_id','=',$id);
		$data = $query->paginate(30);

    

        return view('operation.detail',compact('data','operation'));
    }

    public function account ($id, Request $request)
    {
        $account = Customer::with('operation')->accounts()->findOrFail($id);
		//load transaction
		

		//sorting
		$sort = 'date';
		$dir = 'desc';

		$from = $request->from;
		$to = $request->to;;

        $query = Transaction::orderBy($sort,$dir);

		if($from && $to) //dates are set!
		{
			$query = $query->where('date','>=',$from)->where('date','<=',$to);
		}
		else
		{
			$fromDate = Carbon::now()->subYear(1)->toDateString();
			$toDate = Carbon::now()->toDateString();
			$query = $query->where('date','>=',$fromDate)->where('date','<=',$toDate);
		}

		$dataList = $query->where(function($query) use($id) {
			$query->where('receiver_id','=',$id)->orWhere('sender_id','=',$id);
		})->paginate(50);

    

        return view('operation.account',compact('dataList','account'));
    }

	public function editAccount($id)
	{
		$account = Customer::accounts()->findOrFail($id);
		$operations = Operation::all();

		return view('operation.account-edit',compact('account','operations'));
	}

	public function updateAccount($id, Request $request)
	{
		$account = Customer::accounts()->findOrFail($id);
		$operations = Operation::all();

		$account = Customer::accounts()->findOrFail($id);
		
		$account->parent_id = $request->parent_id;
		$account->name = $request->name;
		$account->description =$request->description;

		$account->save();

		return redirect()->back()->with('success', 'Operation Updated'); 

	}

	public function accountList (Request $request)
    {
      
		$query = Customer::accounts()->with('operation');
		//dates are set!
		//dates are set!
		if($request->name)
			$query = $query->where('name','LIKE',"%$request->name%");

    	$data = $query->paginate(100);

        return view('operation.account-list',compact('data'));
    }

	public function createAccount()
	{
	
		$operations = Operation::all();

		return view('operation.account-create',compact('operations'));
	}

	public function postCreateAccount(Request $request)
	{
		$rules = [          
            'name'  => 'required',
            'description' => 'required',
			'parent_id' =>'required'

		];

        $attributes = [

            'name'  => 'Address',
            'description' => 'Description',
			'parent_id' => 'Journal'
        
        ];

        $this->validate($request, $rules, [], $attributes);

		DB::beginTransaction();

		
		$account = new Customer();
		$account->name = $request->name;
		$account->description = $request->description;
		$account->parent_id = $request->parent_id;
		$account->type = Customer::TYPE_ACCOUNT;
		if(!$account->save())
		{
			DB::rollBack();
			return redirect()->back()->withInput()->withErrors($account->getErrors());
		}

		$stat = new CustomerStat();
		$stat->customer_id = $account->id;
		$stat->balance = 0;
		if(!$stat->save())
		{
			DB::rollBack();
			return redirect()->back()->with('error', 'error dol')->withErrors($stat->getErrors())->withInput();
		}

		DB::commit();
		return redirect()->route('operation.account',$account->id)->with('success', 'Account created.');
	}

}
