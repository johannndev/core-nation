<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Jubelio Return</p>

       
    </div>

    {{-- <div id="alert-border-2">

    @if ((session('errorMessage')))

    <div  class="flex items-center p-4 mb-4 text-red-800 border-t-4 border-red-300 bg-red-50 dark:text-red-400 dark:bg-gray-800 dark:border-red-800" role="alert">
        <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
          <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <div class="ms-3 text-sm font-medium">
            {{session('errorMessage')}}
        </div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700"  data-dismiss-target="#alert-border-2" aria-label="Close">
          <span class="sr-only">Dismiss</span>
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
          </svg>
        </button>
    </div>
        
    @endif --}}

   

   



    <div class="mb-8">


        <section class="bg-gray-50 dark:bg-gray-900 ">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <form action="{{route('filter.get',['action' =>'jubelio.log.index'])}}" method="post" >

                        @csrf

                        <div class="flex flex-col md:flex-row items-end justify-between p-4">
                        
                            
                            <div class="w-full md:w-4/6">
                            
                                <div class="grid gap-4 md:grid-cols-5">
                                    <div>
                                        <label for="from" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">From</label>
                                        <input type="date" id="from" name="from" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('from')}}"/>
                                    </div>
                                    <div>
                                        <label for="to" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">To</label>
                                        <input type="date" id="to" name="to" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('to')}}"/>
                                    </div>
                                    <div>
                                        <label for="invoice" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Invoice</label>
                                        <input type="text" id="invoice" name="invoice" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{Request('invoice')}}" />
                                    </div>

                                    <div>
                                        <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                        <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="">Choose a status</option>
                                            <option  {{Request('status','OPEN') == 'OPEN' ? 'selected' : 'null'}} value="OPEN">Open</option>
                                            <option  {{Request('status','OPEN') == 'SOLVED' ? 'selected' : 'null'}} value="SOLVED">Solved</option>
                                           
                                           
                                          </select>
                                    </div>

                                  

                                </div>

                                    
                                
                            </div>
                            <div class=" mt-4 md:mt-0 w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                                <button type="submit" class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="h-4 w-4 mr-2 " viewbox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                                    </svg>
                                    Filter
                                </button>

                                <a href="{{route('jubelio.log.index')}}" class="flex items-center justify-center py-2 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" >
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                      </svg>

                                      
                                    {{-- <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" c viewbox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                                    </svg> --}}

                                    Clear
                                </a>

                            
                            </div>

                            
                        </div>
                    </form>
                  

                   

                   
                </div>

                


                <div class="grid grid-cols-1 gap-4 mt-6">

                    @forelse ($dataList as $item)
                        <div class="block py-4 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100">
                            
                            <div class="px-4">

                              
                                <div class="flex justify-start md:justify-between flex-col md:flex-row">

                                    <div>
                                        <div>
                                            <span class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-sm me-2  border border-gray-300 ">
                                                <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586l2.982 2.982a1 1 0 0 1 0 1.414Z"/>
                                                </svg>
                                                
                                                {{$item->updated_at}}
                                            </span>
                                        </div>

                                        <div class="flex flex-col md:flex-row items-start md:items-center">

                                  

                                           
                                            <div>
                                                <a href={{ route('transaction.index',['invoice' => $item->invoice, 'type' => 0]) }} class="text-blue-500 hover:text-blue-600 hover:underline">{{$item->invoice}}</a>
                                               
                                                

                                                
                                            
                                                    
                                            </div>
        
                                            <div class="flex mt-1 md:mt-0">

                                                @if ($item->status == 2)
                                                <div>
                                                    <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-green-900 dark:text-green-300">CRON SOLVED</span>
                                                </div>

                                                @endif

                                                @if ($item->status == 1)
                                                <div>
                                                    <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-green-900 dark:text-green-300 uppercase">finished {{$item->user ? 'By '.$item->user->username : ""}}</span>
                                                </div>

                                                @endif

                                              
                                            </div>
        
                                        </div>

                                    </div>

                                

                                    <div>
                                        <div class="flex items-center mt-2 md:mt-0">

                                            @if ($item->status == 0)
                                           
                                            <div>
                                                <a href="{{ route('jubelio.return.finished',$item->id) }}" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg px-3 py-2 text-xs me-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Finished</a>

                                            </div>

                                            <div>

                                                <a href="{{route('jubelio.return.detail',$item->id)}}" type="button" class="capitalize text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg px-3 py-2 text-xs me-2  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Confirmation </a>

                                            </div>

                                            @endif


                                            <div>
                                                <button id="dropdownMore-{{$item->id}}" data-dropdown-toggle="dropdownM-{{$item->id}}" class=" inline-flex items-center  text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg px-3 py-2 text-xs me-2  dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" type="button">More <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                                    </svg>
                                                </button>
                                                    
                                                <!-- Dropdown menu -->
                                                <div id="dropdownM-{{$item->id}}" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">
                                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownMore-{{$item->id}}">
                                                        <li>
                                                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Dashboard</a>
                                                        </li>
                                                        <li>
                                                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Settings</a>
                                                        </li>
                                                        <li>
                                                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Earnings</a>
                                                        </li>
                                                        <li>
                                                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Sign out</a>
                                                        </li>
                                                    </ul>
                                                </div>                                                   
                                            </div>

                                        </div>
                                    </div>

                                </div>
                                    
                                  

                                    


                                
                               
                            </div>

                            <hr class="my-4">

                            @isset($item->pesan)

                            <div class="px-4 text-sm text-gray-500">
                                <div class="p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                                    {{$item->pesan}}
                                </div>
                               
                            </div>
                                
                            @endisset

                            @empty($item->pesan)
                                <p class="p-4">Pesan tidak tersedia</p>
                            @endempty

                           

                            <hr class="my-4 px-4">

                            <div class="px-4 text-gray">
                                <div class="flex space-x-3">

                                    <div class="flex items-center space-x-1">

                                        <div>
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-gray-500">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                                            </svg>
                                            
                                            
                                        </div>

                                        <div class="font-bold text-sm">
                                            {{$item->store_name}}
                                        </div>

                                    </div>

                                    <div class="flex items-center space-x-1">

                                        <div>
                                            
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"class="h-4 w-4 text-gray-500">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                            </svg>
                                                
                                        </div>

                                        <div class="font-bold text-sm">
                                            {{$item->location_name}}
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                @empty
                    
                @endforelse

                </div>

                {{$dataList->onEachSide(1)->links()}}
            
            </div>
        </section>
       

    </div>

</x-layouts.layout>