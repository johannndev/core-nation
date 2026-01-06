<?php

namespace App\Models;

use App\Helpers\StockManagerHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $table = 'transaction_details';
	public $timestamps = false;

	protected $rules = array(
		'transaction_id' => 'required',
		'item_id' => 'required',
		'quantity' => 'required',
	);

	protected $dates = ['date'];

	protected $fillable = array('item_id', 'quantity', 'price', 'discount');

	public static function table()
	{
		return 'transaction_details';
	}

	public function transaction()
	{
		return $this->belongsTo('App\Models\Transaction','transaction_id');
	}

	public function receiver()
	{
		return $this->belongsTo('App\Models\Customer','receiver_id')->withTrashed();
	}

	public function sender()
	{
		return $this->belongsTo('App\Models\Customer','sender_id')->withTrashed();
	}

	//relasi ke model DestySync dengan belongsTo


	public function destyReceiver()
	{
		return $this->belongsTo('App\Models\DestySync', 'receiver_id', 'warehouse_id');
	}

	public function destySender()
	{
		return $this->belongsTo('App\Models\DestySync', 'sender_id', 'warehouse_id');
	}

	public function item()
	{
		return $this->belongsTo('App\Models\Item','item_id');
	}

	public function getTypeNameAttribute()
	{
		return Transaction::$types[$this->transaction_type];
	}

	public function getWareHouse($id)
	{
		$data = WarehouseItem::whereIn('warehouse_id',StockManagerHelpers::$list)->where('item_id',$id)->get();

		return $id;
	}
}
