<?php

namespace App\Models;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Po extends Model
{
    use HasFactory;

    protected $table = 'pos';

    protected $guarded = [];


	public function receiver()
	{
		return $this->belongsTo('App\Models\Customer','receiver_id')->withTrashed();
	}

	public function sender()
	{
		return $this->belongsTo('App\Models\Customer','sender_id')->withTrashed();
	}

	public function user(): HasOne
	{
		return $this->hasOne(User::class, 'id','receiver_id');
	}

	public function customer(): HasOne
    {
        return $this->hasOne(User::class, 'id','user_id');
    }

	public function lokasi(): HasOne
    {
        return $this->hasOne(Location::class, 'id','location_id');
    }

	public function transactionDetail(): HasMany
    {
        return $this->hasMany(PoDetail::class, 'transaction_id', 'id');
    }

	public function addError($key, $msg)
	{
		if(!$this->errors instanceof MessageBag)
			$this->errors = new MessageBag;
		$this->errors->add($key, $msg);
		return false;
	}

	public function getErrors()
	{
		if(!$this->errors instanceof MessageBag)
			$this->errors = new MessageBag;
		return $this->errors->toArray();
	}
}
