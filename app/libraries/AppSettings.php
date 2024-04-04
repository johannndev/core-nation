<?
namespace App\Libraries;
use App\Models\AppSetting;
use Apps, Cache, Config, Dater, DB, Event, Input, InputForm, Redirect, Response, Session, URL, View, ModelException, Exception, Auth;
class AppSettings extends BaseManager
{
	const CACHE_KEY = 'app_settings.';
	const CACHE_TIME = 3600;
	protected $_data;
	public function __construct()
	{
		$this->load();
	}

	public function getAll()
	{
		if(!empty($this->_data)) return $this->_data;
		return $this->load();
	}

	public function load()
	{
		$this->_data = Cache::remember(self::CACHE_KEY, self::CACHE_TIME, function () {
			$app = AppSetting::lists('value','name');
			return $app;
		});
		return $this->_data;
	}

	public function get($key)
	{
		return isset($this->_data[$key])?$this->_data[$key]:null;
	}

	public function flush()
	{
		Cache::forget(self::CACHE_KEY);
		$this->_data = array();
	}

	public function edit($input)
	{
		foreach($this->_data as $name => $value)
		{
			if(!isset($input[$name]))
				AppSetting::where('name','=',$name)->update(array('value' => 0));
			else
			{
				if($input[$name] == $this->_data[$name]) continue;
				AppSetting::where('name','=',$name)->update(array('value' => $input[$name]));
			}
		}
		$this->flush();
		return true;
	}
}
