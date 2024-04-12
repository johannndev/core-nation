<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HT extends Model
{
    protected $table = 'hashtag_transaction';
	public $timestamps = false;

	public static function table()
	{
		return 'hashtag_transaction';
	}

	public function receiver()
	{
		return $this->belongsTo('App\Models\Customer','receiver_id');
	}

	public function sender()
	{
		return $this->belongsTo('App\Models\Customer','sender_id');
	}

	public function transaction()
	{
		return $this->belongsTo('App\Models\Transaction','transaction_id');
	}
}
