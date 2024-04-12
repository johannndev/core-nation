<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = array('name', 'code', 'pcode', 'price' , 'cost', 'description', 'description2','url');

	const BRAND_00 = 0;
	const BRAND_CA = 1;
	const BRAND_CC = 2;
	const BRAND_CB = 3;
	const BRAND_CD = 4;
	const BRAND_CR = 5;
	const BRAND_CN = 6;
	const BRAND_CM = 7;
	const BRAND_CX = 8;
	const BRAND_CS = 9;
	const BRAND_HJ = 10;
	const BRAND_CP = 11;
	const BRAND_CJ = 12;
	const BRAND_PL = 13;
	const BRAND_DC = 14;
	const BRAND_CE = 15;
	const BRAND_CI = 16;
	const BRAND_CX0 = 17;
	const BRAND_CX7 = 18;
	const BRAND_CX8 = 19;
	const BRAND_CX9 = 20;

	public static $brands = array(
		self::BRAND_00 => 'No Brand',
		self::BRAND_CA => 'CA',
		self::BRAND_CC => 'CC',
		self::BRAND_CB => 'CB',
		self::BRAND_CD => 'CD',
		self::BRAND_CR => 'CR',
		self::BRAND_CN => 'CN',
		self::BRAND_CM => 'CM',
		self::BRAND_CX => 'CX',
		self::BRAND_CS => 'CS',
		self::BRAND_HJ => 'HJ',
		self::BRAND_CP => 'CP',
		self::BRAND_CJ => 'CJ',
		self::BRAND_PL => 'PL',
		self::BRAND_DC => 'DC',
		self::BRAND_CE => 'CE',
		self::BRAND_CI => 'CI',
		self::BRAND_CX0 => 'CX0',
		self::BRAND_CX7 => 'CX7',
		self::BRAND_CX8 => 'CX8',
		self::BRAND_CX9 => 'CX9',
	);

	public static $brandsJSON = array(
		array('id' => self::BRAND_00, 'name' => 'No Brand'),
		array('id' => self::BRAND_CA, 'name' => 'CA'),
		array('id' => self::BRAND_CC, 'name' => 'CC'),
		array('id' => self::BRAND_CB, 'name' => 'CB'),
		array('id' => self::BRAND_CD, 'name' => 'CD'),
		array('id' => self::BRAND_CR, 'name' => 'CR'),
		array('id' => self::BRAND_CN, 'name' => 'CN'),
		array('id' => self::BRAND_CM, 'name' => 'CM'),
		array('id' => self::BRAND_CX, 'name' => 'CX'),
		array('id' => self::BRAND_CS, 'name' => 'CS'),
		array('id' => self::BRAND_HJ, 'name' => 'HJ'),
		array('id' => self::BRAND_CP, 'name' => 'CP'),
		array('id' => self::BRAND_CJ, 'name' => 'CJ'),
		array('id' => self::BRAND_PL, 'name' => 'PL'),
		array('id' => self::BRAND_DC, 'name' => 'DC'),
		array('id' => self::BRAND_CE, 'name' => 'CE'),
		array('id' => self::BRAND_CI, 'name' => 'CI'),
		array('id' => self::BRAND_CX0, 'name' => 'CX0'),
		array('id' => self::BRAND_CX7, 'name' => 'CX7'),
		array('id' => self::BRAND_CX8, 'name' => 'CX8'),
		array('id' => self::BRAND_CX9, 'name' => 'CX9'),
	);

	const TYPE_ITEM = 1;
	const TYPE_ASSET_LANCAR = 2;
	const TYPE_ASSET_TETAP = 3;
	const TYPE_SERVICE = 5;

	public static function table()
	{
		return 'items';
	}

	public static $types = array(
		self::TYPE_ITEM => 'Item',
		self::TYPE_ASSET_LANCAR => 'Asset Lancar',
		self::TYPE_ASSET_TETAP => 'Asset Tetap',
	);

	
	public function warehouseItem(): HasMany
    {
        return $this->hasMany(WarehouseItem::class, 'item_id', 'id');
    }
}
