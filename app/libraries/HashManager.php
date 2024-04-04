<?
namespace App\Libraries;

use App\Models\Updater,App\Models\HashTag,App\Models\HT,App\Models\Transaction;
use App\Libraries\Keys;
use Apps, App, Cache, Config, Dater, DB, Event, Input, InputForm, Redirect, Response, Session, URL, View, ModelException, Exception, Auth;
class HashManager extends StaticManager
{
	public static $pattern = '/#(\w+)/';
	public static function parse($text)
	{
		preg_match_all(self::$pattern, $text, $matches);
		if(empty($matches[1]))
			return array();
		return $matches[1];
	}

	public static function save($t)
	{
		$data = self::parse($t->description);
		if(empty($data))
			return true;

		//save in the start of the month
		$date = Dater::fromSQL($t->date);

		$hashes = array();
		$links = array();
		foreach ($data as $key) {
			$hash = HashTag::firstOrCreate(array('name' => strtolower($key)));
			$ht = new HT;
			$ht->hash_id = $hash->id;
			$ht->transaction_id = $t->id;
			$ht->total = abs($t->total);
			$ht->month = $date->month;
			$ht->year = $date->year;
			$ht->date = $t->date;
			$ht->transaction_type = $t->type;
			$ht->sender_id = $t->sender_id;
			$ht->receiver_id = $t->receiver_id;
			$ht->sender_type = $t->sender_type;
			$ht->receiver_type = $t->receiver_type;
			if(!$ht->save())
				throw new Exception('cannot save hash transaction relation', 1);

			$hashes[] = '#'.$hash->name;
			$links[] = '<a href="'.URL::action('HashController@getTransactions',array($hash->id)).'">#'.$hash->name.'</a>';
//			self::toggleUpdater($hash->id);
		}

		$t->description = str_replace($hashes, $links, $t->description);
		$t->save();
		return true;
	}

	public static function delete($t)
	{
		HT::where('transaction_id','=',$t->id)->delete();
		return true;
	}

	public static function edit($t)
	{
		self::delete($t);
		self::save($t);
	}

	protected static function toggleUpdater($entity_id,$update = true)
	{
		if(!$u = Updater::where('entity_id','=',$entity_id)->where('app_id','=',Apps::TRACK_HASHTAG)->first())
			$u = new Updater;
		$u->date = Dater::now()->format(Dater::$SQLFormat);
		$u->entity_id = $entity_id;
		$u->app_id = Apps::TRACK_HASHTAG;
		if($update)
			$u->flag = Updater::NEED_UPDATE;
		else
			$u->flag = Updater::NO_UPDATE;

		if(!$u->save())
			throw new \Exception('cannot save updater');
		Cache::forget(Keys::updater($entity_id,Apps::TRACK_HASHTAG));
	}

	protected static function checkUpdater($hash_id)
	{
//		Cache::forget(Keys::updater($hash_id,Apps::TRACK_HASHTAG));
		$u = Cache::remember(Keys::updater($hash_id,Apps::TRACK_HASHTAG), Keys::CACHE_GENERIC_TIME, function () use($hash_id) {
			return Updater::where('entity_id','=',$hash_id)->where('app_id','=',Apps::TRACK_HASHTAG)->first();
		});
		if(!$u) return true;
		if($u->flag == Updater::NEED_UPDATE) return true;
		return false;
	}
}