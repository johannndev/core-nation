<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestySync extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function destyWarehouse()
    {
        return $this->hasOne(DestyWarehouse::class,'id','desty_warehouse_id');
    }

    public function warehouse()
    {
        return $this->hasOne(Customer::class,'id','warehouse_id');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class,'id','customer_id');
    }
}
