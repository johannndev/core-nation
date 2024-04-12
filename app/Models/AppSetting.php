<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use HasFactory;

    protected $table = 'app_settings';

	public static $desc = array(
		'tutup_buku' => 'tanggal tutup buku tiap bulan',
		'sell_100' => 'treat sell items w/ 100% discount as journal entry',
		'ongkir' => 'treat shipping cost as journal entry',
	);

	public static function table()
	{
		return 'app_settings';
	}
}
