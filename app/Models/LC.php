<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LC extends Model
{
    use HasFactory;

    protected $table = 'location_customer';
	public $timestamps = false;

	public function customer()
	{
		return $this->belongsTo('App\Models\Customer','customer_id');
	}

	public function location()
	{
		return $this->belongsTo('App\Models\Location','location_id');
	}
}
