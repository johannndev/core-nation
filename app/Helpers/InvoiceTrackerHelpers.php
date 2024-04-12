<?php

namespace App\Helpers;


use App\Models\BalanceTracker;
use App\Models\Customer;
use App\Models\CustomerStat;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\Updater;
use App\Models\WarehouseItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

use App\Helpers\KeysHelper as Keys;
use App\Helpers\AppsHelper as Apps;



class InvoiceTrackerHelpers
{

    public static $types = array(
		Customer::TYPE_RESELLER, Customer::TYPE_CUSTOMER, Customer::TYPE_SUPPLIER
	);
	public static function flag($t)
	{
		switch($t->type)
		{
			case Transaction::TYPE_CASH_OUT:
			case Transaction::TYPE_SELL:
			case Transaction::TYPE_RETURN_SUPPLIER:
				self::toggleUpdater($t->receiver_id);
				break;
			case Transaction::TYPE_BUY:
			case Transaction::TYPE_CASH_IN:
			case Transaction::TYPE_RETURN:
				self::toggleUpdater($t->sender_id);
				break;
			case Transaction::TYPE_ADJUST:
				if(in_array($t->sender_type, self::$types)) self::toggleUpdater($t->sender_id);
				if(in_array($t->receiver_type, self::$types)) self::toggleUpdater($t->receiver_id);
			default: break;
		}

		//toggle warehouse
		if($t->sender->type == Customer::TYPE_WAREHOUSE) self::toggleUpdater($t->sender_id);
		if($t->receiver->type == Customer::TYPE_WAREHOUSE) self::toggleUpdater($t->receiver_id);
	}

	protected static function toggleUpdater($entity_id,$update = true)
	{
		if(!$u = Updater::where('entity_id','=',$entity_id)->where('app_id','=',Apps::TRACK_INVOICE)->first())
			$u = new Updater;
		$u->date = Carbon::now()->toDateString();
		$u->entity_id = $entity_id;
		$u->app_id = Apps::TRACK_INVOICE;
		if($update)
			$u->flag = Updater::NEED_UPDATE;
		else
			$u->flag = Updater::NO_UPDATE;

		if(!$u->save())
			throw new \Exception('cannot save invoice');
		Cache::forget(Keys::updater($entity_id,Apps::TRACK_INVOICE));
	}

	public static function track($customer)
	{
		if(!self::checkUpdater($customer->id))
			return false;

		switch($customer->type)
		{
			case Customer::TYPE_CUSTOMER:
			case Customer::TYPE_RESELLER:
				self::updateCustomer($customer);
				break;
			case Customer::TYPE_SUPPLIER:
				self::updateSupplier($customer);
				break;
			case Customer::TYPE_WAREHOUSE:
				self::updateWarehouse($customer);
				break;
			default: break;
		}
	}

	public static function updateCustomer($customer)
	{
		$balance = $customer->stat->balance;
		$t_ids = array();
		$partial_id = 0;
		$partial_balance = 0;
		$partial_due = null;
		if($balance < 0)
		{
			$balance = abs($balance);
			$transactions = Transaction::where('date','<=',Carbon::now()->toDateString())->where('type','=',Transaction::TYPE_SELL)->where('receiver_id','=',$customer->id)->orderBy('date','desc')->orderBy('id','desc')->take(100)->get();
			foreach($transactions as $t)
			{
				$balance = $balance - abs($t->total);
				$t_ids[] = $t->id;
				if($balance <= 0)
				{
					$partial_id = $t->id;
					$partial_balance = abs($balance);
					if(!empty($t->due))
						$partial_due = $t->due;
					break;
				}
			}
		}

		//save to db
		if(!$tracker = BalanceTracker::find($customer->id))
		{
			$tracker = new BalanceTracker;
			$tracker->customer_id = $customer->id;
		}
		$tracker->partial_id = $partial_id;
		$tracker->partial_balance = $partial_balance;
		$tracker->transaction_ids = $t_ids;
		if(!$tracker->save())
			throw new \Exception('unable to track transaction');

		//unflag the update
		self::toggleUpdater($customer->id,false);
		//forget cache
		Cache::forget(Keys::tracker($customer->id));
	}

	public static function updateSupplier($customer)
	{
		$balance = $customer->stat->balance;
		$t_ids = array();
		$partial_id = 0;
		$partial_balance = 0;
		$partial_due = null;
		if($balance > 0)
		{
			$transactions = Transaction::where('date','<=',Carbon::now()->toDateString())->where('type','=',Transaction::TYPE_BUY)->where('sender_id','=',$customer->id)->orderBy('date','desc')->orderBy('id','desc')->take(100)->get();
			foreach($transactions as $t)
			{
				$balance = $balance - abs($t->total);
				$t_ids[] = $t->id;
				if($balance <= 0)
				{
					$partial_id = $t->id;
					$partial_balance = abs($balance);
					if(!empty($t->due))
						$partial_due = $t->due;
					break;
				}
			}
		}

		//save to db
		if(!$tracker = BalanceTracker::find($customer->id))
		{
			$tracker = new BalanceTracker;
			$tracker->customer_id = $customer->id;
		}
		$tracker->partial_id = $partial_id;
		$tracker->partial_balance = $partial_balance;
		$tracker->transaction_ids = $t_ids;
		if(!$tracker->save())
			throw new \Exception('unable to track transaction');

		//unflag the update
		self::toggleUpdater($customer->id,false);
		//forget cache
		Cache::forget(Keys::tracker($customer->id));
	}

	public static function updateWarehouse($warehouse)
	{
		$wi = WarehouseItem::table();
		$i = Item::table();

		$asset = DB::table("$wi as wi")->select(array(
			DB::raw('SUM( quantity * item_table.price ) as total_asset')
		))
		->where('warehouse_id','=',$warehouse->id)->where('quantity','>',0)
		->join("$i as item_table", 'wi.item_id','=', DB::raw("item_table.id AND item_table.type != ".Item::TYPE_ASSET_TETAP))->first();

		$cs = CustomerStat::find($warehouse->id);
		$cs->balance = $asset->total_asset;
		if(!$cs->save())
			throw new \Exception('Error Saving Warehouse Balance', 1);

		//unflag the update
		self::toggleUpdater($warehouse->id,false);
		//forget cache
		Cache::forget(Keys::tracker($warehouse->id));
	}

	public static function getBalanceTracker($customer_id)
	{
		return Cache::remember(Keys::tracker($customer_id),Keys::CACHE_GENERIC_TIME, function () use($customer_id) {
			return BalanceTracker::find($customer_id);
		});
	}

	protected static function checkUpdater($customer_id)
	{
//		Cache::forget(Keys::updater($customer_id,Apps::TRACK_INVOICE));
		$u = Cache::remember(Keys::updater($customer_id,Apps::TRACK_INVOICE), Keys::CACHE_GENERIC_TIME, function () use($customer_id) {
			return Updater::where('entity_id','=',$customer_id)->where('app_id','=',Apps::TRACK_INVOICE)->first();
		});
		if(!$u) return true;
		if($u->flag == Updater::NEED_UPDATE) return true;
		return false;
	}
}




