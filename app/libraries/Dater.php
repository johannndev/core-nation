<?
namespace App\Libraries;

use Carbon\Carbon;

class Dater extends Carbon
{
	public static $format = 'd/m/Y';
	public static $memberFormat = 'dmY';
	public static $SQLFormat = 'Y-m-d';
	public static $monthFormat = 'm/Y';

	public static function printToday()
	{
		return Dater::now()->format(self::$format);
	}

	public static function display($time = null)
	{
		if(empty($time) || $time <= 0 || $time == '0000-00-00') return '';
		if(preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/",$time)) return $time;
		return Dater::createFromFormat(static::$SQLFormat,$time)->format(self::$format);
	}

	public static function createMY($month,$year)
	{
		return Dater::createFromFormat('j/m/Y',"1/$month/$year");
	}

	public static function inputMY($my)
	{
		return Dater::createFromFormat('j/m/Y',"1/$my");
	}

	public static function displayMonthYear($time = null)
	{
		if(empty($time)) return '';
		return Dater::createFromFormat(static::$SQLFormat,$time)->format('m/Y');
	}

	public function toSQLFormat()
	{
		return $this->format(self::$SQLFormat);
	}

	public static function toSQL($date = null)
	{
		if(!$date) return '0000-00-00';
		if($date instanceof Dater || $date instanceof Carbon)
			return $date->format(self::$SQLFormat);
		return Dater::createFromFormat(self::$format, $date)->format(self::$SQLFormat);
	}

	public static function fromSQL($date)
	{
		return Dater::createFromFormat(self::$SQLFormat,$date);
	}

	public static function isEmpty($time)
	{
		if(empty($time) || intval($time) <= 0 || $time == '0000-00-00') return true;
		return false;
	}

	//only accepts SQL format
	public static function tutupBuku($date)
	{
		$validator = \Validator::make(array('date' => $date), array('date' => 'cdate'));
		if($validator->fails())
			return false;
		return true;
	}

	public static function fromJS($date)
	{
		return Dater::createFromTimestamp(strtotime($date));
	}
}