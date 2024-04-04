<?
namespace App\Libraries;
use Illuminate\Support\MessageBag;
use Apps, App, Cache, Config, Dater, DB, Event, Input, InputForm, Redirect, Response, Session, URL, View, ModelException, Exception, Auth;
class StaticManager
{
	protected static $messages;

	protected static function error($message = null, $name = null)
	{
		if(!self::$messages instanceof MessageBag)
			self::$messages = new MessageBag;

		if(!$name) $name = 'error';
		if(!$message)
			self::$messages->add($name,self::$messages->first());
		elseif($message instanceof MessageBag)
			self::$messages = $message;
		else
			self::$messages->add($name,$message);

		return false;
	}

	public static function getErrors()
	{
		return self::$messages;
	}

	public static function getError()
	{
		return self::$messages->first();
	}
}