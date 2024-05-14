<?php

namespace App\Models;

use App\Helpers\LocalHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class ItemGroup extends Model
{
    use HasFactory;

    protected $table = 'item_group';

	protected $fillable = array('name');

	public static function table()
	{
		return 'item_group';
	}

	public static $rules = array(
		'name' => 'required|unique:item_group,name',
	);

	public function items()
	{
		return $this->hasMany('App\Models\Item','group_id');
	}

	public $timestamps = false;

	// public function getDetailLink()
	// {
	// 	return URL::action('ItemsController@getGroupDetail', $this->id);
	// }

	// public function getStatLink()
	// {
	// 	return URL::action('ItemsController@getGroupStat', $this->id);
	// }

	public function getUploadPath()
	{
		$folder = str_pad(substr($this->id, -2), 2, '0', STR_PAD_LEFT);

		return Config::get('local.item_image_path').$folder;
	}

	public function getImageUrl($number = 0)
	{
		return self::getImagePath($this->id, $number);
	}

	public static function getImagePath($id, $number = 0)
	{
		$folder = str_pad(substr($id, -2), 2, '0', STR_PAD_LEFT);

		if($number > 0)
			return 'https://cdn.corenationactive.com'.LocalHelper::$var['item_image_url'].$folder.'/'.$id.'-'.$number.'.jpg';
		return 'https://cdn.corenationactive.com'.LocalHelper::$var['item_image_url'].$folder.'/'.$id.'.jpg';
	}
}
