<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logjubelio extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return route('transaction.getDetail',$this->transaction_id);

        
    }
}
