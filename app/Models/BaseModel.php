<?php

namespace App\Models;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasFactory;

    public function getErrors()
	{
		if(!$this->errors instanceof MessageBag)
			$this->errors = new MessageBag;
		return $this->errors->toArray();
	}

    public function addError($key, $msg)
	{
		if(!$this->errors instanceof MessageBag)
			$this->errors = new MessageBag;
		$this->errors->add($key, $msg);
		return false;
	}
}
