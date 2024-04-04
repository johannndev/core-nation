<?
namespace App\Libraries;
use App\Models\User;
use App\Models\Acl;
use Apps, App, Cache, Config, Dater, DB, Event, Input, InputForm, Redirect, Response, Session, URL, View, ModelException, Exception, Auth;
class Access
{
	const ADMIN = 0;
	const TIME = 600;

	protected $_acl;
	protected $_user;

	public function __construct()
	{
		$this->_user = Auth::user();
		if(!$this->_user) $this->_user = new User;
		if($this->_user->role_id != Access::ADMIN)
			$this->load_rules();
	}

	public function getAcl()
	{
		return $this->_acl;
	}

	public function isAdmin()
	{
		return $this->_user->role_id == Access::ADMIN;
	}

	public function has($app)
	{
		if($this->_user->role_id == Access::ADMIN)
			return true;

		return $this->trueHas($app);
	}

	protected function trueHas($app)
	{
		if(is_array($app))
		{
			foreach($app as $app_id)
			{
				if(isset($this->_acl[$app_id]) && !empty($this->_acl[$app_id])) return true;
			}
			return false;
		}

		if(!isset(Apps::$list[$app])) return true; //app not in acl

		if(!isset($this->_acl[$app]) || empty($this->_acl[$app])) return false;
		return true;
	}

	public function can($action,$app)
	{
		if($this->_user->role_id == Access::ADMIN)
			return true;

		return $this->trueCan($action, $app);
	}

	protected function trueCan($action, $app)
	{
		if(!isset(Apps::$list[$app])) return true; //app not in acl
		if(!isset(Apps::$actions[$app][$action])) return true; //app has no action in acl

		if(!isset($this->_acl[$app]) || empty($this->_acl[$app])) return false; //user cant do shit on this app

		return in_array($action,$this->_acl[$app]);
	}

	public function filter($app)
	{
		if($this->_user->role_id == Access::ADMIN)
			return false;

		if(!isset(Apps::$list[$app])) return false; //app not in acl
		if(!isset($this->_acl[$app])) return false; //no filter

		return $this->_acl[$app];
	}

	public function hide($action,$app)
	{
		if($this->_user->role_id == Access::ADMIN)
			return false;

		return $this->trueCan($action,$app);
	}

	public function app($app)
	{
		if($this->_user->role_id == Access::ADMIN)
			return true;

		if(!isset($this->_acl[$app])) return true;
		return !empty($this->_acl[$app]);
	}

	/*
		loaded once per page request
	*/
	public function load_rules($role_id = null)
	{
		if(!$role_id)
			$role_id = $this->_user->role_id;

		$this->_acl = Cache::remember('access2.'.$role_id, Access::TIME, function () use($role_id) {
			$acl = array();

			//init the acl
			foreach(Apps::$list as $app => $link)
			{
				$acl[$app] = array();
			}
			$rules = Acl::where('role_id','=',$role_id)->orderBy('app_id','desc')->get();

			if(!$rules)
				return $acl;

			foreach($rules as $rule)
			{
				$acl[$rule->app_id][] = $rule->action;
			}

			return $acl;
		});

		return $this->_acl;
	}

	public function generateSidebar($roleId)
	{
		Cache::forget('access2.'.$roleId);
		$this->load_rules($roleId); //switch to another role
		$sidebar = '';
		//oh fun
		if($this->trueHas(array(Apps::TRANSACTIONS,Apps::DELETED))) {
				$sidebar .= '<li><div class="collapsible-header"><i class="material-icons">filter_drama</i>Transactions</div><div class="collapsible-body collection">';
				if($this->trueCan('index',Apps::TRANSACTIONS)) {
					$sidebar .= '<a class="collection-item" href="'.URL::action('TransactionsController@getIndex').'">List</a>';
				}
				if($this->trueCan('cash-in',Apps::TRANSACTIONS)) {
					$sidebar .= '<a class="collection-item" href="'.URL::action('TransactionsController@getCashIn').'">Cash In</a>';
				}
				if($this->trueCan('cash-out',Apps::TRANSACTIONS)) {
					$sidebar .= '<a class="collection-item" href="'.URL::action('TransactionsController@getCashOut').'">Cash Out</a>';
				}
				if($this->trueCan('adjust',Apps::TRANSACTIONS)) {
					$sidebar .= '<a class="collection-item" href="'.URL::action('TransactionsController@getAdjust').'">Adjust</a>';
				}
				if($this->trueCan('transfer',Apps::TRANSACTIONS)) {
					$sidebar .= '<a class="collection-item" href="'.URL::action('TransactionsController@getTransfer').'">Transfer</a>';
				}
				if($this->trueCan('return',Apps::TRANSACTIONS)) {
					$sidebar .= '<a class="collection-item" href="'.URL::action('TransactionsController@getReturn').'">Return</a>';
				}
				if($this->trueCan('return-supplier',Apps::TRANSACTIONS)) {
					$sidebar .= '<a class="collection-item" href="'.URL::action('TransactionsController@getReturnSupplier').'">Return Supplier</a>';
				}
				if($this->trueCan('index',Apps::DELETED)) {
					$sidebar .= '<a class="collection-item" href="'.URL::action('DeletedController@getIndex').'">Deleted List</a>';
				}
				$sidebar .= '</div></li>';
		}

		if($this->trueHas(array(Apps::CUSTOMERS,Apps::SUPPLIERS,Apps::WAREHOUSES,Apps::ACCOUNTS,Apps::VWAREHOUSES,Apps::RESELLERS))) {
			$sidebar .= '<li><div class="collapsible-header"><i class="material-icons">place</i>Addr Book</div><div class="collapsible-body collection">';
			if($this->trueHas(Apps::CUSTOMERS)) {
				$sidebar .= '<div class="collection-item">';
				$sidebar .= '<a class="left" href="'.URL::action('CustomersController@getIndex').'">Customers</a>';
				if($this->trueCan('create',Apps::CUSTOMERS)) {
					$sidebar .= '<a href="'.URL::action('CustomersController@getCreate').'" class="secondary-content right"><i class="material-icons">add_box</i></a>';
				}
				$sidebar .= '</div>';
			}
			if($this->trueHas(Apps::RESELLERS)) {
				$sidebar .= '<div class="collection-item">';
				$sidebar .= '<a class="left" href="'.URL::action('ResellersController@getIndex').'">Resellers</a>';
				if($this->trueCan('create',Apps::RESELLERS)) {
					$sidebar .= '<a href="'.URL::action('ResellersController@getCreate').'" class="secondary-content right"><i class="material-icons">add_box</i></a>';
				}
				$sidebar .= '</div>';
			}
			if($this->trueHas(Apps::SUPPLIERS)) {
				$sidebar .= '<div class="collection-item">';
				$sidebar .= '<a class="left" href="'.URL::action('SuppliersController@getIndex').'">Suppliers</a>';
				if($this->trueCan('create',Apps::SUPPLIERS)) {
					$sidebar .= '<a href="'.URL::action('SuppliersController@getCreate').'" class="secondary-content right"><i class="material-icons">add_box</i></a>';
				}
				$sidebar .= '</div>';
			}
			if($this->trueHas(Apps::WAREHOUSES)) {
				$sidebar .= '<div class="collection-item">';
				$sidebar .= '<a class="left" href="'.URL::action('WarehousesController@getIndex').'">Warehouses</a>';
				if($this->trueCan('create',Apps::WAREHOUSES) && $this->trueCan('index',Apps::WAREHOUSES)) {
					$sidebar .= '<a href="'.URL::action('WarehousesController@getCreate').'" class="secondary-content right"><i class="material-icons">add_box</i></a>';
				}
				$sidebar .= '</div>';
			}
			if($this->trueHas(Apps::BANKACCOUNTS)) {
				$sidebar .= '<div class="collection-item">';
				$sidebar .= '<a class="left" href="'.URL::action('BankAccountsController@getIndex').'">Accounts</a>';
				if($this->trueCan('create',Apps::BANKACCOUNTS)) {
					$sidebar .= '<a href="'.URL::action('BankAccountsController@getCreate').'" class="secondary-content right"><i class="material-icons">add_box</i></a>';
				}
				$sidebar .= '</div>';
			}
			if($this->trueHas(Apps::VWAREHOUSES)) {
				$sidebar .= '<div class="collection-item">';
				$sidebar .= '<a class="left" href="'.URL::action('VWarehousesController@getIndex').'">V. Warehouses</a>';
				if($this->trueCan('create',Apps::VWAREHOUSES)) {
					$sidebar .= '<a href="'.URL::action('VWarehousesController@getCreate').'" class="secondary-content right"><i class="material-icons">add_box</i></a>';
				}
				$sidebar .= '</div>';
			}
			if($this->trueHas(Apps::VACCOUNTS)) {
				$sidebar .= '<div class="collection-item">';
				$sidebar .= '<a class="left" href="'.URL::action('VAccountsController@getIndex').'">V. Accounts</a>';
				if($this->trueCan('create',Apps::VACCOUNTS)) {
					$sidebar .= '<a href="'.URL::action('VAccountsController@getCreate').'" class="secondary-content right"><i class="material-icons">add_box</i></a>';
				}
				$sidebar .= '</div>';
			}
			$sidebar .= '</div></li>';
		}

		if($this->trueHas(array(Apps::ITEMS,Apps::ASSET_LANCAR,Apps::ASSET_TETAP,Apps::TAGS))) {
			$sidebar .= '<li><div class="collapsible-header"><i class="material-icons">whatshot</i>Stuffs</div><div class="collapsible-body collection">';
			if($this->trueCan('index',Apps::ITEMS)) {
				$sidebar .= '<a class="collection-item" href="'.URL::action('ItemsController@getIndex').'">Items</a>';
			}
			if($this->trueCan('group',Apps::ITEMS)) {
				$sidebar .= '<a class="collection-item" href="'.URL::action('ItemsController@getGroup').'">Items Group</a>';
			}
			if($this->trueHas(Apps::ASSET_LANCAR)) {
				$sidebar .= '<div class="collection-item">';
				$sidebar .= '<a class="left" href="'.URL::action('AssetLancarController@getIndex').'">Asset Lancar</a>';
				if($this->trueCan('create',Apps::ASSET_LANCAR)) {
					$sidebar .= '<a href="'.URL::action('AssetLancarController@getCreate').'" class="secondary-content right"><i class="material-icons">add_box</i></a>';
				}
				$sidebar .= '</div>';
			}
			if($this->trueHas(Apps::ASSET_TETAP)) {
				$sidebar .= '<div class="collection-item">';
				$sidebar .= '<a class="left" href="'.URL::action('AssetTetapController@getIndex').'">Asset Tetap</a>';
				if($this->trueCan('create',Apps::ASSET_TETAP)) {
					$sidebar .= '<a href="'.URL::action('AssetTetapController@getCreate').'" class="secondary-content right"><i class="material-icons">add_box</i></a>';
				}
				$sidebar .= '</div>';
			}
			if($this->trueHas(Apps::VITEM)) {
				$sidebar .= '<div class="collection-item">';
				$sidebar .= '<a class="left" href="'.URL::action('VItemsController@getIndex').'">V. Items</a>';
				if($this->trueCan('create',Apps::VITEM)) {
					$sidebar .= '<a href="'.URL::action('VItemsController@getCreate').'" class="secondary-content right"><i class="material-icons">add_box</i></a>';
				}
				$sidebar .= '</div>';
			}
			if($this->trueHas(Apps::TAGS)) {
				$sidebar .= '<div class="collection-item">';
				$sidebar .= '<a class="left" href="'.URL::action('TagsController@getIndex').'">Tags</a>';
				if($this->trueCan('create',Apps::TAGS)) {
					$sidebar .= '<a href="'.URL::action('TagsController@getCreate').'" class="secondary-content right"><i class="material-icons">add_box</i></a>';
				}
				$sidebar .= '</div>';
			}
			if($this->trueCan('price',Apps::ITEMS)) {
				$sidebar .= '<div class="row">
					<div id="items-price" class="col-xs-12"><a href="'.URL::action('ItemsController@getPrice').'"><span class="icon-tab">&nbsp;</span>Pricing</a></div>
				</div>';
			}
			$sidebar .= '</div></li>';
		}

		if($this->trueHas(array(Apps::ACCOUNTS,Apps::OPERATION))) {
			$sidebar .= '<li><div class="collapsible-header"><i class="material-icons">payment</i>Journals</div><div class="collapsible-body collection">';
			if($this->trueHas(Apps::OPERATION)) {
				$sidebar .= '<div class="collection-item">';
				$sidebar .= '<a class="left" href="'.URL::action('OperationsController@getIndex').'">Operations</a>';
				if($this->trueCan('create',Apps::OPERATION)) {
					$sidebar .= '<a href="'.URL::action('OperationsController@getCreate').'" class="secondary-content right"><i class="material-icons">add_box</i></a>';
				}
				$sidebar .= '</div>';

				$sidebar .= '<div class="collection-item">';
				$sidebar .= '<a class="left" href="'.URL::action('OperationsController@getAccounts').'">Accounts</a>';
				if($this->trueCan('create-account',Apps::OPERATION)) {
					$sidebar .= '<a href="'.URL::action('OperationsController@getCreateAccount').'" class="secondary-content right"><i class="material-icons">add_box</i></a>';
				}
				$sidebar .= '</div>';
			}
			$sidebar .= '</div></li>';
		}

		if($this->trueHas(array(Apps::LOCATIONS,Apps::USERS))) {
			$sidebar .= '<li><div class="collapsible-header"><i class="material-icons">perm_identity</i>Users</div><div class="collapsible-body collection">';
			if($this->trueHas(Apps::USERS)) {
				$sidebar .= '<div class="collection-item">';
				$sidebar .= '<a class="left" href="'.URL::action('UsersController@getIndex').'">Users</a>';
				if($this->trueCan('create',Apps::USERS)) {
					$sidebar .= '<a href="'.URL::action('UsersController@getCreate').'" class="secondary-content right"><i class="material-icons">add_box</i></a>';
				}
				$sidebar .= '</div>';
			}
			if($this->trueHas(Apps::USERS)) {
				$sidebar .= '<div class="collection-item">';
				$sidebar .= '<a class="left" href="'.URL::action('UsersController@getRoles').'">Roles</a>';
				if($this->trueCan('create-role',Apps::USERS) && $this->trueCan('roles',Apps::USERS)) {
					$sidebar .= '<a href="'.URL::action('UsersController@getCreateRole').'" class="secondary-content right"><i class="material-icons">add_box</i></a>';
				}
				$sidebar .= '</div>';
			}
			if($this->trueHas(Apps::LOCATIONS)) {
				$sidebar .= '<div class="collection-item">';
				$sidebar .= '<a class="left" href="'.URL::action('LocationsController@getIndex').'">Locations</a>';
				if($this->trueCan('create',Apps::LOCATIONS)) {
					$sidebar .= '<a href="'.URL::action('LocationsController@getCreate').'" class="secondary-content right"><i class="material-icons">add_box</i></a>';
				}
				$sidebar .= '</div>';
			}
			if($this->trueCan('settings',Apps::LOCATIONS)) {
				$sidebar .= '<a class="collection-item" href="'.URL::action('LocationsController@getSettings').'">Loc. Settings</a>';
			}
			$sidebar .= '</div></li>';
		}

		if($this->trueHas(array(Apps::REPORTS))) {
				$sidebar .= '<li><div class="collapsible-header"><i class="material-icons">filter_drama</i>Reports</div><div class="collapsible-body collection">';
				if($this->trueCan('profit-loss',Apps::REPORTS)) {
					$sidebar .= '<a class="collection-item" href="'.URL::action('ReportsController@getProfitLoss').'">Profit Loss</a>';
				}
				if($this->trueCan('balance',Apps::REPORTS)) {
					$sidebar .= '<a class="collection-item" href="'.URL::action('ReportsController@getBalance').'">Balance</a>';
				}
				if($this->trueCan('cash',Apps::REPORTS)) {
					$sidebar .= '<a class="collection-item" href="'.URL::action('ReportsController@getCash').'">Nett Cash</a>';
				}
				$sidebar .= '</div></li>';
		}

		if($this->trueCan('index',Apps::SETTINGS)) {
			$sidebar .= '<li><div class="collapsible-header"><i class="material-icons">filter_drama</i>System</div><div class="collapsible-body collection">';
			$sidebar .= '<a class="collection-item" href="'.URL::action('SettingsController@getIndex').'">Loc. Settings</a>';
			$sidebar .= '</div></li>';
		}

		if($this->trueHas(array(Apps::PRODUKSI, Apps::SETORAN))) {
			$sidebar .= '<li><div class="collapsible-header"><i class="material-icons">build</i>Produksi</div><div class="collapsible-body collection">';

			$sidebar .= '<div class="collection-item">';
			$sidebar .= '<a class="left" href="'.URL::action('ProduksiController@getIndex').'">Produksi</a>';
			if($this->trueCan('create',Apps::PRODUKSI)) {
				$sidebar .= '<a href="'.URL::action('ProduksiController@getCreateProduksi').'" class="secondary-content right"><i class="material-icons">add_box</i></a>';
			}
			$sidebar .= '</div>';

			$sidebar .= '<div class="collection-item">';
			if($this->trueCan('index',Apps::SETORAN)) {
				$sidebar .= '<a href="'.URL::action('SetoranController@getIndex').'">Setoran</a>';
			}
			$sidebar .= '</div>';

			$sidebar .= '<div class="collection-item">';
			$sidebar .= '<a class="left" href="'.URL::action('ProduksiController@getJahitList').'">Jahit</a>';
			if($this->trueCan('create',Apps::PRODUKSI)) {
				$sidebar .= '<a href="'.URL::action('ProduksiController@getCreateJahit').'" class="secondary-content right"><i class="material-icons">add_box</i></a>';
			}
			$sidebar .= '</div>';

			$sidebar .= '<div class="collection-item">';
			$sidebar .= '<a class="left" href="'.URL::action('ProduksiController@getPotongList').'">Potong</a>';
			if($this->trueCan('create-account',Apps::PRODUKSI)) {
				$sidebar .= '<a href="'.URL::action('ProduksiController@getCreatePotong').'" class="secondary-content right"><i class="material-icons">add_box</i></a>';
			}
			$sidebar .= '</div>';

			$sidebar .= '</div></li>';
		}
		if($this->trueHas(array(Apps::BORONGAN))) {
			$sidebar .= '<li><div class="collapsible-header"><i class="material-icons">perm_identity</i>Borongan</div><div class="collapsible-body collection">';

			$sidebar .= '<div class="collection-item">';
			$sidebar .= '<a class="left" href="'.URL::action('BoronganController@getIndex').'">Borongan</a>';
			if($this->trueCan('create',Apps::BORONGAN)) {
				$sidebar .= '<a href="'.URL::action('BoronganController@getAdd').'" class="secondary-content right"><i class="material-icons">add_box</i></a>';
			}
			$sidebar .= '</div>';
			$sidebar .= '</div></li>';
		}
		if($this->trueHas(array(Apps::CONTRIBUTORS))) {
			$sidebar .= '<li class="collection-item"><a href="'.URL::action('ContributorsController@getIndex').'"><i class="material-icons">announcement</i>Contributors</a></li>';
		}

		return $sidebar;
	}
}
