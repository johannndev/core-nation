<?php

namespace App\Helpers;

use App\Models\Customer;
use App\Models\Deleted;
use App\Models\DeletedDetail;
use App\Models\Depreciation;
use App\Models\Item;
use App\Models\Produksi;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class DeleterHelper
{
    protected $d;//new
	protected $t;
	protected $sm;

	public function delete($transaction)
	{
		$this->t = $transaction;//saves passing around var
		$this->sm = new StatManagerHelper;

		//clone transaction
		$this->d = new Deleted();
		foreach($transaction->getAttributes() as $key => $val)
		{
			$this->d->setAttribute($key,$val);
		}

		if(!$this->d->save())
		{
			$msg = Arr::flatten($this->d->getErrors());
          
			throw new \Exception($msg[0]);
		}

       
		$result = false;

       
		switch($this->t->type)
		{
			case Transaction::TYPE_BUY:
				$result = $this->delete_buy();
				break;
			case Transaction::TYPE_RETURN:
				$result = $this->delete_buy();
				break;
			case Transaction::TYPE_SELL:
				$result = $this->delete_sell();
				break;
			case Transaction::TYPE_RETURN_SUPPLIER:
				$result = $this->delete_sell();
				break;
			case Transaction::TYPE_CASH_IN:
			case Transaction::TYPE_CASH_OUT:
				$result = $this->delete_income_expense();
				break;
			case Transaction::TYPE_TRANSFER:
				$result = $this->delete_transfer();
				break;
			case Transaction::TYPE_ADJUST:
				$result = $this->delete_adjust();
				break;
			case Transaction::TYPE_MOVE:
				$result = $this->delete_move();
				break;
			case Transaction::TYPE_PRODUCTION:
				$result = $this->delete_production();
				break;
			case Transaction::TYPE_USE:
				$result = $this->delete_use();
				break;
			default:
				break;
		}

      

		if(!$result) return false;

		//update the transaction status
		$transaction->delete();
		if(!$this->d->save())
			throw new \Exception('error deleting transaction, error #'.__LINE__);

		return $this->d;
	}

	protected function delete_income_expense()
	{
		//expense
		if($this->t->type == Transaction::TYPE_CASH_OUT)
		{
			$this->add($this->t->sender_id);
			$this->add($this->t->receiver_id);
			if($this->sm->add($this->t->sender_id,$this->t) === false)	throw new \Exception('error updating balance. ERROR#'.__LINE__);
			if($this->sm->add($this->t->receiver_id,$this->t) === false) throw new \Exception('error updating balance. ERROR#'.__LINE__);
		}
		else
		{
			$this->deduct($this->t->sender_id);
			$this->deduct($this->t->receiver_id);
			if($this->sm->deduct($this->t->sender_id,$this->t) === false)	throw new \Exception('error updating balance. ERROR#'.__LINE__);
			if($this->sm->deduct($this->t->receiver_id,$this->t) === false) throw new \Exception('error updating balance. ERROR#'.__LINE__);
		}
		return true;
	}

	protected function delete_transfer()
	{
		//add to sender
		$this->add($this->t->sender_id);
		if($this->sm->add($this->t->sender_id,$this->t) === false)	throw new \Exception('error updating balance. ERROR#'.__LINE__);

		//deduct from receiver
		$this->deduct($this->t->receiver_id);
		if($this->sm->deduct($this->t->receiver_id,$this->t) === false) throw new \Exception('error updating balance. ERROR#'.__LINE__);

		return true;
	}

	//buy == sender
	protected function delete_buy()
	{
		//deduct from sender
		$this->deduct($this->t->sender_id);
		if($this->sm->deduct($this->t->sender_id,$this->t) === false) throw new \Exception('error updating sender balance. ERROR#'.__LINE__);

		//get the old details
		$details = array_filter(explode(',',$this->t->detail_ids));
		$details = TransactionDetail::with('item')->whereIn('id',array_filter(explode(',',$this->t->detail_ids)))->get();

		//copy to deleted table
		foreach($details as $detail)
		{
			//copy
			$del_detail = new DeletedDetail();
			foreach($detail->getAttributes() as $key => $val)
			{
				$del_detail->setAttribute($key,$val);
			}
			$del_detail->save();

			//flag depreciation
			$item = Item::find($detail->item_id);
			if($item->type == Item::TYPE_ASSET_TETAP)
			{
				$dep = Depreciation::find($item->id);
				if($dep)
				{
					$dep->buy_date = $detail->date;
					$dep->buy_price = $detail->price;
					$expire = Carbon::parse($detail->date)->addMonths(intval($dep->value));
					$dep->expire_date = Carbon::parse($expire)->toDateString();
					if(!$dep->save())
						throw new \Exception('Penyusutan tidak bisa di simpan');
				}
			}

			//add back to supplier = sender
			ItemsManagerHelper::add($this->t->sender_id, $detail->item, $detail->quantity);
			ItemsManagerHelper::deduct($this->t->receiver_id, $detail->item, $detail->quantity, false);

			//delete the old detail
			$detail->delete();
		}

		return true;
	}

	//sell == receiver
	protected function delete_sell()
	{
		$this->add($this->t->receiver_id);
		if($this->sm->add($this->t->receiver_id,$this->t) === false) throw new \Exception('error updating receiver balance. ERROR#'.__LINE__);

		//get the old details
		$details = array_filter(explode(',',$this->t->detail_ids));
		$details = TransactionDetail::with('item')->whereIn('id',array_filter(explode(',',$this->t->detail_ids)))->get();

		//copy to deleted table
		foreach($details as $detail)
		{
			//copy
			$del_detail = new DeletedDetail;
			foreach($detail->getAttributes() as $key => $val)
			{
				$del_detail->setAttribute($key,$val);
			}

			$del_detail->save();
			$detail_ids[] = $del_detail->id;

			ItemsManagerHelper::add($this->t->sender_id, $detail->item, $detail->quantity);
			ItemsManagerHelper::deduct($this->t->receiver_id, $detail->item, $detail->quantity, true);

			//delete the old detail
			$detail->delete();
		}

		return true;
	}

	//delete move
	protected function delete_move()
	{
		//get the old details
		$details = array_filter(explode(',',$this->t->detail_ids));
		$details = TransactionDetail::with('item')->whereIn('id',array_filter(explode(',',$this->t->detail_ids)))->get();

		$transactionModel = new Transaction;
		$can_minus = $transactionModel->can_minus($this->t->receiver);
		//copy to deleted table
		foreach($details as $detail)
		{
			//copy
			$del_detail = new DeletedDetail;
			foreach($detail->getAttributes() as $key => $val)
			{
				$del_detail->setAttribute($key,$val);
			}
			$del_detail->save();

			ItemsManagerHelper::add($this->t->sender_id, $detail->item, $detail->quantity);
			ItemsManagerHelper::deduct($this->t->receiver_id, $detail->item, $detail->quantity, $can_minus);

			$detail->delete();
		}

		return true;
	}

	//delete production
	protected function delete_production()
	{
		//get the old details
		$details = array_filter(explode(',',$this->t->detail_ids));
		$details = TransactionDetail::with('item')->whereIn('id',array_filter(explode(',',$this->t->detail_ids)))->get();

		//copy to deleted table
		foreach($details as $detail)
		{
			//copy
			$del_detail = new DeletedDetail;
			foreach($detail->getAttributes() as $key => $val)
			{
				$del_detail->setAttribute($key,$val);
			}
			$del_detail->save();

			ItemsManagerHelper::deduct($this->t->receiver_id, $detail->item, $detail->quantity);

			//update the produksi
			DB::table(Produksi::table())->where('detail_id','=',$detail->id)->update(array('invoice' => '','detail_id' => 0,'status' => DB::raw('status / '.Produksi::STATUS_TURUN)));

			$detail->delete();
		}

		return true;
	}

	//delete use
	protected function delete_use()
	{
		//get the old details
		$details = array_filter(explode(',',$this->t->detail_ids));
		$details = TransactionDetail::with('item')->whereIn('id',array_filter(explode(',',$this->t->detail_ids)))->get();

		//copy to deleted table
		foreach($details as $detail)
		{
			//copy
			$del_detail = new DeletedDetail;
			foreach($detail->getAttributes() as $key => $val)
			{
				$del_detail->setAttribute($key,$val);
			}
			$del_detail->save();

			ItemsManagerHelper::add($this->t->sender_id, $detail->item, $detail->quantity);

			$detail->delete();
		}

		return true;
	}

	protected function delete_adjust()
	{
		//add to sender
		$this->add($this->t->sender_id);
		if($this->sm->add($this->t->sender_id,$this->t) === false)	throw new \Exception('error updating balance. ERROR#'.__LINE__);

		//deduct from receiver
		if(!in_array($this->t->receiver_type, array(Customer::TYPE_WAREHOUSE, Customer::TYPE_VWAREHOUSE)))
		{
			$this->deduct($this->t->receiver_id);
			if($this->sm->deduct($this->t->receiver_id,$this->t) === false) throw new \Exception('error updating balance. ERROR#'.__LINE__);
		}

		return true;
	}

	protected function addReal($id)
	{
		$total = abs($this->t->real_total);
		DB::table(Transaction::table())->where('date','=',$this->t->date)->where('id','>',$this->t->id)->where('sender_id','=',$id)->update(array('sender_balance' => DB::raw('sender_balance + '.$total)));
		DB::table(Transaction::table())->where('date','=',$this->t->date)->where('id','>',$this->t->id)->where('receiver_id','=',$id)->update(array('receiver_balance' => DB::raw('receiver_balance + '.$total)));
	}

	protected function deductReal($id)
	{
		$total = abs($this->t->real_total);
		DB::table(Transaction::table())->where('date','=',$this->t->date)->where('id','>',$this->t->id)->where('sender_id','=',$id)->update(array('sender_balance' => DB::raw('sender_balance - '.$total)));
		DB::table(Transaction::table())->where('date','=',$this->t->date)->where('id','>',$this->t->id)->where('receiver_id','=',$id)->update(array('receiver_balance' => DB::raw('receiver_balance - '.$total)));
	}

	protected function add($id)
	{
		$total = abs($this->t->total);
		DB::table(Transaction::table())->where('date','=',$this->t->date)->where('id','>',$this->t->id)->where('sender_id','=',$id)->update(array('sender_balance' => DB::raw('sender_balance + '.$total)));
		DB::table(Transaction::table())->where('date','=',$this->t->date)->where('id','>',$this->t->id)->where('receiver_id','=',$id)->update(array('receiver_balance' => DB::raw('receiver_balance + '.$total)));
	}

	protected function deduct($id)
	{
		$total = abs($this->t->total);
		DB::table(Transaction::table())->where('date','=',$this->t->date)->where('id','>',$this->t->id)->where('sender_id','=',$id)->update(array('sender_balance' => DB::raw('sender_balance - '.$total)));
		DB::table(Transaction::table())->where('date','=',$this->t->date)->where('id','>',$this->t->id)->where('receiver_id','=',$id)->update(array('receiver_balance' => DB::raw('receiver_balance - '.$total)));
	}

}




