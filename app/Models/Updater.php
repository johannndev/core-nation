<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Updater extends Model
{
    use HasFactory;

    const NEED_UPDATE = 0;
	const NO_UPDATE = 1;
	protected $table = 'updater';

	public function customer()
	{
		return $this->belongsTo('App\Models\Customer','entity_id');
	}

	public $timestamps = false;
}
