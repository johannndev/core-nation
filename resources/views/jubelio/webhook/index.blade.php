<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Webhook History</p>

       
    </div>

    <div class="mb-8">


        <section class="bg-gray-50 dark:bg-gray-900 ">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">

                    <form action="{{route('filter.get',['action' =>'jubelio.webhook.order'])}}" metdod="post">
                        @csrf

                        <div class="flex flex-col md:flex-row items-end justify-between p-4">
                        
                            
                            <div class="w-full md:w-4/6">
                            
                                <div class="grid gap-4 md:grid-cols-5 items-end">
                                    <div>
                                        <label for="invoice" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Invoice</label>
                                        <input type="text" id="invoice" name="invoice" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('invoice')}}"/>
                                    </div>

                                </div>

                                    
                                
                            </div>
                            <div class="mt-4 w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                                <button type="submit" class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="h-4 w-4 mr-2 " viewbox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                                    </svg>
                                    Filter
                                </button>

                                <a href="{{route('jubelio.webhook.order')}}" class="flex items-center justify-center py-2 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">

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
                 
                    <div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        
                                        <th scope="col" class="px-4 py-3">Order ID</th>
                                        <th scope="col" class="px-4 py-3">Invoice</th>
                                        <th scope="col" class="px-4 py-3">Type</th>
                                        <th scope="col" class="px-4 py-3">Order Status</th>
                                        <th scope="col" class="px-4 py-3">Submit By</th>
                                        <th scope="col" class="px-4 py-3">Error</th>
                                        <th scope="col" class="px-4 py-3">Status</th>
                                        <th scope="col" class="px-4 py-3"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ( $dataList as $item)
                                        
                                  
                    
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-100">

                                        <th class="px-4 py-3">{{$item->jubelio_order_id}}</th>
                                        <td class="px-4 py-3">{{$item->invoice}}</td>
                                        <td class="px-4 py-3">{{$item->type}}</td>
                                        <td class="px-4 py-3">{{$item->order_status}}</td>
                                        <td class="px-4 py-3">{{$item->execute_by}}

                                              @if ($item->execute_by == null)

                                                <span class="bg-blue-100 text-blue-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-sm me-2 dark:bg-blue-700 dark:text-blue-400 border border-blue-500 ">

                                                        <div role="status">
                                                            <svg aria-hidden="true" class="w-2.5 h-2.5 me-1.5 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                                            </svg>
                                                            <span class="sr-only">Loading...</span>
                                                        </div>

                                                        Pending...
                                                    </span>

                                              @elseif ($item->execute_by == 0)

                                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-sm me-2 dark:bg-yellow-700 dark:text-yellow-400 border border-yellow-500 ">

                                                    <svg class="w-2.5 h-2.5 me-1.5"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" >
                                                        <path fill-rule="evenodd" d="M2.25 5.25a3 3 0 0 1 3-3h13.5a3 3 0 0 1 3 3V15a3 3 0 0 1-3 3h-3v.257c0 .597.237 1.17.659 1.591l.621.622a.75.75 0 0 1-.53 1.28h-9a.75.75 0 0 1-.53-1.28l.621-.622a2.25 2.25 0 0 0 .659-1.59V18h-3a3 3 0 0 1-3-3V5.25Zm1.5 0v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5Z" clip-rule="evenodd" />
                                                    </svg>

                                                    Cronjob
                                                </span>

                                              @elseif ($item->execute_by > 0)

                                                <span class="bg-green-100 text-green-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-sm me-2 dark:bg-green-700 dark:text-green-400 border border-green-500 ">

                                                    <svg class="w-2.5 h-2.5 me-1.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" >
                                                        <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                                                    </svg>

                                                    {{ $item->user->name }}
                                                </span>

                                              @else

                                              @endif
                                        </td>
                                        <td class="px-4 py-3">

                                            @if ($item->status == 0)
                                                @if ($item->error)

                                                    <span class="bg-red-100 text-red-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-sm me-2 dark:bg-red-700 dark:text-red-400 border border-red-500 ">

                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-2.5 h-2.5 me-1.5" stroke-width="2" stroke="currentColor">
                                                        <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                                        </svg>

                                                        Error
                                                    </span>
                                                
                                                @else

                                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-sm me-2 dark:bg-blue-700 dark:text-blue-400 border border-blue-500 ">

                                                        <div role="status">
                                                            <svg aria-hidden="true" class="w-2.5 h-2.5 me-1.5 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                                            </svg>
                                                            <span class="sr-only">Loading...</span>
                                                        </div>

                                                        Pending...
                                                    </span>

                                                @endif
                                            @else

                                                <span class="bg-green-100 text-green-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-sm me-2 dark:bg-green-700 dark:text-green-400 border border-green-500 ">
                                                
                                                    <svg class="w-2.5 h-2.5 me-1.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"  stroke-width="2" stroke="currentColor">
                                                        <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd" />
                                                    </svg>

                                                    Clear
                                                </span>

                                                
                                                    
                                            @endif
                                        </td>

                                         <td class="px-4 py-3">

                                             
                                            @if ($item->status == 0)
                                                <span class="bg-blue-100 text-blue-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-sm me-2 dark:bg-blue-700 dark:text-blue-400 border border-blue-500 ">

                                                    <div role="status">
                                                        <svg aria-hidden="true" class="w-2.5 h-2.5 me-1.5 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                                        </svg>
                                                        <span class="sr-only">Loading...</span>
                                                    </div>

                                                    Pending...
                                                </span>

                                            @elseif ($item->status == 1)
                                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-sm me-2 dark:bg-yellow-700 dark:text-yellow-400 border border-yellow-500 ">

                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"  class="w-2.5 h-2.5 me-1.5" stroke-width="2" stroke="currentColor">
                                                        <path fill-rule="evenodd" d="M4.755 10.059a7.5 7.5 0 0 1 12.548-3.364l1.903 1.903h-3.183a.75.75 0 1 0 0 1.5h4.992a.75.75 0 0 0 .75-.75V4.356a.75.75 0 0 0-1.5 0v3.18l-1.9-1.9A9 9 0 0 0 3.306 9.67a.75.75 0 1 0 1.45.388Zm15.408 3.352a.75.75 0 0 0-.919.53 7.5 7.5 0 0 1-12.548 3.364l-1.902-1.903h3.183a.75.75 0 0 0 0-1.5H2.984a.75.75 0 0 0-.75.75v4.992a.75.75 0 0 0 1.5 0v-3.18l1.9 1.9a9 9 0 0 0 15.059-4.035.75.75 0 0 0-.53-.918Z" clip-rule="evenodd" />
                                                    </svg>


                                                    {{ $item->run_count }} run
                                                </span>

                                            @elseif ($item->status == 2)

                                                <span class="bg-green-100 text-green-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-sm me-2 dark:bg-green-700 dark:text-green-400 border border-green-500 ">
                                                
                                                    <svg class="w-2.5 h-2.5 me-1.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"  stroke-width="2" stroke="currentColor">
                                                        <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd" />
                                                    </svg>

                                                    Success
                                                </span>

                                            @else
                                                
                                            @endif

                                        </td>
                                        <td class="px-4 py-3 flex">
                                            <a href="{{route('jubelio.webhook.detail',$item->id)}}" class=" items-center justify-center text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 me-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-primary-800">
                                                Detail
                                            </a>


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
            </div>
        </section>
       

    </div>

    @push('jsBody')


        
    @endpush

</x-layouts.layout>