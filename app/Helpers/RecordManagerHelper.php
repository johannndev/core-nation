<?php

namespace App\Helpers;

use App\Models\Customer;
use App\Models\CustomerClass;
use App\Models\MonthlyRecord;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RecordManagerHelper
{
    protected $_user;
	protected $_month;
	protected $_year;
	public function __construct($txn = null)
	{
		// parent::__construct($txn);
		$this->_user = Auth::user();
		$date = Carbon::now();
		$this->_month = $date->month;
		$this->_year = $date->year;
	}

	public function checkDate($month,$year)
	{
       

		if($year >= $this->_year)
		{
			if($year > $this->_year)
				return false;
			if($month > $this->_month)
				return false;
		}

		return true;
	}

	public function update($month,$year)
	{
		$date = Carbon::createFromFormat('m/Y',"$month/$year");

		$validator = Validator::make(array('date' => $date->toDateString()), array('date' => 'date'));
		if(!$validator->fails())
			return true;

		return false;
	}

	public function createProfitLoss($month, $year)
	{
       

		$date = Carbon::createFromFormat('j/m/Y',"1/".$month."/".$year);
		$from = $date->startOfMonth()->format(DateHelper::$SQLFormat);
		$to = $date->endOfMonth()->format(DateHelper::$SQLFormat);

		$cc = CustomerClass::table();
		$c = Customer::table();
		$transactions = DB::table(Transaction::table())
			->select(array(
				DB::raw('SUM( total ) as price'), 'type',
				DB::raw('SUM( total_items ) as items'),
				DB::raw('SUM( cogs ) as total_cogs'),
				DB::raw('SUM( real_total ) as total_real'),
				DB::raw('SUM( adjustment ) as total_adjustment')
			))
			->where('date','>=',$from)->where('date','<=',$to)
			->where('receiver_type', '!=', Customer::TYPE_ACCOUNT)
			->where('sender_type','!=', Customer::TYPE_ACCOUNT);

		$transactions = $transactions->groupBy('type')->get();

		//check if exist
		if(!$monthly = MonthlyRecord::where('month','=',$month)->where('year','=',$year)->first())
		{
			$monthly = new MonthlyRecord;
			$monthly->month = $month;
			$monthly->year = $year;
		}

		$monthly->cogs = 0;
		$adjustBuy = 0;
		$adjustRS = 0;
		foreach($transactions as $t)
		{
			switch($t->type)
			{
				case Transaction::TYPE_BUY:
					$monthly->buy = $t->price;
					$monthly->buy_items = $t->items;
					$adjustBuy -= $t->total_adjustment;
					break;
				case Transaction::TYPE_RETURN:
					$monthly->return_total = abs($t->price);
					$monthly->return_items = $t->items;
					break;
				case Transaction::TYPE_SELL:
					$monthly->sell = abs($t->price);
					$monthly->sell_items = $t->items;
					$monthly->cogs = $t->total_cogs;
					break;
				case Transaction::TYPE_MOVE: $monthly->move = $t->price; $monthly->move_items = $t->items; break;
				case Transaction::TYPE_TRANSFER: $monthly->transfer = $t->price; break;
				case Transaction::TYPE_CASH_OUT: $monthly->cash_out = $t->price; break;
				case Transaction::TYPE_USE:
					$monthly->use_total = $t->price;
					$monthly->use_items = $t->items;
					break;
				case Transaction::TYPE_CASH_IN: $monthly->cash_in = $t->price; break;
				case Transaction::TYPE_RETURN_SUPPLIER:
					$monthly->return_supplier_items = $t->items;
					$adjustRS += $t->total_adjustment;
					break;
				case Transaction::TYPE_DEPRECIATION: $monthly->depreciation = abs($t->price); break;
			}
		}

		$monthly->total_revenue = $monthly->sell - $monthly->return_total;
		$monthly->adjustment = $adjustRS - $adjustBuy;

		$data = DB::table($cc)->select(array(
			'date',
			DB::raw('SUM(cash_in) as total_cash_in'),
			DB::raw('SUM(cash_out) as total_cash_out'),
			DB::raw('SUM(adjust) as total_adjust'),
		))->where('date','=',$from)->join($c, "$c.id",'=',"$cc.customer_id")->where("$c.type",'=', Customer::TYPE_ACCOUNT)->first();

		if(!$data || empty($data))
			$total_operational = 0;
		else
			$total_operational = $data->total_cash_in - $data->total_cash_out + $data->total_adjust;

		$monthly->gross_profit = $monthly->total_revenue - $monthly->use_total - $monthly->cogs;
		$monthly->total_operational = $total_operational;
		$monthly->ebitda = $monthly->gross_profit + $monthly->total_operational;
		$monthly->nett = $monthly->gross_profit + $monthly->total_operational - $monthly->depreciation - $monthly->adjustment;
		if(!$monthly->save())
			return 'cannot save monthly data';

		return true;
	}

	public function getProfitLoss($month, $year)
	{
      
		$error = false;
		$date = Carbon::createFromDate("$year","$month","01");
		$from = $date->startOfMonth()->format(DateHelper::$SQLFormat);
		$to = $date->endOfMonth()->format(DateHelper::$SQLFormat);
		if(!$this->checkDate($month,$year))
			return 'invalid month';

		if($this->update($month,$year))
			if(!$result = $this->createProfitLoss($month, $year))
				return false;

		$t = MonthlyRecord::where('month','=',$month)->where('year','=',$year)->first();
		if(!$t)	$error = true;

		//init, avoid division by 0
		$report = array(
			'revenue' => 0,
			'return' => 0,
			'use' => 0,
			'total_revenue' => 0,
			'gross_profit' => 0,
			'total_operational' => 0,
			'nett' => 0,
			'ebitda' => 0,
			'revenue_margin' => 0,
			'ebitda_margin' => 0,
			'gross_profit_margin' => 0,
			'nett_margin' => 0,
			'depreciation' => 0,
			'depreciation_margin' => 0,
			'cogs' => 0,
			'buy' => 0,
			'cash_in' => 0,
			'cash_out' => 0,
			'adjustment' => 0,
		);

		if(!$error)
		{
			$report['return'] = $t->return_total;
			$report['revenue'] = $t->sell;
			$report['use'] = $t->use_total;
			$report['total_revenue'] = $t->total_revenue;
			$report['gross_profit'] = $t->gross_profit;
			$report['total_operational'] = $t->total_operational;
			$report['nett'] = $t->nett;
			$report['ebitda'] = $t->ebitda;
			$report['depreciation'] = $t->depreciation;
			$report['cogs'] = $t->cogs;
			$report['buy'] = $t->buy;
			$report['cash_in'] = $t->cash_in;
			$report['cash_out'] = $t->cash_out;
			$report['adjustment'] = $t->adjustment;
		}

		//init the operational
		$cc = CustomerClass::table();
		$c = Customer::table();
		$data = DB::table($cc)->select(array(
			'date', "$c.parent_id",
			DB::raw('SUM(cash_in) as total_cash_in'),
			DB::raw('SUM(cash_out) as total_cash_out'),
			DB::raw('SUM(adjust) as total_adjust'),
		))->where('date','=',$from)->join($c, "$c.id",'=',"$cc.customer_id")->where("$c.type",'=', Customer::TYPE_ACCOUNT)->groupBy("$c.parent_id")->get();

		$operational = array();
		$operational['total'] = array('cash_in' => 0, 'cash_out' => 0, 'adjust' => 0, 'total' => 0);
		foreach ($data as $op) {
			$parent_id = $op->parent_id;
			if(!isset($operational[$parent_id])) $operational[$parent_id] = array('cash_out' => 0, 'cash_in' => 0, 'adjust' => 0, 'total' => 0);
			$operational[$parent_id]['cash_in'] = $operational[$parent_id]['cash_in'] + $op->total_cash_in;
			$operational[$parent_id]['cash_out'] = $operational[$parent_id]['cash_out'] + $op->total_cash_out;
			$operational[$parent_id]['adjust'] = $operational[$parent_id]['adjust'] + $op->total_adjust;
			$operational[$parent_id]['total'] = $operational[$parent_id]['cash_in'] - $operational[$parent_id]['cash_out'] + $operational[$parent_id]['adjust'];
			$operational['total']['cash_in'] += $operational[$parent_id]['cash_in'];
			$operational['total']['cash_out'] += $operational[$parent_id]['cash_out'];
			$operational['total']['adjust'] += $operational[$parent_id]['adjust'];
			$operational['total']['total'] += $operational[$parent_id]['total'];
		}

		$report['revenue'] = floatval($report['revenue']);
		if(!empty($report['revenue']))
		{
			$EBITDA = abs($report['gross_profit']) + abs($report['total_operational']);
			$nett = abs($report['gross_profit']) - abs($report['total_operational']);
			$report['revenue_margin'] = round(100*$report['total_revenue']/abs($report['revenue']),2);
			$report['gross_profit_margin'] = round(100*$report['gross_profit']/abs($report['revenue']),2);
			$report['nett_margin'] = round(100*$report['nett']/abs($report['revenue']),2);
			$report['ebitda_margin'] = round(100*$report['ebitda']/abs($report['revenue']),2);
		}

		return array('operational' => $operational, 'report' => $report);
	}
}




