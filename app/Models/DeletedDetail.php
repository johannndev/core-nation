<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeletedDetail extends Model
{
    use HasFactory;

    protected $table = 'deleted_details';

    protected $guarded = [];
    public $timestamps = false;


    public function item()
	{
		return $this->belongsTo('App\Models\Item','item_id');
	}
}
