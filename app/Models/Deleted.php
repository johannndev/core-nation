<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Deleted extends Model
{
    use HasFactory;

    protected $table = 'deleted';

    protected $guarded = [];

	public static $rules = array(
		'date' => 'required|cdate',
	);

    public function receiver()
	{
		return $this->belongsTo('App\Models\Customer','receiver_id')->withTrashed();
	}

	public function sender()
	{
		return $this->belongsTo('App\Models\Customer','sender_id')->withTrashed();
	}

    public function user()
	{
		return $this->belongsTo('App\Models\User','user_id');
	}

    public function transactionDetail(): HasMany
    {
        return $this->hasMany(DeletedDetail::class, 'transaction_id', 'id');
    }


    public function getTypeNameAttribute()
	{
		return Transaction::$types[$this->type];
	}


}
