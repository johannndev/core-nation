<?php

namespace App\Helpers;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Cache;

class AppSettingsHelper
{
    const CACHE_KEY = 'app_settings.';
	const CACHE_TIME = 3600;
	protected $_data;
	public function __construct()
	{
		$this->load();
	}

    public function load()
	{
		$this->_data = Cache::remember(self::CACHE_KEY, self::CACHE_TIME, function () {
			$app = AppSetting::pluck('value','name');
			return $app;
		});
		return $this->_data;
	}

    public function getAppSettings($name)
    {
        $getCache = Cache::get(self::CACHE_KEY);

        $value= $getCache[$name];


        return $value;
    }
}




