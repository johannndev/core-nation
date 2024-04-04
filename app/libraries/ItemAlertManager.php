<?
namespace App\Libraries;
use Apps, App, Cache, Config, Dater, DB, Event, Input, InputForm, Redirect, Response, Session, URL, View, ModelException, Exception, Auth;
class ItemAlertManager extends BaseManager
{
	public static $errors;
	public static $iam;

	public static function check($item_id,$quantity,$item_code)
	{
		//1. check local
		if(!self::$iam) self::$iam = array();
		if(isset(self::$iam[$item_id]))
		{
			$alert = self::evaluate($item_id,$quantity);
		}
		else //2. check cache
		{
			$data = Cache::remember('itemalert.'.$item_id, function () use($item_id) {
				$result = ItemAlert::where('item_id','=',$item_id)->first();
				if(!$result) return false;
				return array('type' => $result->type,'quantity' => $result->quantity);
			}, 86400);
			if(!$data) return false;
			self::$iam[$item_id] = $data;
			$alert = self::evaluate($item_id,$quantity);
		}

		//3. check alert
		if(!$alert)
			return false;
		//4. create alert
		$alert = new Alert;
		$alert->message = 'Item: '.$item_code.' tinggal '.$quantity.'!!!!!!!!';
		$alert->expires = 0;
		$alert->save();
	}

	protected static function evaluate($item_id,$quantity)
	{
		switch(self::$iam[$item_id]['type'])
		{
			case ItemAlert::LE: return ($quantity <= self::$iam[$item_id]['quantity']); break;
			case ItemAlert::GE: return ($quantity >= self::$iam[$item_id]['quantity']); break;
			default: return false; break;
		}
	}

	protected static function error($msg = null)
	{
		if($msg)
			self::$errors->add('error',$msg);
		else
			self::$errors->add('error','error saving customer data');

		return false;
	}
}
?>