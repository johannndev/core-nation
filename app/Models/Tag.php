<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    const TYPE_NORMAL = 0;
	const TYPE_TYPE = 3; //type, ats, apj, c34, c78
	const TYPE_SIZE = 7; //m,l,xl
	const TYPE_COMPONENT = 8; //bra, topi, sabuk, motif, doreng
	const TYPE_JAHIT = 2;
	const TYPE_MATERIAL = 9;
	const TYPE_VARIATION = 10;

	protected $fillable = array('name', 'code', 'type', 'price');

	protected $rules = array(
		'name' => 'required'
	);

	public static $types = array(
//		self::TYPE_NORMAL => 'Normal',
		self::TYPE_JAHIT => 'Jahit',
		self::TYPE_TYPE => 'Type',
		self::TYPE_SIZE => 'Size',
//		self::TYPE_COMPONENT => 'Komponen',
//		self::TYPE_VARIATION => 'Variasi',
	);

	public static $typesJSON = array(
		array('id' => self::TYPE_JAHIT,'name' => 'Jahit'),
		array('id' => self::TYPE_TYPE,'name' => 'Type'),
		array('id' => self::TYPE_SIZE,'name' => 'Size'),
	);

	public static function loadSizes()
	{
		return Tag::where('type','=',self::TYPE_SIZE)->pluck('code','id');
	}

	public static function loadGenres()
	{
		$genre = Tag::where('type','=',self::TYPE_TYPE);

		$genre = $genre->pluck('code','id');

		return $genre;
	}

	public static function table()
	{
		return 'tags';
	}

	public function items()
	{
		return $this->belongsToMany('App\Models\Item','item_tag');
	}

	public function ongkos()
	{
		return $this->hasOne('App\Models\OngkosTag','tag_id');
	}
}
