<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseItem extends Model
{
    use HasFactory;

    protected $table = 'warehouse_item';
	protected $fillable = array('item_id', 'warehouse_id', 'quantity');
	public $timestamps = false;

	public static function table()
	{
		return 'warehouse_item';
	}

	public function warehouse()
	{
		return $this->belongsTo('App\Models\Customer','warehouse_id')->withTrashed();
	}

	public function item()
	{
		return $this->belongsTo('App\Models\Item','item_id');
	}
}
