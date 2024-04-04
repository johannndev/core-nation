<!DOCTYPE html>
<html lang="en" ng-app="rootApp">
<head>
<title>CoreNation</title>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no" />

<link rel="shortcut icon" href="{{ asset('img/ico/fav.ico') }}">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ asset('img/ico/ico144.png') }}">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ asset('img/ico/ico114.png') }}">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ asset('img/ico/ico72.png') }}">
<link rel="apple-touch-icon-precomposed" href="{{ asset('img/ico/ico57.png') }}">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/materialize.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('css/app.css') }}"/>
  @foreach($header_css as $path)
  <link rel="stylesheet" href="{{ asset($path) }}" />
  @endforeach

  @foreach($header_js as $path)
  <script src="{{ asset($path) }}"></script>
  @endforeach
<link rel="stylesheet" type="text/css" href="{{ asset('css/print.css') }}" media="print" />
</head>

<body ng-controller="LayoutController as lc">
<ul id="dropdown1" class="dropdown-content">
  <li><a href="{{ URL::action('ProfileController@getIndex') }}"><i class="material-icons">perm_identity</i>Profile</a></li>
  <li class="divider"></li>
  <li><a href="{{ URL::action('AuthController@getLogout') }}"><i class="material-icons">power_settings_new</i>Logout</a></li>
</ul>
<nav class="light-blue lighten-1" role="navigation">
  <div class="nav-wrapper">
    <a href="#" data-activates="left-menu" class="button-collapse"><i class="material-icons">menu</i></a>
    <ul id="navbar-left-menu" class="left hide-on-med-and-down">
      <li><a href="{{ URL::action('TransactionsController@getBuy') }}">Buy</a></li>
      <li><a href="{{ URL::action('TransactionsController@getSell') }}">Sell</a></li>
      <li><a href="{{ URL::action('TransactionsController@getMove') }}">Move</a></li>
      <li><a href="{{ URL::action('TransactionsController@getUse') }}">Use</a></li>
      <li><a href="{{ URL::action('ItemsController@getCreate') }}">Create Item</a></li>
    </ul>

    <ul id="nav-mobile" class="side-nav">
      <li><a href="{{ URL::action('TransactionsController@getBuy') }}">Buy</a></li>
      <li><a href="{{ URL::action('TransactionsController@getSell') }}">Sell</a></li>
      <li><a href="{{ URL::action('TransactionsController@getMove') }}">Move</a></li>
      <li><a href="{{ URL::action('TransactionsController@getUse') }}">Use</a></li>
      <li><a href="{{ URL::action('ItemsController@getCreate') }}">Create Item</a></li>
    </ul>
    <a href="#" data-activates="nav-mobile" class="button-collapse right"><i class="material-icons">menu</i></a>
    <ul class="right">
      <!-- Dropdown Trigger -->
      <li><a class="dropdown-button" href="#!" data-activates="dropdown1">Menu<i class="material-icons right">arrow_drop_down</i></a></li>
    </ul>

  </div>
</nav>

<div id="left-menu" class="side-nav fixed ph">
<h5 class="center-align"><a href="{{ URL::action('HomeController@getIndex') }}"><img src="{{ asset('img/logo.png') }}" /></a></h5>
  <ul class="collapsible popout" data-collapsible="accordion">
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
      <a class="left" href="{{ URL::action('ItemsController@getGroup') }}">Item Group</a>
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
      <a class="left" href="{{ URL::action('ContributorsController@getIndex') }}">Contributors</a>
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
    <a class="collection-item" href="{{ URL::action('ReportsController@getBalance') }}">Balance</a>
    <a class="collection-item" href="{{ URL::action('ReportsController@getCash') }}">Nett Cash</a>
  </div>
</li>
<li>
  <div class="collapsible-header"><i class="material-icons">filter_drama</i>System</div>
  <div class="collapsible-body collection">
    <a class="collection-item" href="{{ URL::action('SettingsController@getIndex') }}">Settings</a>
  </div>
</li>
<li>
  <div class="collapsible-header"><i class="material-icons">build</i>Produksi</div>
  <div class="collapsible-body collection">
    <div class="collection-item">
      <a class="left" href="{{ URL::action('ProduksiController@getIndex') }}">Produksi</a>
      <a href="{{ URL::action('ProduksiController@getCreateProduksi') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
    <div class="collection-item">
      <a href="{{ URL::action('SetoranController@getIndex') }}">Setoran</a>
    </div>
    <div class="collection-item">
      <a class="left" href="{{ URL::action('ProduksiController@getPotongList') }}">Potong</a>
      <a href="{{ URL::action('ProduksiController@getCreatePotong') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
    <div class="collection-item">
      <a class="left" href="{{ URL::action('ProduksiController@getJahitList') }}">Jahit</a>
      <a href="{{ URL::action('ProduksiController@getCreateJahit') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
  </div>
</li>
<li>
  <div class="collapsible-header"><i class="material-icons">perm_identity</i>Borongan</div>
  <div class="collapsible-body collection">
    <div class="collection-item">
      <a class="left" href="{{ URL::action('BoronganController@getIndex') }}">Borongan</a>
      <a href="{{ URL::action('BoronganController@getAdd') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
  </div>
</li>
<li>
  <div class="collapsible-header"><i class="material-icons">build</i>SPG</div>
  <div class="collapsible-body collection">
    <div class="collection-item">
      <a class="left" href="{{ URL::action('SpgController@getIndex') }}">List</a>
      <a href="{{ URL::action('SpgController@getCreate') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
  </div>
</li>
@else
{{ $user->role->sidenav }}
@endif
  </ul>
</div>

<div id="content-container">
<nav class="teal accent-1" role="navigation">
  <div class="nav-wrapper">
    <a href="#" class="blue-grey-text text-darken-3 flow-text aria-title"><b>{{ $action }}</b></a>
    @if(isset($caption) && !empty($caption))
    <ul>
      <li><a href="#!">{{ $caption }}</a></li>
    </ul>
    @endif
    @if(isset($editButton) && !empty($editButton))
    <ul class="right">
      <li><a href="{{ $editButton }}"><i class="material-icons">mode_edit</i></a></li>
    </ul>
    @endif
  </div>
</nav>
<div id="content">
    {{ $slot }}
</div>

<div id="footer" class="ph">
<p>Aria v6.0</p>
</div>

<div id="flashModal" class="modal">
  <div class="modal-content">
    <h4 class="@{{ lc.modal.class }}">@{{ lc.modal.title }}</h4>
    <ul class="collection">
      <li class="collection-item" ng-repeat="msg in lc.modal.msg">@{{ msg }}</li>
    </ul>
  </div>
  <div class="modal-footer">
    <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Ok</a>
  </div>
</div>

</div>

<script src="{{ asset('js/jquery-2.1.4.min.js') }}"></script>
<script src="{{ asset('js/angular/angular.min.js') }}"></script>
<script src="{{ asset('js/angular/angular-cookie.min.js') }}"></script>
<script src="{{ asset('js/materialize.min.js') }}"></script>
<script src="{{ asset('js/blazy.min.js') }}"></script>
<script src="{{ asset('js/lodash.core.min.js') }}"></script>
<script src="{{ asset('js/ui-bootstrap-custom-tpls-2.2.0.min.js') }}"></script>
<script src="{{ asset('js/filesaver.min.js') }}"></script>
<script src="{{ asset('js/json-export-excel.min.js') }}"></script>
<script src="{{ asset('js/aria/inits.js') }}"></script>
<script type="text/javascript">

$(document).ready(function () {
  $('.modal').modal();
  $('select').material_select();
$('.dateform').on('mousedown',function(event){
    event.preventDefault();
})

  $('.dateform').pickadate({
    format: 'dd/mm/yyyy',
    max: new Date()
  });
  $('.datedue').pickadate({
    format: 'dd/mm/yyyy',
    min: new Date()
  })
  $('.pickdate').pickadate({
    format: 'dd/mm/yyyy'
  })
  $(".button-collapse").sideNav();
  var bLazy = new Blazy({
      src: 'data-blazy' // Default is data-src
  });
});

angular.module('ariaApp.global', [])
.constant('ariaGlobal', {
@if($message = Session::get('success'))
  modal: {title: 'Success', msg: [{{ json_encode($message) }}] },
@elseif($message = Session::get('error'))
  modal: {title: 'Error', msg: [{{ json_encode($message) }}] },
@endif
})

</script>

@stack('script')

<script src="{{ asset('js/aria/services/helperService.js') }}"></script>
<script src="{{ asset('js/aria/bootstrap.js') }}"></script>
<script src="{{ asset('js/aria/controllers/LayoutController.js') }}"></script>

<script type="text/javascript">
  angular.module('rootApp', ['layout', 'ariaApp', 'ipCookie', 'ui.bootstrap', 'ngJsonExportExcel']);
</script>

@foreach($footer_js as $path)
<script src="{{ asset($path) }}"></script>
@endforeach
</body>
</html>
