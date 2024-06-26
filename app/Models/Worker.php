<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Worker extends Model
{
    use HasFactory,SoftDeletes;


    protected $dates = ['deleted_at'];

	const TYPE_POTONG = 1;
	const TYPE_JAHIT = 2;

	protected $table = 'prod_worker';
	protected $fillable = ['name'];

	public function scopeJahit($query)
	{
		$query = $query->where('type','=',Worker::TYPE_JAHIT);
		return $query;
	}

	public function scopePotong($query)
	{
		$query = $query->where('type','=',Worker::TYPE_POTONG);
		return $query;
	}
}
