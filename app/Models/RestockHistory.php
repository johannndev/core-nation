<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestockHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'restock_id',
        'item_id',
        'step',
        'action',
        'qty_before',
        'qty_after',
        'qty_changed',
        'invoice',
        'user_id',
        'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restock()
    {
        return $this->belongsTo(Restock::class);
    }
    
}
