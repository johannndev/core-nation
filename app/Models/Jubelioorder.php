<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jubelioorder extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->hasOne(User::class,'id','execute_by',);
    }

    public function trx()
    {
        return $this->hasOne(Transaction::class,'invoice','invoice');
    }

    // protected $casts = [
    //     'payload' => 'array',
    // ];


}
