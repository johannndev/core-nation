<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestyPayload extends Model
{
    use HasFactory;

    protected $guarded = [];

    // otomatis cast item_list ke array
    protected $casts = [
        'item_list' => 'array',
    ];
}
