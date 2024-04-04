<?
namespace App\Libraries;

use App\Models\Gaji;
use App\Models\Cuti, App\Models\Pelanggaran;
use App\Models\Personnel;
use Apps, App, Cache, Config, Dater, DB, Event, Input, InputForm, Redirect, Response, Session, URL, View, ModelException, Exception, Auth;
class GajiManager extends BaseManager
{
	public function addCuti($cuti,$diff)
	{
		if(!$gpu = Personnel::find($cuti->personnel_id))
			return true;

		//get dates
		$date = Dater::fromSQL($cuti->date_from);
		$month = $date->month;
		$year = $date->year;

		//1. find the gaji
		$gaji = Gaji::where('personnel_id','=',$cuti->personnel_id)->where('month','=',$month)->where('year','=',$year)->first();
		if(!$gaji)
			$gaji = $this->initGaji($gpu,$date);

		//2. flip the counters
		switch($cuti->type)
		{
			case Cuti::MENDADAK:
				$gaji->mendadak_counter += $diff;
				if($gaji->mendadak_counter > 3)
					$gaji->cuti_mendadak = (3 * $gaji->gaji_harian) + bcmul(($gaji->mendadak_counter - 3) * $gaji->gaji_harian,1.1,2);
				else
					$gaji->cuti_mendadak = $gaji->mendadak_counter * $gaji->gaji_harian;
				break;
			case Cuti::SAKIT: $gaji->sakit_counter += $diff; break;
			case Cuti::TAHUNAN: $gaji->tahunan_counter += $diff; break;
		}
		$gaji->premi_hangus = 1;

		//save the gaji
		if(!$gaji->save())
			return $this->error('cannot save gaji');

		return true;
	}

	public function deductCuti($cuti,$diff)
	{
		if(!$gpu = Personnel::find($cuti->personnel_id))
			return true;

		//get dates
		$date = Dater::fromSQL($cuti->date_from);
		$month = $date->month;
		$year = $date->year;

		//1. find the gaji
		$gaji = Gaji::where('personnel_id','=',$cuti->personnel_id)->where('month','=',$month)->where('year','=',$year)->first();
		//2. flip the counters
		switch($cuti->type)
		{
			case Cuti::MENDADAK:
				$gaji->mendadak_counter -= $diff;
				if($gaji->mendadak_counter > 3)
					$gaji->cuti_mendadak = (3 * $gaji->gaji_harian) + bcmul(($gaji->mendadak_counter - 3) * $gaji->gaji_harian,1.1,2);
				else
					$gaji->cuti_mendadak = $gaji->mendadak_counter * $gaji->gaji_harian;
				break;
			case Cuti::SAKIT: $gaji->sakit_counter -= $diff; break;
			case Cuti::TAHUNAN: $gaji->tahunan_counter -= $diff; break;
		}
		if($gaji->mendadak_counter == 0 && $gaji->sakit_counter == 0 && $gaji->tahunan_counter == 0)
			$gaji->premi_hangus = 0;

		//save the gaji
		if(!$gaji->save())
			return $this->error('cannot save gaji');

		return true;
	}

	public function addPelanggaran($pelanggaran)
	{
		if(!$gpu = Personnel::find($pelanggaran->personnel_id))
			return true;

		//get dates
		$date = Dater::fromSQL($pelanggaran->disipliner_date);
		$month = $date->month;
		$year = $date->year;

		//1. find the gaji
		$gaji = Gaji::where('personnel_id','=',$pelanggaran->personnel_id)->where('month','=',$month)->where('year','=',$year)->first();
		if(!$gaji)
			$gaji = $this->initGaji($gpu,$date);

		//2. add pelanggaran
		$gaji->sanksi += $pelanggaran->administrative;
		$gaji->pelanggaran_counter++;

		//save the gaji
		if(!$gaji->save())
			return $this->error('cannot save gaji');

		return true;
	}

	public function deductPelanggaran($pelanggaran)
	{
		if(!$gpu = Personnel::find($pelanggaran->personnel_id))
			return true;

		//get dates
		$date = Dater::fromSQL($pelanggaran->disipliner_date);
		$month = $date->month;
		$year = $date->year;

		//1. find the gaji
		$gaji = Gaji::where('personnel_id','=',$pelanggaran->personnel_id)->where('month','=',$month)->where('year','=',$year)->first();

		//2. add pelanggaran
		$gaji->sanksi -= $pelanggaran->administrative;
		$gaji->pelanggaran_counter--;

		//save the gaji
		if(!$gaji->save())
			return $this->error('cannot save gaji');

		return true;
	}

	public static function getTotal($gaji)
	{
		//do a quick check
		if(!intval($gaji->bonus) && !intval($gaji->terlambat) && !intval($gaji->bonus) && !intval($gaji->sanksi) && !intval($gaji->cuti_mendadak) && !intval($gaji->premi_hangus) && !intval($gaji->tunjangan))
			return $gaji->total_gaji;

		$total = 0;
		$total = $gaji->total_gaji + $gaji->bonus + $gaji->tunjangan;
		$total -= $gaji->premi_hangus == 1 ? $gaji->premi : 0;
		$total = $total - $gaji->terlambat - $gaji->sanksi - $gaji->cuti_mendadak;

		//check for terlambat
		$total = $total - bcdiv(bcmul($gaji->terlambat,$gaji->gaji_harian),100);

		//round to nearest 1000
		if($total % 1000 == 0) return $total;
		$total = $total % 1000 < 500 ? round($total,-3) + 1000 : round($total,-3);
		return $total;
	}

	public function initGaji($gpu,$date)
	{
		if(!$gaji = Gaji::where('month', '=', $date->month)->where('year', '=', $date->year)->where('personnel_id', '=', $gpu->id)->first())
		{
			$gaji = new Gaji;
			$gaji->month = $date->month;
			$gaji->year = $date->year;
			$gaji->personnel_id = $gpu->id;
		}
		$gaji->gaji_bulanan = $gpu->bulanan;
		$gaji->gaji_harian = $gpu->harian;
		$gaji->premi = $gpu->premi;
		$gaji->terlambat = 0; //manual
		$gaji->bonus = 0; //manual
		$gaji->sanksi = 0;
		$gaji->cuti_mendadak = 0;
		$gaji->premi_hangus = 0;
		$gaji->tunjangan = 0;
		$gaji->mendadak_counter = 0;
		$gaji->sakit_counter = 0;
		$gaji->tahunan_counter = 0;
		$gaji->pelanggaran_counter = 0;
		$gaji->total_gaji = $gaji->gaji_bulanan + round(bcmul($gaji->gaji_harian, 26)) + $gaji->premi;

		return $gaji;
	}

	public function recalculate($gaji, $date)
	{
		$start = Dater::toSQL($date->startOfMonth());
		$stop = Dater::toSQL($date->endOfMonth());

		//recalculate cuti
		$cuti = Cuti::where('personnel_id', '=', $gaji->personnel_id)->where('date_from', '>=', $start)->where('date_to', '<=', $stop)->get();
		foreach ($cuti as $c) {
			$from = Dater::fromSQL($c->date_from);
			$to = Dater::fromSQL($c->date_to);
			$diff = $from->diffInDays($to) + 1;

			switch($c->type)
			{
				case Cuti::MENDADAK:
					$gaji->mendadak_counter += $diff;
					$gaji->cuti_mendadak = $gaji->mendadak_counter * $gaji->gaji_harian;
					break;
				case Cuti::SAKIT: $gaji->sakit_counter += $diff; break;
				case Cuti::TAHUNAN: $gaji->tahunan_counter += $diff; break;
			}
			$gaji->premi_hangus = 1;
		}

		//recalculate pelanggaran
		$pelanggaran = Pelanggaran::where('personnel_id', '=', $gaji->personnel_id)->where('disipliner_date', '>=', $start)->where('disipliner_date', '<=', $stop)->get();
		foreach ($pelanggaran as $p) {
			$gaji->sanksi += $pelanggaran->administrative;
			$gaji->pelanggaran_counter++;
		}

		return $gaji;
	}
}