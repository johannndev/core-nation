<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatSell extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id', 
        'bulan', 
        'tahun', 
        'sender_id', 
        'type', 
        'sum_qty', 
        'sum_total'
    ];

    public static $types = array(
		2 => 'Sell',
		15 => 'Return',
	);

    public function getTypeNameAttribute()
	{
		return self::$types[$this->type];
	}

    public function sender()
	{
		return $this->belongsTo('App\Models\Customer','sender_id')->withTrashed();
	}

    public function group()
	{
		return $this->hasOne(ItemGroup::class, 'id', 'group_id');
	}
}
