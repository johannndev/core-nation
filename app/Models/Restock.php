<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restock extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'date',
        'status',
        'restocked_quantity',
        'in_production_quantity',
        'shipped_quantity',
        'missing_quantity',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
