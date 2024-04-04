<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
	public $timestamps = true;
	protected $fillable = array('entity_id', 'app_id');

	public static function table()
	{
		return 'notifications';
	}

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
