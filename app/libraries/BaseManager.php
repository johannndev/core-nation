<?
namespace App\Libraries;
use Illuminate\Support\MessageBag;
use Apps, App, Cache, Config, Dater, DB, Event, Input, InputForm, Redirect, Response, Session, URL, View, ModelException, Exception, Auth;
class BaseManager
{
	protected $messages;
	protected $_txn;

	public function __construct()
	{
		$this->messages = new MessageBag;
	}

	protected function error($message = null, $name = null)
	{
		if(!$name) $name = 'error';
		if(!$message)
			$this->messages->add($name,$this->messages->first());
		elseif($message instanceof MessageBag)
			$this->messages = $message;
		else
			$this->messages->add($name,$message);

		return false;
	}

	public function getErrors()
	{
		return $this->messages;
	}

	public function getError()
	{
		return $this->messages->first();
	}
}