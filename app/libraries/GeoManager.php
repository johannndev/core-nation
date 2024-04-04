<?
namespace App\Libraries;

use App\Models\Province, App\Models\City, App\Libraries\Keys, App\Models\CustomerStat;
use App\Models\Customer, App\Models\CustomerClass;
class GeoManager extends BaseManager
{
	protected static $_province;
	protected static $_city;

	public static function getProvinces()
	{
		if(is_array(self::$_province) && !empty(self::$_province))
			return self::$_province;

		self::$_province = Cache::remember(Keys::province(), Keys::CACHE_1D, function () {
			$p = Province::get();
			return $p;
		});

		return self::$_province;
	}

	public static function getCities($pid = 0)
	{
		if(isset(self::$_city[$pid]) && !empty(self::$_city[$pid]))
			return self::$_city[$pid];

		self::$_city[$pid] = array();
		self::$_city[$pid] = Cache::remember(Keys::city($pid), Keys::CACHE_1D, function () use($pid) {
			$c = City::where('province_id', '=', $pid)->get();
			return $c;
		});

		return self::$_city[$pid];
	}

	public static function getProvincesArray()
	{
		return self::getProvinces()->toArray();
	}

	public static function getCitiesArray($pid = 0)
	{
		return self::getCities($pid)->toArray();
	}

	public static function getData($type = Customer::TYPE_RESELLER)
	{
		$provinceTable = Province::table();
		$customerTable = Customer::table();
		$ccTable = CustomerClass::table();

		//get the current month
		$date = Dater::now()->startOfMonth()->format(Dater::$SQLFormat);

		//load once, handle in js
		$provinceData = DB::table($customerTable)->select(array(
			DB::raw("COUNT($customerTable.province_id) as total_customers"),
			"$provinceTable.name as name",
			"$provinceTable.lat",
			"$provinceTable.lng",
			"$provinceTable.id as id",
			DB::raw("SUM($ccTable.sell) as total_sell"),
			DB::raw("SUM($ccTable.return) as total_return"),
			DB::raw("SUM($ccTable.buy) as total_buy"),
			DB::raw("SUM($ccTable.cash_in) as total_cash_in"),
			DB::raw("SUM($ccTable.cash_out) as total_cash_out"),
		))->where('type', '=', $type);
		$provinceData = $provinceData->join($provinceTable,"$provinceTable.id",'=',"$customerTable.province_id");
		$provinceData = $provinceData->join($ccTable, "$ccTable.customer_id", '=', "$customerTable.id")->where("$ccTable.date", '=', $date)->groupBy('id')->groupBy('type')->get();

		return $provinceData;
	}

	public static function getCitiesStats($pid = 0)
	{
		$cityTable = City::table();
		$provinceTable = Province::table();
		$customerTable = Customer::table();
		$ccTable = CustomerClass::table();

		//get the current month
		$date = Dater::now()->startOfMonth()->format(Dater::$SQLFormat);

		//load once, handle in js
		$provinceData = DB::table($customerTable)->select(array(
			DB::raw("COUNT($customerTable.province_id) as total_customers"),
			"$cityTable.name as name",
			"$cityTable.lat",
			"$cityTable.lng",
			"$cityTable.id as id",
			DB::raw("SUM($ccTable.sell) as total_sell"),
		)); //->where('type', '=', $type);
		$provinceData = $provinceData->join($cityTable,"$cityTable.id",'=',"$customerTable.city_id")->where("$cityTable.province_id", '=', $pid);
		$provinceData = $provinceData->join($ccTable, "$ccTable.customer_id", '=', "$customerTable.id")->where("$ccTable.date", '=', $date)->groupBy('id')->groupBy('type')->get();

		return $provinceData;
	}

}