<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Transaction Item </p>

       
    </div>

    
    <div class="mb-8">
    
        

        <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px">
            
              
                <li class="me-2">
                    <a href="{{route('item.detail',$tid)}}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Detail</a>
                </li>
                <li class="me-2">
                    <a href="{{route('item.transaction',$tid)}}" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500" aria-current="page">Transaction</a>
                </li>
                <li class="me-2">
                    <a href="{{route('item.stat',$tid)}}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Stats</a>
                </li>
                <li class="me-2">
                    <a href="{{route('item.jubelio',$tid)}}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Jubelio</a>
                </li>
                
            </ul>
        </div>
    </div>

    <div class="mb-8">


        <section class="bg-gray-50 dark:bg-gray-900 ">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <form action="{{route('item.transactionFilter',$tid)}}" method="get">
                        <div class="flex flex-col md:flex-row items-end justify-between p-4">
                        
                            
                            <div class="w-full md:w-4/6">
                            
                                <div class="">
                                    <div>

                                        <x-partial.select-addr :dataProp='$dataListPropCustomer' />

                                    </div>
                                    

                                   

                                </div>

                                    
                                
                            </div>
                            <div class="mt-4 mb-4 w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                                <button type="submit" class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="h-4 w-4 mr-2 " viewbox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                                    </svg>
                                    Search
                                </button>

                                <a href="{{route('item.transaction',$tid)}}" class="flex items-center justify-center py-2 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">

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
                                    <th scope="col" class="px-4 py-3">Invoice</th>
                                    <th scope="col" class="px-4 py-3">Type</th>
                                    <th scope="col" class="px-4 py-3">Description</th>
                                    <th scope="col" class="px-4 py-3">Quantity</th>
                                    <th scope="col" class="px-4 py-3">Sender</th>
                                    <th scope="col" class="px-4 py-3">Receiver</th>
                                    <th scope="col" class="px-4 py-3">Price</th>
                      
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ( $dataList as $item)
                                    
                              

                                <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                                   
                                    <th scope="row" class="px-4 py-3  whitespace-nowrap ">
                                        
                                        <a href="{{route('transaction.getDetail',$item->transaction->id)}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{\App\Helpers\DateHelper::display($item->date)}}</a>

                                    </th>
                           
                                    <td class="px-4 py-3">{{$item->transaction->invoice}}</td>
                                    <td class="px-4 py-3">{{$item->transaction->type_name}}</td>
                                    <td class="px-4 py-3 w-60">{{$item->transaction->description}}</td>
                                    <td class="px-4 py-3">{{$item->quantity}}</td>
                                    <td class="px-4 py-3">
                                        @isset($item->transaction->sender)
                                            <a href="{{$item->sender->getDetailLink()}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$item->transaction->sender->name}}</a>
                                        @endisset
                                    </td>
                                    <td class="px-4 py-3">
                                        @isset($item->transaction->receiver)
                                            <a href="{{$item->receiver->getDetailLink()}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$item->transaction->receiver->name}}</a>
                                        @endisset
                                    </td>
                                  
                                    <td class="px-4 py-3">{{$item->price}}</td>

                                    
                                    
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

    @push('jsBody')

    <script>
     

     
    </script>
        
    @endpush

</x-layouts.layout>
