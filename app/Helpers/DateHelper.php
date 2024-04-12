<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static $format = 'd/m/Y';
	public static $memberFormat = 'dmY';
	public static $SQLFormat = 'Y-m-d';
	public static $monthFormat = 'm/Y';

    
    public static function display($time = null)
	{
		if(empty($time) || $time <= 0 || $time == '0000-00-00') return '';
		if(preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/",$time)) return $time;
		return Carbon::createFromFormat(static::$SQLFormat,$time)->format(self::$format);
	}

	public static function toSQL($date = null)
	{
		if(!$date) return '0000-00-00';
		if($date instanceof DateHelper || $date instanceof Carbon)
			return $date->format(self::$SQLFormat);
		return Carbon::createFromFormat(self::$format, $date)->format(self::$SQLFormat);
	}

}




