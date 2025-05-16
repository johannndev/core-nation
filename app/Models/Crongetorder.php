<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Crongetorder extends Model
{
    use HasFactory;

    public function orderDetail(): HasMany
    {
        return $this->hasMany(Crongetorderdetail::class, 'get_order_id','id');
    }
}
