<aside
    class="print:hidden print:w-0 fixed top-0 left-0 z-40 w-64 h-screen pt-14 transition-transform -translate-x-full bg-white border-r border-gray-200 md:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
    aria-label="Sidenav"
    id="drawer-navigation"
>
    <div class="overflow-y-auto py-5 px-3 h-full bg-white dark:bg-gray-800 mt-10 md:mt-0">

    <ul class="space-y-2">
        <li>
        <a
            href="{{route('dashboard')}}"
            class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
        >
            <svg
            aria-hidden="true"
            class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
            >
            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
            </svg>
            <span class="ml-3">Dashboard</span>
        </a>
        </li>

        @if(auth()->user()->can('transactions.list') || auth()->user()->can('transactions.cashIn') || auth()->user()->can('transactions.cashOut') || auth()->user()->can('transactions.adjust') || auth()->user()->can('transactions.transfer') || auth()->user()->can('transactions.return') || auth()->user()->can('transactions.returnSuplier') || auth()->user()->can('transactions.deleteList') || auth()->user()->can('cnpo list') )
        <li>

           
            <button
                type="button"
                class="flex items-center p-2 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                aria-controls="dropdown-pages"
                data-collapse-toggle="dropdown-pages"
                >
                <svg
                aria-hidden="true"
                class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
                >
                <path
                    fill-rule="evenodd"
                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                    clip-rule="evenodd"
                ></path>
                </svg>
                <span class="flex-1 ml-3 text-left whitespace-nowrap"
                >Transactions</span
                >
                <svg
                aria-hidden="true"
                class="w-6 h-6"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
                >
                <path
                    fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                ></path>
                </svg>
            </button>
            <ul id="dropdown-pages" class="hidden py-2 space-y-2">

                @can('transactions.list')
                <li>
                <a
                    href="{{route('transaction.index')}}"
                    class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                    >List</a
                >
                </li>
                @endcan

                @can('transactions.cashIn')
                <li>
                <a
                    href="{{route('transaction.cashIn')}}"
                    class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                    >Cash In</a
                >
                </li>
                @endcan

                @can('transactions.cashOut')
                <li>
                <a
                    href="{{route('transaction.cashOut')}}"
                    class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                    >Cash Out</a
                >
                </li>
                @endcan

                @can('transactions.adjust')
                <li>
                    <a
                        href="{{route('transaction.adjust')}}"
                        class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        >Adjust</a
                    >
                </li>
                @endcan

                @can('transactions.transfer')
                <li>
                    <a
                        href="{{route('transaction.transfer')}}"
                        class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        >Transfer</a
                    >
                </li>
                @endcan

                @can('transactions.return')
                <li>
                    <a
                        href="{{route('transaction.return')}}"
                        class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        >Return</a
                    >
                </li>
                @endcan

                @can('transactions.returnSupplier')
                <li>
                    <a
                        href="{{route('transaction.returnSupplier')}}"
                        class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        >Return Supplier</a
                    >
                </li>
                @endcan

                <li>
                    <a
                        href="{{route('jubelio.return.index')}}"
                        class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        >Return Jubelio</a
                    >
                </li>

                @can('cnpo list')
                <li>
                    <a
                        href="{{route('transaction.Poindex')}}"
                        class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        >PO List</a
                    >
                </li>
                @endcan

                @can('transactions.deleteList')
                <li>
                    <a
                        href="{{route('transaction.delete')}}"
                        class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        >Deleted List</a
                    >
                </li>
                @endcan

              
                <li>
                    <a
                        href="{{route('export.sellItem')}}"
                        class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        >Export Sell</a
                    >
                </li>
               
                
            </ul>
        </li>

        @endif

        @if(auth()->user()->can('customer list') || auth()->user()->can('supplier list') || auth()->user()->can('reseller list') || auth()->user()->can('warehouse list') || auth()->user()->can('vwarehouse list') || auth()->user()->can('account list') || auth()->user()->can('vaccount list')   )

        <li>
            <button
                type="button"
                class="flex items-center p-2 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                aria-controls="dropdown-addrbook"
                data-collapse-toggle="dropdown-addrbook"
            >
                <svg
                aria-hidden="true"
                class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
                >
                <path
                    fill-rule="evenodd"
                    d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                    clip-rule="evenodd"
                ></path>
                </svg>
                <span class="flex-1 ml-3 text-left whitespace-nowrap"
                >Addr Book</span
                >
                <svg
                aria-hidden="true"
                class="w-6 h-6"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
                >
                <path
                    fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                ></path>
                </svg>
            </button>
            <ul id="dropdown-addrbook" class="hidden py-2 space-y-2">

                @can('customer list')
                <li>
        
                    <div class="flex justify-between">
        
                        <a href="{{route('customer.index')}}" class=" flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Customer</a>
        
                        <a href="{{route('customer.create')}}" class="flex items-center py-2 px-4  w-auto text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                                
                                
                        </a>
                        
                    </div>
                
                </li>
                @endcan

                @can('reseller list')

                <li>
        
                    <div class="flex justify-between">
        
                        <a href="{{route('reseller.index')}}" class=" flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Reseller</a>
        
                        <a href="{{route('reseller.create')}}" class="flex items-center py-2 px-4  w-auto text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                                
                                
                        </a>
                        
                    </div>
                
                </li>

                @endcan

                @can('supplier list')

                <li>
        
                    <div class="flex justify-between">
        
                        <a href="{{route('supplier.index')}}" class=" flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Supplier</a>
        
                        <a href="{{route('supplier.create')}}" class="flex items-center py-2 px-4  w-auto text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                                
                                
                        </a>
                        
                    </div>
                
                </li>
                @endcan

                @can('warehouse list')

                <li>
        
                    <div class="flex justify-between">
        
                        <a href="{{route('warehouse.index')}}" class=" flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Warehouse</a>
        
                        <a href="{{route('warehouse.create')}}" class="flex items-center py-2 px-4  w-auto text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                                
                                
                        </a>
                        
                    </div>
                
                </li>

                @endcan

                @can('vwarehouse list')

                <li>
        
                    <div class="flex justify-between">
        
                        <a href="{{route('vwarehouse.index')}}" class=" flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">V. Warehouse</a>
        
                        <a href="{{route('vwarehouse.create')}}" class="flex items-center py-2 px-4  w-auto text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                                
                                
                        </a>
                        
                    </div>
                
                </li>

                @endcan

                @can('account list')

                <li>
        
                    <div class="flex justify-between">
        
                        <a href="{{route('account.index')}}" class=" flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Account</a>
        
                        <a href="{{route('account.create')}}" class="flex items-center py-2 px-4  w-auto text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                                
                                
                        </a>
                        
                    </div>
                
                </li>

                @endcan

                @can('vaccount list')

                <li>
        
                    <div class="flex justify-between">
        
                        <a href="{{route('vaccount.index')}}" class=" flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">V. Account</a>
        
                        <a href="{{route('vaccount.create')}}" class="flex items-center py-2 px-4  w-auto text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                                
                                
                        </a>
                        
                    </div>
                
                </li>

                @endcan

               
                
            </ul>
            
        </li>

        @endif

        @if(auth()->user()->can('item list') || auth()->user()->can('item group') || auth()->user()->can('asset lancar list') || auth()->user()->can('contributor') )
        <li>
            <button
                type="button"
                class="flex items-center p-2 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                aria-controls="dropdown-sales"
                data-collapse-toggle="dropdown-sales"
            >
                <svg
                aria-hidden="true"
                class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
                >
                <path
                    fill-rule="evenodd"
                    d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                    clip-rule="evenodd"
                ></path>
                </svg>
                <span class="flex-1 ml-3 text-left whitespace-nowrap"
                >Stuff</span
                >
                <svg
                aria-hidden="true"
                class="w-6 h-6"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
                >
                <path
                    fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                ></path>
                </svg>
            </button>
            <ul id="dropdown-sales" class="hidden py-2 space-y-2">

               
                @can('item list')

                <li>

                    <div class="flex justify-between">

                        <a href="{{route('item.index')}}" class=" flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Item</a>

                        <a href="{{route('item.create')}}" class="flex items-center py-2 px-4  w-auto text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            
                            
                        </a>
                        
                    </div>
            
                </li>

                @endcan

                @can('item group')

                <li>
                <a
                    href="{{route('item.group')}}"
                    class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                    >Item Group</a
                >
                </li>

                @endcan

                @can('asset lancar list')

                <li>

                    <div class="flex justify-between">

                        <a href="{{route('asetLancar.index')}}" class=" flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Asset Lancar</a>

                        <a href="{{route('asetLancar.create')}}" class="flex items-center py-2 px-4  w-auto text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            
                            
                        </a>
                        
                    </div>
            
                </li>

                @endcan

                @can('contributor')

                <li>
                    <a
                        href="{{route('contributor.index')}}"
                        class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        >Contributors </a
                    >
                </li>

                @endcan

               
            </ul>
            </li>

        </li>
        @endif


        @if(auth()->user()->can('operation list') || auth()->user()->can('operation account')  )
        <li>

            <button
                type="button"
                class="flex items-center p-2 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                aria-controls="dropdown-journal"
                data-collapse-toggle="dropdown-journal"
                >
                <svg
                aria-hidden="true"
                class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
                >
                <path
                    fill-rule="evenodd"
                    d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                    clip-rule="evenodd"
                ></path>
                </svg>
                <span class="flex-1 ml-3 text-left whitespace-nowrap"
                >Journals</span
                >
                <svg
                aria-hidden="true"
                class="w-6 h-6"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
                >
                <path
                    fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                ></path>
                </svg>
            </button>
            <ul id="dropdown-journal" class="hidden py-2 space-y-2">
                @can('operation list')
                    
                <li>

                    <div class="flex justify-between">

                        <a href="{{route('operation.index')}}" class=" flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Operation</a>

                        <a href="{{route('operation.create')}}" class="flex items-center py-2 px-4  w-auto text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            
                            
                        </a>
                        
                    </div>
            
                </li>
                @endcan

                @can('operation account')
                
                <li>

                    <div class="flex justify-between">

                        <a href="{{route('operation.account.list')}}" class=" flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Account List</a>

                        <a href="{{route('operation.account.create')}}" class="flex items-center py-2 px-4  w-auto text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            
                            
                        </a>
                        
                    </div>
            
                </li>

                @endcan
            
            </ul>
        </li>
        @endif

        @if(auth()->user()->can('produksi list') || auth()->user()->can('setoran list') || auth()->user()->can('produksi jahit') || auth()->user()->can('produksi potong')  )
        <li>

            <button
                type="button"
                class="flex items-center p-2 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                aria-controls="dropdown-produksi"
                data-collapse-toggle="dropdown-produksi"
                >
                <svg
                aria-hidden="true"
                class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
                >
                <path
                    fill-rule="evenodd"
                    d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                    clip-rule="evenodd"
                ></path>
                </svg>
                <span class="flex-1 ml-3 text-left whitespace-nowrap"
                >Produksi</span
                >
                <svg
                aria-hidden="true"
                class="w-6 h-6"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
                >
                <path
                    fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                ></path>
                </svg>
            </button>
            <ul id="dropdown-produksi" class="hidden py-2 space-y-2">

                @can('produksi list')
                <li>

                    <div class="flex justify-between">

                        <a href="{{route('produksi.index')}}" class=" flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Produksi</a>

                        <a href="{{route('produksi.create')}}" class="flex items-center py-2 px-4  w-auto text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            
                            
                        </a>
                        
                    </div>

                </li>
                @endcan

                @can('setoran list')

                <li>
                    <a
                        href="{{route('setoran.index')}}"
                        class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        >Setoran</a
                    >
                </li>

                @endcan

                @can('produksi potong')
                
                <li>

                    <div class="flex justify-between">

                        <a href="{{route('produksi.getPotongList')}}" class=" flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Potong</a>

                        <a href="{{route('produksi.getPotongCreate')}}" class="flex items-center py-2 px-4  w-auto text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            
                            
                        </a>
                        
                    </div>
            
                </li>

                @endcan

                @can('produksi jahit')
                
                <li>

                    <div class="flex justify-between">

                        <a href="{{route('produksi.getJahitList')}}" class=" flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Jahit</a>

                        <a href="{{route('produksi.getJahitCreate')}}" class="flex items-center py-2 px-4  w-auto text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            
                            
                        </a>
                        
                    </div>

                </li>

                @endcan
            
            </ul>

        </li>
        @endif

        @if(auth()->user()->can('borongan list'))

        <li>

            <button
                type="button"
                class="flex items-center p-2 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                aria-controls="dropdown-borongan"
                data-collapse-toggle="dropdown-borongan"
                >
                <svg
                aria-hidden="true"
                class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
                >
                <path
                    fill-rule="evenodd"
                    d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                    clip-rule="evenodd"
                ></path>
                </svg>
                <span class="flex-1 ml-3 text-left whitespace-nowrap"
                >Borongan</span
                >
                <svg
                aria-hidden="true"
                class="w-6 h-6"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
                >
                <path
                    fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                ></path>
                </svg>
            </button>
            <ul id="dropdown-borongan" class="hidden py-2 space-y-2">

                @can('borongan list')
                    
                <li>

                    <div class="flex justify-between">

                        <a href="{{route('borongan.index')}}" class=" flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Borongan</a>

                        <a href="{{route('borongan.create')}}" class="flex items-center py-2 px-4  w-auto text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            
                            
                        </a>
                        
                    </div>

                </li>

                @endcan
            
            
            </ul>

        </li>

        @endif

        @if(auth()->user()->can('karyawan list') || auth()->user()->can('gajih list'))

        <li>

            <button
                type="button"
                class="flex items-center p-2 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                aria-controls="dropdown-karyawan"
                data-collapse-toggle="dropdown-karyawan"
                >
                <svg
                aria-hidden="true"
                class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
                >
                <path
                    fill-rule="evenodd"
                    d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                    clip-rule="evenodd"
                ></path>
                </svg>
                <span class="flex-1 ml-3 text-left whitespace-nowrap"
                >Karyawan</span
                >
                <svg
                aria-hidden="true"
                class="w-6 h-6"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
                >
                <path
                    fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                ></path>
                </svg>
            </button>
            <ul id="dropdown-karyawan" class="hidden py-2 space-y-2">

                @can('karyawan list')
                    
                <li>

                    <div class="flex justify-between">

                        <a href="{{route('karyawan.index')}}" class=" flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Karyawan</a>

                        <a href="{{route('karyawan.create')}}" class="flex items-center py-2 px-4  w-auto text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            
                            
                        </a>
                        
                    </div>

                </li>

                @endcan

                @can('gajih list')

                <li>
                    <a
                        href="{{route('gaji.index')}}"
                        class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        >Gaji</a
                    >
                </li>

                @endcan
            
            
            </ul>

        </li>

        @endif

        @if(auth()->user()->can('setting edit'))

        <li>

            <button
                type="button"
                class="flex items-center p-2 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                aria-controls="dropdown-system"
                data-collapse-toggle="dropdown-system"
                >
                <svg
                aria-hidden="true"
                class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
                >
                <path
                    fill-rule="evenodd"
                    d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                    clip-rule="evenodd"
                ></path>
                </svg>
                <span class="flex-1 ml-3 text-left whitespace-nowrap"
                >System</span
                >
                <svg
                aria-hidden="true"
                class="w-6 h-6"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
                >
                <path
                    fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                ></path>
                </svg>
            </button>
            <ul id="dropdown-system" class="hidden py-2 space-y-2">

                @can('setting edit')
                    
                <li>
                    <a
                        href="{{route('setting.index')}}"
                        class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        >Settings</a
                    >
                </li>

                @endcan

                <li>
                    <a
                        href="{{route('cronrunner.index')}}"
                        class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        >Cron Runner</a
                    >
                </li>

                <li>

                    <div class="flex justify-between">

                        <a href="{{route('jubelio.sync.index')}}" class=" flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Jubelio Sync</a>

                        <a href="{{route('jubelio.sync.create')}}" class="flex items-center py-2 px-4  w-auto text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            
                            
                        </a>

                      
                        
                    </div>

                </li>

                <li>
                    <a
                        href="{{route('jubelio.webhook.order')}}"
                        class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        >Jubelio Webhook</a
                    >
                </li>

                <li>
                    <a
                        href="{{route('jubelio.log.index')}}"
                        class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        >Jubelio Log</a
                    >
                </li>

                <li>
                    <a
                        href="{{route('jubelio.order.getall')}}"
                        class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        >Jubelio Get Order</a
                    >
                </li>

                 <li>
                    <a
                        href="{{route('jubelio.order.cek')}}"
                        class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        >Jubelio Cek Order</a
                    >
                </li>

               
            
            
            </ul>

        </li>

        @endif

        @if(auth()->user()->can('report nett cash'))

        <li>

            <button
                type="button"
                class="flex items-center p-2 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                aria-controls="dropdown-report"
                data-collapse-toggle="dropdown-report"
            >
                <svg
                aria-hidden="true"
                class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
                >
                <path
                    fill-rule="evenodd"
                    d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                    clip-rule="evenodd"
                ></path>
                </svg>
                <span class="flex-1 ml-3 text-left whitespace-nowrap"
                >Report</span
                >
                <svg
                aria-hidden="true"
                class="w-6 h-6"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
                >
                <path
                    fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                ></path>
                </svg>
            </button>
            <ul id="dropdown-report" class="hidden py-2 space-y-2">
                @can('report nett cash')
                    
               
                    <li>
                        <a
                            href="{{route('report.cash')}}"
                            class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                            >Nett Cash Sby</a
                        >
                    </li>

                @endcan

                <li>
                    <a
                        href="{{route('cashflow.index')}}"
                        class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        >Cash Flow</a
                    >
                </li>

                <li>
                    <a
                        href="{{route('compare.index')}}"
                        class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        >Compare</a
                    >
                </li>

                <li>
                    <a
                        href="{{route('statsale.index')}}"
                        class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        >Item sale</a
                    >
                </li>
            

            </ul>


        </li>

        @endif

        @if(auth()->user()->can('user list') || auth()->user()->can('user role'))

        <li>

            <button
                type="button"
                class="flex items-center p-2 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                aria-controls="dropdown-user"
                data-collapse-toggle="dropdown-user"
                >
                <svg
                aria-hidden="true"
                class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
                >
                <path
                    fill-rule="evenodd"
                    d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                    clip-rule="evenodd"
                ></path>
                </svg>
                <span class="flex-1 ml-3 text-left whitespace-nowrap"
                >User</span
                >
                <svg
                aria-hidden="true"
                class="w-6 h-6"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
                >
                <path
                    fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                ></path>
                </svg>
            </button>
            <ul id="dropdown-user" class="hidden py-2 space-y-2">

                @can('user list')
                    
                <li>

                    <div class="flex justify-between">

                        <a href="{{route('user.list')}}" class=" flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">User</a>

                        <a href="{{route('user.create')}}" class="flex items-center py-2 px-4  w-auto text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            
                            
                        </a>
                        
                    </div>

                </li>

                @endcan

                @can('user role')
                <li>

                    <div class="flex justify-between">

                        <a href="{{route('role.indexRole')}}" class=" flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Role</a>

                        <a href="{{route('role.createRole')}}" class="flex items-center py-2 px-4  w-auto text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            
                            
                        </a>
                        
                    </div>

                </li>

                @endcan

                @can('location')
                    
                <li>

                    <div class="flex justify-between">

                        <a href="{{route('location.index')}}" class=" flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Location</a>

                        <a href="{{route('location.create')}}" class="flex items-center py-2 px-4  w-auto text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            
                            
                        </a>
                        
                    </div>
                    
                </li>

                @endcan
            
            
            </ul>

        </li>
        @endif

  

    
    
        {{-- <li>
        <a
            href="#"
            class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
        >
            <svg
            aria-hidden="true"
            class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
            >
            <path
                d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z"
            ></path>
            <path
                d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"
            ></path>
            </svg>
            <span class="flex-1 ml-3 whitespace-nowrap">Messages</span>
            <span
            class="inline-flex justify-center items-center w-5 h-5 text-xs font-semibold rounded-full text-primary-800 bg-primary-100 dark:bg-primary-200 dark:text-primary-800"
            >
            4
            </span>
        </a>
        </li>
        <li>
        <button
            type="button"
            class="flex items-center p-2 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
            aria-controls="dropdown-authentication"
            data-collapse-toggle="dropdown-authentication"
        >
            <svg
            aria-hidden="true"
            class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
            >
            <path
                fill-rule="evenodd"
                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                clip-rule="evenodd"
            ></path>
            </svg>
            <span class="flex-1 ml-3 text-left whitespace-nowrap"
            >Authentication</span
            >
            <svg
            aria-hidden="true"
            class="w-6 h-6"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
            >
            <path
                fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd"
            ></path>
            </svg>
        </button>
        <ul id="dropdown-authentication" class="hidden py-2 space-y-2">
            <li>
            <a
                href="#"
                class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                >Sign In</a
            >
            </li>
            <li>
            <a
                href="#"
                class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                >Sign Up</a
            >
            </li>
            <li>
            <a
                href="#"
                class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                >Forgot Password</a
            >
            </li>
        </ul>
        </li> --}}
    </ul>
    {{-- <ul
        class="pt-5 mt-5 space-y-2 border-t border-gray-200 dark:border-gray-700"
    >
        <li>
        <a
            href="#"
            class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg transition duration-75 hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group"
        >
            <svg
            aria-hidden="true"
            class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
            >
            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
            <path
                fill-rule="evenodd"
                d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                clip-rule="evenodd"
            ></path>
            </svg>
            <span class="ml-3">Docs</span>
        </a>
        </li>
        <li>
        <a
            href="#"
            class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg transition duration-75 hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group"
        >
            <svg
            aria-hidden="true"
            class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
            >
            <path
                d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"
            ></path>
            </svg>
            <span class="ml-3">Components</span>
        </a>
        </li>
        <li>
        <a
            href="#"
            class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg transition duration-75 hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group"
        >
            <svg
            aria-hidden="true"
            class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
            >
            <path
                fill-rule="evenodd"
                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-2 0c0 .993-.241 1.929-.668 2.754l-1.524-1.525a3.997 3.997 0 00.078-2.183l1.562-1.562C15.802 8.249 16 9.1 16 10zm-5.165 3.913l1.58 1.58A5.98 5.98 0 0110 16a5.976 5.976 0 01-2.516-.552l1.562-1.562a4.006 4.006 0 001.789.027zm-4.677-2.796a4.002 4.002 0 01-.041-2.08l-.08.08-1.53-1.533A5.98 5.98 0 004 10c0 .954.223 1.856.619 2.657l1.54-1.54zm1.088-6.45A5.974 5.974 0 0110 4c.954 0 1.856.223 2.657.619l-1.54 1.54a4.002 4.002 0 00-2.346.033L7.246 4.668zM12 10a2 2 0 11-4 0 2 2 0 014 0z"
                clip-rule="evenodd"
            ></path>
            </svg>
            <span class="ml-3">Help</span>
        </a>
        </li>
    </ul> --}}
    </div>

</aside>