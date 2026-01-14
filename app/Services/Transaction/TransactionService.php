<?php

namespace App\Services\Transaction;

use App\Models\Transaction;
use App\Models\Customer;
use App\Helpers\StatManagerHelper;
use App\Helpers\InvoiceTrackerHelpers;
use Illuminate\Support\Facades\DB;
use App\Exceptions\ModelException;

class TransactionService
{
    public function createTransaction($type = null, $dataJubelio)
    {
        try {
            DB::statement('SET TRANSACTION ISOLATION LEVEL READ COMMITTED');
            DB::beginTransaction();

            $customer  = Customer::findOrFail($dataJubelio->customer);
            $warehouse = Customer::findOrFail($dataJubelio->warehouse);

            $transaction = new Transaction();
            $transaction->date        = $dataJubelio->date;
            $transaction->type        = $type;
            $transaction->adjustment = $dataJubelio->adjustment;
            $transaction->user_id     = -100;
            $transaction->submit_type = 4;
            $transaction->description = $dataJubelio->note ?? '';
            $transaction->invoice     = $dataJubelio->invoice;
            $transaction->due         = $dataJubelio->due ?: '0000-00-00';
            $transaction->detail_ids  = ' ';
            $transaction->save();

            switch ($type) {
                case Transaction::TYPE_BUY:
                case Transaction::TYPE_RETURN:
                    $transaction->sender_id   = $customer->id;
                    $transaction->receiver_id = $warehouse->id;
                    break;
                case Transaction::TYPE_SELL:
                case Transaction::TYPE_RETURN_SUPPLIER:
                    $transaction->sender_id   = $warehouse->id;
                    $transaction->receiver_id = $customer->id;
                    break;
            }

            $transaction->init($type);
            $transaction->save();

            if (!$transaction->createDetails($dataJubelio->addMoreInputFields)) {
                throw new ModelException($transaction->getErrors());
            }

            $transaction->checkPPN($transaction->sender, $transaction->receiver);

            $sm = new StatManagerHelper();

            if (in_array($type, [Transaction::TYPE_SELL, Transaction::TYPE_RETURN_SUPPLIER])) {
                $transaction->total = 0 - $transaction->total;
                $transaction->receiver_balance = $sm->deduct(
                    $transaction->receiver_id,
                    $transaction,
                    true
                );
            }

            $transaction->save();
            InvoiceTrackerHelpers::flag($transaction);

            DB::commit();

            return [
                'status' => '200',
                'message' => 'ok',
                'transaction_id' => $transaction->id,
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            return [
                'status' => '422',
                'message' => $e->getMessage(),
            ];
        }
    }
}
