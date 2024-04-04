<?
namespace App\Libraries;
use App\Models\BaseModel;
use Apps, App, Cache, Config, Dater, DB, Event, Input, Redirect, Response, Session, URL, View, ModelException, Exception, Auth;
class InputForm
{
	public static $data = array();

	public static function get($keys, $default = null)
	{
		if(!is_array($keys))
			return self::has($keys)?self::$data[$keys]:$default;

		$return = array();

		foreach($keys as $key)
		{
			if(self::has($key))
				$return[$key] = self::get($key);
		}

		return $return;
	}

	public static function clear()
	{
		self::$data = array();
		Input::flush();
	}

	//accepts key[key2][key3]
	//checks local data
	//recursive
	public static function has($keys)
	{
		return self::recursive_has($keys,self::$data);
	}

	protected static function recursive_has($keys,$data)
	{
		if(!is_array($keys))
			$keys = self::keyToArray($keys);

		if(count($keys) == 1) return isset($data[$keys[0]]);
		elseif(!isset($data[$keys[0]])) return false;

		if(isset($data[$keys[0]]))
		{
			$data = $data[$keys[0]];
			array_shift($keys);
			return self::recursive_has($keys,$data);
		}
		return false;
	}

	//loads a data to local storage, name = custom name
	public static function load($data, $name = false)
	{
		//check if its a model
		if($data instanceof BaseModel)
		{
			if(!$name)
				return self::$data = $data->toArray();
			self::$data[$name] = $data->toArray();
			return self::$data[$name];
		}

		if(!$name)
			return self::$data = $data;

		self::$data[$name] = $data;
		return self::$data;
	}

	//gets old input
	//recursive
	public static function old($keys, $default = null)
	{
		return self::recursive_old($keys,$default,self::$data);
	}

	protected static function recursive_old($keys,$default,$data)
	{
		if(!is_array($keys))
			$keys = self::keyToArray($keys);

		if(count($keys) == 1)
		{
			//check input
			if(isset($data[$keys[0]]))
				return $data[$keys[0]];
			//check local
			if(isset(self::$data[$keys[0]]))
				return self::$data[$keys[0]];
			return $default;
		}

		if(isset($data[$keys[0]]) || isset(self::$data[$keys[0]]))
		{
			$data = isset($data[$keys[0]])?$data[$keys[0]]:self::$data[$keys[0]];
			array_shift($keys);
			return self::recursive_old($keys,$default, $data);
		}
		return $default;
	}

	public static function set_array($a)
	{
		if(!is_array($a))
			return;
		foreach($a as $key => $val)
		{
			self::$data[$key] = $val;
		}
	}

	protected static function keyToArray($key)
	{
		$key = str_replace(']','',$key);
		return array_filter(explode('[',$key.'[['),'strlen');
	}

	public static function text($field, $error = false, $label = null, $default = null)
	{
		return self::field('text',$field, $error, $label, $default);
	}

	public static function textarea($field, $error = false, $label = null, $default = null)
	{
		return self::field('textarea',$field, $error, $label, $default);
	}

	public static function select($field, $data, $error = false, $label = null, $default = null)
	{
		return self::field('select',$field, $error, $label, $default, $data);
	}

	public static function date($field, $error = false, $label = null, $default = null)
	{
		return self::field('date',$field, $error, $label, $default);
	}

	protected static function field($type, $field, $error, $label, $default, $data = null)
	{
		if(is_array($error) && !empty($error)) $error = $error[0];
		if(!$label) $label = $field;
		$class = false;
		if($error)
			$class = ' has-error';

		if(!$default)
			$default = InputForm::old($field, null);

		$return = '<div class="form-group'.$class.'">'.Form::label($label);
		switch($type)
		{
			case 'text':
				if(!$error)
					return $return.Form::text($field,$default,array('class' => 'form-control')).'</div>';
				return $return.Form::text($field,$default,array('class' => 'form-control')).'<span class="help-block">'.$error.'</span></div>';
				break;
			case 'textarea':
				if(!$error)
					return $return.Form::textarea($field,$default,array('class' => 'form-control')).'</div>';
				return $return.Form::textarea($field,$default,array('class' => 'form-control')).'<span class="help-block">'.$error.'</span></div>';
				break;
			case 'date':
				if(!$error)
					return $return.Form::text($field,$default,array('class' => 'datepick form-control')).'</div>';
				return $return.Form::text($field,$default,array('class' => 'datepick form-control')).'<span class="help-block">'.$error.'</span></div>';
				break;
			case 'select':
				if(!$error)
					return $return.Form::select($field,$data,$default);
				return $return.Form::select($field,$data,$default,array('class' => 'form-control')).'<span class="help-block">'.$error.'</span></div>';
			default: return false; break;
		}
	}
}