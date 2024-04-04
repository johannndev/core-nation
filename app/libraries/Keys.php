<?
namespace App\Libraries;
class Keys
{
	//times
	const CACHE_GENERIC_TIME = 600;
	const CACHE_SHORT_TIME = 300;
	const CACHE_1D = 86400;

	//keys
	const CACHE_UPDATER = 'updater';
	const CACHE_TRACKER = 'tracker';
	const CACHE_NOTIF = 'notif';
	const CACHE_USER_SETTINGS = 'user_settings';
	const CACHE_LOCATION = 'location_data';
	const CACHE_ADVICE = 'advice';
	const CACHE_ASSET = 'asset';

	public static function location($id)
	{
		return self::CACHE_LOCATION.'.'.$id;
	}

	public static function updater($entity_id,$app_id)
	{
		return self::CACHE_UPDATER.'.'.$entity_id.'.'.$app_id;
	}

	public static function tracker($id)
	{
		return self::CACHE_TRACKER.'.'.$id;
	}

	public static function notifs()
	{
		return self::CACHE_NOTIF;
	}

	public static function us($id)
	{
		return self::CACHE_USER_SETTINGS.'.'.$id;
	}

	public static function advice()
	{
		return self::CACHE_ADVICE;
	}

	public static function asset($id)
	{
		return self::CACHE_ASSET.'.'.$id;
	}

	public static function province()
	{
		return 'province';
	}

	public static function city($pid)
	{
		return 'city.'.$pid;
	}
}