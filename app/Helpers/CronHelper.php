<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CronHelper
{

    public static function refreshCronCache()
    {
        $crons = DB::table('cronruns')->get();
        Cache::forever('active_crons', $crons);
    }

    public static function getCachedCrons()
    {
        return Cache::rememberForever('active_crons', function () {
            return DB::table('cronruns')->get();
        });

        
    }

    public static function getActiveCrons()
    {
        return self::getCachedCrons()->filter(function ($cron) {
            return $cron->status == 1 && (
                $cron->active_until === null ||
                now()->lte(\Carbon\Carbon::parse($cron->active_until))
            );
        });
    }
}




