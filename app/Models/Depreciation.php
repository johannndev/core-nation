<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depreciation extends Model
{
    use HasFactory;

    protected $table = 'depreciation';
	protected $primaryKey = 'item_id';

	public static function table()
	{
		return 'depreciation';
	}

	public function item()
	{
		return $this->belongsTo('App\Models\Item');
	}

	public function warehouses()
	{
		return $this->hasMany('App\Models\WarehouseItem', 'item_id');
	}

	public function depreciate($stop = null)
	{
		$test = intval($this->buy_date);
		if(empty($test) || empty($this->value)) return 0;

		if(!$stop) $stop = Carbon::now();

		$expire = Carbon::createFromDate('Y-m-d', $this->expire_date);
		$buy = Carbon::createFromDate('Y-m-d', $this->buy_date);

		//if expired
		if($expire->diffInMonths($stop) < 0)
			return 0;

		//special case for 1 month, expired last month
		if($expire->diffInMonths($buy) > 0 && $this->value == 1)
			return 0;

		//special case for 1 month, expires now
		if($this->value == 1)
			return $this->buy_price;

		return $this->monthlyDepreciation();
	}

	public function monthlyDepreciation()
	{
		if(empty($this->value))
			return 0;
		return bcdiv($this->buy_price, $this->value, 2);
	}

	public function calcValue()
	{
		$test = intval($this->buy_date);
		if(empty($test) || empty($this->value)) return 0;

		$buy = Carbon::createFromFormat('Y-m-d',$this->buy_date);
		$now = Carbon::now();

		$months = $now->diffInMonths($buy);
		if($months < 0) return $this->buy_price;

		$months += 1;

		return $this->buy_price - bcmul($this->monthlyDepreciation(), $months, 2);
	}
}
