@if($access->isAdmin())
<li>
  <div class="collapsible-header"><i class="material-icons">filter_drama</i>Transactions</div>
  <div class="collapsible-body collection">
    <a class="collection-item" href="{{ URL::action('TransactionsController@getIndex') }}">List</a>
    <a class="collection-item" href="{{ URL::action('TransactionsController@getCashIn') }}">Cash In</a>
    <a class="collection-item" href="{{ URL::action('TransactionsController@getCashOut') }}">Cash Out</a>
    <a class="collection-item" href="{{ URL::action('TransactionsController@getAdjust') }}">Adjust</a>
    <a class="collection-item" href="{{ URL::action('TransactionsController@getTransfer') }}">Transfer</a>
    <a class="collection-item" href="{{ URL::action('TransactionsController@getReturn') }}">Return</a>
    <a class="collection-item" href="{{ URL::action('TransactionsController@getReturnSupplier') }}">Return Supplier</a>
    <a class="collection-item" href="{{ URL::action('DeletedController@getIndex') }}">Deleted List</a>
  </div>
</li>
<li>
  <div class="collapsible-header"><i class="material-icons">place</i>Addr Book</div>
  <div class="collapsible-body collection">
    <div class="collection-item">
      <a class="left" href="{{ URL::action('CustomersController@getIndex') }}">Customers</a>
      <a href="{{ URL::action('CustomersController@getCreate') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
    <div class="collection-item">
      <a class="left" href="{{ URL::action('ResellersController@getIndex') }}">Resellers</a>
      <a href="{{ URL::action('ResellersController@getCreate') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
    <div class="collection-item">
      <a class="left" href="{{ URL::action('SuppliersController@getIndex') }}">Suppliers</a>
      <a href="{{ URL::action('SuppliersController@getCreate') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
    <div class="collection-item">
      <a class="left" href="{{ URL::action('WarehousesController@getIndex') }}">Warehouses</a>
      <a href="{{ URL::action('WarehousesController@getCreate') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
    <div class="collection-item">
      <a class="left" href="{{ URL::action('VWarehousesController@getIndex') }}">V. Warehouses</a>
      <a href="{{ URL::action('VWarehousesController@getCreate') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
    <div class="collection-item">
      <a class="left" href="{{ URL::action('BankAccountsController@getIndex') }}">Accounts</a>
      <a href="{{ URL::action('BankAccountsController@getCreate') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
    <div class="collection-item">
      <a class="left" href="{{ URL::action('VAccountsController@getIndex') }}">V. Accounts</a>
      <a href="{{ URL::action('VAccountsController@getCreate') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
  </div>
</li>
<li>
  <div class="collapsible-header"><i class="material-icons">whatshot</i>Stuffs</div>
  <div class="collapsible-body collection">
    <div class="collection-item">
      <a class="left" href="{{ URL::action('ItemsController@getIndex') }}">Items</a>
      <a href="{{ URL::action('ItemsController@getCreate') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
    <div class="collection-item">
      <a class="left" href="{{ URL::action('AssetLancarController@getIndex') }}">Asset Lancar</a>
      <a href="{{ URL::action('AssetLancarController@getCreate') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
    <div class="collection-item">
      <a class="left" href="{{ URL::action('AssetTetapController@getIndex') }}">Asset Tetap</a>
      <a href="{{ URL::action('AssetTetapController@getCreate') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
    <div class="collection-item">
      <a class="left" href="{{ URL::action('VItemsController@getIndex') }}">V. Item</a>
      <a href="{{ URL::action('VItemsController@getCreate') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
    <div class="collection-item">
      <a class="left" href="{{ URL::action('TagsController@getIndex') }}">Tags</a>
      <a href="{{ URL::action('TagsController@getCreate') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
  </div>
</li>
<li>
  <div class="collapsible-header"><i class="material-icons">payment</i>Journals</div>
  <div class="collapsible-body collection">
    <div class="collection-item">
      <a class="left" href="{{ URL::action('OperationsController@getIndex') }}">Operations</a>
      <a href="{{ URL::action('OperationsController@getCreate') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
    <div class="collection-item">
      <a class="left" href="{{ URL::action('OperationsController@getAccounts') }}">Accounts</a>
      <a href="{{ URL::action('OperationsController@getCreateAccount') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
    <a class="collection-item" href="{{ URL::action('HashController@getIndex') }}">Hashtags</a>
  </div>
</li>
<li>
  <div class="collapsible-header"><i class="material-icons">perm_identity</i>Users</div>
  <div class="collapsible-body collection">
    <div class="collection-item">
      <a class="left" href="{{ URL::action('UsersController@getIndex') }}">Users</a>
      <a href="{{ URL::action('UsersController@getCreate') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
    <div class="collection-item">
      <a class="left" href="{{ URL::action('UsersController@getRoles') }}">Roles</a>
      <a href="{{ URL::action('UsersController@getCreateRole') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
    <div class="collection-item">
      <a class="left" href="{{ URL::action('LocationsController@getIndex') }}">Locations</a>
      <a href="{{ URL::action('LocationsController@getCreate') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
    <a class="collection-item" href="{{ URL::action('LocationsController@getSettings') }}">Loc. Settings</a>
  </div>
</li>
<li>
  <div class="collapsible-header"><i class="material-icons">filter_drama</i>Reports</div>
  <div class="collapsible-body collection">
    <a class="collection-item" href="{{ URL::action('ReportsController@getProfitLoss') }}">Profit Loss</a>
  </div>
</li>
<li>
  <div class="collapsible-header"><i class="material-icons">filter_drama</i>System</div>
  <div class="collapsible-body collection">
    <a class="collection-item" href="{{ URL::action('SettingsController@getIndex') }}">Settings</a>
  </div>
</li>
@else
{{ $user->role->sidenav }}
@endif