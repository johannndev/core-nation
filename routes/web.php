<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\AsetLancarController;
use App\Http\Controllers\ContributorController;
use App\Http\Controllers\DeletedController;
use App\Http\Controllers\FilterQueryController;
use App\Http\Controllers\HashController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemsController;
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

    Route::get('/item', [ItemsController::class, 'index'])->name('item.index');
    Route::get('/item/create', [ItemsController::class, 'create'])->name('item.create');
    Route::post('/item/store', [ItemsController::class, 'postCreate'])->name('item.post');
    Route::get('/item/{id}/edit', [ItemsController::class, 'edit'])->name('item.edit');
    Route::post('/item/{id}/update', [ItemsController::class, 'postEdit'])->name('item.update');
    Route::get('/item/filter', [FilterQueryController::class, 'itemFilter'])->name('item.filter');
    Route::get('/item/{id}/detail', [ItemsController::class, 'detail'])->name('item.detail');
    Route::get('/item/{id}/transaction', [ItemsController::class, 'transaction'])->name('item.transaction');
    Route::get('/item/{id}/transaction/filter', [FilterQueryController::class, 'itemTransFilter'])->name('item.transactionFilter');
    Route::get('/item/{id}/stat', [ItemsController::class, 'stat'])->name('item.stat');
    Route::get('/item/{id}/stat/filter', [FilterQueryController::class, 'itemStatFilter'])->name('item.statFilter');
    Route::get('/item/group', [ItemsController::class, 'group'])->name('item.group');
    Route::get('/item/group/filter', [FilterQueryController::class, 'itemGroupTransFilter'])->name('item.filterGroup');
    Route::get('/item/group/{id}/detail', [ItemsController::class, 'groupDetail'])->name('item.detailGroup');
    Route::get('/item/group/{id}/stat', [ItemsController::class, 'groupStat'])->name('item.statGroup');
    Route::get('/item/group/{id}/stat/filter', [FilterQueryController::class, 'itemGroupStatFilter'])->name('item.statFilterGroup');

    Route::get('/asset-lancar', [AsetLancarController::class, 'index'])->name('asetLancar.index');
    Route::get('/asset-lancar/create', [AsetLancarController::class, 'create'])->name('asetLancar.create');
    Route::post('/asset-lancar/store', [AsetLancarController::class, 'postCreate'])->name('asetLancar.postCreate');
    Route::get('/asset-lancar/{id}/edit', [AsetLancarController::class, 'edit'])->name('asetLancar.edit');
    Route::post('/asset-lancar/{id}/update', [AsetLancarController::class, 'postEdit'])->name('asetLancar.update');
    Route::get('/asset-lancar/filter', [FilterQueryController::class, 'assetLancarFilter'])->name('asetLancar.filter');
    Route::get('/asset-lancar/{id}/detail', [AsetLancarController::class, 'detail'])->name('asetLancar.detail');
    Route::get('/asset-lancar/{id}/transaction', [AsetLancarController::class, 'transaction'])->name('asetLancar.transaction');
    Route::get('/asset-lancar/{id}/transaction/filter', [FilterQueryController::class, 'assetLancarTransFilter'])->name('asetLancar.transactionFilter');
    Route::get('/asset-lancar/{id}/stat', [AsetLancarController::class, 'stat'])->name('asetLancar.stat');
    Route::get('/asset-lancar/{id}/stat/filter', [FilterQueryController::class, 'assetLancarStatFilter'])->name('asetLancar.statFilter');

    Route::get('/contributors', [ContributorController::class, 'index'])->name('contributor.index');
    Route::get('/contributors/filter', [FilterQueryController::class, 'contributorFilter'])->name('contributor.filter');

    Route::get('/hash/{id}/transaction', [HashController::class, 'getTransactions'])->name('hash.getTransactions');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
});

require __DIR__.'/auth.php';
