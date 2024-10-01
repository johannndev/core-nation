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

    public function getGpuAttribute()
	{
        $gpuTotal = $this->bulanan+$this->harian+$this->premi;

		return $gpuTotal;
	}

    public function bank()
    {
        return $this->belongsTo(Customer::class,'bank_id','id');
    }

    public function bankSingle()
    {
        return $this->hasOne(Customer::class,'id','bank_id');
    }
}
