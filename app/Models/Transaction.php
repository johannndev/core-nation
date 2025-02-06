<?php

namespace App\Models;

use App\Exceptions\ModelException;
use App\Helpers\CCManagerHelper;
use App\Helpers\StatManagerHelper;
use Carbon\Carbon;
use Illuminate\Support\MessageBag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;




class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $guarded = [];

    const TYPE_BUY = 1;
	const TYPE_SELL = 2;
	const TYPE_MOVE = 3;
	const TYPE_TRANSFER = 6;
	const TYPE_CASH_OUT = 7;
	const TYPE_USE = 8;
	const TYPE_CASH_IN = 9;
	const TYPE_ADJUST = 12;
	const TYPE_RETURN = 15;
	const TYPE_PRODUCTION = 16;
	const TYPE_RETURN_SUPPLIER = 17;
	const TYPE_DEPRECIATION = 18;

	const STATUS_NORMAL = 0;
	const STATUS_PAID = 1;
	const STATUS_DUE = 2;

	public static $types = array(
		self::TYPE_BUY => 'Buy',
		self::TYPE_SELL => 'Sell',
		self::TYPE_MOVE => 'Move Items',
		self::TYPE_TRANSFER => 'Transfer',
		self::TYPE_CASH_OUT => 'Cash Out',
		self::TYPE_USE => 'Use Items',
		self::TYPE_CASH_IN => 'Cash In',
		self::TYPE_ADJUST => 'Adjust',
		self::TYPE_RETURN => 'Return',
		self::TYPE_PRODUCTION => 'Production',
		self::TYPE_RETURN_SUPPLIER => 'Return Supplier',
		self::TYPE_DEPRECIATION => 'Depreciation',
	);

	public static $typesJSON = array(
		array('id' => 0, 'name' => 'All'),
		array('id' => self::TYPE_BUY, 'name' => 'Buy'),
		array('id' => self::TYPE_SELL, 'name' => 'Sell'),
		array('id' => self::TYPE_MOVE, 'name' => 'Move'),
		array('id' => self::TYPE_TRANSFER, 'name' => 'Transfer'),
		array('id' => self::TYPE_CASH_OUT, 'name' => 'Cash Out'),
		array('id' => self::TYPE_USE, 'name' => 'Use'),
		array('id' => self::TYPE_CASH_IN, 'name' => 'Cash In'),
		array('id' => self::TYPE_ADJUST, 'name' => 'Adjust'),
		array('id' => self::TYPE_RETURN, 'name' => 'Return'),
		array('id' => self::TYPE_PRODUCTION, 'name' => 'Production'),
		array('id' => self::TYPE_RETURN_SUPPLIER, 'name' => 'Return'),
		array('id' => self::TYPE_DEPRECIATION, 'name' => 'Depreciation'),
	);

	public static $statuses = array(
		self::STATUS_NORMAL => 'Normal',
		self::STATUS_PAID => 'Paid',
		self::STATUS_DUE => 'Due',
	);
	

	public static $typesCustomer = array(
		1 => 'Customer',
		2 => 'Warehouse',
		3 => 'Banks',
		4 => 'Supplier',
		5 => 'V. Warehouse',
		6 => 'V. Account',
		7 => 'Reseller',
		8 => 'Account',
	);

	public static $rules = array(
		'date' => 'required|cdate',
		'sender_id' => 'required',
		'receiver_id' => 'required|different:sender_id',
	);

	

	public static function table()
	{
		return 'transactions';
	}

	public function getPriceColor($price)
	{
		if ($price < 0) {
			return 'text-red-500 font-bold';
		} elseif ($price > 0) {
			return 'text-green-500 font-bold';
		}
		return 'text-gray-500';
	}



	public function getTypeNameAttribute()
	{
		return self::$types[$this->type];
	}

	public function getTypeSenderNameAttribute()
	{
		return self::$typesCustomer[$this->sender_type];
	}

	public function getTypeReceiverNameAttribute()
	{
		return self::$typesCustomer[$this->receiver_type];
	}

	public function receiver()
	{
		return $this->belongsTo('App\Models\Customer','receiver_id')->withTrashed();
	}

	public function sender()
	{
		return $this->belongsTo('App\Models\Customer','sender_id')->withTrashed();
	}

	public function user()
	{
		return $this->belongsTo('App\Models\User','user_id');
	}

	public function transactionDetail(): HasMany
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id', 'id');
    }

	public function addError($key, $msg)
	{
		if(!$this->errors instanceof MessageBag)
			$this->errors = new MessageBag;
		$this->errors->add($key, $msg);
		return false;
	}

	public function getErrors()
	{
		if(!$this->errors instanceof MessageBag)
			$this->errors = new MessageBag;
		return $this->errors->toArray();
	}

	

    public function checkPPN($sender, $receiver) {

		

		if($sender->ppn || $receiver->ppn) {
			$this->ppn = abs(round(bcdiv(bcmul($this->total,0.11,5),1.11,5),2));
		} else {
			$this->ppn = 0;
		}
		//update total!!!
		if($this->total > 0)
			$this->total = $this->total - $this->ppn;
		else
			$this->total = $this->total + $this->ppn;

		
	}

    public function attachIncome($date, $sender, $receiver, $total)
	{
		$sm = new StatManagerHelper;
		$payment = new Transaction;
		$payment->date = $date;
		$payment->receiver_id = $receiver;
		$payment->init(Transaction::TYPE_CASH_IN,$sender);
		$payment->total = $total;

		$sender_balance = $sm->add($payment->sender_id,$payment);
		if($sender_balance === false)
			throw new \Exception($sm->getErrors()->first());
		$receiver_balance = $sm->add($payment->receiver_id,$payment);
		if($receiver_balance === false)
			throw new \Exception($sm->getErrors()->first());

		//update the balances
		$payment->receiver_balance = $receiver_balance;
		$payment->sender_balance = $sender_balance;
		$payment->invoice = $this->invoice;

		//gets the transaction id
		if(!$payment->save())
			throw new ModelException($payment->getErrors()->first());


        $date = Carbon::create($payment->date)->startOfMonth()->toDateString();
		$cc = new CCManagerHelper;
		$cc->update(array(
			'date' => $date,
			'type' => Transaction::TYPE_CASH_IN,
			'customer' => $payment->sender,
			'total' => $payment->total,
		));

		return $payment;
	}

	public function init($type, $customerId = null)
	{
		$this->type = $type;
		switch($type)
		{
			case Transaction::TYPE_BUY:
			case Transaction::TYPE_RETURN:
				$this->receiver_balance = 0;
				break;

			case Transaction::TYPE_SELL:
			case Transaction::TYPE_RETURN_SUPPLIER:
				$this->sender_balance = 0;
				break;

			case Transaction::TYPE_TRANSFER:
				static::$rules['total'] = 'required';
				break;

			case Transaction::TYPE_CASH_IN:
				$this->sender_id = $customerId;
				static::$rules['total'] = 'required';
				break;

			case Transaction::TYPE_CASH_OUT:
				$this->receiver_id = $customerId;
				static::$rules['total'] = 'required';
				break;

			case Transaction::TYPE_DEPRECIATION:
				static::$rules = array(
					'sender_id' => 'required',
				);
				$this->receiver_id = 0;
				$this->receiver_balance = $this->sender_balance = 0;
				break;

			case Transaction::TYPE_MOVE:
				$this->receiver_balance = $this->sender_balance = 0;
				break;

			case Transaction::TYPE_USE:
				static::$rules = array(
					'sender_id' => 'required',
					'date' => 'required|cdate',
				);
				$this->receiver_balance = $this->sender_balance = 0;
				$this->receiver_id = 0;
				break;

			case Transaction::TYPE_PRODUCTION:
				static::$rules = array(
					'receiver_id' => 'required',
					'date' => 'required|cdate',
				);
				$this->receiver_balance = $this->sender_balance = 0;
				$this->sender_id = 0;
			default: break;
		}

		if(!empty($this->due))
			$this->due = Carbon::createFromFormat('Y-m-d',$this->due)->toDateString();
		if(!empty($this->invoice))
			$this->invoice = trim($this->invoice);
		if(Auth::user())
		{
			$this->user_id = Auth::user()->id;
			$this->location_id = Auth::user()->location_id;
		}
		if(!empty($this->receiver_id))
		{
			$receiver = Customer::find($this->receiver_id);
			$this->receiver_type = $receiver->type;
		}
		if(!empty($this->sender_id))
		{
			$sender = Customer::find($this->sender_id);
			$this->sender_type = $sender->type;
		}
	}

	public function createDetails($details)
	{
		// dd($details);
		$total = 0;
		$real_total = 0;
		$total_items = 0.0;
		$cogs = 0;

		$saved = 0; //number of saved details
		$detail_ids = array(); //collect the detail ids

		//set the details years
		$can_minus = $this->can_minus(Customer::find($this->sender_id));

		$transactionDetails = array();
		//save items as array[item_id] => detail_id
		$item_ids = array();
		$savedDetails = array(); //to update saved detail instead of retrieving
		//start detail loop
		foreach($details as $i => $detail)
		{
			//skip empty code, quantity <= 0
			if(empty($detail['itemId']) || $detail['quantity'] <= 0)
				continue;

			$detail['quantity'] = str_replace(',','.',$detail['quantity']);
			$itemId = $detail['itemId'];
			$duplicate = false;
			//1. check for duplicate item id, duplicate price, duplicate discount
			if(isset($item_ids[$itemId])) {
				//set variables for easy debugging
				$savedDetailId = $item_ids[$itemId];
				$savedDetail = $savedDetails[$savedDetailId];
				//make sure the price and disc are the same for duplicates
				if(bccomp($savedDetail->price,$detail['price'],2) == 0 && bccomp($savedDetail->discount,$detail['discount'],2) == 0)
					$duplicate = true;
				//check for MOVE
				if($this->type == Transaction::TYPE_MOVE)
					$duplicate = true;
			}

			//store for total
			$oldQuantity = 0;
			$oldTotal = 0;
			//if duplicate, retrieve old transactiondetail
			if($duplicate) {
				$savedDetailId = $item_ids[$itemId];
				$savedDetail = $savedDetails[$savedDetailId];
				//save old variables
				$oldQuantity = $savedDetail->quantity;
				$oldTotal = $savedDetail->total;

				//only new variables is quantity - SWITCH TO NEW QUANTITY, first, then add quantity before saving
				$savedDetail->quantity = $detail['quantity'];
				$detail = $savedDetail;
			}
			else { //not a duplicate, create a new one
				$detail = new TransactionDetail(array(
					'quantity' => $detail['quantity'],
					'item_id' => $detail['itemId'],
					'price' => $detail['price'],
					'discount' => $detail['discount'],
				));
				//set default values
				$detail->transaction_id = $this->id;
				$detail->date = $this->date;
				$detail->transaction_type = $this->type;
				$detail->receiver_id = $this->receiver_id;
				$detail->sender_id = $this->sender_id;
				$detail->transaction_disc = $this->discount ? $this->discount : 0;
			}
			$item = Item::find($detail->item_id);

			//calculate the total price
			$detail->total = $this->getTotal($detail);

			//special case prices
			switch ($this->type) {
				case Transaction::TYPE_USE:
					$detail->price = $item->cost;
					$detail->total = $detail->price * $detail->quantity;
					break;
				case Transaction::TYPE_MOVE:
					$detail->price = $item->price;
					$detail->total = $detail->price * $detail->quantity;
					break;
				case Transaction::TYPE_SELL:
					$cogs = $cogs + bcmul($item->cost, $detail->quantity, 2);
				default:
					break;
			}

			$total += $detail->total;
			$real_total = $real_total + bcmul($detail->price,$detail->quantity, 2);
			$total_items += $detail->quantity;

			if($this->type == Transaction::TYPE_BUY && $item->type == Item::TYPE_ASSET_TETAP)
			{
				if($detail->quantity != 1)
				{
					$this->addError("details[$i][quantity]","Asset tetap cuma bisa beli 1 pcs");
					return false;
				}
				$dep = Depreciation::find($item->id);
				if(!$dep)
					return $this->addError('error','Asset tetap: '.$item->name.' tidak punya penyusutan, edit asset tetap dulu');
				$dep->buy_date = $detail->date;
				$dep->buy_price = $detail->price;
				$expire = Carbon::createFromDate('Y-m-d', $this->expire_date)->addMonths($dep->value);
				$dep->expire_date = $expire->toDateString();
				if(!$dep->save())
					return $this->addError('error','Penyusutan tidak bisa di simpan');
			}

			//deduct from sender, add to receiver
			try	{
				$this->deduct($this->sender_id, $item, $detail->quantity, $can_minus);

				

				if($this->receiver_id > 0)
					$this->add($this->receiver_id, $item, $detail->quantity);
			}
			catch(\Exception $e) {
				return $this->addError('error',$e->getMessage());
			}

			//check for item alert
//			if($this->type == Transaction::TYPE_USE)
//				ItemAlertManager::check($detail->item_id,$sender->quantity,$item->code);

			//if duplicate, add old quantity to new quantity
			if($duplicate) {
				$detail->quantity = bcadd($detail->quantity, $oldQuantity);
				$detail->total = bcadd($detail->total, $oldTotal);
			}

			if(!$detail->save())
				return $this->addError('error',$detail->errors->first());

			$saved++;
			$detail_ids[] = $detail->id;
			$transactionDetails[] = $detail;
			//save for duplicate check
			$item_ids[$detail->item_id] = $detail->id;
			$savedDetails[$detail->id] = $detail; //to update saved detail instead of retrieving
		}
		//END detail loop

		if(!$this->getAttribute('invoice')) $this->setAttribute('invoice',$this->id);
		if($this->errors instanceof MessageBag)
			if($this->errors->first())
				return false;

		if($saved < 1)
			$this->addError('error','Error, empty details');

		$total = ($total - bcmul($total, bcdiv($this->discount, 100,4), 2)) + $this->adjustment;
		$this->setAttribute('detail_ids',trim(implode(',',array_filter($detail_ids))),',');
		$this->setAttribute('total',$total);
		$this->setAttribute('cogs', $cogs);
		$this->setAttribute('real_total',$real_total);
		$this->setAttribute('total_items',$total_items);

		return $transactionDetails;
	}

	public function getTotal($d)
	{
		$price = bcmul($d->price,$d->quantity,2);
		$price = $price - round(($price / 100) * $d->discount, 2);
		return $price;
	}

	public function can_minus($sender)
	{
		if($sender->type == Customer::TYPE_WAREHOUSE) return false;

		//cannot minus if type = sell, returnsupplier, move, use except for customer type = stock
		switch($this->type)
		{
			case self::TYPE_SELL:
			case self::TYPE_RETURN_SUPPLIER:
			case self::TYPE_USE:
				return false;
			default: return true; break;
		}
		return true;
	}

	public function deduct($warehouse_id, $item, $quantity, $can_minus = false)
	{
		if($item->type == Item::TYPE_SERVICE) return true;

		if(!$wi = WarehouseItem::where('warehouse_id','=',$warehouse_id)->where('item_id','=',$item->id)->lockForUpdate()->first())
			$wi = WarehouseItem::create(array('warehouse_id' => $warehouse_id, 'item_id' => $item->id, 'quantity' => 0));

			// dd($wi);

		if(!$can_minus && ($wi->quantity - $quantity) < 0) //check if minus is allowed
			throw new \Exception("{$item->name} cuma ada {$wi->quantity}, mau diambil {$quantity}");

		$wi->quantity -= $quantity;
		if(!$wi->save())
			throw new \Exception($wi->errors->first());
		return $wi;
	}

	public static function add($warehouse_id, $item, $quantity)
	{
		if($item->type == Item::TYPE_SERVICE) return true;

		if(!$wi = WarehouseItem::where('warehouse_id','=',$warehouse_id)->where('item_id','=',$item->id)->lockForUpdate()->first())
			$wi = WarehouseItem::create(array('warehouse_id' => $warehouse_id, 'item_id' => $item->id, 'quantity' => 0));

		$wi->quantity += $quantity;

		if(!$wi->save())
			throw new \Exception($wi->errors->first());
		return $wi;
	}

	public function attachOngkir($date, $receiver_id, $total, $account_id)
	{
		if(empty($account_id))
			throw new ModelException('Freight cost account not set');

		$sm = new StatManagerHelper;
		$transaction = new Transaction;
		$transaction->date = $date;
		$transaction->init(Transaction::TYPE_CASH_IN);
		$transaction->total = $total;
		$transaction->invoice = $this->invoice;
		$transaction->receiver_id = $receiver_id;
		$transaction->sender_id = $account_id;

		$sender_balance = $sm->add($transaction->sender_id,$transaction);
		if($sender_balance === false)
			throw new ModelException($sm->getErrors()->first());

		$receiver_balance = $sm->add($transaction->receiver_id,$transaction);
		if($receiver_balance === false)
			throw new ModelException($sm->getErrors()->first());

		$transaction->sender_balance = $sender_balance;
		$transaction->receiver_balance = $receiver_balance;
		if(!$transaction->save())
			throw new ModelException($transaction->getErrors()->first());

		return $transaction;
	}


}
