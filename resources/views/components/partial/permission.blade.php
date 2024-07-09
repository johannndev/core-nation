<div>

    

     <div class="mb-8">

        <div>
            <p class="font-medium mb-2">Transaction</p>
        </div>
        <div>
            <div class="mb-4">
                <div class="flex items-center">
                    <input id="checkbox-all-transaction" type="checkbox" value="1" class="checkbox-role checkbox-all-transaction w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">Semua</label>
                </div>
            </div>

            <div class="grid md:grid-cols-5 gap-4">

                

                @foreach ($transactionList as $item)

                <div>
                    <div class="flex items-center">
                        <input @if($permissionsArray) @if(array_key_exists($item['name'],$permissionsArray)) checked @endif @endif id="checkbox-role" name="permissions[]" type="checkbox" value="{{$item['name']}}" class="checkbox-role checkbox-transaction w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">{{$item['label']}}</label>
                    </div>
                </div>
                    
                @endforeach

                
            </div>
        </div>

    </div>

    <div class="mb-8">

        <div>
            <p class="font-medium mb-2">Customer Addr Book</p>
        </div>
        <div>
            <div class="mb-4">
                <div class="flex items-center">
                    <input id="checkbox-all-customer" type="checkbox" value="1" class="checkbox-role checkbox-all-customer w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">Semua</label>
                </div>
            </div>
            
            <div class="grid md:grid-cols-5 gap-4">

                @foreach ($customerList as $item)

                <div>
                    <div class="flex items-center">
                        <input  @if($permissionsArray) @if(array_key_exists($item['name'],$permissionsArray)) checked @endif @endif  id="checkbox-role" name="permissions[]" type="checkbox" value="{{$item['name']}}" class="checkbox-role checkbox-customer w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">{{$item['label']}}</label>
                    </div>
                </div>
                    
                @endforeach

                
            </div>
        </div>

    </div>

    <div class="mb-8">

        <div>
            <p class="font-medium mb-2">Supplier Addr Book</p>
        </div>
        <div>
            <div class="mb-4">
                <div class="flex items-center">
                    <input id="checkbox-all-supplier" type="checkbox" value="1" class="checkbox-role checkbox-all-supplier w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">Semua</label>
                </div>
            </div>

            <div class="grid md:grid-cols-5 gap-4">

                @foreach ($supplierList as $item)

                <div>
                    <div class="flex items-center">
                        <input  @if($permissionsArray) @if(array_key_exists($item['name'],$permissionsArray)) checked @endif @endif   id="checkbox-role" name="permissions[]" type="checkbox" value="{{$item['name']}}" class="checkbox-role checkbox-supplier w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">{{$item['label']}}</label>
                    </div>
                </div>
                    
                @endforeach

                
            </div>
        </div>

    </div>

     

    

    <div class="mb-8">

        <div>
            <p class="font-medium mb-2">Reseller Addr Book</p>
        </div>
        <div>
            <div class="mb-4">
                <div class="flex items-center">
                    <input id="checkbox-all-reseller" type="checkbox" value="1" class="checkbox-role checkbox-all-reseller w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">Semua</label>
                </div>
            </div>
            <div class="grid md:grid-cols-5 gap-4">

                @foreach ($resellerList as $item)

                <div>
                    <div class="flex items-center">
                        <input @if($permissionsArray) @if(array_key_exists($item['name'],$permissionsArray)) checked @endif @endif  id="checkbox-role" name="permissions[]" type="checkbox" value="{{$item['name']}}" class="checkbox-role checkbox-reseller w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">{{$item['label']}}</label>
                    </div>
                </div>
                    
                @endforeach

                
            </div>
        </div>

    </div>

  
     


    <div class="mb-8">

        <div>
            <p class="font-medium mb-2">Warehouse Addr Book</p>
        </div>
        <div>
            <div class="mb-4">
                <div class="flex items-center">
                    <input id="checkbox-all-wh" type="checkbox" value="1" class="checkbox-role checkbox-all-wh w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">Semua</label>
                </div>
            </div>
            <div class="grid md:grid-cols-5 gap-4">

                @foreach ($warehouseList as $item)

                <div>
                    <div class="flex items-center">
                        <input @if($permissionsArray) @if(array_key_exists($item['name'],$permissionsArray)) checked @endif @endif  id="checkbox-role" name="permissions[]" type="checkbox" value="{{$item['name']}}" class="checkbox-role checkbox-wh w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">{{$item['label']}}</label>
                    </div>
                </div>
                    
                @endforeach

                
            </div>
        </div>

    </div>

    

    <div class="mb-8">

        <div>
            <p class="font-medium mb-2">V. Warehouse Addr Book</p>
        </div>
        <div>
            <div class="mb-4">
                <div class="flex items-center">
                    <input id="checkbox-all-vwh" type="checkbox" value="1" class="checkbox-role checkbox-all-vwh w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">Semua</label>
                </div>
            </div>
            <div class="grid md:grid-cols-5 gap-4">

                @foreach ($vwarehouseList as $item)

                <div>
                    <div class="flex items-center">
                        <input @if($permissionsArray) @if(array_key_exists($item['name'],$permissionsArray)) checked @endif @endif id="checkbox-role" name="permissions[]" type="checkbox" value="{{$item['name']}}" class="checkbox-role checkbox-vwh w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">{{$item['label']}}</label>
                    </div>
                </div>
                    
                @endforeach

                
            </div>
        </div>

    </div>

   

    <div class="mb-8">

        <div>
            <p class="font-medium mb-2">Account Addr Book</p>
        </div>
        <div>
            <div class="mb-4">
                <div class="flex items-center">
                    <input id="checkbox-all-account" type="checkbox" value="1" class="checkbox-role checkbox-all-account w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">Semua</label>
                </div>
            </div>
            <div class="grid md:grid-cols-5 gap-4">

                @foreach ($accountList as $item)

                <div>
                    <div class="flex items-center">
                        <input @if($permissionsArray) @if(array_key_exists($item['name'],$permissionsArray)) checked @endif @endif  id="checkbox-role" name="permissions[]" type="checkbox" value="{{$item['name']}}" class="checkbox-role checkbox-account w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">{{$item['label']}}</label>
                    </div>
                </div>
                    
                @endforeach

                
            </div>
        </div>

    </div>

    
    <div class="mb-8">

        <div>
            <p class="font-medium mb-2">V. Account Addr Book</p>
        </div>
        <div>
            <div class="mb-4">
                <div class="flex items-center">
                    <input id="checkbox-all-vaccount" type="checkbox" value="1" class="checkbox-role checkbox-all-vaccount w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">Semua</label>
                </div>
            </div>
            <div class="grid md:grid-cols-5 gap-4">

                @foreach ($vaccountList as $item)

                <div>
                    <div class="flex items-center">
                        <input @if($permissionsArray) @if(array_key_exists($item['name'],$permissionsArray)) checked @endif @endif  id="checkbox-role" name="permissions[]" type="checkbox" value="{{$item['name']}}" class="checkbox-role checkbox-vaccount w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">{{$item['label']}}</label>
                    </div>
                </div>
                    
                @endforeach

                
            </div>
        </div>

    </div>

     


    <div class="mb-8">

        <div>
            <p class="font-medium mb-2">Items</p>
        </div>
        <div>
            <div class="mb-4">
                <div class="flex items-center">
                    <input id="checkbox-all-item" type="checkbox" value="1" class="checkbox-role checkbox-all-item w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">Semua</label>
                </div>
            </div>
            <div class="grid md:grid-cols-5 gap-4">

                @foreach ($itemList as $item)

                <div>
                    <div class="flex items-center">
                        <input @if($permissionsArray) @if(array_key_exists($item['name'],$permissionsArray)) checked @endif @endif  id="checkbox-role" name="permissions[]" type="checkbox" value="{{$item['name']}}" class="checkbox-role checkbox-item w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">{{$item['label']}}</label>
                    </div>
                </div>
                    
                @endforeach

                
            </div>
        </div>

    </div>

    

    <div class="mb-8">

        <div>
            <p class="font-medium mb-2">Asset Lancar</p>
        </div>
        <div>
            <div class="mb-4">
                <div class="flex items-center">
                    <input id="checkbox-all-al" type="checkbox" value="1" class="checkbox-role checkbox-all-al w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">Semua</label>
                </div>
            </div>
            <div class="grid md:grid-cols-5 gap-4">

                @foreach ($assetLancarList as $item)

                <div>
                    <div class="flex items-center">
                        <input @if($permissionsArray) @if(array_key_exists($item['name'],$permissionsArray)) checked @endif @endif  id="checkbox-role" name="permissions[]" type="checkbox" value="{{$item['name']}}" class="checkbox-role checkbox-al w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">{{$item['label']}}</label>
                    </div>
                </div>
                    
                @endforeach

                
            </div>
        </div>

    </div>

    

   

    <div class="mb-8">

        <div>
            <p class="font-medium mb-2">Operation</p>
        </div>
        <div>
            <div class="mb-4">
                <div class="flex items-center">
                    <input id="checkbox-all-op" type="checkbox" value="1" class="checkbox-role checkbox-all-op w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">Semua</label>
                </div>
            </div>
            <div class="grid md:grid-cols-5 gap-4">

                @foreach ($operationList as $item)

                <div>
                    <div class="flex items-center">
                        <input @if($permissionsArray) @if(array_key_exists($item['name'],$permissionsArray)) checked @endif @endif  id="checkbox-role" name="permissions[]" type="checkbox" value="{{$item['name']}}" class="checkbox-role checkbox-op  w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">{{$item['label']}}</label>
                    </div>
                </div>
                    
                @endforeach

                
            </div>
        </div>

    </div>

  

     <div class="mb-8">

        <div>
            <p class="font-medium mb-2">Contributor</p>
        </div>
        <div>
            <div class="grid md:grid-cols-5 gap-4">

                @foreach ($contributorList as $item)

                <div>
                    <div class="flex items-center">
                        <input @if($permissionsArray) @if(array_key_exists($item['name'],$permissionsArray)) checked @endif @endif   id="checkbox-role" name="permissions[]" type="checkbox" value="{{$item['name']}}" class="checkbox-role w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">{{$item['label']}}</label>
                    </div>
                </div>
                    
                @endforeach

                
            </div>
        </div>

    </div>

     

    <div class="mb-8">

        <div>
            <p class="font-medium mb-2">Produksi</p>
        </div>
        <div>
            <div class="mb-4">
                <div class="flex items-center">
                    <input id="checkbox-all-prod" type="checkbox" value="1" class="checkbox-role checkbox-all-prod w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">Semua</label>
                </div>
            </div>
            <div class="grid md:grid-cols-5 gap-4">

                @foreach ($produksiList as $item)

                <div>
                    <div class="flex items-center">
                        <input @if($permissionsArray) @if(array_key_exists($item['name'],$permissionsArray)) checked @endif @endif   id="checkbox-role" name="permissions[]" type="checkbox" value="{{$item['name']}}" class="checkbox-role checkbox-prod w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">{{$item['label']}}</label>
                    </div>
                </div>
                    
                @endforeach

                
            </div>
        </div>

    </div> 

    <div class="mb-8">

        <div>
            <p class="font-medium mb-2">Setoran</p>
        </div>
        <div>
            <div class="mb-4">
                <div class="flex items-center">
                    <input id="checkbox-all-setoran" type="checkbox" value="1" class="checkbox-role checkbox-all-setoran w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">Semua</label>
                </div>
            </div>
            <div class="grid md:grid-cols-5 gap-4">

                @foreach ($setoranList as $item)

                <div>
                    <div class="flex items-center">
                        <input @if($permissionsArray) @if(array_key_exists($item['name'],$permissionsArray)) checked @endif @endif   id="checkbox-role" name="permissions[]" type="checkbox" value="{{$item['name']}}" class="checkbox-role checkbox-setoran w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">{{$item['label']}}</label>
                    </div>
                </div>
                    
                @endforeach

                
            </div>
        </div>

    </div> 

    <div class="mb-8">

        <div>
            <p class="font-medium mb-2">Borongan</p>
        </div>
        <div>
            <div class="mb-4">
                <div class="flex items-center">
                    <input id="checkbox-all-borongan" type="checkbox" value="1" class="checkbox-role checkbox-all-borongan w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">Semua</label>
                </div>
            </div>
            <div class="grid md:grid-cols-5 gap-4">

                @foreach ($boronganList as $item)

                <div>
                    <div class="flex items-center">
                        <input @if($permissionsArray) @if(array_key_exists($item['name'],$permissionsArray)) checked @endif @endif   id="checkbox-role" name="permissions[]" type="checkbox" value="{{$item['name']}}" class="checkbox-role  checkbox-borongan w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">{{$item['label']}}</label>
                    </div>
                </div>
                    
                @endforeach

                
            </div>
        </div>

    </div> 

    <div class="mb-8">

        <div>
            <p class="font-medium mb-2">Report</p>
        </div>
        <div>
            <div class="grid md:grid-cols-5 gap-4">

                @foreach ($reportList as $item)

                <div>
                    <div class="flex items-center">
                        <input @if($permissionsArray) @if(array_key_exists($item['name'],$permissionsArray)) checked @endif @endif   id="checkbox-role" name="permissions[]" type="checkbox" value="{{$item['name']}}" class="checkbox-role w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">{{$item['label']}}</label>
                    </div>
                </div>
                    
                @endforeach

                
            </div>
        </div>

    </div>
    
    <div class="mb-8">

        <div>
            <p class="font-medium mb-2">Setting</p>
        </div>
        <div>
            <div class="grid md:grid-cols-5 gap-4">

                @foreach ($settingList as $item)

                <div>
                    <div class="flex items-center">
                        <input @if($permissionsArray) @if(array_key_exists($item['name'],$permissionsArray)) checked @endif @endif   id="checkbox-role" name="permissions[]" type="checkbox" value="{{$item['name']}}" class="checkbox-role w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">{{$item['label']}}</label>
                    </div>
                </div>
                    
                @endforeach

                
            </div>
        </div>

    </div>

    <div class="mb-8">

        <div>
            <p class="font-medium mb-2">User</p>
        </div>
        <div>
            <div class="mb-4">
                <div class="flex items-center">
                    <input id="checkbox-all-user" type="checkbox" value="1" class="checkbox-role checkbox-all-user w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">Semua</label>
                </div>
            </div>
            <div class="grid md:grid-cols-5 gap-4">

                @foreach ($userList as $item)

                <div>
                    <div class="flex items-center">
                        <input @if($permissionsArray) @if(array_key_exists($item['name'],$permissionsArray)) checked @endif @endif   id="checkbox-role" name="permissions[]" type="checkbox" value="{{$item['name']}}" class="checkbox-role checkbox-user w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-role" class="ms-2 text-sm text-gray-900 dark:text-gray-300">{{$item['label']}}</label>
                    </div>
                </div>
                    
                @endforeach

                
            </div>
        </div>

    </div>

    @push('jsBody')

    <script>

        var allChecked = 0;
        var allcek = 0;

        var allTransactionChecked = 0;
        var allTransaction = 0;
        var allCustomerChecked = 0;
        var allCustomer = 0;
        var allSupplierChecked = 0;
        var allSupplier = 0;
        var allResellerChecked = 0;
        var allReseller = 0;
        var allWhChecked = 0;
        var allWh = 0;
        var allVWhChecked = 0;
        var allVWh = 0;
        var allAccountChecked = 0;
        var allAccount = 0;
        var allVAccountChecked = 0;
        var allVAccount = 0;
        var allItemChecked = 0;
        var allItem = 0;
        var allAlChecked = 0;
        var allAl = 0;
        var allOpChecked = 0;
        var allOp = 0;
        var allProdChecked = 0;
        var allProd = 0;
        var allSetoranChecked = 0;
        var allSetoran = 0;
        var allBoronganChecked = 0;
        var allBorongan = 0;
        var allUserChecked = 0;
        var allUser = 0;

        function cekChecked(){
            allChecked = $('.checkbox-role').not(':checked').length;
            allcek = $('.checkbox-role').length;

            if(allChecked == 0){
                $('#checkbox-all').prop('indeterminate', false);
                $('#checkbox-all').prop('checked', true);

            }else if(allChecked < allcek && allChecked > 0){
                $('#checkbox-all').prop('indeterminate', true);
            }else{
                $('#checkbox-all').prop('indeterminate', false);
                $('#checkbox-all').prop('checked', false);

            }

        } 

        function cekTransaction(){
            allTransactionChecked = $('.checkbox-transaction').not(':checked').length;
            allTransaction = $('.checkbox-transaction').length;

           

            if(allTransactionChecked == 0){
            $('.checkbox-all-transaction').prop('indeterminate', false);
            $('.checkbox-all-transaction').prop('checked', true);
            

            }else if(allTransactionChecked < allTransaction && allTransactionChecked > 0){
                $('.checkbox-all-transaction').prop('indeterminate', true);
            }else{
                $('.checkbox-all-transaction').prop('indeterminate', false);
                $('.checkbox-all-transaction').prop('checked', false);

            }

        } 

        function cekCustomer(){
            allCustomerChecked = $('.checkbox-customer').not(':checked').length;
            allCustomer = $('.checkbox-customer').length;

            if(allCustomerChecked == 0){
                $('.checkbox-all-customer').prop('indeterminate', false);
                $('.checkbox-all-customer').prop('checked', true);

            }else if(allCustomerChecked < allCustomer && allCustomerChecked > 0){
                $('.checkbox-all-customer').prop('indeterminate', true);
            }else{
                $('.checkbox-all-customer').prop('indeterminate', false);
                $('.checkbox-all-customer').prop('checked', false);

            }

        }

        function cekSupplier(){
            allSupplierChecked = $('.checkbox-supplier').not(':checked').length;
            allSupplier = $('.checkbox-supplier').length;

            if(allSupplierChecked == 0){
                $('.checkbox-all-supplier').prop('indeterminate', false);
                $('.checkbox-all-supplier').prop('checked', true);

            }else if(allSupplierChecked < allSupplier && allSupplierChecked > 0){
                $('.checkbox-all-supplier').prop('indeterminate', true);
            }else{
                $('.checkbox-all-supplier').prop('indeterminate', false);
                $('.checkbox-all-supplier').prop('checked', false);

            }

        }

        function cekReseller(){
            allResellerChecked = $('.checkbox-reseller').not(':checked').length;
            allSupplier = $('.checkbox-reseller').length;

            if(allResellerChecked == 0){
                $('.checkbox-all-reseller').prop('indeterminate', false);
                $('.checkbox-all-reseller').prop('checked', true);

            }else if(allResellerChecked < allSupplier && allResellerChecked > 0){
                $('.checkbox-all-reseller').prop('indeterminate', true);
            }else{
                $('.checkbox-all-reseller').prop('indeterminate', false);
                $('.checkbox-all-reseller').prop('checked', false);

            }

        }

        function cekWh(){
            allWhChecked = $('.checkbox-wh').not(':checked').length;
            allWh = $('.checkbox-wh').length;

            if(allWhChecked == 0){
                $('.checkbox-all-wh').prop('indeterminate', false);
                $('.checkbox-all-wh').prop('checked', true);

            }else if(allWhChecked < allWh && allWhChecked > 0){
                $('.checkbox-all-wh').prop('indeterminate', true);
            }else{
                $('.checkbox-all-wh').prop('indeterminate', false);
                $('.checkbox-all-wh').prop('checked', false);

            }

        }

        function cekVWh(){
            allVWhChecked = $('.checkbox-vwh').not(':checked').length;
            allVWh = $('.checkbox-vwh').length;

            if(allVWhChecked == 0){
                $('.checkbox-all-vwh').prop('indeterminate', false);
                $('.checkbox-all-vwh').prop('checked', true);

            }else if(allVWhChecked < allVWh && allVWhChecked > 0){
                $('.checkbox-all-vwh').prop('indeterminate', true);
            }else{
                $('.checkbox-all-vwh').prop('indeterminate', false);
                $('.checkbox-all-vwh').prop('checked', false);

            }

        }

        function cekAccount(){
            allAccountChecked = $('.checkbox-account').not(':checked').length;
            allAccount = $('.checkbox-account').length;

            if(allAccountChecked == 0){
                $('.checkbox-all-account').prop('indeterminate', false);
                $('.checkbox-all-account').prop('checked', true);

            }else if(allAccountChecked < allAccount && allAccountChecked > 0){
                $('.checkbox-all-account').prop('indeterminate', true);
            }else{
                $('.checkbox-all-account').prop('indeterminate', false);
                $('.checkbox-all-account').prop('checked', false);

            }

        }

        function cekVAccount(){
            allVAccountChecked = $('.checkbox-vaccount').not(':checked').length;
            allVAccount = $('.checkbox-vaccount').length;

            if(allVAccountChecked == 0){
                $('.checkbox-all-vaccount').prop('indeterminate', false);
                $('.checkbox-all-vaccount').prop('checked', true);

            }else if(allVAccountChecked < allVAccount && allVAccountChecked > 0){
                $('.checkbox-all-vaccount').prop('indeterminate', true);
            }else{
                $('.checkbox-all-vaccount').prop('indeterminate', false);
                $('.checkbox-all-vaccount').prop('checked', false);

            }

        }

        function cekItem(){
            allItemChecked = $('.checkbox-item').not(':checked').length;
            allItem = $('.checkbox-item').length;

            if(allItemChecked == 0){
                $('.checkbox-all-item').prop('indeterminate', false);
                $('.checkbox-all-item').prop('checked', true);

            }else if(allItemChecked < allItem && allItemChecked > 0){
                $('.checkbox-all-item').prop('indeterminate', true);
            }else{
                $('.checkbox-all-item').prop('indeterminate', false);
                $('.checkbox-all-item').prop('checked', false);

            }

        }

        function cekAl(){
            allAlChecked = $('.checkbox-al').not(':checked').length;
            allAl = $('.checkbox-al').length;

            if(allAlChecked == 0){
                $('.checkbox-all-al').prop('indeterminate', false);
                $('.checkbox-all-al').prop('checked', true);

            }else if(allAlChecked < allAl && allAlChecked > 0){
                $('.checkbox-all-al').prop('indeterminate', true);
            }else{
                $('.checkbox-all-al').prop('indeterminate', false);
                $('.checkbox-all-al').prop('checked', false);

            }

        }

        function cekOp(){
            allAlChecked = $('.checkbox-op').not(':checked').length;
            allAl = $('.checkbox-op').length;

            if(allAlChecked == 0){
                $('.checkbox-all-op').prop('indeterminate', false);
                $('.checkbox-all-op').prop('checked', true);

            }else if(allAlChecked < allAl && allAlChecked > 0){
                $('.checkbox-all-op').prop('indeterminate', true);
            }else{
                $('.checkbox-all-op').prop('indeterminate', false);
                $('.checkbox-all-op').prop('checked', false);

            }

        }

        function cekProd(){
            allProdChecked = $('.checkbox-prod').not(':checked').length;
            allProd = $('.checkbox-prod').length;

            if(allProdChecked == 0){
                $('.checkbox-all-prod').prop('indeterminate', false);
                $('.checkbox-all-prod').prop('checked', true);

            }else if(allProdChecked < allProd && allProdChecked > 0){
                $('.checkbox-all-prod').prop('indeterminate', true);
            }else{
                $('.checkbox-all-prod').prop('indeterminate', false);
                $('.checkbox-all-prod').prop('checked', false);

            }

        }

        function cekSetoran(){
            allSetoranChecked = $('.checkbox-setoran').not(':checked').length;
            allSetoran = $('.checkbox-setoran').length;

            if(allSetoranChecked == 0){
                $('.checkbox-all-setoran').prop('indeterminate', false);
                $('.checkbox-all-setoran').prop('checked', true);

            }else if(allSetoranChecked < allSetoran && allSetoranChecked > 0){
                $('.checkbox-all-setoran').prop('indeterminate', true);
            }else{
                $('.checkbox-all-setoran').prop('indeterminate', false);
                $('.checkbox-all-setoran').prop('checked', false);

            }

        }

        function cekBorongan(){
            allBoronganChecked = $('.checkbox-borongan').not(':checked').length;
            allBorongan = $('.checkbox-borongan').length;

            if(allBoronganChecked == 0){
                $('.checkbox-all-borongan').prop('indeterminate', false);
                $('.checkbox-all-borongan').prop('checked', true);

            }else if(allBoronganChecked < allBorongan && allBoronganChecked > 0){
                $('.checkbox-all-borongan').prop('indeterminate', true);
            }else{
                $('.checkbox-all-borongan').prop('indeterminate', false);
                $('.checkbox-all-borongan').prop('checked', false);

            }

        }

        function cekUser(){
            allUserChecked = $('.checkbox-user').not(':checked').length;
            allUser = $('.checkbox-user').length;

            if(allUserChecked == 0){
                $('.checkbox-all-user').prop('indeterminate', false);
                $('.checkbox-all-user').prop('checked', true);

            }else if(allUserChecked < allUser && allUserChecked > 0){
                $('.checkbox-all-user').prop('indeterminate', true);
            }else{
                $('.checkbox-all-user').prop('indeterminate', false);
                $('.checkbox-all-user').prop('checked', false);

            }

        }

        $(function(){

            cekChecked()
            cekTransaction();
            cekCustomer();
            cekSupplier();
            cekReseller();
            cekWh();
            cekVWh();
            cekAccount();
            cekVAccount();
            cekItem();
            cekAl();
            cekOp();
            cekProd();
            cekSetoran();
            cekBorongan();
            cekUser();

            $(".checkbox-role").click(function(){cekChecked();})
            
            $(".checkbox-role.checkbox-transaction").click(function(){cekTransaction(); console.log('halo'); })
            $(".checkbox-role.checkbox-customer").click(function(){cekCustomer(); })
            $(".checkbox-role.checkbox-supplier").click(function(){cekSupplier(); })
            $(".checkbox-role.checkbox-reseller").click(function(){cekReseller(); })
            $(".checkbox-role.checkbox-wh").click(function(){cekWh(); })
            $(".checkbox-role.checkbox-vwh").click(function(){cekVWh(); })
            $(".checkbox-role.checkbox-account").click(function(){cekAccount(); })
            $(".checkbox-role.checkbox-vaccount").click(function(){cekVAccount(); })
            $(".checkbox-role.checkbox-item").click(function(){cekItem(); })
            $(".checkbox-role.checkbox-al").click(function(){cekAl(); })
            $(".checkbox-role.checkbox-op").click(function(){cekOp(); })
            $(".checkbox-role.checkbox-prod").click(function(){cekProd(); })
            $(".checkbox-role.checkbox-setoran").click(function(){cekSetoran(); })
            $(".checkbox-role.checkbox-borongan").click(function(){cekBorongan(); })
            $(".checkbox-role.checkbox-user").click(function(){cekUser(); })
            
            

            

            $("#checkbox-all").click(function(){
                if((this).checked == true){
                    $('.checkbox-role').prop('checked', true);
                }else{
                    $('.checkbox-role').prop('checked', false);
                }

                
            })

            $("#checkbox-all-transaction").click(function(){
                if((this).checked == true){
                    $('.checkbox-role.checkbox-transaction').prop('checked', true);
                }else{
                    $('.checkbox-role.checkbox-transaction').prop('checked', false);
                }

                cekChecked();
            })

            $("#checkbox-all-customer").click(function(){
                if((this).checked == true){
                    $('.checkbox-role.checkbox-customer').prop('checked', true);
                }else{
                    $('.checkbox-role.checkbox-customer').prop('checked', false);
                }

                cekChecked();
            })

            $("#checkbox-all-supplier").click(function(){
                if((this).checked == true){
                    $('.checkbox-role.checkbox-supplier').prop('checked', true);
                }else{
                    $('.checkbox-role.checkbox-supplier').prop('checked', false);
                }

                cekChecked();
            })

            $("#checkbox-all-reseller").click(function(){
                if((this).checked == true){
                    $('.checkbox-role.checkbox-reseller').prop('checked', true);
                }else{
                    $('.checkbox-role.checkbox-reseller').prop('checked', false);
                }

                cekChecked();
            })

            $("#checkbox-all-wh").click(function(){
                if((this).checked == true){
                    $('.checkbox-role.checkbox-wh').prop('checked', true);
                }else{
                    $('.checkbox-role.checkbox-wh').prop('checked', false);
                }

                cekChecked();
            })

            $("#checkbox-all-vwh").click(function(){
                if((this).checked == true){
                    $('.checkbox-role.checkbox-vwh').prop('checked', true);
                }else{
                    $('.checkbox-role.checkbox-vwh').prop('checked', false);
                }

                cekChecked();
            })

            $("#checkbox-all-account").click(function(){
                if((this).checked == true){
                    $('.checkbox-role.checkbox-account').prop('checked', true);
                }else{
                    $('.checkbox-role.checkbox-account').prop('checked', false);
                }

                cekChecked();
            })

            $("#checkbox-all-vaccount").click(function(){
                if((this).checked == true){
                    $('.checkbox-role.checkbox-vaccount').prop('checked', true);
                }else{
                    $('.checkbox-role.checkbox-vaccount').prop('checked', false);
                }

                cekChecked();
            })

            $("#checkbox-all-item").click(function(){
                if((this).checked == true){
                    $('.checkbox-role.checkbox-item').prop('checked', true);
                }else{
                    $('.checkbox-role.checkbox-item').prop('checked', false);
                }

                cekChecked();
            })

            $("#checkbox-all-al").click(function(){
                if((this).checked == true){
                    $('.checkbox-role.checkbox-al').prop('checked', true);
                }else{
                    $('.checkbox-role.checkbox-al').prop('checked', false);
                }

                cekChecked();
            })

            $("#checkbox-all-op").click(function(){
                if((this).checked == true){
                    $('.checkbox-role.checkbox-op').prop('checked', true);
                }else{
                    $('.checkbox-role.checkbox-op').prop('checked', false);
                }

                cekChecked();
            })

            $("#checkbox-all-prod").click(function(){
                if((this).checked == true){
                    $('.checkbox-role.checkbox-prod').prop('checked', true);
                }else{
                    $('.checkbox-role.checkbox-prod').prop('checked', false);
                }

                cekChecked();
            })

            $("#checkbox-all-setoran").click(function(){
                if((this).checked == true){
                    $('.checkbox-role.checkbox-setoran').prop('checked', true);
                }else{
                    $('.checkbox-role.checkbox-setoran').prop('checked', false);
                }

                cekChecked();
            })

            $("#checkbox-all-borongan").click(function(){
                if((this).checked == true){
                    $('.checkbox-role.checkbox-borongan').prop('checked', true);
                }else{
                    $('.checkbox-role.checkbox-borongan').prop('checked', false);
                }

                cekChecked();
            })
            $("#checkbox-all-user").click(function(){
                if((this).checked == true){
                    $('.checkbox-role.checkbox-user').prop('checked', true);
                }else{
                    $('.checkbox-role.checkbox-user').prop('checked', false);
                }

                cekChecked();
            })



        })

    </script>

@endpush

</div>