<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoronganDetail extends Model
{
    use HasFactory;

    protected $table = 'prod_borongandetail';
	protected $fillable = array('item_id', 'ongkos', 'quantity');

	public static function table()
	{
		return 'prod_borongandetail';
	}

	public function item()
	{
		return $this->belongsTo('App\Models\Item','item_id');
	}

	public function produksi()
	{
		return $this->belongsTo('App\Models\Produksi','produksi_id');
	}

	public function serial()
	{
		return Produksi::toSerial($this->produksi_id);
	}
}
