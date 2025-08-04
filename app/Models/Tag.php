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
	const TYPE_WARNA = 20;

	protected $fillable = array('name', 'code', 'type', 'price');

	protected $rules = array(
		'name' => 'required'
	);

	public $timestamps = false;

	public static $types = array(
//		self::TYPE_NORMAL => 'Normal',
		self::TYPE_JAHIT => 'Jahit',
		self::TYPE_TYPE => 'Type',
		self::TYPE_SIZE => 'Size',
		self::TYPE_WARNA => 'Warna',
//		self::TYPE_COMPONENT => 'Komponen',
//		self::TYPE_VARIATION => 'Variasi',
	);

	public static $typesCreate = array(
		self::TYPE_JAHIT => 'Jahit',
		self::TYPE_TYPE => 'Type',
		self::TYPE_SIZE => 'Size',
	);

	public static $asetLancarCreate = array(
		self::TYPE_SIZE =>['name'=>'Size','name_input'=>'sizes'],
	);

	public static $typesJSON = array(
		array('id' => self::TYPE_JAHIT,'name' => 'Jahit'),
		array('id' => self::TYPE_TYPE,'name' => 'Type'),
		array('id' => self::TYPE_SIZE,'name' => 'Size'),
		array('id' => self::TYPE_WARNA,'name' => 'Warna'),
	);

	public function getTypeNameAttribute()
	{
		return self::$types[$this->type];
	}

	public function getItemTypeTextAttribute()
	{
		if ($this->item_type > 0) {
			return Item::$types[$this->item_type] ?? 'Unknown';
		}

		return 'All';
	}

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
