<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestyWarehouse extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function payloads()
    {
        return $this->hasMany(DestyPayload::class, 'platform_warehouse_id', 'platform_warehouse_id')
                    ->whereColumn('store_id', 'store_id');
    }

    //relation to desty sync one to one
    public function destySync()
    {
        return $this->hasOne(DestySync::class,'desty_warehouse_id','id');
    }
}
