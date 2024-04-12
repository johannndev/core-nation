<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerClass extends Model
{
    use HasFactory;

    protected $table = 'customer_class';
	public $timestamps = false;

	public static function table()
	{
		return 'customer_class';
	}

	public function customer()
	{
		return $this->belongsTo('App\Models\Customer','customer_id');
	}
}
