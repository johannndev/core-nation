<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyRecord extends Model
{
    use HasFactory;

    protected $table = 'monthly_records';

	public static function table()
	{
		return 'monthly_records';
	}

    public $timestamps = false;
}
