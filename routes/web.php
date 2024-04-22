<?php

use App\Http\Controllers\AjaxController;
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
    Route::get('/ajax/getCustomerSingle', [AjaxController::class, 'getCostumerSingle'])->name('ajax.getCostumerSingle');
    Route::get('/getItem', [AjaxController::class, 'getItemAjax'])->name('ajax.getitem');
    Route::get('/getItemName', [AjaxController::class, 'getItem'])->name('ajax.getitemName');

    Route::get('/transaction', [TransactionsController::class, 'index'])->name('transaction.index');
    Route::get('/transaction/filter', [FilterQueryController::class, 'transactionFilter'])->name('transaction.filter');
    
    Route::get('/transaction/sell', [TransactionsController::class, 'sell'])->name('transaction.sell');
    Route::post('/transaction/sell/post', [TransactionsController::class, 'postSell'])->name('transaction.postSell');

    Route::get('/transaction/buy', [TransactionsController::class, 'buy'])->name('transaction.buy');
    Route::post('/transaction/buy/post', [TransactionsController::class, 'postbuy'])->name('transaction.postBuy');


    Route::get('/transaction/{id}/detail', [TransactionsController::class, 'getDetail'])->name('transaction.getDetail');

    Route::get('/hash/{id}/transaction', [HashController::class, 'getTransactions'])->name('hash.getTransactions');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
