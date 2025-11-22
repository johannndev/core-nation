<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestyPayload extends Model
{
    use HasFactory;

    protected $guarded = [];

    // otomatis cast item_list ke array
    protected $casts = [
        'item_list' => 'array',
    ];

    // Relasi ke warehouse via kombinasi 2 field
    public function warehouse()
    {
        return $this->hasOne(DestyWarehouse::class, 'platform_warehouse_id', 'platform_warehouse_id')
                    ->whereColumn('store_id', 'store_id');
    }
}
