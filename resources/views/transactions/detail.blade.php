<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Detail Transaction #{{$data->id}}</p>

       
    </div>

    <div class="mb-8">


        <div class="flex justify-between">
            <div class="flex items-center">
                <div>
                    <img src="{{ asset('img/logo.png') }}" alt="" srcset="">
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-500">Invoice #{{$data->id}}</p>
                    <p class="font-bold">{{$data->receiver->name}}</p>
                </div>
            </div>
            <div>

                <div class="text-right">
                    <p class="text-sm text-gray-500">Total</p>
                    <p class="font-bold text-xl">{{number_format($data->total,2)}}</p>
                </div>

            </div>
        </div>

        <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">

        <div class="grid grid-cols-2 gap-4">

            <div>
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="grid grid-cols-1 divide-y">
                        <div>
                            <div class="grid grid-cols-4 p-4">
                                <div>
                                    <p class="font-bold">From</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->sender->name}}</p>
                                </div>
                            </div>
                        </div>
                       
                        <div>
                            <div class="grid grid-cols-4 p-4">
                                <div>
                                    <p class="font-bold">To</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->receiver->name}}</p>
                                </div>
                            </div>
                        </div>

                        @if ($data->description)

                        <div>
                            <div class="grid grid-cols-4 p-4">
                                <div>
                                    <p class="font-bold">Note</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->description}}</p>
                                </div>
                            </div>
                        </div>
                            
                        @endif
                        
                    </div>
                </div>
            </div>

            <div>
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="grid grid-cols-1 divide-y">
                        <div>
                            <div class="grid grid-cols-4 p-4">
                                <div>
                                    <p class="font-bold">Date</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{\Carbon\Carbon::parse($data->date)->format('d/m/Y')}}</p>
                                </div>
                            </div>
                        </div>
                       
                        <div>
                            <div class="grid grid-cols-4 p-4">
                                <div>
                                    <p class="font-bold">Due</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->due == "0000-00-00" ? "-" : \Carbon\Carbon::parse($data->due)->format('d/m/Y')}}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="grid grid-cols-4 p-4">
                                <div>
                                    <p class="font-bold">Invoice Discount</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->discount}}%</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="grid grid-cols-4 p-4">
                                <div>
                                    <p class="font-bold">Adjustment</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{number_format($data->adjustment,2)}}</p>
                                </div>
                            </div>
                        </div>

                        @if($data->ppn > 0)

                        <div>
                            <div class="grid grid-cols-4 p-4">
                                <div>
                                    <p class="font-bold">PPN</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{number_format($data->ppn,2)}}</p>
                                </div>
                            </div>
                        </div>

                        @endif

                        <div>
                            <div class="grid grid-cols-4 p-4">
                                <div>
                                    <p class="font-bold">Items</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->total_items}}</p>
                                </div>
                            </div>
                        </div>


                        @if($data->user)

                        <div>
                            <div class="grid grid-cols-4 p-4">
                                <div>
                                    <p class="font-bold">User</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->user->username}}</p>
                                </div>
                            </div>
                        </div>

                        @endif

                        <div>
                            <div class="grid grid-cols-4 p-4">
                                <div>
                                    <p class="font-bold">Total Before Disc</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{number_format($data->real_total,2)}}</p>
                                </div>
                            </div>
                        </div>

                     
                        
                    </div>
                </div>
            </div>

        </div>

       
       

    </div>

    <section class="bg-gray-50 dark:bg-gray-900 mb-8">
        <div class=" ">
            <div class="relative overflow-hidden bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
                <div class="flex flex-col px-4 py-3 space-y-3 lg:flex-row lg:items-center lg:justify-between lg:space-y-0 lg:space-x-4">
                    <div class="flex items-center flex-1 space-x-4">
                        <button type="button" class="flex items-center justify-center flex-shrink-0 px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            <svg  class="w-4 h-4 mr-2"  aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M8 3a2 2 0 0 0-2 2v3h12V5a2 2 0 0 0-2-2H8Zm-3 7a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h1v-4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v4h1a2 2 0 0 0 2-2v-5a2 2 0 0 0-2-2H5Zm4 11a1 1 0 0 1-1-1v-4h8v4a1 1 0 0 1-1 1H9Z" clip-rule="evenodd"/>
                            </svg>
                            Print
                        </button>
                        <button type="button" class="flex items-center justify-center flex-shrink-0 px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            <svg class="w-4 h-4 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M5.617 2.076a1 1 0 0 1 1.09.217L8 3.586l1.293-1.293a1 1 0 0 1 1.414 0L12 3.586l1.293-1.293a1 1 0 0 1 1.414 0L16 3.586l1.293-1.293A1 1 0 0 1 19 3v18a1 1 0 0 1-1.707.707L16 20.414l-1.293 1.293a1 1 0 0 1-1.414 0L12 20.414l-1.293 1.293a1 1 0 0 1-1.414 0L8 20.414l-1.293 1.293A1 1 0 0 1 5 21V3a1 1 0 0 1 .617-.924ZM9 7a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2H9Zm0 4a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2H9Zm0 4a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2H9Z" clip-rule="evenodd"/>
                              </svg>
                              
                           
                            Recaipt
                        </button>
                    </div>
                    <div class="flex flex-col flex-shrink-0 space-y-3 md:flex-row md:items-center lg:justify-end md:space-y-0 md:space-x-3">
                        <button type="button" class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 rounded-lg">
                            

                            <svg class="h-3.5 w-3.5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                            </svg>
                              
                           Delete
                        </button>
                   
                        
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="p-4">
                                    <div class="flex items-center">
                                        <div  class="w-4 h-4"></div>
                                    
                                    </div>
                                </th>
                                <th scope="col" class="px-4 py-3">Item</th>
                               
                                <th scope="col" class="px-4 py-3">Code</th>
                             
                                <th scope="col" class="px-4 py-3">Quantity</th>
                                <th scope="col" class="px-4 py-3">Price</th>
                                <th scope="col" class="px-4 py-3">Discount(%)</th>
                                <th scope="col" class="px-4 py-3">Sub-Total</th>
                            </tr>
                        </thead>
                        <tbody  id="accordion-collapse" data-accordion="collapse">

                            @forelse ($data->transactionDetail as $itemTd)
                            @php
                                $idItem = $itemTd->item->id;
                                $type = 'items';
                            @endphp
                            
                           
                            <tr class="border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="w-4 px-4 py-3">
                                    <div class="flex items-center">
                                        <button type="button" class="" data-accordion-target="#accordion-collapse-body-{{$itemTd->id}}" aria-expanded="false" aria-controls="accordion-collapse-body-{{$itemTd->id}}" >
                                           
                                            <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <th scope="row" class="flex items-center px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="h-8 w-8 mr-3">
                                        <x-partial.image :idItem="$idItem" :type="$type"/>
                                    </div>
                                   
                                    {{$itemTd->item->name}}

                                    
                                </th>
                               
                                <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{$itemTd->item->code}}
                                </td>
                              

                                <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{$itemTd->quantity}}
                                </td>

                                <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{$itemTd->price}}
                                </td>

                                <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{$itemTd->discount}}
                                </td>

                                <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{$itemTd->total}}
                                </td>
                               
                            </tr>
                            <tr class="border-b bg-gray-100 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 hidden" id="accordion-collapse-body-{{$itemTd->id}}"  aria-labelledby="accordion-collapse-heading-{{$itemTd->id}}">
                                <td colspan="11">
                                    <div class="p-4">
                                        <div class="grid grid-cols-3 gap-4 ">

                                            <div >

                                                <div class="w-4/6 mr-3">
                                                    <x-partial.image :idItem="$idItem" :type="$type"/>
                                                </div>

                                                {{-- <img src="https://flowbite.s3.amazonaws.com/blocks/application-ui/products/imac-front-image.png" alt="{{$itemTd->item->name}} Image" class=""> --}}

                                               
                                            </div>

                                            <div class="col-span-2">
                                                <div class="grid grid-cols-2 divide-x gap-4">
                                                    <div>
                                                        <div class="grid grid-cols-2 py-4">
                                                            <div>
                                                                <p class="font-bold">Barcode</p>
                                                            </div>
                                                            <div class="text-right">
                                                                <p> {{$itemTd->item->id}}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="grid grid-cols-2 p-4">
                                                            <div>
                                                                <p class="font-bold">SKU</p>
                                                            </div>
                                                            <div class="text-right">
                                                                <p> {{$itemTd->item->pcode}}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                </div>

                                                <div class="grid grid-cols-1 divide-y">
                                                    <div>
                                                       
                                                        <div class="py-4">
                                                            <div>
                                                                <p class="font-bold">Description</p>
                                                            </div>
                                                            <div class="col-span-3">
                                                                <p> {{$itemTd->item->description}}</p>
                                                            </div>
                                                        </div>
                                                       
                                                    </div>
                                                    <div>
                                                       
                                                        <div class="py-4">
                                                            <div>
                                                                <p class="font-bold">Np</p>
                                                            </div>
                                                            <div class="col-span-3">
                                                                <p> {{$itemTd->item->description2}}</p>
                                                            </div>
                                                        </div>
                                                       
                                                    </div>
                                                    
                                                </div>

                                                
                                                
                                            </div>

                                            

                                        </div>

                                        

                                        <x-transaction.warehouse-item  :idItem="$idItem"/>
                                    </div>
                                </td>
                            </tr>
                                
                            @empty
                                
                            @endforelse
                            
                            
                        </tbody>
                    </table>
                </div>
                {{-- <nav class="flex flex-col items-start justify-between p-4 space-y-3 md:flex-row md:items-center md:space-y-0" aria-label="Table navigation">
                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                        Showing
                        <span class="font-semibold text-gray-900 dark:text-white">1-10</span>
                        of
                        <span class="font-semibold text-gray-900 dark:text-white">1000</span>
                    </span>
                    <ul class="inline-flex items-stretch -space-x-px">
                        <li>
                            <a href="#" class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                <span class="sr-only">Previous</span>
                                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-center px-3 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">1</a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-center px-3 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">2</a>
                        </li>
                        <li>
                            <a href="#" aria-current="page" class="z-10 flex items-center justify-center px-3 py-2 text-sm leading-tight border text-primary-600 bg-primary-50 border-primary-300 hover:bg-primary-100 hover:text-primary-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">3</a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-center px-3 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">...</a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-center px-3 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">100</a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                <span class="sr-only">Next</span>
                                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </li>
                    </ul>
                </nav> --}}
            </div>
        </div>
      </section>

</x-layouts.layout>