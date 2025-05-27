<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Crongetorderdetail extends Model
{
    use HasFactory;

    public function transaksi(): HasOne
    {
        return $this->hasOne(Transaction::class,'invoice','invoice');
    }

    public function logJubelio(): HasOne
    {
        return $this->hasOne(Logjubelio::class,'invoice','invoice');
    }

    public function Jubelio(): HasOne
    {
        return $this->hasOne(Jubelioorder::class,'invoice','invoice');
    }
}
