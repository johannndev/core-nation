<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseCompare extends Model
{
    use HasFactory;

    public function warehouse()
	{
		return $this->hasOne(Customer::class,'id','werehouse_id');
	}

}
