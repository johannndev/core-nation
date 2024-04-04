<?
namespace App\Libraries;
use App\Models\WarehouseItem;
use Apps, App, Cache, Config, Dater, DB, Event, Input, InputForm, Redirect, Response, Session, URL, View, ModelException, Exception, Auth;
class StockManager
{
	const ONLINE_SBY = 2875;
	const ONLINE_WTC = 2792;
	const CENTRAL = 2628;
	const SAMBISARI = 2874;
	const WTC = 2839;
	const M_PIM = 1874;
	const M_PURI = 2205;
	const S_EMPO = 2196;

	public static $names = array(
		self::ONLINE_SBY => 'Jubelio',
		self::ONLINE_WTC => 'Online WTC',
		self::SAMBISARI => 'Sambisari',
		self::WTC => 'WTC',
		self::M_PIM => 'PIM',
		self::M_PURI => 'Puri',
		self::CENTRAL => 'Central',
		self::S_EMPO => 'Empo',
	);

	public static $list  = array(
		self::ONLINE_SBY,
		self::ONLINE_WTC,
		self::SAMBISARI,
		self::WTC,
		self::M_PIM,
		self::M_PURI,
		self::CENTRAL,
		self::S_EMPO,
	);

	public static function checkBatch($item_ids)
	{
		$result = array();
		$result['warehouse'] = array();
		$result['item'] = array();
		$data = WarehouseItem::whereIn('warehouse_id',self::$list)->whereIn('item_id',array_unique($item_ids))->get();
		foreach ($data as $value) {
			//store data by warehouse
			if(!isset($result['warehouse'][$value->warehouse_id]))
				$result['warehouse'][$value->warehouse_id] = array();
			$result['warehouse'][$value->warehouse_id][$value->item_id] = $value->quantity;

			//store data by item
			if(!isset($result['item'][$value->item_id]))
				$result['item'][$value->item_id] = array();
			$result['item'][$value->item_id][$value->warehouse_id] = $value->quantity;
		}
		return $result;
	}
}
