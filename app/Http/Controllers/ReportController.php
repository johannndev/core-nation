<?php

namespace App\Http\Controllers;

use App\Helpers\RecordManagerHelper;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Operation;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;


class ReportController extends Controller
{
	public function profitLoss(Request $request)
	{
		$datesNow = Carbon::now()->subMonth(6);
		$datesLast = Carbon::now()->subMonth(7);


		$rm = new RecordManagerHelper();
		$pl = $rm->getProfitLoss($datesNow->month, $datesNow->year);
		$compare = $rm->getProfitLoss($datesNow->month, $datesNow->year);

		$ops = Operation::pluck('name', 'id');
		$operations = [];
		foreach ($ops as $id => $name) {
			$operations[] = ['id' => $id, 'name' => $name];
		}

		dd($operations, $pl, $compare, $ops);
	}



	// public function cash(Request $request)
	// {

	// 	$datesNow = Carbon::now();

	// 	$month = $request->month;
	// 	$year  = $request->year ?? $datesNow->year;

	// 	if ($month) {
	// 		$date = Carbon::createFromDate($year, $month, 1);
	// 		$startDate = $date->startOfMonth()->toDateString();
	// 		$endDate   = $date->endOfMonth()->toDateString();
	// 	} else {
	// 		$startDate = Carbon::createFromDate($year, 1, 1)->startOfYear()->toDateString();
	// 		$endDate   = Carbon::createFromDate($year, 12, 31)->endOfYear()->toDateString();
	// 	}

	// 	// ✅ TAMBAH BANK DI SINI
	// 	$customers = Customer::whereIn('type', [
	// 		Customer::TYPE_CUSTOMER,
	// 		Customer::TYPE_RESELLER,
	// 		Customer::TYPE_BANK
	// 	])->get();

	// 	$customerList = $customers->where('type', Customer::TYPE_CUSTOMER)->values();
	// 	$resellerList = $customers->where('type', Customer::TYPE_RESELLER)->values();
	// 	$bankList     = $customers->where('type', Customer::TYPE_BANK)->values(); // ✅ NEW

	// 	$allIds = $customers->pluck('id')->toArray();

	// 	// $rows = Transaction::whereBetween('date', [$startDate, $endDate])
	// 	// 	->where(function ($q) use ($allIds) {
	// 	// 		$q->whereIn('sender_id', $allIds)
	// 	// 			->orWhereIn('receiver_id', $allIds);
	// 	// 	})
	// 	// 	->selectRaw("
	// 	// 	sender_id,
	// 	// 	receiver_id,
	// 	// 	type,
	// 	// 	SUM(total) as total
	// 	// ")
	// 	// 	->groupBy('sender_id', 'receiver_id', 'type')
	// 	// 	->get();

	// 	$query = Transaction::whereBetween('date', [$startDate, $endDate])
	// 		->where(function ($q) use ($allIds) {
	// 			$q->whereIn('sender_id', $allIds)
	// 				->orWhereIn('receiver_id', $allIds);
	// 		})
	// 		->selectRaw("
	//     sender_id,
	//     receiver_id,
	//     type,
	//     SUM(total) as total
	// ")
	// 		->groupBy('sender_id', 'receiver_id', 'type');

	// 	// ✅ DEBUG (TAMPIL DI HALAMAN, TIDAK STOP)
	// 	$fullSql = vsprintf(
	// 		str_replace('?', '%s', $query->toSql()),
	// 		collect($query->getBindings())->map(function ($b) {
	// 			return is_numeric($b) ? $b : "'" . addslashes($b) . "'";
	// 		})->toArray()
	// 	);

	// 	dump('QUERY CASH:', $fullSql);

	// 	// lanjut normal
	// 	$rows = $query->get();

	// 	$init = function () {
	// 		return [
	// 			'cashIn' => [],
	// 			'cashOut' => [],
	// 			'sell' => [],
	// 			'return' => [],
	// 			'nettCash' => 0,
	// 			'nettSell' => 0,
	// 		];
	// 	};

	// 	$customerReport = $init();
	// 	$resellerReport = $init();
	// 	$bankReport     = $init(); // ✅ NEW

	// 	$customerMap = $customerList->pluck('id')->flip();
	// 	$resellerMap = $resellerList->pluck('id')->flip();
	// 	$bankMap     = $bankList->pluck('id')->flip(); // ✅ NEW

	// 	foreach ($rows as $row) {

	// 		// ================= CASH IN =================
	// 		if ($row->type == Transaction::TYPE_CASH_IN && isset($customerMap[$row->sender_id])) {
	// 			$customerReport['cashIn'][$row->sender_id] = ($customerReport['cashIn'][$row->sender_id] ?? 0) + $row->total;
	// 		}
	// 		if ($row->type == Transaction::TYPE_CASH_IN && isset($resellerMap[$row->sender_id])) {
	// 			$resellerReport['cashIn'][$row->sender_id] = ($resellerReport['cashIn'][$row->sender_id] ?? 0) + $row->total;
	// 		}
	// 		if ($row->type == Transaction::TYPE_CASH_IN && isset($bankMap[$row->receiver_id])) { // ✅ NEW
	// 			$bankReport['cashIn'][$row->receiver_id] = ($bankReport['cashIn'][$row->receiver_id] ?? 0) + $row->total;
	// 		}

	// 		// ================= CASH OUT =================
	// 		if ($row->type == Transaction::TYPE_CASH_OUT && isset($customerMap[$row->receiver_id])) {
	// 			$customerReport['cashOut'][$row->receiver_id] = ($customerReport['cashOut'][$row->receiver_id] ?? 0) + $row->total;
	// 		}
	// 		if ($row->type == Transaction::TYPE_CASH_OUT && isset($resellerMap[$row->receiver_id])) {
	// 			$resellerReport['cashOut'][$row->receiver_id] = ($resellerReport['cashOut'][$row->receiver_id] ?? 0) + $row->total;
	// 		}
	// 		if ($row->type == Transaction::TYPE_CASH_OUT && isset($bankMap[$row->sender_id])) { // ✅ NEW
	// 			$bankReport['cashOut'][$row->sender_id] = ($bankReport['cashOut'][$row->sender_id] ?? 0) + $row->total;
	// 		}

	// 		// ================= SELL =================
	// 		if ($row->type == Transaction::TYPE_SELL && isset($customerMap[$row->receiver_id])) {
	// 			$customerReport['sell'][$row->receiver_id] = ($customerReport['sell'][$row->receiver_id] ?? 0) + $row->total;
	// 		}
	// 		if ($row->type == Transaction::TYPE_SELL && isset($resellerMap[$row->receiver_id])) {
	// 			$resellerReport['sell'][$row->receiver_id] = ($resellerReport['sell'][$row->receiver_id] ?? 0) + $row->total;
	// 		}
	// 		if ($row->type == Transaction::TYPE_SELL && isset($bankMap[$row->receiver_id])) { // ✅ NEW
	// 			$bankReport['sell'][$row->receiver_id] = ($bankReport['sell'][$row->receiver_id] ?? 0) + $row->total;
	// 		}

	// 		// ================= RETURN =================
	// 		if ($row->type == Transaction::TYPE_RETURN && isset($customerMap[$row->sender_id])) {
	// 			$customerReport['return'][$row->sender_id] = ($customerReport['return'][$row->sender_id] ?? 0) + $row->total;
	// 		}
	// 		if ($row->type == Transaction::TYPE_RETURN && isset($resellerMap[$row->sender_id])) {
	// 			$resellerReport['return'][$row->sender_id] = ($resellerReport['return'][$row->sender_id] ?? 0) + $row->total;
	// 		}
	// 		if ($row->type == Transaction::TYPE_RETURN && isset($bankMap[$row->sender_id])) { // ✅ NEW
	// 			$bankReport['return'][$row->sender_id] = ($bankReport['return'][$row->sender_id] ?? 0) + $row->total;
	// 		}
	// 	}

	// 	$calc = function (&$report) {
	// 		$report['nettCash'] = array_sum($report['cashIn']) + array_sum($report['cashOut']);
	// 		$report['nettSell'] = array_sum($report['sell']) - array_sum($report['return']);
	// 	};

	// 	$calc($customerReport);
	// 	$calc($resellerReport);
	// 	$calc($bankReport); // ✅ NEW

	// 	$yearList = [];
	// 	for ($i = 2019; $i <= date('Y'); $i++) {
	// 		$yearList[] = $i;
	// 	}
	// 	$yearList = array_reverse($yearList);


	// 	return view('report.cash', [
	// 		'customerList' => $customerList,
	// 		'resellerList' => $resellerList,
	// 		'bankList'     => $bankList, // ✅ NEW

	// 		'customerReport' => $customerReport,
	// 		'resellerReport' => $resellerReport,
	// 		'bankReport'     => $bankReport, // ✅ NEW

	// 		'month' => $month,
	// 		'year' => $year,
	// 		'yearList' => $yearList,
	// 		'datesNow' => $datesNow,
	// 	]);
	// }

	public function cash(Request $request)
	{
		$datesNow = Carbon::now();

		$month = $request->month;
		$year  = $request->year ?? $datesNow->year;

		// ================= DATE RANGE =================
		if ($month) {
			$date = Carbon::createFromDate($year, $month, 1);
			$startDate = $date->startOfMonth()->toDateString();
			$endDate   = $date->endOfMonth()->toDateString();
		} else {
			$startDate = Carbon::createFromDate($year, 1, 1)->startOfYear()->toDateString();
			$endDate   = Carbon::createFromDate($year, 12, 31)->endOfYear()->toDateString();
		}

		// ================= CUSTOMER LIST =================
		$customers = Customer::whereIn('type', [
			Customer::TYPE_CUSTOMER,
			Customer::TYPE_RESELLER,
			Customer::TYPE_BANK
		])->get();

		$customerList = $customers->where('type', Customer::TYPE_CUSTOMER)->values();
		$resellerList = $customers->where('type', Customer::TYPE_RESELLER)->values();
		$bankList     = $customers->where('type', Customer::TYPE_BANK)->values();

		// ================= QUERY =================
		$rows = Transaction::whereBetween('date', [$startDate, $endDate])
			->where(function ($q) {

				// ✅ CUSTOMER & RESELLER dari sender
				$q->where(function ($sub) {
					$sub->whereIn('sender_type', [
						Customer::TYPE_CUSTOMER,
						Customer::TYPE_RESELLER
					])
						->whereHas('sender'); // auto skip soft delete
				})

					// ✅ BANK dari receiver + sender harus customer/reseller
					->orWhere(function ($sub) {
						$sub->where('receiver_type', Customer::TYPE_BANK)
							->whereIn('sender_type', [
								Customer::TYPE_CUSTOMER,
								Customer::TYPE_RESELLER
							])
							->whereHas('sender') // bank valid
							->whereHas('senderWithoutTrashed');  // sender valid
					});
			})
			->selectRaw("
            sender_id,
            sender_type,
            receiver_id,
            receiver_type,
            type,
            SUM(total) as total
        ")
			->groupBy(
				'sender_id',
				'sender_type',
				'receiver_id',
				'receiver_type',
				'type'
			)
			->get();

		// ================= INIT =================
		$init = fn() => [
			'cashIn' => [],
			'cashOut' => [],
			'sell' => [],
			'return' => [],
			'nettCash' => 0,
			'nettSell' => 0,
		];

		$customerReport = $init();
		$resellerReport = $init();
		$bankReport     = $init();

		$add = function (&$report, $key, $id, $value) {
			$report[$key][$id] = ($report[$key][$id] ?? 0) + $value;
		};

		// ================= LOOP =================
		foreach ($rows as $row) {

			// ===== CASH IN =====
			if ($row->type == Transaction::TYPE_CASH_IN) {

				// customer & reseller dari sender
				if ($row->sender_type == Customer::TYPE_CUSTOMER) {
					$add($customerReport, 'cashIn', $row->sender_id, $row->total);
				}

				if ($row->sender_type == Customer::TYPE_RESELLER) {
					$add($resellerReport, 'cashIn', $row->sender_id, $row->total);
				}

				// bank dari receiver
				if (
					$row->receiver_type == Customer::TYPE_BANK &&
					in_array($row->sender_type, [
						Customer::TYPE_CUSTOMER,
						Customer::TYPE_RESELLER
					])
				) {
					$add($bankReport, 'cashIn', $row->receiver_id, $row->total);
				}
			}

			// ===== CASH OUT =====
			if ($row->type == Transaction::TYPE_CASH_OUT) {

				if ($row->sender_type == Customer::TYPE_CUSTOMER) {
					$add($customerReport, 'cashOut', $row->sender_id, $row->total);
				}

				if ($row->sender_type == Customer::TYPE_RESELLER) {
					$add($resellerReport, 'cashOut', $row->sender_id, $row->total);
				}

				if (
					$row->receiver_type == Customer::TYPE_BANK &&
					in_array($row->sender_type, [
						Customer::TYPE_CUSTOMER,
						Customer::TYPE_RESELLER
					])
				) {
					$add($bankReport, 'cashOut', $row->receiver_id, $row->total);
				}
			}

			// ===== SELL =====
			if ($row->type == Transaction::TYPE_SELL) {

				if ($row->sender_type == Customer::TYPE_CUSTOMER) {
					$add($customerReport, 'sell', $row->sender_id, $row->total);
				}

				if ($row->sender_type == Customer::TYPE_RESELLER) {
					$add($resellerReport, 'sell', $row->sender_id, $row->total);
				}

				if (
					$row->receiver_type == Customer::TYPE_BANK &&
					in_array($row->sender_type, [
						Customer::TYPE_CUSTOMER,
						Customer::TYPE_RESELLER
					])
				) {
					$add($bankReport, 'sell', $row->receiver_id, $row->total);
				}
			}

			// ===== RETURN =====
			if ($row->type == Transaction::TYPE_RETURN) {

				if ($row->sender_type == Customer::TYPE_CUSTOMER) {
					$add($customerReport, 'return', $row->sender_id, $row->total);
				}

				if ($row->sender_type == Customer::TYPE_RESELLER) {
					$add($resellerReport, 'return', $row->sender_id, $row->total);
				}

				if (
					$row->receiver_type == Customer::TYPE_BANK &&
					in_array($row->sender_type, [
						Customer::TYPE_CUSTOMER,
						Customer::TYPE_RESELLER
					])
				) {
					$add($bankReport, 'return', $row->receiver_id, $row->total);
				}
			}
		}

		// ================= CALC =================
		$calc = function (&$report) {
			$report['nettCash'] = array_sum($report['cashIn']) + array_sum($report['cashOut']);
			$report['nettSell'] = array_sum($report['sell']) - array_sum($report['return']);
		};

		$calc($customerReport);
		$calc($resellerReport);
		$calc($bankReport);

		// ================= YEAR LIST =================
		$yearList = collect(range(2019, date('Y')))->reverse()->values();

		// ================= SET A =================
		// CUSTOMER + RESELLER (sender)
		// $A = Transaction::whereBetween('date', [$startDate, $endDate])
		// 	->where('type', Transaction::TYPE_CASH_IN)
		// 	->whereIn('sender_type', [
		// 		Customer::TYPE_CUSTOMER,
		// 		Customer::TYPE_RESELLER
		// 	])
		// 	->pluck('id');

		// // ================= SET B =================
		// // BANK (receiver)
		// $B = Transaction::whereBetween('date', [$startDate, $endDate])
		// 	->where('type', Transaction::TYPE_CASH_IN)
		// 	->where('receiver_type', Customer::TYPE_BANK)
		// 	->pluck('id');

		// // ================= SELISIH =================
		// $onlyCustomerReseller = $A->diff($B)->values();
		// $onlyBank             = $B->diff($A)->values();

		// // ================= DETAIL =================

		// // 🔴 CUSTOMER / RESELLER → TIDAK MASUK BANK
		// $detailCustomerReseller = Transaction::whereIn('id', $onlyCustomerReseller)
		// 	->get([
		// 		'id',
		// 		'sender_type',
		// 		'receiver_type', // 🔥 ini yang kita butuh
		// 		'total'
		// 	]);

		// // 🔴 BANK → BUKAN DARI CUSTOMER / RESELLER
		// $detailBank = Transaction::whereIn('id', $onlyBank)
		// 	->get([
		// 		'id',
		// 		'sender_type',   // 🔥 ini yang kita butuh
		// 		'receiver_type',
		// 		'total'
		// 	]);

		// // ================= OUTPUT =================
		// dd([
		// 	'CUSTOMER_RESELLER_TIDAK_MASUK_BANK' => [
		// 		'count' => $detailCustomerReseller->count(),
		// 		'data' => $detailCustomerReseller->map(function ($row) {
		// 			return [
		// 				'id' => $row->id,
		// 				'receiver_type' => $row->receiver_type, // 🔥 fokus sini
		// 				'total' => $row->total,
		// 			];
		// 		}),
		// 	],

		// 	'BANK_TIDAK_DARI_CUSTOMER_RESELLER' => [
		// 		'count' => $detailBank->count(),
		// 		'data' => $detailBank->map(function ($row) {
		// 			return [
		// 				'id' => $row->id,
		// 				'sender_type' => $row->sender_type, // 🔥 fokus sini
		// 				'total' => $row->total,
		// 			];
		// 		}),
		// 	],
		// ]);



		// ================= RETURN =================
		return view('report.cash', [
			'customerList' => $customerList,
			'resellerList' => $resellerList,
			'bankList'     => $bankList,

			'customerReport' => $customerReport,
			'resellerReport' => $resellerReport,
			'bankReport'     => $bankReport,

			'month' => $month,
			'year' => $year,
			'yearList' => $yearList,
			'datesNow' => $datesNow,
		]);
	}

	public function pembelian(Request $request)
	{
		// =========================
		// 1. FILTER TANGGAL
		// =========================
		$datesNow = Carbon::now();


		$month = $request->month;
		$year  = $request->year ?? $datesNow->year;

		if ($month) {
			// 👉 FILTER BULAN
			$date = Carbon::createFromDate($year, $month, 1);
			$startDate = $date->startOfMonth()->toDateString();
			$endDate   = $date->endOfMonth()->toDateString();
		} else {
			// 👉 FILTER 1 TAHUN
			$startDate = Carbon::createFromDate($year, 1, 1)->startOfYear()->toDateString();
			$endDate   = Carbon::createFromDate($year, 12, 31)->endOfYear()->toDateString();
		}

		// =========================
		// 2. AMBIL CUSTOMER (SUPPLIER + ACCOUNT)
		// =========================
		$customers = Customer::withTrashed()
			->whereIn('type', [
				Customer::TYPE_SUPPLIER,
				Customer::TYPE_ACCOUNT
			])
			->get();

		$supplierList = $customers
			->where('type', Customer::TYPE_SUPPLIER)
			->values();

		$accountList = $customers
			->where('type', Customer::TYPE_ACCOUNT)
			->values();

		$allIds = $customers->pluck('id')->toArray();

		// mapping biar O(1)
		$supplierMap = $supplierList->pluck('id')->flip();
		$accountMap  = $accountList->pluck('id')->flip();

		// =========================
		// 3. QUERY TUNGGAL (OPTIMIZED)
		// =========================
		$rows = Transaction::whereBetween('date', [$startDate, $endDate])
			->where(function ($q) use ($allIds) {
				$q->whereIn('sender_id', $allIds)
					->orWhereIn('receiver_id', $allIds);
			})
			->selectRaw("
            sender_id,
            receiver_id,
            type,
            SUM(total) as total
        ")
			->groupBy('sender_id', 'receiver_id', 'type')
			->get();

		// =========================
		// 4. INIT REPORT
		// =========================
		$supplierReport = [
			'buy' => [],
			'returnSupplier' => [],
			'cashInSupplier' => [],
			'cashInAccount' => [],
			'cashOutSupplier' => [],
			'nettBuy' => 0,
			'cashOutAccount' => [],
		];

		// =========================
		// 5. LOOP DATA (CORE LOGIC)
		// =========================
		foreach ($rows as $row) {

			// ======================
			// BUY (uang keluar ke supplier)
			// ======================
			if ($row->type == Transaction::TYPE_BUY && isset($supplierMap[$row->sender_id])) {
				$supplierReport['buy'][$row->sender_id] =
					($supplierReport['buy'][$row->sender_id] ?? 0) + $row->total;
			}

			// ======================
			// RETURN SUPPLIER (uang masuk dari supplier)
			// ======================
			if ($row->type == Transaction::TYPE_RETURN_SUPPLIER && isset($supplierMap[$row->sender_id])) {
				$supplierReport['returnSupplier'][$row->sender_id] =
					($supplierReport['returnSupplier'][$row->sender_id] ?? 0) + $row->total;
			}

			// ======================
			// CASH IN
			// ======================
			if ($row->type == Transaction::TYPE_CASH_IN) {

				// supplier
				if (isset($supplierMap[$row->sender_id])) {
					$supplierReport['cashInSupplier'][$row->sender_id] =
						($supplierReport['cashInSupplier'][$row->sender_id] ?? 0) + $row->total;
				}

				// account
				if (isset($accountMap[$row->sender_id])) {
					$supplierReport['cashInAccount'][$row->sender_id] =
						($supplierReport['cashInAccount'][$row->sender_id] ?? 0) + $row->total;
				}
			}

			// ======================
			// CASH OUT (NEW)
			// ======================
			if ($row->type == Transaction::TYPE_CASH_OUT) {

				// ke supplier (uang keluar)
				if (isset($supplierMap[$row->receiver_id])) {
					$supplierReport['cashOutSupplier'][$row->receiver_id] =
						($supplierReport['cashOutSupplier'][$row->receiver_id] ?? 0) + $row->total;
				}

				// dari account
				if (isset($accountMap[$row->sender_id])) {
					$supplierReport['cashOutAccount'][$row->sender_id] =
						($supplierReport['cashOutAccount'][$row->sender_id] ?? 0) + $row->total;
				}
			}
		}

		// =========================
		// 6. HITUNG NETT
		// =========================
		$supplierReport['nettBuy'] =
			array_sum($supplierReport['buy'])
			- array_sum($supplierReport['returnSupplier'])
			- array_sum($supplierReport['cashInSupplier']);
		// ⚠️ cashInAccount tidak dihitung (internal)

		// =========================
		// 7. YEAR LIST
		// =========================
		$yearList = [];
		for ($i = 2019; $i <= date('Y'); $i++) {
			$yearList[] = $i;
		}
		$yearList = array_reverse($yearList);

		// =========================
		// 8. RETURN VIEW
		// =========================
		return view('report.pembelian', [
			'supplierList'   => $supplierList,
			'supplierReport' => $supplierReport,
			'accountList'    => $accountList,

			'month'    => $month,
			'year'     => $year,
			'yearList' => $yearList,
			'datesNow' => $datesNow,
		]);
	}

	public function income(Request $request)
	{
		// Ambil input dari request atau pakai default
		$month = $request->input('bulan');
		$year = $request->input('tahun');
		$periode = $request->input('type'); // 6 atau 12

		// Jika ada filter bulan & tahun, hitung ke depan
		if ($month && $year && $periode) {
			$startDate = Carbon::create($year, $month, 1)->startOfMonth();
			$endDate = Carbon::create($year, $month, 1)->addMonths($periode - 1)->endOfMonth();
		} else {
			// Default: 12 bulan ke belakang dari bulan ini
			$endDate = Carbon::now()->endOfMonth();
			$startDate = Carbon::now()->subMonths(11)->startOfMonth();
		}

		$startDateString = $startDate->toDateString();
		$endDateString = $endDate->toDateString();

		// Constant
		$typeSell = Transaction::TYPE_SELL;
		$typeReturn = Transaction::TYPE_RETURN;
		$typeCashIn = Transaction::TYPE_CASH_IN;
		$typeCashOut = Transaction::TYPE_CASH_OUT;

		$receiverTypeCustomer = Customer::TYPE_CUSTOMER;
		$receiverTypeReseller = Customer::TYPE_RESELLER;
		$receiverTypeAccount = Customer::TYPE_ACCOUNT;
		$receiverTypeSupplier = Customer::TYPE_SUPPLIER;

		$rawData = DB::table('transactions')
			->leftJoin('customers as sender', 'transactions.sender_id', '=', 'sender.id')
			->leftJoin('customers as receiver', 'transactions.receiver_id', '=', 'receiver.id')
			->selectRaw("
				YEAR(transactions.date) as year,
				MONTH(transactions.date) as month,

				-- Penjualan (pakai receiver)
				SUM(CASE 
					WHEN transactions.type = ? AND transactions.receiver_type IN (?, ?) AND receiver.is_online = 0
					THEN transactions.total ELSE 0 END
				) as sell_offline,

				SUM(CASE 
					WHEN transactions.type = ? AND transactions.receiver_type IN (?, ?) AND receiver.is_online = 1
					THEN transactions.total ELSE 0 END
				) as sell_online,

				SUM(CASE 
					WHEN transactions.type = ? AND transactions.receiver_type IN (?, ?)
					THEN transactions.total ELSE 0 END
				) as sell_total,

				-- Return (pakai receiver)
				SUM(CASE 
					WHEN transactions.type = ? AND transactions.receiver_type IN (?, ?) AND receiver.is_online = 0
					THEN transactions.total ELSE 0 END
				) as return_offline,

				SUM(CASE 
					WHEN transactions.type = ? AND transactions.receiver_type IN (?, ?) AND receiver.is_online = 1
					THEN transactions.total ELSE 0 END
				) as return_online,

				-- Cash In (pakai sender)
				SUM(CASE 
					WHEN transactions.type = ? AND transactions.sender_type IN (?, ?) AND sender.is_online = 0
					THEN transactions.total ELSE 0 END
				) as cashin_offline,

				SUM(CASE 
					WHEN transactions.type = ? AND transactions.sender_type IN (?, ?) AND sender.is_online = 1
					THEN transactions.total ELSE 0 END
				) as cashin_online,

				SUM(CASE 
					WHEN transactions.type = ? AND transactions.sender_type IN (?, ?)
					THEN transactions.total ELSE 0 END
				) as cashin_total,

				SUM(CASE 
					WHEN transactions.type = ? AND transactions.sender_type = ?
					THEN transactions.total ELSE 0 END
				) as cashin_journal,

				-- Cash Out (pakai receiver)
				SUM(CASE 
					WHEN transactions.type = ? AND transactions.receiver_type IN (?, ?) AND receiver.is_online = 0
					THEN transactions.total ELSE 0 END
				) as cashout_offline,

				SUM(CASE 
					WHEN transactions.type = ? AND transactions.receiver_type IN (?, ?) AND receiver.is_online = 1
					THEN transactions.total ELSE 0 END
				) as cashout_online,

				SUM(CASE 
					WHEN transactions.type = ? AND transactions.receiver_type IN (?, ?)
					THEN transactions.total ELSE 0 END
				) as cashout_total,

				SUM(CASE 
					WHEN transactions.type = ? AND transactions.receiver_type = ?
					THEN transactions.total ELSE 0 END
				) as cashout_journal,

				SUM(CASE 
					WHEN transactions.type = ? AND transactions.receiver_type = ?
					THEN transactions.total ELSE 0 END
				) as cashout_supplier
			", [
				$typeSell,
				$receiverTypeCustomer,
				$receiverTypeReseller,
				$typeSell,
				$receiverTypeCustomer,
				$receiverTypeReseller,
				$typeSell,
				$receiverTypeCustomer,
				$receiverTypeReseller,

				$typeReturn,
				$receiverTypeCustomer,
				$receiverTypeReseller,
				$typeReturn,
				$receiverTypeCustomer,
				$receiverTypeReseller,

				$typeCashIn,
				$receiverTypeCustomer,
				$receiverTypeReseller,
				$typeCashIn,
				$receiverTypeCustomer,
				$receiverTypeReseller,
				$typeCashIn,
				$receiverTypeCustomer,
				$receiverTypeReseller,
				$typeCashIn,
				$receiverTypeAccount,

				$typeCashOut,
				$receiverTypeCustomer,
				$receiverTypeReseller,
				$typeCashOut,
				$receiverTypeCustomer,
				$receiverTypeReseller,
				$typeCashOut,
				$receiverTypeCustomer,
				$receiverTypeReseller,
				$typeCashOut,
				$receiverTypeAccount,
				$typeCashOut,
				$receiverTypeSupplier
			])
			->whereBetween('transactions.date', [$startDateString, $endDateString])
			->groupByRaw('YEAR(transactions.date), MONTH(transactions.date)')
			->orderByRaw('YEAR(transactions.date), MONTH(transactions.date)')
			->get();

		$results = [];

		// Inisialisasi seluruh bulan dari startDate ke endDate
		$period = Carbon::parse($startDate)->startOfMonth();
		$end = Carbon::parse($endDate)->startOfMonth();

		while ($period <= $end) {
			$key = $period->format('M-y'); // contoh: Jan-25
			$results[$key] = [
				'sell_offline' => 0,
				'sell_online' => 0,
				'sell_total' => 0,
				'return_offline' => 0,
				'return_online' => 0,
				'nett_revenue' => 0,
				'cash_in_offline' => 0,
				'cash_in_online' => 0,
				'cash_in_total' => 0,
				'cash_in_journal' => 0,
				'nett_cash_in' => 0,
				'cash_out_offline' => 0,
				'cash_out_online' => 0,
				'cash_out_total' => 0,
				'cash_out_journal' => 0,
				'cash_out_supplier' => 0,
				'nett_cash_out' => 0,
				'nett_cash' => 0,

			];
			$period->addMonth();
		}

		// Isi data dari query
		foreach ($rawData as $row) {

			$sellOffline = (float) abs($row->sell_offline);
			$sellOnline = (float) abs($row->sell_online);
			$sellTotal = (float) abs($row->sell_total);
			$returnOffline = (float) abs($row->return_offline);
			$returnOnline = (float) abs($row->return_online);
			$cashInOffline = (float) abs($row->cashin_offline);
			$cashInOnline = (float) abs($row->cashin_online);
			$cashInTotal = (float) abs($row->cashin_total);
			$cashInJournal = (float) abs($row->cashin_journal);
			$cashOutOffline = (float) abs($row->cashout_offline);
			$cashOutOnline = (float) abs($row->cashout_online);
			$cashOutTotal = (float) abs($row->cashout_total);
			$cashOutJournal = (float) abs($row->cashout_journal);
			$cashOutSupplier = (float) abs($row->cashout_supplier);

			$totalIncome = $sellOffline + $sellOnline + $returnOffline + $returnOnline;
			$totalCashIn = $cashInOffline + $cashInOnline + $cashInJournal;
			$totalCashOut = $cashOutOffline + $cashOutOnline + $cashOutJournal;

			$key = Carbon::create($row->year, $row->month)->format('M-y');
			$results[$key] = [
				'sell_offline' => $sellOffline,
				'sell_online' => $sellOnline,
				'sell_total' => $sellTotal,
				'return_offline' => $returnOffline,
				'return_online' => $returnOnline,
				'nett_revenue' => $totalIncome,
				'cash_in_offline' => $cashInOffline,
				'cash_in_online' => $cashInOnline,
				'cash_in_total' => $cashInTotal,
				'cash_in_journal' => $cashInJournal,
				'nett_cash_in' =>  $totalCashIn,
				'cash_out_offline' => $cashOutOffline,
				'cash_out_online' => $cashOutOnline,
				'cash_out_total' => $cashOutTotal,
				'cash_out_journal' => $cashOutJournal,
				'cash_out_supplier' => $cashOutSupplier,
				'nett_cash_out' =>  $totalCashOut,
				'nett_cash' => $totalCashIn - $totalCashOut,
			];
		}

		$dateList = [];
		foreach ($results as $key => $row) {

			$dateList[$key] = [
				'date' => $key,
			];
		}

		$income = [];
		foreach ($results as $key => $row) {

			$income[$key] = [
				'sell_offline' => $row['sell_offline'],
				'sell_online' => $row['sell_online'],
				'sell_total' => $row['sell_total'],
				'return_offline' => $row['return_offline'],
				'return_online' => $row['return_online'],
				'nett_revenue' => $row['nett_revenue']
			];
		}

		$cashIn = [];
		foreach ($results as $key => $row) {

			$cashIn[$key] = [
				'cash_in_offline' => $row['cash_in_offline'],
				'cash_in_online' => $row['cash_in_online'],
				'cash_in_total' => $row['cash_in_total'],
				'cash_in_journal' => $row['cash_in_journal'],
				'nett_cash_in' => $row['nett_cash_in'],
			];
		}

		$cashOut = [];
		foreach ($results as $key => $row) {

			$cashOut[$key] = [
				'cash_out_offline' => $row['cash_out_offline'],
				'cash_out_online' => $row['cash_out_online'],
				'cash_out_total' => $row['cash_out_total'],
				'cash_out_journal' => $row['cash_out_journal'],
				'cash_out_supplier' => $row['cash_out_supplier'],
				'nett_cash_out' => $row['nett_cash_out'],
			];
		}

		$cashTotal = [];
		foreach ($results as $key => $row) {

			$cashTotal[$key] = [
				'nett_cash' => $row['nett_cash'],
			];
		}

		return view('report.income', compact('results', 'dateList', 'income', 'cashIn', 'cashOut', 'cashTotal', 'startDateString', 'endDateString'));
	}

	public function incomeBook(Request $request, $id)
	{
		$startDate = $request->startDate;
		$endDate = $request->endDate;

		$period = Carbon::parse($startDate);
		$allMonths = [];
		while ($period <= $endDate) {
			$allMonths[] = $period->format('M-y');
			$period->addMonth();
		}

		$isOnline = 0;
		$type = 'transactions.receiver_id';
		$whereType = 'transactions.receiver_type';
		$groupType = 'transactions.receiver_id';
		$all = 'n';
		$label = $id;
		$customerTypes = [Customer::TYPE_CUSTOMER, Customer::TYPE_RESELLER];

		if (str_contains($id, 'sell')) {
			$typeSell = Transaction::TYPE_SELL;
		} elseif (str_contains($id, 'return')) {
			$typeSell = Transaction::TYPE_RETURN;
		} elseif (str_contains($id, 'cash-in')) {
			$typeSell = Transaction::TYPE_CASH_IN;
			$type = 'transactions.sender_id';
			$whereType = 'transactions.sender_type';
			$groupType = 'transactions.sender_id';
		} elseif (str_contains($id, 'cash-out')) {
			$typeSell = Transaction::TYPE_CASH_OUT;
		}

		if (str_contains($id, 'online')) {
			$isOnline = 1;
		}

		if ($id == 'cash-in-journal' || $id == 'cash-out-journal') {
			$customerTypes = [Customer::TYPE_ACCOUNT];
		} elseif ($id == 'cash-out-supplier') {
			$customerTypes = [Customer::TYPE_SUPPLIER];
		}

		if (str_contains($id, 'all') || str_contains($id, 'journal') || $id == 'cash-out-supplier') {
			$all = 'y';
		}

		$rawData = DB::table('transactions')
			->join('customers', function ($join) use ($isOnline, $type, $all) {
				$join->on($type, '=', 'customers.id');
				if ($all == 'n') {
					$join->where('customers.is_online', $isOnline);
				}
			})
			->selectRaw("
				customers.name as customer_name,
				DATE_FORMAT(transactions.date, '%b-%y') as bulan,
				SUM(transactions.total) as total
			")
			->where('transactions.type', $typeSell)
			->whereIn($whereType, $customerTypes)
			->whereBetween('transactions.date', [$startDate, $endDate])
			->groupBy('customers.name', 'bulan')
			->orderBy('customers.name')
			->get();

		$customerTotals = [];
		$grandTotalPerMonth = array_fill_keys($allMonths, 0);
		$grandTotalOverall = 0;

		foreach ($rawData as $row) {
			$name = $row->customer_name;
			$bulan = $row->bulan;
			$total = (float) $row->total;

			if (!isset($customerTotals[$name])) {
				$customerTotals[$name] = array_fill_keys($allMonths, 0);
				$customerTotals[$name]['Total'] = 0; // Total semua bulan per customer
			}

			$customerTotals[$name][$bulan] = $total;
			$customerTotals[$name]['Total'] += $total;
			$grandTotalPerMonth[$bulan] += $total;
			$grandTotalOverall += $total;
		}

		return view('report.incomeBook', compact(
			'customerTotals',
			'allMonths',
			'grandTotalPerMonth',
			'grandTotalOverall',
			'label'
		));
	}



	public function grubItem()
	{
		// 🔥 STEP 1: Aggregate warehouse_item
		$wi = DB::table('warehouse_item')
			->select(
				'warehouse_id',
				'item_id',
				DB::raw('SUM(quantity) as qty')
			)
			->groupBy('warehouse_id', 'item_id');

		// 🔥 STEP 2: SUMMARY per gudang
		$data = DB::table('customers as c')
			->leftJoinSub($wi, 'wi', function ($join) {
				$join->on('wi.warehouse_id', '=', 'c.id');
			})
			->leftJoin('items as i', 'i.id', '=', 'wi.item_id')
			->where('c.type', Customer::TYPE_WAREHOUSE)
			->select(
				'c.id',
				'c.name as nama_gudang',

				DB::raw('COUNT(DISTINCT wi.item_id) as total_item'),
				DB::raw('COALESCE(SUM(wi.qty), 0) as total_qty'),

				DB::raw("
                COALESCE(SUM(
                    wi.qty * 
                    CASE 
                        WHEN i.type = '" . Item::TYPE_ASSET_LANCAR . "' THEN COALESCE(i.cost, 0)
                        WHEN i.type = '" . Item::TYPE_ITEM . "' THEN (COALESCE(i.price, 0) * 0.3)
                        ELSE 0
                    END
                ), 0) as total_cost
            ")
			)
			->groupBy('c.id', 'c.name')
			->orderBy('c.name')
			->get();

		// 🔍 VALIDASI jumlah gudang
		$totalWarehouse = Customer::withTrashed()
			->where('type', Customer::TYPE_WAREHOUSE)
			->count();

		// dd($data, $totalWarehouse);

		return view('report.whItem', compact('totalWarehouse', 'data'));
	}

	public function laporanBiaya(Request $request)
	{
		// =========================
		// 1. FILTER TANGGAL
		// =========================
		$datesNow = Carbon::now();

		$month = $request->month;
		$year  = $request->year ?? $datesNow->year;

		if ($month) {
			$date = Carbon::createFromDate($year, $month, 1);
			$startDate = $date->startOfMonth()->toDateString();
			$endDate   = $date->endOfMonth()->toDateString();
		} else {
			$startDate = Carbon::createFromDate($year, 1, 1)->startOfYear()->toDateString();
			$endDate   = Carbon::createFromDate($year, 12, 31)->endOfYear()->toDateString();
		}

		// =========================
		// 2. AMBIL LIST UNTUK VIEW (Tabel)
		// =========================
		// Kita tetap mengambil datanya untuk me-render nama-nama di tabel Blade
		$customers = Customer::withTrashed()
			->whereIn('type', [
				Customer::TYPE_ACCOUNT,
				Customer::TYPE_BANK
			])
			->get();

		$accountList = $customers->where('type', Customer::TYPE_ACCOUNT)->values();
		$bankList    = $customers->where('type', Customer::TYPE_BANK)->values();

		// =========================
		// 3. QUERY TUNGGAL (SUPER OPTIMIZED)
		// =========================
		// Filter langsung di DB menggunakan sender_type dan receiver_type
		$rows = Transaction::whereBetween('date', [$startDate, $endDate])
			->where(function ($q) {
				// Kondisi A: Dari Account ke Bank
				$q->where(function ($q2) {
					$q2->where('sender_type', Customer::TYPE_ACCOUNT)
						->where('receiver_type', Customer::TYPE_BANK);
				})
					// Kondisi B: Dari Bank ke Account
					->orWhere(function ($q2) {
						$q2->where('sender_type', Customer::TYPE_BANK)
							->where('receiver_type', Customer::TYPE_ACCOUNT);
					});
			})
			->selectRaw("
            sender_id,
            receiver_id,
            sender_type,
            receiver_type,
            SUM(total) as total
        ")
			->groupBy('sender_id', 'receiver_id', 'sender_type', 'receiver_type')
			->get();

		// =========================
		// 4. INIT REPORT
		// =========================
		$accountReport = [
			'cashIn'  => [], // Sender = Account, Receiver = Bank
			'cashOut' => [], // Sender = Bank, Receiver = Account
		];

		$bankReport = [
			'cashIn'  => [], // Sender = Bank, Receiver = Account
			'cashOut' => [], // Sender = Account, Receiver = Bank
		];

		// =========================
		// 5. LOOP DATA (CORE LOGIC)
		// =========================
		foreach ($rows as $row) {

			// ---------------------------------------------------------
			// KONDISI A: Uang mengalir dari ACCOUNT ke BANK
			// ---------------------------------------------------------
			if ($row->sender_type == Customer::TYPE_ACCOUNT && $row->receiver_type == Customer::TYPE_BANK) {
				// Jurnal (Account): Cash IN
				$accountReport['cashIn'][$row->sender_id] =
					($accountReport['cashIn'][$row->sender_id] ?? 0) + $row->total;

				// Bank: Cash OUT
				$bankReport['cashOut'][$row->receiver_id] =
					($bankReport['cashOut'][$row->receiver_id] ?? 0) + $row->total;
			}

			// ---------------------------------------------------------
			// KONDISI B: Uang mengalir dari BANK ke ACCOUNT
			// ---------------------------------------------------------
			if ($row->sender_type == Customer::TYPE_BANK && $row->receiver_type == Customer::TYPE_ACCOUNT) {
				// Jurnal (Account): Cash OUT (Account sebagai receiver)
				$accountReport['cashOut'][$row->receiver_id] =
					($accountReport['cashOut'][$row->receiver_id] ?? 0) + $row->total;

				// Bank: Cash IN (Bank sebagai sender)
				$bankReport['cashIn'][$row->sender_id] =
					($bankReport['cashIn'][$row->sender_id] ?? 0) + $row->total;
			}
		}

		// =========================
		// 6. YEAR LIST
		// =========================
		$yearList = [];
		for ($i = 2019; $i <= date('Y'); $i++) {
			$yearList[] = $i;
		}
		$yearList = array_reverse($yearList);

		// =========================
		// 7. RETURN VIEW
		// =========================
		return view('report.biaya_jurnal_bank', [
			'accountList'   => $accountList,
			'accountReport' => $accountReport,

			'bankList'      => $bankList,
			'bankReport'    => $bankReport,

			'month'    => $month,
			'year'     => $year,
			'yearList' => $yearList,
			'datesNow' => $datesNow,
		]);
	}
}
