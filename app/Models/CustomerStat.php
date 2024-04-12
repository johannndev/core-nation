<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerStat extends Model
{
    use HasFactory;

    protected $table = 'customerstat';
	protected $primaryKey = 'customer_id';
	public $timestamps = true;

	public static function table()
	{
		return 'customerstat';
	}

	public function customer()
	{
		return $this->belongsTo('App\Models\Customer','customer_id');
	}
}
