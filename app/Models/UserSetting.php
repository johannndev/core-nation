<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;

	protected $guarded = [];

    protected $table = 'usersettings';

	const TYPE_WAREHOUSE = 1;
	const TYPE_ACCOUNT = 2;

	public static $list = array(
		'default_buy_warehouse' => array('desc' => 'Buy Warehouse','type' => self::TYPE_WAREHOUSE),
		'default_sell_warehouse' => array('desc' => 'Sell Warehouse','type' => self::TYPE_WAREHOUSE),
		'default_move_warehouse' => array('desc' => 'Move Warehouse','type' => self::TYPE_WAREHOUSE),
		'default_use_warehouse' => array('desc' => 'Use Warehouse','type' => self::TYPE_WAREHOUSE),
		'default_income_account' => array('desc' => 'Income Account','type' => self::TYPE_ACCOUNT),
		'default_expense_account' => array('desc' => 'Expense Account','type' => self::TYPE_ACCOUNT),
		'default_journal_account' => array('desc' => 'Journal Account','type' => self::TYPE_ACCOUNT),
	);

	public function warehouse()
	{
		return $this->hasOne(Customer::class,'id','value');
	}
}
