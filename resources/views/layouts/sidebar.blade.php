<div class="panel-group" id="sidebar-accordion">
@if($access->isAdmin())
<div class="panel">
	<div class="panel-heading"><a class="accordion-toggle" data-toggle="collapse" data-parent="#sidebar-accordion" href="#collapse2"><span class="icon-notebook">&nbsp;</span>Transactions</a></div>
	<div id="collapse2" class="panel-collapse collapse">
		<div class="panel-body">
			<div class="row">
				<div id="transactions-index" class="col-xs-12"><a href="{{ URL::action('TransactionsController@getIndex') }}"><span class="icon-list-ol">&nbsp;</span>List</a></div>
			</div>
			<div class="row">
				<div id="transactions-cash-in" class="col-xs-12"><a href="{{ URL::action('TransactionsController@getCashIn') }}"><span class="icon-download">&nbsp;</span>Cash In</a></div>
			</div>
			<div class="row">
				<div id="transactions-cash-out" class="col-xs-12"><a href="{{ URL::action('TransactionsController@getCashOut') }}"><span class="icon-upload">&nbsp;</span>Cash Out</a></div>
			</div>
			<div class="row">
				<div id="transactions-adjust" class="col-xs-12"><a href="{{ URL::action('TransactionsController@getAdjust') }}"><span class="icon-tab">&nbsp;</span>Adjust</a></div>
			</div>
			<div class="row">
				<div id="transactions-transfer" class="col-xs-12"><a href="{{ URL::action('TransactionsController@getTransfer') }}"><span class="icon-tab">&nbsp;</span>Transfer</a></div>
			</div>
			<div class="row">
				<div id="transactions-return" class="col-xs-12"><a href="{{ URL::action('TransactionsController@getReturn') }}"><span class="icon-sad">&nbsp;</span>Return</a></div>
			</div>
			<div class="row">
				<div id="transactions-return-supplier" class="col-xs-12"><a href="{{ URL::action('TransactionsController@getReturnSupplier') }}"><span class="icon-truck">&nbsp;</span>Return Supplier</a></div>
			</div>
			<div class="row">
				<div id="deleted-index" class="col-xs-12"><a href="{{ URL::action('DeletedController@getIndex') }}"><span class="icon-remove">&nbsp;</span>Deleted</a></div>
			</div>
		</div>
	</div>
</div>

<div class="panel">
	<div class="panel-heading"><a class="accordion-toggle" data-toggle="collapse" data-parent="#sidebar-accordion" href="#collapse1"><span class="icon-address-book">&nbsp;</span>Addr. Book</a></div>
	<div id="collapse1" class="panel-collapse collapse">
		<div class="panel-body">
				<div class="row">
				<div id="customers-index" class="col-xs-9"><a href="{{ URL::action('CustomersController@getIndex') }}"><span class="icon-users">&nbsp;</span>Customers</a></div>
				<div id="customers-create" class="col-xs-3"><a href="{{ URL::action('CustomersController@getCreate') }}"><span class="icon-plus">&nbsp;</span></a></div>
			</div>
			<div class="row">
				<div id="resellers-index" class="col-xs-9"><a href="{{ URL::action('ResellersController@getIndex') }}"><span class="icon-users">&nbsp;</span>Resellers</a></div>
				<div id="resellers-create" class="col-xs-3"><a href="{{ URL::action('ResellersController@getCreate') }}"><span class="icon-plus">&nbsp;</span></a></div>
			</div>
			<div class="row">
				<div id="suppliers-index" class="col-xs-9"><a href="{{ URL::action('SuppliersController@getIndex') }}"><span class="icon-office">&nbsp;</span>Suppliers</a></div>
				<div id="suppliers-create" class="col-xs-3"><a href="{{ URL::action('SuppliersController@getCreate') }}"><span class="icon-plus">&nbsp;</span></a></div>
			</div>
			<div class="row">
				<div id="warehouses-index" class="col-xs-9"><a href="{{ URL::action('WarehousesController@getIndex') }}"><span class="icon-home">&nbsp;</span>Warehouses</a></div>
				<div id="warehouses-create" class="col-xs-3"><a href="{{ URL::action('WarehousesController@getCreate') }}"><span class="icon-plus">&nbsp;</span></a></div>
			</div>
			<div class="row">
				<div id="bank-accounts-index" class="col-xs-9"><a href="{{ URL::action('BankAccountsController@getIndex') }}"><span class="icon-coin">&nbsp;</span>Accounts</a></div>
				<div id="bank-accounts-create" class="col-xs-3"><a href="{{ URL::action('BankAccountsController@getCreate') }}"><span class="icon-plus">&nbsp;</span></a></div>
			</div>
			<div class="row">
				<div id="vwarehouses-index" class="col-xs-9"><a href="{{ URL::action('VWarehousesController@getIndex') }}"><span class="icon-screen">&nbsp;</span>V. Warehouse</a></div>
				<div id="vwarehouses-create" class="col-xs-3"><a href="{{ URL::action('VWarehousesController@getCreate') }}"><span class="icon-plus">&nbsp;</span></a></div>
			</div>
			<div class="row">
				<div id="vaccounts-index" class="col-xs-9"><a href="{{ URL::action('VAccountsController@getIndex') }}"><span class="icon-archive">&nbsp;</span>V. Accounts</a></div>
				<div id="vaccounts-create" class="col-xs-3"><a href="{{ URL::action('VAccountsController@getCreate') }}"><span class="icon-plus">&nbsp;</span></a></div>
			</div>
		</div>
	</div>
</div>

<div class="panel">
	<div class="panel-heading"><a class="accordion-toggle" data-toggle="collapse" data-parent="#sidebar-accordion" href="#collapse3"><span class="icon-t-shirt">&nbsp;</span>Stuff</a></div>
	<div id="collapse3" class="panel-collapse collapse">
		<div class="panel-body">
			<div class="row">
				<div id="items-index" class="col-xs-12"><a href="{{ URL::action('ItemsController@getIndex') }}"><span class="icon-t-shirt">&nbsp;</span>Items</a></div>
			</div>
			<div class="row">
				<div id="asset-lancar-index" class="col-xs-9"><a href="{{ URL::action('AssetLancarController@getIndex') }}"><span class="icon-weather">&nbsp;</span>Asset Lancar</a></div>
				<div id="asset-lancar-create" class="col-xs-3"><a href="{{ URL::action('AssetLancarController@getCreate') }}"><span class="icon-plus">&nbsp;</span></a></div>
			</div>
			<div class="row">
				<div id="asset-tetap-index" class="col-xs-9"><a href="{{ URL::action('AssetTetapController@getIndex') }}"><span class="icon-cloudy">&nbsp;</span>Asset Tetap</a></div>
				<div id="asset-tetap-create" class="col-xs-3"><a href="{{ URL::action('AssetTetapController@getCreate') }}"><span class="icon-plus">&nbsp;</span></a></div>
			</div>
			<div class="row">
				<div id="vitems-index" class="col-xs-9"><a href="{{ URL::action('VItemsController@getIndex') }}"><span class="icon-cloudy">&nbsp;</span>V. Item</a></div>
				<div id="vitems-create" class="col-xs-3"><a href="{{ URL::action('VItemsController@getCreate') }}"><span class="icon-plus">&nbsp;</span></a></div>
			</div>
			<div class="row">
				<div id="tags-index" class="col-xs-9"><a href="{{ URL::action('TagsController@getIndex') }}"><span class="icon-tags">&nbsp;</span>Tags</a></div>
				<div id="tags-create" class="col-xs-3"><a href="{{ URL::action('TagsController@getCreate') }}"><span class="icon-plus">&nbsp;</span></a></div>
			</div>
			<div class="row">
				<div id="items-price" class="col-xs-12"><a href="{{ URL::action('ItemsController@getPrice') }}"><span class="icon-tab">&nbsp;</span>Pricing</a></div>
			</div>
			<div class="row">
				<div id="items-sell-stats" class="col-xs-12"><a href="{{ URL::action('ItemsController@getSellStats') }}"><span class="icon-pie">&nbsp;</span>Sell Stats</a></div>
			</div>
			<div class="row">
				<div id="items-use-stats" class="col-xs-12"><a href="{{ URL::action('ItemsController@getUseStats') }}"><span class="icon-sigma">&nbsp;</span>Use Stats</a></div>
			</div>
		</div>
	</div>
</div>

<div class="panel">
	<div class="panel-heading"><a class="accordion-toggle" data-toggle="collapse" data-parent="#sidebar-accordion" href="#collapse4"><span class="icon-calendar">&nbsp;</span>Journals</a></div>
	<div id="collapse4" class="panel-collapse collapse">
		<div class="panel-body">
			<div class="row">
				<div id="operations-index" class="col-xs-9"><a href="{{ URL::action('OperationsController@getIndex') }}"><span class="icon-notebook">&nbsp;</span>Operations</a></div>
				<div id="operations-create" class="col-xs-3"><a href="{{ URL::action('OperationsController@getCreate') }}"><span class="icon-plus">&nbsp;</span></a></div>
			</div>
			<div class="row">
				<div id="operations-accounts" class="col-xs-9"><a href="{{ URL::action('OperationsController@getAccounts') }}"><span class="icon-notebook">&nbsp;</span>Accounts</a></div>
				<div id="operations-create-account" class="col-xs-3"><a href="{{ URL::action('OperationsController@getCreateAccount') }}"><span class="icon-plus">&nbsp;</span></a></div>
			</div>
		</div>
	</div>
</div>
<div class="panel">
	<div class="panel-heading"><a class="accordion-toggle" data-toggle="collapse" data-parent="#sidebar-accordion" href="#collapse8"><span class="icon-users">&nbsp;</span>Personnels</a></div>
	<div id="collapse8" class="panel-collapse collapse">
		<div class="panel-body">
			<div class="row">
				<div id="personnels-index" class="col-xs-9"><a href="{{ URL::action('PersonnelsController@getIndex') }}"><span class="icon-users">&nbsp;</span>Personnels</a></div>
				<div id="personnels-create" class="col-xs-3"><a href="{{ URL::action('PersonnelsController@getCreate') }}"><span class="icon-plus">&nbsp;</span></a></div>
			</div>
			<div class="row">
				<div id="personnels-gpu" class="col-xs-12"><a href="{{ URL::action('PersonnelsController@getGpu') }}"><span class="icon-users">&nbsp;</span>GPU</a></div>
			</div>
			<div class="row">
				<div id="personnels-private-gpu" class="col-xs-12"><a href="{{ URL::action('PersonnelsController@getPrivateGpu') }}"><span class="icon-users">&nbsp;</span>Private GPU</a></div>
			</div>
			<div class="row">
				<div id="pelanggaran-index" class="col-xs-9"><a href="{{ URL::action('PelanggaranController@getIndex') }}"><span class="icon-evil">&nbsp;</span>Pelanggaran</a></div>
				<div id="pelanggaran-add" class="col-xs-3"><a href="{{ URL::action('PelanggaranController@getAdd') }}"><span class="icon-plus">&nbsp;</span></a></div>
			</div>
			<div class="row">
				<div id="cuti-index" class="col-xs-9"><a href="{{ URL::action('CutiController@getIndex') }}"><span class="icon-grin">&nbsp;</span>Cuti</a></div>
				<div id="cuti-add" class="col-xs-3"><a href="{{ URL::action('CutiController@getAdd') }}"><span class="icon-plus">&nbsp;</span></a></div>
			</div>
			<div class="row">
				<div id="gaji-index" class="col-xs-12"><a href="{{ URL::action('GajiController@getIndex') }}"><span class="icon-coin">&nbsp;</span>Gaji</a></div>
			</div>
			<div class="row">
				<div id="gaji-private" class="col-xs-12"><a href="{{ URL::action('GajiController@getPrivate') }}"><span class="icon-coin">&nbsp;</span>Private Gaji</a></div>
			</div>
		</div>
	</div>
</div>
<div class="panel">
	<div class="panel-heading"><a class="accordion-toggle" data-toggle="collapse" data-parent="#sidebar-accordion" href="#collapse9"><span class="icon-pie">&nbsp;</span>Reports</a></div>
	<div id="collapse9" class="panel-collapse collapse">
		<div class="panel-body"s::>
			<div class="row">
				<div id="reports-profit-loss" class="col-xs-12"><a href="{{ URL::action('ReportsController@getProfitLoss') }}"><span class="icon-signup">&nbsp;</span>Profit/Loss</a></div>
			</div>
			<div class="row">
				<div id="reports-customer-class" class="col-xs-12"><a href="{{ URL::action('ReportsController@getCustomerClass') }}"><span class="icon-pie">&nbsp;</span>Classification</a></div>
			</div>
			<div class="row">
				<div id="reports-geo" class="col-xs-12"><a href="{{ URL::action('ReportsController@getGeo') }}"><span class="icon-pie">&nbsp;</span>Geo</a></div>
			</div>
			<div class="row">
				<div id="reports-cash-flow" class="col-xs-12"><a href="{{ URL::action('ReportsController@getCashFlow') }}"><span class="icon-pie">&nbsp;</span>Cash Flow</a></div>
			</div>
			<div class="row">
				<div id="reports-revenue" class="col-xs-12"><a href="{{ URL::action('ReportsController@getRevenue') }}"><span class="icon-coin">&nbsp;</span>Revenues</a></div>
			</div>
		</div>
	</div>
</div>

<div class="panel">
	<div class="panel-heading"><a class="accordion-toggle" data-toggle="collapse" data-parent="#sidebar-accordion" href="#collapse12"><span class="icon-pie">&nbsp;</span>Notifications</a></div>
	<div id="collapse12" class="panel-collapse collapse">
		<div class="panel-body"s::>
			<div class="row">
				<div id="notification-index" class="col-xs-12"><a href="{{ URL::action('NotificationsController@getIndex') }}"><span class="icon-signup">&nbsp;</span>Font Page</a></div>
			</div>
			<div class="row">
				<div id="notification-due-reseller" class="col-xs-12"><a href="{{ URL::action('NotificationsController@getDueReseller') }}"><span class="icon-pie">&nbsp;</span>Due Resellers</a></div>
			</div>
			<div class="row">
				<div id="notification-due-supplier" class="col-xs-12"><a href="{{ URL::action('NotificationsController@getDueSupplier') }}"><span class="icon-pie">&nbsp;</span>Due Suppliers</a></div>
			</div>
		</div>
	</div>
</div>

<div class="panel">
	<div class="panel-heading"><a class="accordion-toggle" data-toggle="collapse" data-parent="#sidebar-accordion" href="#collapse10"><span class="icon-users">&nbsp;</span>Users</a></div>
	<div id="collapse10" class="panel-collapse collapse">
		<div class="panel-body">
				<div class="row">
				<div id="users-index" class="col-xs-9"><a href="{{ URL::action('UsersController@getIndex') }}"><span class="icon-user">&nbsp;</span>Users</a></div>
				<div id="users-create" class="col-xs-3"><a href="{{ URL::action('UsersController@getCreate') }}"><span class="icon-plus">&nbsp;</span></a></div>
			</div>
			<div class="row">
				<div id="users-role" class="col-xs-9"><a href="{{ URL::action('UsersController@getRoles') }}"><span class="icon-cool">&nbsp;</span>Roles</a></div>
				<div id="users-create-role" class="col-xs-3"><a href="{{ URL::action('UsersController@getCreateRole') }}"><span class="icon-plus">&nbsp;</span></a></div>
			</div>
			<div class="row">
				<div id="locations-index" class="col-xs-9"><a href="{{ URL::action('LocationsController@getIndex') }}"><span class="icon-paperplane">&nbsp;</span>Locations</a></div>
				<div id="locations-create" class="col-xs-3"><a href="{{ URL::action('LocationsController@getCreate') }}"><span class="icon-plus">&nbsp;</span></a></div>
			</div>
			<div class="row">
				<div id="locations-settings" class="col-xs-12"><a href="{{ URL::action('LocationsController@getSettings') }}"><span class="icon-signup">&nbsp;</span>Loc. Settings</a></div>
			</div>
		</div>
	</div>
</div>

@include('layouts.sidebar_modules')

<ul class="list-group">
	<li id="hash-index" class="list-group-item"><a href="{{ URL::action('HashController@getIndex') }}"><span class="icon-hash">&nbsp;</span>Hashtags</a></li>
	<li id="settings-index" class="list-group-item"><a href="{{ URL::action('SettingsController@getIndex') }}"><span class="icon-cog">&nbsp;</span>Settings</a></li>
</ul>

@else
{{ $user->role->sidebar }}
@endif
</div>
