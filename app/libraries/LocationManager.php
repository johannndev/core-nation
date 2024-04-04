<?
namespace App\Libraries;
use App\Models\User,App\Models\LC,App\Models\Location;
use App\Libraries\Keys;
use Apps, App, Cache, Config, Dater, DB, Event, Input, InputForm, Redirect, Response, Session, URL, View, ModelException, Exception, Auth;
class LocationManager extends BaseManager
{
	const ADMIN = 1;
	const TIME = 600;

	const TYPE_KIDS = 1;
	const TYPE_PARENTS = 2;

	const CACHE_KEY = 'location_data_';

	protected $_loc;
	protected $_user;

	public function __construct($txn = null)
	{
		parent::__construct($txn);
		$this->_user = Auth::user();
		if(!$this->_user) $this->_user = new User;
		if(!empty($this->_user->location_id))
			$this->get_location();
	}

	public function bound()
	{
		if($this->_user->role_id == Access::ADMIN) return false;
		return ($this->_user->location_id > 0);
	}

	public function free()
	{
		return $this->_user->location_id == 0;
	}

	public function can($id,$loc = null)
	{
		if($this->_user->location_id == 0)
			return true;

		if(!$loc) $loc = $this->_user->location_id;
		if(!isset($this->_loc[$loc]) || empty($this->_loc[$loc])) $this->get_location($loc);

		return in_array($id,$this->_loc[$loc]);
	}

	public function get_location($location_id = null)
	{
		if(!$location_id)
			$location_id = $this->_user->location_id;

		$this->_loc[$location_id] = Cache::remember(Keys::location($location_id), Keys::CACHE_GENERIC_TIME, function () use($location_id) {
			$loc = array();

			//load the loc
			$location = Location::find($location_id);
			$child_ids = explode(',',$location->child_ids);
			$loc = LC::whereIn('location_id',array_filter(array_merge($child_ids,array($location_id))))->lists('customer_id');
			if(!is_array($loc))
				$loc = array();

			//add 0 for empty ids
			$loc[] = -1;
			$loc[] = 0;

			return $loc;
		});

		return $this->_loc[$location_id];
	}

	public function get_locations($loc = array())
	{
		if(!is_array($loc)) $loc = array($loc);
		$return = array();
		foreach($loc as $loc_id)
		{
			$return = array_merge($return,$this->get_location($loc_id));
		}
		return array_unique($return);
	}

	public function forget($id = null)
	{
		if(!$id) $id = $this->_user->location_id;
		if(isset($this->_loc[$id])) $this->_loc[$id] = null;

		Cache::forget(Keys::location($id));
	}

	public static function forgetParents($loc)
	{
		$parents = $loc->parents();
		foreach($parents as $p)
			Cache::forget(Keys::location($p->id));
	}

	public function assign($lid,$cid)
	{
		$lc = new LC;
		$lc->location_id = $lid;
		$lc->customer_id = $cid;
		if(!$lc->save())
			throw new \Exception('cannot save notifs.<br/>'.$n->getErrors()->first());
		Cache::forget(Keys::location($lid));
	}
}