<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    public function gajih()
    {
        return $this->hasMany(Gajih::class,'karyawan_id','id');
    }
}
