<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borongan extends Model
{
    use HasFactory;

    protected $table = 'prod_borongan';
	protected $fillable = array('date', 'jahit_id', 'tres', 'permak' ,'lain2');

	public static function table()
	{
		return 'borongan';
	}

	public function user()
	{
		return $this->belongsTo('App\Models\User','user_id');
	}

	public function jahit()
	{
		return $this->belongsTo('App\Models\Worker','jahit_id');
	}

	public $timestamps = false;

	// public function getDetailLink()
	// {
	// 	return \URL::action('BoronganController@getDetail',array($this->id));
	// }
}
