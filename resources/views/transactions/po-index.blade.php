<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">PO List</p>

       
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
                    <form action="{{route('filter.get',['action' => 'transaction.Poindex'])}}" method="post">
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
                                        <label for="customer" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Customer</label>
                                        <input type="text" id="customer" name="customer" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{Request('customer')}}" />
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

                                <a href="{{route('transaction.Poindex')}}" class="flex items-center justify-center py-2 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">

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
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Date</th>
                                    <th scope="col" class="px-4 py-3">Customer</th>
                                    <th scope="col" class="px-4 py-3">Invoice</th>
                                    <th scope="col" class="px-4 py-3">Location</th>
                                    <th scope="col" class="px-4 py-3">Total</th>
                                    <th scope="col" class="px-4 py-3">Items</th>
                                    <th scope="col" class="px-4 py-3">Available</th>
                                    <th scope="col" class="px-4 py-3">Status</th>
                                   
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ( $dataList as $item)
                                    
                                

                                <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                                    <th scope="row" class="px-4 py-3  whitespace-nowrap ">

                                    <a href="{{route('transaction.Podetail',$item->id)}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{\Carbon\Carbon::parse($item->date)->format('d/m/Y')}}</a>

                                    </th>
                                  
                                    <td class="px-4 py-3">{{$item->customer->username}}</td>
                                    <td class="px-4 py-3">{{$item->invoice}}</td>
                                    <td class="px-4 py-3">
                                        @if ($item->location_id > 0)
                                            {{$item->lokasi->name}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{{number_format($item->total,2)}}</td>
                                    <td class="px-4 py-3">{{number_format($item->total_items,2)}}</td>
                                    <td class="px-4 py-3">{{number_format($item->transaction_detail_sum_available_quantity,2)}}</td>
                                    <td>
                                        @if ($item->status == 0 )
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">Pending</span>

                                        @elseif ($item->status == 1 )
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">Partial</span>

                                        @elseif ($item->status == 2 )
                                            <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Success</span>


                                        @else

                                            <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Failed</span>

                                            
                                        @endif
                                    </td>
                                    
                                </tr>
                                    
                                @empty

                                <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                                   
                                    <td class="px-4 py-3 text-center" colspan="9">Data Empty</td>
                                   
                                    
                                    
                                </tr>
                                    
                                @endforelse ()
                                
                              
                            
                            </tbody>
                        </table>
                    </div>

                    {{$dataList->onEachSide(1)->links()}}

                   
                </div>
            </div>
        </section>
       

    </div>

</x-layouts.layout>