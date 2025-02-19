<?php

namespace App\View\Components\Partial;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Spatie\Permission\Models\Permission as ModelsPermission;

class Permission extends Component
{
    /**
     * Create a new component instance.
     */

     public $data;
     public $userList, $settingList,$reportList, $boronganList, $setoranList, $produksiList, $contributorList, $operationList, $assetLancarList,$itemList, $vaccountList, $accountList, $transactionList, $customerList, $supplierList, $resellerList, $warehouseList, $vwarehouseList, $poList, $cnpoList,$karyawanList;
    

    public function __construct($data = [])
    {   
        $this->data = $data;
        $this->transactionList = $this->transactionPermissionList();
        $this->customerList = $this->customerPermissionList();
        $this->supplierList = $this->supplierPermissionList();
        $this->resellerList = $this->resellerPermissionList();
        $this->warehouseList = $this->warehousePermissionList(); 
        $this->vwarehouseList = $this->vwarehousePermissionList(); 
        $this->accountList = $this->accountPermissionList();
        $this->vaccountList = $this->vaccountPermissionList();
        $this->itemList = $this->itemPermissionList();
        $this->assetLancarList = $this->assetLancarPermissionList();
        $this->operationList = $this->operationPermissionList();
        $this->contributorList = $this->contributorPermissionList();
        $this->produksiList = $this->produksiPermissionList();
        $this->setoranList = $this->setoranPermissionList();
        $this->boronganList =  $this->boronganPermissionList();
        $this->reportList = $this->reportPermissionList();
        $this->settingList = $this->settingPermissionList();
        $this->userList = $this->userPermissionList();
        $this->poList = $this->poPermissionList();
        $this->cnpoList = $this->cnpoPermissionList();
        $this->karyawanList = $this->karyawanPermissionList();
        

    }

    

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $perm = $this->addPermissionList();

      

        // foreach($perm as $p){

        //     $permission = ModelsPermission::create(['name' => $p['name']]);
    
        // }

        $permissionsArray = [];

        if($this->data){

            $permissionsArray = $this->data->permissions->pluck('id','name')->toArray();

            

        }

    

        return view('components.partial.permission',compact('permissionsArray'));
    }

    private function poPermissionList(){
        $data = [
            ['name' => 'po list', 'label' => 'List'],
            ['name' => 'po create', 'label' => 'Create'],
            ['name' => 'po detail', 'label' => 'Detail'],
            ['name' => 'po delete', 'label' => 'Delete'],
            ['name' => 'po item', 'label' => 'Item'],
           
           
        ];

        return $data;
    }

    private function cnpoPermissionList(){
        $data = [
            ['name' => 'cnpo list', 'label' => 'List'],
            ['name' => 'cnpo detail', 'label' => 'Detail'],
            ['name' => 'cnpo update', 'label' => 'Update Quantity'],
            ['name' => 'cnpo kosong', 'label' => 'Update Kosong'],
            ['name' => 'cnpo delete', 'label' => 'Delete'],
           
           
        ];

        return $data;
    }

    private function transactionPermissionList(){
        $data = [
            ['name' => 'transactions.list', 'label' => 'List'],
            ['name' => 'transactions.detail', 'label' => 'Detail'],
            ['name' => 'transactions.buy', 'label' => 'New Buy'],
            ['name' => 'transactions.sell', 'label' => 'New Sell'],
            ['name' => 'transactions.move', 'label' => 'New Move'],
            ['name' => 'transactions.use', 'label' => 'Use Item'],
            ['name' => 'transactions.cashIn', 'label' => 'Cash In'],
            ['name' => 'transactions.cashOut', 'label' => 'Cash Out'],
            ['name' => 'transactions.adjust', 'label' => 'New Adjust'],
            ['name' => 'transactions.transfer', 'label' => 'Transfer between accounts'],
            ['name' => 'transactions.return', 'label' => 'Return'],
            ['name' => 'transactions.returnSuplier', 'label' => 'Return Suplier'],
            ['name' => 'transactions.deleteList', 'label' => 'Delete List'],
            ['name' => 'transactions.delete', 'label' => 'Delete Transaction'],
            ['name' => 'transactions.sellbatch', 'label' => 'Sell Batch'],
            ['name' => 'transactions.movebatch', 'label' => 'Move Batch'],
            
        ];

        return $data;
    }

    private function customerPermissionList(){
        $data = [
            ['name' => 'customer list', 'label' =>'List'],
            ['name' => 'customer create', 'label' =>'Create'],
            ['name' => 'customer edit', 'label' =>'Edit'],
            ['name' => 'customer transaction', 'label' =>'View Transaction'],
            ['name' => 'customer detail', 'label' =>'Detail'],
            ['name' => 'customer item', 'label' =>'View Item'],
            ['name' => 'customer stat', 'label' =>'View stat'],
            ['name' => 'customer search', 'label' =>'Search'],
            ['name' => 'customer delete', 'label' =>'Delete'],
            ['name' => 'customer restore', 'label' =>'Restore'],
        ];

        return $data;
    }

    private function supplierPermissionList(){
        $data = [
            ['name' => 'supplier list', 'label' =>'List'],
            ['name' => 'supplier create', 'label' =>'Create'],
            ['name' => 'supplier edit', 'label' =>'Edit'],
            ['name' => 'supplier transaction', 'label' =>'View Transaction'],
            ['name' => 'supplier detail', 'label' =>'Detail'],
            ['name' => 'supplier item', 'label' =>'View Item'],
            ['name' => 'supplier stat', 'label' =>'View stat'],
            ['name' => 'supplier search', 'label' =>'Search'],
            ['name' => 'supplier delete', 'label' =>'Delete'],
            ['name' => 'supplier restore', 'label' =>'Restore'],
        ];

        return $data;
    }

    private function resellerPermissionList(){
        $data = [
            ['name' => 'reseller list', 'label' =>'List'],
            ['name' => 'reseller create', 'label' =>'Create'],
            ['name' => 'reseller edit', 'label' =>'Edit'],
            ['name' => 'reseller transaction', 'label' =>'View Transaction'],
            ['name' => 'reseller detail', 'label' =>'Detail'],
            ['name' => 'reseller item', 'label' =>'View Item'],
            ['name' => 'reseller stat', 'label' =>'View stat'],
            ['name' => 'reseller search', 'label' =>'Search'],
            ['name' => 'reseller delete', 'label' =>'Delete'],
            ['name' => 'reseller restore', 'label' =>'Restore'],
        ];

        return $data;
    }

    private function warehousePermissionList(){
        $data = [
            ['name' => 'warehouse list', 'label' =>'List'],
            ['name' => 'warehouse create', 'label' =>'Create'],
            ['name' => 'warehouse edit', 'label' =>'Edit'],
            ['name' => 'warehouse transaction', 'label' =>'View Transaction'],
            ['name' => 'warehouse detail', 'label' =>'Detail'],
            ['name' => 'warehouse item', 'label' =>'View Item'],
            ['name' => 'warehouse stat', 'label' =>'View stat'],
            ['name' => 'warehouse search', 'label' =>'Search'],
            ['name' => 'warehouse delete', 'label' =>'Delete'],
            ['name' => 'warehouse restore', 'label' =>'Restore'],
        ];

        return $data;
    }

    private function vwarehousePermissionList(){
        $data = [
            ['name' => 'vwarehouse list', 'label' =>'List'],
            ['name' => 'vwarehouse create', 'label' =>'Create'],
            ['name' => 'vwarehouse edit', 'label' =>'Edit'],
            ['name' => 'vwarehouse transaction', 'label' =>'View Transaction'],
            ['name' => 'vwarehouse detail', 'label' =>'Detail'],
            ['name' => 'vwarehouse item', 'label' =>'View Item'],
            ['name' => 'vwarehouse stat', 'label' =>'View stat'],
            ['name' => 'vwarehouse search', 'label' =>'Search'],
            ['name' => 'vwarehouse delete', 'label' =>'Delete'],
            ['name' => 'vwarehouse restore', 'label' =>'Restore'],
        ];

        return $data;
    }

    private function accountPermissionList(){
        $data = [
            ['name' => 'account list', 'label' =>'List'],
            ['name' => 'account create', 'label' =>'Create'],
            ['name' => 'account edit', 'label' =>'Edit'],
            ['name' => 'account transaction', 'label' =>'View Transaction'],
            ['name' => 'account detail', 'label' =>'Detail'],
            ['name' => 'account item', 'label' =>'View Item'],
            ['name' => 'account stat', 'label' =>'View stat'],
            ['name' => 'account search', 'label' =>'Search'],
            ['name' => 'account delete', 'label' =>'Delete'],
            ['name' => 'account restore', 'label' =>'Restore'],
            ['name' => 'account hide balance', 'label' =>'Hide Balance'],
        ];

        return $data;
    }

    private function vaccountPermissionList(){
        $data = [
            ['name' => 'vaccount list', 'label' =>'List'],
            ['name' => 'vaccount create', 'label' =>'Create'],
            ['name' => 'vaccount edit', 'label' =>'Edit'],
            ['name' => 'vaccount transaction', 'label' =>'View Transaction'],
            ['name' => 'vaccount detail', 'label' =>'Detail'],
            ['name' => 'vaccount item', 'label' =>'View Item'],
            ['name' => 'vaccount stat', 'label' =>'View stat'],
            ['name' => 'vaccount search', 'label' =>'Search'],
            ['name' => 'vaccount delete', 'label' =>'Delete'],
            ['name' => 'vaccount restore', 'label' =>'Restore'],
        ];

        return $data;
    }

    private function addPermissionList(){
        $data = [
            ['name' => 'produksi delete potong', 'label' =>'List'],
            ['name' => 'produksi delete jahit', 'label' =>'Create'],
           
          
        ];

        return $data;

        
    }

    private function itemPermissionList(){
        $data = [
            ['name' => 'item list', 'label' => 'List'],
            ['name' => 'item create', 'label' => 'Create'],
            ['name' => 'item edit', 'label' => 'Edit'],
            ['name' => 'item transaction', 'label' => 'View Transaction'],
            ['name' => 'item detail', 'label' => 'Detail'],
            ['name' => 'item stat', 'label' => 'View stat'],
            ['name' => 'item group', 'label' => 'View Group'],
            ['name' => 'item search', 'label' => 'View stat'],
        ];

        return $data;
    }

    private function assetLancarPermissionList(){
        $data = [
            ['name' => 'asset lancar list', 'label' => 'List'],
            ['name' => 'asset lancar create', 'label' => 'Create'],
            ['name' => 'asset lancar edit', 'label' => 'Edit'],
            ['name' => 'asset lancar transaction', 'label' => 'View Transaction'],
            ['name' => 'asset lancar detail', 'label' => 'Detail'],
            ['name' => 'asset lancar stat', 'label' => 'View stat'],
            ['name' => 'asset lancar search', 'label' => 'View stat'],
        ];

        return $data;
    }
    

    private function operationPermissionList(){
        $data = [
            ['name' => 'operation list', 'label' =>'List'],
            ['name' => 'operation create', 'label' =>'Create'],
            ['name' => 'operation edit', 'label' =>'Edit'],
            ['name' => 'operation detail', 'label' =>'Detail'],
            ['name' => 'operation search', 'label' =>'Search'],
            ['name' => 'operation account', 'label' =>'Account List'],
            ['name' => 'operation account detail', 'label' =>'Account Detail'],
            ['name' => 'operation account create', 'label' =>'Account Create'],
            ['name' => 'operation account edit', 'label' =>'Account Edit'],
        ];

        return $data;

        
    }



    private function contributorPermissionList(){
        $data = [
            ['name' => 'contributor', 'label' =>'View Contributor'],
        ];

        return $data;

        
    }

    private function produksiPermissionList(){
        $data = [
            ['name' => 'produksi list', 'label' =>'List'],
            ['name' => 'produksi create', 'label' =>'Create'],
            ['name' => 'produksi edit', 'label' =>'Edit'],
            ['name' => 'produksi detail', 'label' =>'Detail'],
            ['name' => 'produksi search', 'label' =>'Search'],
            ['name' => 'produksi setor', 'label' =>'Setor'],
            ['name' => 'produksi jahit', 'label' =>'Jahit List'],
            ['name' => 'produksi jahit detail', 'label' =>'Jahit Detail'],
            ['name' => 'produksi jahit create', 'label' =>'Jahit Create'],
            ['name' => 'produksi jahit edit', 'label' =>'Jahit Edit'],
            ['name' => 'produksi delete jahit', 'label' =>'Jahit Delete'],
            ['name' => 'produksi potong', 'label' =>'Potong List'],
            ['name' => 'produksi potong detail', 'label' =>'Potong Detail'],
            ['name' => 'produksi potong create', 'label' =>'Potong Create'],
            ['name' => 'produksi potong edit', 'label' =>'Potong Edit'],
            ['name' => 'produksi delete potong', 'label' =>'Potong Delete'],
        ];

        return $data;

        
    }

    private function setoranPermissionList(){
        $data = [
            ['name' => 'setoran list', 'label' =>'List'],
            ['name' => 'setoran detail', 'label' =>'Detail'],
            ['name' => 'setoran edit', 'label' =>'Edit Warna/Deskripsi'],
            ['name' => 'setoran edit item', 'label' =>'Edit Item'],
            ['name' => 'setoran edit jahit', 'label' =>'Edit Jahit'],
            ['name' => 'setoran edit status', 'label' =>'Edit Status'],
            ['name' => 'setoran ke gudang', 'label' =>'Setor ke Gudang'],
            ['name' => 'setoran search', 'label' =>'Search'],
           
            
        ];

        return $data;

        
    }

    private function boronganPermissionList(){
        $data = [
            ['name' => 'borongan list', 'label' =>'List'],
            ['name' => 'borongan create', 'label' =>'Create'],
            ['name' => 'borongan load', 'label' =>'Load Borongan'],
            ['name' => 'borongan detail', 'label' =>'Detail'],
           
 
        ];

        return $data;

        
    }

    private function reportPermissionList(){
        $data = [
            ['name' => 'report nett cash', 'label' =>'View Nett Cash'],
            ['name' => 'report.compare', 'label' =>'Compare'],
            ['name' => 'report.itemsale', 'label' =>'Item Sale'],
            ['name' => 'cash-flow', 'label' =>'Cash Flow'],
 
        ];

        return $data;

        
    }

    private function settingPermissionList(){
        $data = [
            ['name' => 'setting edit', 'label' =>'Edit Setting'],
            ['name' => 'location', 'label' =>'Location'],
 
        ];

        return $data;

        
    }

    private function userPermissionList(){
        $data = [
            ['name' => 'user list', 'label' =>'List'],
            ['name' => 'user create', 'label' =>'Create'],
            ['name' => 'user edit', 'label' =>'Edit'],
            ['name' => 'user ban', 'label' =>'Banned'],
            ['name' => 'user role', 'label' =>'Role List'],
            ['name' => 'user create role', 'label' =>'Create Role'],
            ['name' => 'user edit role', 'label' =>'Edit Role'],
           
 
        ];

        return $data;

        
    }

    private function karyawanPermissionList(){
        $data = [
            ['name' => 'karyawan list', 'label' =>'List'],
            ['name' => 'karyawan create', 'label' =>'Create'],
            ['name' => 'karyawan edit', 'label' =>'Edit'],
            ['name' => 'karyawan detail', 'label' =>'Detail'],
            ['name' => 'karyawan delete', 'label' =>'Delete'],
            ['name' => 'cuti list', 'label' =>'Cuti List'],
            ['name' => 'cuti create', 'label' =>'Create Create'],
            ['name' => 'gajih list', 'label' =>'Gajih List'],
            ['name' => 'gajih create', 'label' =>'Gajih Create'],
           
           
 
        ];

        return $data;

        
    }



}
