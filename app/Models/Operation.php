<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

    protected $table = 'operations';
	
	public $timestamps = false;

	protected $fillable = ['name','description'];
	public static $rules = [
		'name' => 'required|unique:operations,name',
    ];

	const TYPE_PRODUCTION = 1;
	const TYPE_OPERATION = 2;
	const TYPE_NONOP = 3;

	const TAXABLE = 1;
	const NON_TAXABLE = 0;

	public static function table()
	{
		return 'operations';
	}
}
