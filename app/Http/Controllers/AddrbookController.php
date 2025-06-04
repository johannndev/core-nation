<?php

namespace App\Http\Controllers;

use App\Exceptions\ModelException;
use App\Exports\AddrBookItemExport;
use App\Exports\AddrBookTransactionExport;
use App\Helpers\CCManagerHelper;
use App\Helpers\DateHelper;
use App\Models\Customer;
use App\Models\CustomerStat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class AddrbookController extends Controller
{
    protected function checkExist($c)
	{
		if(empty($c->id))
			$cust = Customer::where('name','=',$c->name)->where('birthdate','=',$c->birthdate)->first();
		else
			$cust = Customer::where('name','=',$c->name)->where('birthdate','=',$c->birthdate)->where('id','!=',$c->id)->first();
		if(!$cust) return false;
		return true;
	}


    public function postCreate(Request $request)
	{
		$requiredProp = "y";

		if($request->type == Customer::TYPE_VWAREHOUSE || $request->type == Customer::TYPE_VACCOUNT || $request->type == Customer::TYPE_WAREHOUSE){
			$requiredProp = "n";
		}

        $rules = [
            'address'  => [Rule::requiredIf($requiredProp == 'y')],
            'name'  => 'required',
           
            'initial' => [Rule::requiredIf($requiredProp == 'y')],
            
		];

        $attributes = [
            'address'  => 'Address',
            'name'  => 'Address',
            'description' => 'Description',
            'initial' => 'Initial',
        ];

        $this->validate($request, $rules, [], $attributes);

		try {
		// $input = Input::get('customer');

		// $initial = Input::get('initial',0);

      

        $customerTypeName = Customer::$types[$request->type];

		DB::beginTransaction();

		$customer = new Customer();

        $customer->address = $request->address;
        $customer->description = '';
        // $customer->phone = $request->phone;
        // $customer->phone2 = $request->phone2;
        // $customer->email = $request->email;
        // $customer->fax = $request->fax;

        if($request->ppn){
            $customer->ppn = 1;
        }

		if($request->is_online){
            $customer->is_online = 1;
        }

		$customer->name = ucfirst(trim($request->name));
		$customer->type = $request->type;

		if($this->checkExist($customer))
			throw new \Exception($customerTypeName.' already exists');

		if(!$customer->save())
			throw new ModelException($customer->getErrors(), __LINE__);

		$memberId = strtoupper(substr($customer->name,-1).Carbon::now()->format(DateHelper::$memberFormat).substr($customer->name, -2, 1));
		$customer->memberId = $customer->id.$memberId;
		if(!$customer->save())
			throw new ModelException($customer->getErrors(), __LINE__);

		//create stat

		if($request->initial){
			$initDepo = $request->initial;
		}else{
			$initDepo = 0;
		}

		$stat = new CustomerStat();
		$stat->customer_id = $customer->id;
		$stat->balance = $initDepo;
		if(!$stat->save())
			throw new ModelException($stat->getErrors(), __LINE__);

		//create class
		$date = Carbon::now()->startOfMonth()->toDateString();
		$cc = new CCManagerHelper;
		if(!$cc->emptyStat($customer,$date))
			throw new \Exception('cannot save '.$customerTypeName.' class');

		//check location
		// if(!empty($this->_data['_user']->location_id))
		// 	$this->_lm->assign($this->_data['_user']->location_id,$customer->id);

		DB::commit();

        return redirect()->route($request->action,$customer->id)->with('success', Customer::$types[$request->type].' created.');

		} catch(ModelException $e) {
			DB::rollBack();

			return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);
		} catch(\Exception $e) {
			DB::rollBack();

			dd($e);

			return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());
		}
	}

    public function postEdit(Request $request,$id)
	{
		$customer = Customer::findOrFail($id);

		$requiredProp = "y";

		if($customer->type == Customer::TYPE_VWAREHOUSE || $customer->type == Customer::TYPE_VACCOUNT){
			$requiredProp = "n";
		}


        $rules = [
            'address'  => [Rule::requiredIf($requiredProp == 'y')],
            'name'  => 'required',
            // 'description' => 'required',
           
            
		];

        $attributes = [
            'address'  => 'Address',
            'name'  => 'Address',
            'description' => 'Description',
            
        ];

        $this->validate($request, $rules, [], $attributes);

		try {
		// $input = Input::get('customer');

		// $initial = Input::get('initial',0);

		DB::beginTransaction();

		if($request->type){
			$typeCustomer = $request->type;
		}else{
			$typeCustomer = $customer->type;
	
		}

		

        $customerTypeName = Customer::$types[$typeCustomer];

        $customer->address = $request->address;
        $customer->description = $request->description;
        // $customer->phone = $request->phone;
        // $customer->phone2 = $request->phone2;
        // $customer->email = $request->email;
        // $customer->fax = $request->fax;

        if($request->ppn){
            $customer->ppn = 1;
        }else{
            $customer->ppn = 0;
        }

		if($request->is_online){
            $customer->is_online = 1;
        }else{
			$customer->is_online = 0;
		}

		$customer->name = ucfirst(trim($request->name));
		$customer->type = $typeCustomer;

		if($this->checkExist($customer))
			throw new \Exception($customerTypeName.' already exists');

		if(!$customer->save())
			throw new ModelException($customer->getErrors(), __LINE__);

		//check location
		// if(!empty($this->_data['_user']->location_id))
		// 	$this->_lm->assign($this->_data['_user']->location_id,$customer->id);

		DB::commit();

        return redirect()->route($request->action,$customer->id)->with('success', Customer::$types[$typeCustomer].' edited.');

		} catch(ModelException $e) {
			DB::rollBack();

			return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);
		} catch(\Exception $e) {
			DB::rollBack();

			dd($e);

			return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());
		}
	}

    public function postDelete($id, Request $request)
	{
		$customer = Customer::where('id', '=', $id)->where('type', '=', $request->type)->first();

	

        $customerTypeName = Customer::$types[$request->type];

        // dd($request);
        //  dd($customer,$id, $request->type);

		if(!$customer)
			return abort(404);

		if($customer->type != Customer::TYPE_VWAREHOUSE && $customer->type != Customer::TYPE_VACCOUNT) {
			if($customer->stat->balance != 0)


                return redirect()->route($request->action.'.detail',$id)->with('fail','Cannot delete '.$customerTypeName.', balance NEED to be 0');
		}

		$customer->delete();

        return redirect()->route($request->action.'.index')->with('success',$customer->name.' deleted');

		
	}

    public function postRestore($id,Request $request)
	{
		$customer = Customer::where('id', '=', $id)->where('type', '=', $request->type)->withTrashed()->first();
		if(!$customer)
			return abort(404);
		$customer->restore();

        return redirect()->route($request->action.'.index')->with('success',$customer->name.' deleted');
	}

	public function exportTransaction(Request $request) 
	{
		return Excel::download(new AddrBookTransactionExport($request->from,$request->to,$request->type,$request->cid), $request->filename.'.csv');
	}

	public function exportItem(Request $request) 
	{

		return Excel::download(new AddrBookItemExport($request->cid,$request->name,$request->sort,$request->show0,), $request->filename.'.csv');
	}
}
