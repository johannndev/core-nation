<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\DeletedController;
use App\Http\Controllers\FilterQueryController;
use App\Http\Controllers\HashController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/cek', [HomeController::class, 'cekData'])->name('');

Route::middleware('auth')->group(function () {

    Route::get('/ajax/getCustomer', [AjaxController::class, 'getCostumer'])->name('ajax.getCostumer');
    Route::get('/ajax/getCustomerCash', [AjaxController::class, 'getCostumerCash'])->name('ajax.getCostumerCash');
    Route::get('/ajax/getCustomerSingle', [AjaxController::class, 'getCostumerSingle'])->name('ajax.getCostumerSingle');
    Route::get('/getItem', [AjaxController::class, 'getItemAjax'])->name('ajax.getitem');
    Route::get('/getItemName', [AjaxController::class, 'getItem'])->name('ajax.getitemName');

    Route::get('/transaction', [TransactionsController::class, 'index'])->name('transaction.index');
    Route::get('/transaction/filter', [FilterQueryController::class, 'transactionFilter'])->name('transaction.filter');
    
    Route::get('/transaction/sell', [TransactionsController::class, 'sell'])->name('transaction.sell');
    Route::post('/transaction/sell/post', [TransactionsController::class, 'postSell'])->name('transaction.postSell');

    Route::get('/transaction/buy', [TransactionsController::class, 'buy'])->name('transaction.buy');
    Route::post('/transaction/buy/post', [TransactionsController::class, 'postbuy'])->name('transaction.postBuy');

    Route::get('/transaction/move', [TransactionsController::class, 'move'])->name('transaction.move');
    Route::post('/transaction/move/post', [TransactionsController::class, 'postmove'])->name('transaction.postMove');

    Route::get('/transaction/use', [TransactionsController::class, 'use'])->name('transaction.use');
    Route::post('/transaction/use/post', [TransactionsController::class, 'postuse'])->name('transaction.postUse');

    Route::get('/transaction/cash-in', [TransactionsController::class, 'cashIn'])->name('transaction.cashIn');
    Route::post('/transaction/cash-in/post', [TransactionsController::class, 'postCashIn'])->name('transaction.cashInPost');

    Route::get('/transaction/cash-out', [TransactionsController::class, 'cashOut'])->name('transaction.cashOut');
    Route::post('/transaction/cash-out/post', [TransactionsController::class, 'postCashOut'])->name('transaction.cashOutPost');

    Route::get('/transaction/adjust', [TransactionsController::class, 'adjust'])->name('transaction.adjust');
    Route::post('/transaction/adjust/post', [TransactionsController::class, 'postAdjust'])->name('transaction.postAdjust');

    Route::get('/transaction/transfer', [TransactionsController::class, 'transfer'])->name('transaction.transfer');
    Route::post('/transaction/transfer/post', [TransactionsController::class, 'postTransfer'])->name('transaction.postTransfer');

    Route::get('/transaction/return', [TransactionsController::class, 'return'])->name('transaction.return');
    Route::post('/transaction/return/post', [TransactionsController::class, 'postReturn'])->name('transaction.postReturn');

    Route::get('/transaction/return-supplier', [TransactionsController::class, 'returnSupplier'])->name('transaction.returnSupplier');
    Route::post('/transaction/return-supplier/post', [TransactionsController::class, 'postReturnSupplier'])->name('transaction.postReturnSupplier');

    Route::get('/transaction/{id}/detail', [TransactionsController::class, 'getDetail'])->name('transaction.getDetail');

    Route::get('/transaction/delete', [DeletedController::class, 'index'])->name('transaction.delete');
    Route::get('/transaction/delete/filter', [FilterQueryController::class, 'transactionFilterDelete'])->name('transaction.deletefilter');
    Route::get('/transaction/{id}/delete/detail', [DeletedController::class, 'getDetailDelete'])->name('transaction.getDetailDelete');



   

    Route::get('/hash/{id}/transaction', [HashController::class, 'getTransactions'])->name('hash.getTransactions');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
