<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HashTag extends Model
{
    use HasFactory;

    protected $table = 'hashtags';
	protected $fillable = array('name');

	public static function table()
	{
		return 'hashtags';
	}
}
