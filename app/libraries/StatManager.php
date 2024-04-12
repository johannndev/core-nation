<?
namespace App\Libraries;

use App\Models\CustomerStat, App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class StatManager extends BaseManager
{
	protected $stat;

	/* gets the row before the new transaction */
	public function last_date($id,$date,$skip = false)
	{
		$last = Transaction::where('date','<=',$date)->where(function($query) use($id)
				{
					$query->where('sender_id','=',$id);
					$query->orWhere('receiver_id','=',$id);
				})
				->orderBy('date','desc')->orderBy('id','desc');

		if($skip) $last = $last->skip(1);
		$last = $last->lockforUpdate()->first();
		return $last;
	}

	//use skip if the transaction is already saved before calling this function eg: buy/sell
	public function add($id, $transaction, $skip = false)
	{
		return $this->update($id, $transaction, $skip);
	}

	public function deduct($id, $transaction, $skip = false)
	{
		return $this->update($id, $transaction, $skip,false);
	}

	public function addReal($id, $transaction, $skip = false)
	{
		return $this->update($id, $transaction, $skip, true, true);
	}

	public function deductReal($id, $transaction, $skip = false)
	{
		return $this->update($id, $transaction, $skip, false, true);
	}

	protected function update($id, $transaction, $skip, $positive = true, $real = false)
	{
		if($real)
			$total = $transaction->real_total;
		else
			$total = $transaction->total;

		if($positive)
			$total = abs($total);
		elseif($total > 0)
			$total = 0 - $total;

		$this->stat = CustomerStat::lockforUpdate()->find($id);
		if(!$this->stat)
			return $this->error('customer stat doesnt exist');

		//get the last transaction before this date, find the balance
		$last = $this->last_date($id,$transaction->date,$skip);
		//no last data? use the current balance
		if(!$last) $last_balance = $this->stat->balance;
		else $last_balance = $last->sender_id == $id?$last->sender_balance:$last->receiver_balance;

		//update the stat
		$this->stat->balance += $total;

		//update the balances in the transaction table
		Transaction::where('sender_id','=',$id)
			->where('date','>',$transaction->date)->lockforUpdate()
			->update(array('sender_balance' => DB::raw('sender_balance + '.$total)));

		Transaction::where('receiver_id','=',$id)
			->where('date','>',$transaction->date)->lockforUpdate()
			->update(array('receiver_balance' => DB::raw('receiver_balance + '.$total)));

		if(!$this->stat->save())
			return false;

		return $last_balance + $total;
	}
}
?>