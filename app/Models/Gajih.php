<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Gajih extends Model
{
    use HasFactory;

    public function karyawan()
    {
        $role = Auth::user()->getRoleNames()[0];

        $data = $this->hasOne(Karyawan::class,'id','karyawan_id');

        if($role != 'superadmin'){
            $data = $data->where('flag',1);
        }

        return $data;
    }
}
