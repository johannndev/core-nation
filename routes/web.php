<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AddrbookController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\AsetLancarController;
use App\Http\Controllers\BoronganController;
use App\Http\Controllers\ContributorController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeletedController;
use App\Http\Controllers\FilterQueryController;
use App\Http\Controllers\HashController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResellerController;
use App\Http\Controllers\SetoranController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\VAccountController;
use App\Http\Controllers\VWarehouseController;
use App\Http\Controllers\WarehouseController;
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

Route::post('/filter', [FilterQueryController::class, 'getFilter'])->name('filter.get');

Route::middleware('auth')->group(function () {

    Route::get('/ajax/getCustomer', [AjaxController::class, 'getCostumer'])->name('ajax.getCostumer');
    Route::get('/ajax/getCustomerCash', [AjaxController::class, 'getCostumerCash'])->name('ajax.getCostumerCash');
    Route::get('/ajax/getCustomerSingle', [AjaxController::class, 'getCostumerSingle'])->name('ajax.getCostumerSingle');
    Route::get('/getItem', [AjaxController::class, 'getItemAjax'])->name('ajax.getitem');
    Route::get('/getItemId', [AjaxController::class, 'getItemId'])->name('ajax.getitemId');
    Route::get('/getItemName', [AjaxController::class, 'getItem'])->name('ajax.getitemName');
    Route::get('/getInvoice', [AjaxController::class, 'getInvoice'])->name('ajax.getInvoice');

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

    Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('/customer/create', [CustomerController::class, 'create'])->name('customer.create');
    Route::get('/customer/{id}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::post('/customer/{id}/update', [CustomerController::class, 'postEdit'])->name('customer.update');
    Route::get('/customer/{id}/transaction', [CustomerController::class, 'transaction'])->name('customer.transaction');
    Route::get('/customer/{id}/detail', [CustomerController::class, 'detail'])->name('customer.detail');
    Route::get('/customer/{id}/item', [CustomerController::class, 'items'])->name('customer.items');
    Route::get('/customer/{id}/stat', [CustomerController::class, 'stat'])->name('customer.stat');

    Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
    Route::get('/supplier/create', [SupplierController::class, 'create'])->name('supplier.create');
    Route::get('/supplier/{id}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
    Route::get('/supplier/{id}/transaction', [SupplierController::class, 'transaction'])->name('supplier.transaction');
    Route::get('/supplier/{id}/detail', [SupplierController::class, 'detail'])->name('supplier.detail');
    Route::get('/supplier/{id}/item', [SupplierController::class, 'items'])->name('supplier.items');
    Route::get('/supplier/{id}/stat', [SupplierController::class, 'stat'])->name('supplier.stat');

    Route::get('/warehouse', [WarehouseController::class, 'index'])->name('warehouse.index');
    Route::get('/warehouse/create', [WarehouseController::class, 'create'])->name('warehouse.create');
    Route::get('/warehouse/{id}/edit', [WarehouseController::class, 'edit'])->name('warehouse.edit');
    Route::get('/warehouse/{id}/transaction', [WarehouseController::class, 'transaction'])->name('warehouse.transaction');
    Route::get('/warehouse/{id}/detail', [WarehouseController::class, 'detail'])->name('warehouse.detail');
    Route::get('/warehouse/{id}/item', [WarehouseController::class, 'items'])->name('warehouse.items');
    Route::get('/warehouse/{id}/stat', [WarehouseController::class, 'stat'])->name('warehouse.stat');

    Route::get('/vwarehouse', [VWarehouseController::class, 'index'])->name('vwarehouse.index');
    Route::get('/vwarehouse/create', [VWarehouseController::class, 'create'])->name('vwarehouse.create');
    Route::get('/vwarehouse/{id}/edit', [VWarehouseController::class, 'edit'])->name('vwarehouse.edit');
    Route::get('/vwarehouse/{id}/transaction', [VWarehouseController::class, 'transaction'])->name('vwarehouse.transaction');
    Route::get('/vwarehouse/{id}/detail', [VWarehouseController::class, 'detail'])->name('vwarehouse.detail');
    Route::get('/vwarehouse/{id}/item', [VWarehouseController::class, 'items'])->name('vwarehouse.items');
    Route::get('/vwarehouse/{id}/stat', [VWarehouseController::class, 'stat'])->name('vwarehouse.stat');

    Route::get('/account', [AccountController::class, 'index'])->name('account.index');
    Route::get('/account/create', [AccountController::class, 'create'])->name('account.create');
    Route::get('/account/{id}/edit', [AccountController::class, 'edit'])->name('account.edit');
    Route::get('/account/{id}/transaction', [AccountController::class, 'transaction'])->name('account.transaction');
    Route::get('/account/{id}/detail', [AccountController::class, 'detail'])->name('account.detail');
    Route::get('/account/{id}/item', [AccountController::class, 'items'])->name('account.items');
    Route::get('/account/{id}/stat', [AccountController::class, 'stat'])->name('account.stat');

    Route::get('/vaccount', [VAccountController::class, 'index'])->name('vaccount.index');
    Route::get('/vaccount/create', [VAccountController::class, 'create'])->name('vaccount.create');
    Route::get('/vaccount/{id}/edit', [VAccountController::class, 'edit'])->name('vaccount.edit');
    Route::get('/vaccount/{id}/transaction', [VAccountController::class, 'transaction'])->name('vaccount.transaction');
    Route::get('/vaccount/{id}/detail', [VAccountController::class, 'detail'])->name('vaccount.detail');
    Route::get('/vaccount/{id}/item', [VAccountController::class, 'items'])->name('vaccount.items');
    Route::get('/vaccount/{id}/stat', [VAccountController::class, 'stat'])->name('vaccount.stat');

    Route::get('/reseller', [ResellerController::class, 'index'])->name('reseller.index');
    Route::get('/reseller/create', [ResellerController::class, 'create'])->name('reseller.create');
    Route::get('/reseller/{id}/edit', [ResellerController::class, 'edit'])->name('reseller.edit');
    Route::get('/reseller/{id}/transaction', [ResellerController::class, 'transaction'])->name('reseller.transaction');
    Route::get('/reseller/{id}/detail', [ResellerController::class, 'detail'])->name('reseller.detail');
    Route::get('/reseller/{id}/item', [ResellerController::class, 'items'])->name('reseller.items');
    Route::get('/reseller/{id}/stat', [ResellerController::class, 'stat'])->name('reseller.stat');

    Route::post('/addrbook/store', [AddrbookController::class, 'postCreate'])->name('addrbook.store');
    Route::post('/addrbook/{id}/update', [AddrbookController::class, 'postEdit'])->name('addrbook.update');
    Route::post('/addrbook/{id}/delete', [AddrbookController::class, 'postDelete'])->name('addrbook.delete');
    Route::post('/addrbook/{id}/restore', [AddrbookController::class, 'postRestore'])->name('addrbook.restore');

    Route::get('/operation', [OperationController::class, 'index'])->name('operation.index');
    Route::get('/operation/create', [OperationController::class, 'create'])->name('operation.create');
    Route::post('/operation/store', [OperationController::class, 'store'])->name('operation.store');
    Route::get('/operation/{id}/edit', [OperationController::class, 'edit'])->name('operation.edit');
    Route::patch('/operation/{id}/store', [OperationController::class, 'update'])->name('operation.update');
    Route::get('/operation/{id}/account/edit', [OperationController::class, 'editAccount'])->name('operation.account.edit');
    Route::patch('/operation/{id}/account/update', [OperationController::class, 'updateAccount'])->name('operation.account.update');
    Route::get('/operation/{id}/detail', [OperationController::class, 'detail'])->name('operation.detail');
    Route::get('/operation/{id}/account', [OperationController::class, 'account'])->name('operation.account');
    Route::get('/operation/account', [OperationController::class, 'accountList'])->name('operation.account.list');
    Route::get('/operation/account/create', [OperationController::class, 'createAccount'])->name('operation.account.create');
    Route::post('/operation/account/store', [OperationController::class, 'postCreateAccount'])->name('operation.postCreateAccount');

    Route::get('/produksi', [ProduksiController::class, 'index'])->name('produksi.index');
    Route::get('/produksi/create', [ProduksiController::class, 'create'])->name('produksi.create');
    Route::post('/produksi/store', [ProduksiController::class, 'storeProduksi'])->name('produksi.store');
    Route::get('/produksi/{id}/detail', [ProduksiController::class, 'detail'])->name('produksi.detail');
    Route::patch('/produksi/{id}/detail/updateJahit', [ProduksiController::class, 'postSaveRow'])->name('produksi.postSaveRow');
    Route::patch('/produksi/{id}/detail/updatewc', [ProduksiController::class, 'postEdit'])->name('produksi.postEdit');
    Route::post('/produksi/{id}/detail/split', [ProduksiController::class, 'postPisahJahit'])->name('produksi.postPisahJahit');
    Route::patch('/produksi/{id}/detail/gantiJahit', [ProduksiController::class, 'postGantiJahit'])->name('produksi.postGantiJahit');
    Route::patch('/produksi/{id}/setor', [ProduksiController::class, 'postSetor'])->name('produksi.postSetor');

    Route::get('/produksi/potong/list', [ProduksiController::class, 'getPotongList'])->name('produksi.getPotongList');
    Route::get('/produksi/potong/create', [ProduksiController::class, 'getPotongCreate'])->name('produksi.getPotongCreate');
    Route::post('/produksi/potong/store', [ProduksiController::class, 'createPotong'])->name('produksi.createPotong');
    Route::get('/produksi/potong/{id}/create', [ProduksiController::class, 'getPotongEdit'])->name('produksi.getPotongEdit');
    Route::patch('/produksi/potong/{id}/update', [ProduksiController::class, 'updatePotong'])->name('produksi.updatePotong');
    Route::delete('/produksi/potong/{id}/delete', [ProduksiController::class, 'postDeletePotong'])->name('produksi.postDeletePotong');

    Route::get('/produksi/jahit/list', [ProduksiController::class, 'getJahitList'])->name('produksi.getJahitList');
    Route::get('/produksi/jahit/create', [ProduksiController::class, 'getJahitCreate'])->name('produksi.getJahitCreate');
    Route::post('/produksi/jahit/store', [ProduksiController::class, 'createJahit'])->name('produksi.createJahit');
    Route::get('/produksi/jahit/{id}/create', [ProduksiController::class, 'getJahitEdit'])->name('produksi.getJahitEdit');
    Route::patch('/produksi/jahit/{id}/update', [ProduksiController::class, 'updateJahit'])->name('produksi.updateJahit');
    Route::delete('/produksi/jahit/{id}/delete', [ProduksiController::class, 'postDeleteJahit'])->name('produksi.postDeleteJahit');
    
    Route::get('/setoran', [SetoranController::class, 'index'])->name('setoran.index');
    Route::get('/setoran/{id}/detail', [SetoranController::class, 'detail'])->name('setoran.detail');
    Route::patch('/setoran/{id}/updatekode', [SetoranController::class, 'postEditItem'])->name('setoran.postEditItem');
    Route::patch('/setoran/{id}/detail/updatewc', [SetoranController::class, 'postEdit'])->name('setoran.postEdit');
    Route::patch('/setoran/{id}/detail/gantiJahit', [SetoranController::class, 'postGantiJahit'])->name('setoran.postGantiJahit');
    Route::patch('/setoran/{id}/detail/gantiStatus', [SetoranController::class, 'postEditStatus'])->name('setoran.postEditStatus');
    Route::patch('/setoran/{id}/detail/postGudang', [SetoranController::class, 'postGudang'])->name('setoran.postGudang');

    Route::get('/borongan', [BoronganController::class, 'index'])->name('borongan.index');

    Route::get('/hash/{id}/transaction', [HashController::class, 'getTransactions'])->name('hash.getTransactions');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
});

require __DIR__.'/auth.php';
