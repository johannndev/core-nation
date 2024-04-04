<?
namespace App\Libraries;

use App\Models\Customer,App\Models\CustomerClass,App\Models\Transaction, App\Models\CustomerStat;
use DB;
class CCManager extends BaseManager
{
	protected $ccs;

	public function emptyStat($c,$date)
	{
		//theres a customer?? not empty la
		if(!$customer = CustomerClass::where('date','=',$date)->where('customer_id','=',$c->id)->first())
			$customer = new CustomerClass;

		$customer->customer_id = $c->id;
		$customer->customer_type = $c->type;
		$customer->date = $date;
		$customer->cash_out = 0;
		$customer->cash_in = 0;
		$customer->return = 0;
		$customer->sell = 0;
		$customer->buy = 0;
		$customer->use = 0;
		$customer->transfer = 0;
		$customer->return_supplier = 0;
		$customer->move = 0;
		$customer->depreciation = 0;
		if(!$customer->save())
			throw new \Exception('cannot create empty class', 1);

		return $customer;
	}
/*
	protected function getRating($type, $data)
	{
		switch($type)
		{
			case Customer::TYPE_CUSTOMER:
			case Customer::TYPE_RESELLER:
				return $this->getCustomerRating($data->cash_in, abs($data->sell), abs($data->return), abs($data->cash_out));
				break;
			case Customer::TYPE_BANK:
			case Customer::TYPE_VACCOUNT:
				return $this->getAccountRating($data->cash_in, abs($data->cash_out), $data->transfer);
				break;
			case Customer::TYPE_SUPPLIER:
				return $this->getSupplierRating($data->buy, abs($data->cash_out), abs($data->return_supplier));
				break;
			case Customer::TYPE_WAREHOUSE:
			case Customer::TYPE_VWAREHOUSE:
				return $this->getWarehouseRating($data->use, $data->move, abs($data->sell), $data->buy);
				break;
			case Customer::TYPE_ACCOUNT:
				return '';
				break;
		}
	}

	public function getCustomerRating($cash_in, $sell, $return, $cash_out)
	{
		$check = $cash_in - $cash_out;
		if(empty($sell) || empty($cash_in) || $sell == '0.00' || $cash_in == '0.00') return 0;
		if(empty($check) || $check == '0.00') return 0;
		//use 1 of the 2 formulas
		if(empty($return) || $return == '0.00')
			return doubleval(($cash_in - $cash_out)/$sell);
		return bcmul(doubleval(($cash_in - $cash_out - $return)/$sell), doubleval(($sell - $return)/($cash_in - $cash_out)),2);
	}

	public function getAccountRating($cash_in, $cash_out, $transfer)
	{
		if(empty($cash_out) || $cash_out == '0.00') return 1;
		return doubleval($cash_in/$cash_out);
	}

	public function getSupplierRating($buy, $cash_out, $rs)
	{
		if(empty($buy) || empty($cash_out) || $buy == '0.00' || $cash_out == '0.00') return 0;
		return bcmul(doubleval(($cash_out - $rs)/$buy), doubleval(($buy - $rs)/$cash_out),2);
	}

	public function getWarehouseRating($use, $move, $sell, $buy)
	{
		if(empty($buy) || empty($sell) || $sell == '0.00' || $buy == '0.00') return 0;
		return bcmul(doubleval($use/$buy), doubleval($move/$sell),2);
	}
*/
	public function createStat($customer, $month, $year)
	{
		$date = Dater::createMY($month,$year);
		$from = $date->startOfMonth()->format(Dater::$SQLFormat);
		$to = $date->endOfMonth()->format(Dater::$SQLFormat);

		//2. get the data
		$customer_id = $customer->id;
		$stat = DB::table(Transaction::table())
			->select(array(DB::raw('MONTH( date ) AS bulan'), DB::raw('SUM( total ) as price'), 'type', DB::raw('SUM( total_items ) as items')))
			->where('date','>=',$from)->where('date','<=',$to)
			->where(function($query) use($customer_id)
			{
				$query->where('sender_id','=',$customer_id);
				$query->orWhere('receiver_id','=',$customer_id);
			})
			->groupBy('type')
			->get();

		//2.1 reset stat
		$this->emptyStat($customer,$from);

		//3. prepare data, set class
		if(!$ccs = CustomerClass::where('date','=',$from)->where('customer_id','=',$customer_id)->first())
		{
			$ccs = new CustomerClass;
			$ccs->customer_id = $customer_id;
			$ccs->date = $from;
		}

		foreach($stat as $data)
		{
			switch($data->type)
			{
				case Transaction::TYPE_CASH_OUT: $ccs->cash_out = abs($data->price); break;
				case Transaction::TYPE_CASH_IN: $ccs->cash_in = $data->price; break;
				case Transaction::TYPE_RETURN: $ccs->return = $data->price; break;
				case Transaction::TYPE_SELL: $ccs->sell = abs($data->price); break;
				case Transaction::TYPE_BUY:	$ccs->buy = $data->price; break;
				case Transaction::TYPE_USE:	$ccs->use = $data->price; break;
				case Transaction::TYPE_TRANSFER: $ccs->transfer = $data->price; break;
				case Transaction::TYPE_RETURN_SUPPLIER: $ccs->return_supplier = abs($data->price); break;
				case Transaction::TYPE_MOVE: $ccs->move = $data->price; break;
				case Transaction::TYPE_ADJUST: $ccs->adjust = $data->price; break;
				case Transaction::TYPE_DEPRECIATION: $ccs->depreciation = abs($data->price); break;
			}
		}

		//4. save the data
		$rating = null;
//		$ccs->rating = $this->getRating($customer->type,$ccs);
//		$ccs->class = $this->getClass($ccs->rating);
		if(!$ccs->save())
			return $this->error('cannot save');

		//save the latest rating to customer stat
		if(!$cs = CustomerStat::find($customer->id))
		{
			$cs = new CustomerStat;
			$cs->customer_id = $customer->id;
			$cs->balance = 0;
		}
		if(!$cs->save())
			return $this->error('cannot save to stat');

		return true;
	}

	//from & to only accepts datetime format
	public function getStat($id,$from = null,$to = null)
	{
		$customer = Customer::findOrFail($id);
		if(!$from || !$to)
		{
			$from = Dater::toSQL(Dater::now()->subMonths(2)->startOfMonth()->format(Dater::$format));
			$to = Dater::toSQL(Dater::now()->endOfMonth()->format(Dater::$format));
		}

		return CustomerClass::where('date','>=',$from)->where('date','<=',$to)->where('customer_id','=',$customer->id)->get();
	}
/*
	public static function getClass($rating)
	{
		if($rating >= 0.9) return 'A';
		if($rating >= 0.8 && $rating < 0.9) return 'B';
		if($rating >= 0.7 && $rating < 0.8) return 'C';
		return 'D';
	}
*/
	public function update($data = array())
	{
		if(!isset($data['date']) || !isset($data['customer']) || empty($data['customer']))
			throw new \Exception('cannot update data, empty data', 1);

		if(!$stat = CustomerClass::where('date','=',$data['date'])->where('customer_id','=',$data['customer']->id)->first())
			$stat = $this->emptyStat($data['customer'], $data['date']);

		switch ($data['type'])
		{
				case Transaction::TYPE_SELL:
					$stat->sell = abs($stat->sell) + abs($data['total']);
					break;
				case Transaction::TYPE_RETURN:
					$stat->return = abs($stat->return) + abs($data['total']);
					break;
				case Transaction::TYPE_CASH_IN:
					$stat->cash_in = abs($stat->cash_in) + abs($data['total']);
					break;
				case Transaction::TYPE_CASH_OUT:
					$stat->cash_out = abs($stat->cash_out) + abs($data['total']);
					break;
				case Transaction::TYPE_ADJUST:
					$stat->adjust = $stat->adjust + $data['total'];
					break;
				case Transaction::TYPE_DEPRECIATION:
					$stat->depreciation = abs($stat->depreciation) + abs($data['total']);
					break;
				case Transaction::TYPE_TRANSFER:
					$stat->transfer = $stat->transfer + $data['total'];
					break;
				default: break;
		}

		if(!$stat->save())
			throw new \Exception('cannot save stat', 1);
	}
}