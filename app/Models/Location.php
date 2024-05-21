<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $table = 'locations';

	public function customers()
	{
		return $this->belongsToMany('App\Models\Customer', 'location_customer');
	}

	public function parent_ids()
	{
		return array_filter(explode(',',$this->parent_ids));
	}

	public function parents()
	{
		$ids = $this->parent_ids();
		if(empty($ids)) return array();
		return Location::whereIn('id',$ids)->get();
	}

	public function child_ids()
	{
		return array_filter(explode(',',$this->child_ids));
	}

	public function children()
	{
		$ids = $this->child_ids();
		if(empty($ids)) return array();
		return Location::whereIn('id',$ids)->get();
	}

	public function viewables()
	{
		$ids = $this->child_ids();
		if(!is_array($ids)) $ids = array();
		$ids[] = $this->id;
		return $ids;
	}
}
