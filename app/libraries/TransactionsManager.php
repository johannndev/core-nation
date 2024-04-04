<?
namespace App\Libraries;
use App\Models\Transaction, App\Models\CustomerStat;
use App\Libraries\StatManager;
use Apps, App, Cache, Config, Dater, DB, Event, Input, InputForm, Redirect, Response, Session, URL, View, ModelException, Exception, Auth;
class TransactionsManager
{
	protected $_query;
	protected $_sort;
	protected $_dir;

	public static function checkSell($transaction, $details)
	{
		//if not sell, dont check
		if($transaction->type != Transaction::TYPE_SELL)
			return true;

		$journal_id = \App::make('appsettings')->get('sell_100');
		if(empty($journal_id)) //journal is not set
			return true;

		$sell_100 = 0;
		foreach($details as $detail)
		{
			if($detail->discount >= 100)
				$sell_100 = $sell_100 + ($detail->cost * $detail->quantity);
		}

		//check for sell_100
		if(empty($sell_100))
			return true;

		$sm = new StatManager;

		$journal = new Transaction;
		$journal->date = $transaction->date;
		$journal->init(Transaction::TYPE_ADJUST);
		$journal->sender_id = $journal_id;
		$journal->receiver_id = $transaction->sender_id;
		$journal->receiver_balance = 0;
		$journal->total = 0 - $sell_100;
		$journal->invoice = $transaction->invoice;
		$journal->description = 'Untuk Transaction Invoice: '.$transaction->invoice;

		//update the account balance
		$sender_balance = $sm->deduct($journal_id,$journal);
		if($sender_balance === false)
			throw new \Exception($sm->getErrors()->first());

		$journal->sender_balance = $sender_balance;
		if(!$journal->save())
			throw new \Exception('error creating adjustment');

		return true;
	}

	public static function toArray($transactions, $lids = false, $summaryId = false)
	{
		$response = array();
		$response['currentPage'] = $transactions->getCurrentPage();
		$response['lastPage'] = $transactions->getLastPage();
		$response['data'] = array();
		foreach ($transactions as $index => $t) {
			$response['data'][$index]['id'] = $t->id;
			$response['data'][$index]['date'] = Dater::display($t->date);
			$response['data'][$index]['sender_balance'] = $t->sender_balance;
			$response['data'][$index]['receiver_balance'] = $t->receiver_balance;
			$response['data'][$index]['description'] = $t->description;
			$response['data'][$index]['invoice'] = $t->invoice;
			$response['data'][$index]['total'] = $t->total;
			$response['data'][$index]['total_items'] = $t->total_items;
			$response['data'][$index]['type'] = $t->printType();
			$response['data'][$index]['detail_link'] = $t->getDetailLink();
			$response['data'][$index]['sender_url'] = $response['data'][$index]['sender_name'] = $response['data'][$index]['receiver_url'] = $response['data'][$index]['receiver_name'] = '';
			if($t->sender) {
				$response['data'][$index]['sender_url'] = $t->sender->getDetailLink();
				$response['data'][$index]['sender_name'] = $t->sender->name;
			}
			if($t->receiver) {
				$response['data'][$index]['receiver_url'] = $t->receiver->getDetailLink();
				$response['data'][$index]['receiver_name'] = $t->receiver->name;
			}
			if(is_array($lids) && !in_array($t->sender_id, $lids))
				$response['data'][$index]['sender_balance'] = 0;
			if(is_array($lids) && !in_array($t->receiver_id, $lids))
				$response['data'][$index]['receiver_balance'] = 0;

			$response['data'][$index]['summary_balance'] = 0;
			if($summaryId && $summaryId == $t->sender_id)
				$response['data'][$index]['summary_balance'] = $response['data'][$index]['sender_balance'];
			elseif($summaryId && $summaryId == $t->receiver_id)
				$response['data'][$index]['summary_balance'] = $response['data'][$index]['receiver_balance'];
		}
		return $response;
	}
}