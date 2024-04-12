<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTag extends Model
{
    use HasFactory;

    protected $table = 'item_tag';
	public $timestamps = false;

	public static function table()
	{
		return 'item_tag';
	}

	public function tag()
	{
		return $this->belongsTo('App\Models\Tag','tag_id');
	}

	public function item()
	{
		return $this->belongsTo('App\Models\Item','item_id');
	}
}
