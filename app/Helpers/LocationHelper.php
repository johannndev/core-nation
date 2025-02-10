<?php

namespace App\Helpers;

use App\Models\LC;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;

class LocationHelper
{

    public function get_location($location_id = null)
	{
		if(!$location_id)
			$location_id = Auth::user()->location_id;

		
			$loc = array();

			//load the loc
			$location = Location::find($location_id);
			$child_ids = explode(',',$location->child_ids);
			$loc = LC::whereIn('location_id',array_filter(array_merge($child_ids,array($location_id))))->pluck('customer_id');
			if(!is_array($loc))
				$loc = array();

			//add 0 for empty ids
			$loc[] = -1;
			$loc[] = 0;

			return $loc;
		

		return $loc;
	}

}