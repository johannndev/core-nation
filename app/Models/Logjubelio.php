<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logjubelio extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'data' => 'array', // Casting agar data otomatis dikonversi menjadi array
    ];

    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return route('transaction.getDetail',$this->transaction_id);

        
    }
}
