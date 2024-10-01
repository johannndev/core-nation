<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    public function gajih()
    {
        return $this->hasMany(Gajih::class,'karyawan_id','id');
    }

    public function gajihSingle()
    {
        $now = Carbon::now();

        return $this->hasOne(Gajih::class,'karyawan_id','id')->where('bulan', $now->month)->where('tahun', $now->year);
    }

    public function bank()
    {

        return $this->hasOne(Customer::class,'id','bank_id');
    }
}
