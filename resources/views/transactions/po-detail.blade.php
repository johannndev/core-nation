<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">PO Transaction #{{$data->id}}</p>

       
    </div>

    <div class="mb-8">


        <div class="flex justify-between">
            <div class="flex items-center">
                <div>
                    <img src="{{ asset('img/logo.png') }}" alt="" srcset="">
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-500">Invoice #{{$data->id}}</p>
                    <p class="font-bold">
                    @isset($data->receiver)
                      <a href="{{$data->receiver->getDetailLink()}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$data->receiver->name}}</a>
                    @endisset
                </div>
            </div>
            <div>

                <div class="text-right">
                    <p class="text-sm text-gray-500">Total</p>
                    <p class="font-bold text-lg md:text-xl">{{number_format($data->total,2)}}</p>
                </div>

            </div>
        </div>

        <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">

        <div class="grid md:grid-cols-2 gap-4">

            <div>
                <div class="bg-white dark:bg-gray-800 relative print:shadow-none shadow-md sm:rounded-lg overflow-hidden">
                    <div class="grid grid-cols-1 divide-y print:divide-y-0">
                        
                       
                        

                        <div>
                            <div class="grid grid-cols-5 p-4 print:p-0 text-sm">
                                <div class="col-span-2">
                                    <p class="font-bold">Customer</p>
                                </div>
                                <div class="col-span-3">
                                  @isset($data->customer)

                                   
                                    <p class="font-medium text-blue-600 dark:text-blue-500"> {{$data->customer->username}}</p>
                                  @endisset
                                </div>
                            </div>
                        </div>
                            
                        
                        

                        @if ($data->description)

                        <div>
                            <div class="grid grid-cols-5 p-4 print:p-0 text-sm">
                                <div class="col-span-2">
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
                <div class="bg-white dark:bg-gray-800 relative  print:shadow-none shadow-md sm:rounded-lg overflow-hidden">
                    <div class="grid grid-cols-1 divide-y print:divide-y-0">
                        <div>
                            <div class="grid grid-cols-5 p-4 print:p-0 text-sm">
                                <div class="col-span-2">
                                    <p class="font-bold">Date</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{\Carbon\Carbon::parse($data->date)->format('d/m/Y')}}</p>
                                </div>
                            </div>
                        </div>
                       
                       

                        

                        @if($data->ppn > 0)

                        <div>
                            <div class="grid grid-cols-5 p-4 print:p-0 text-sm">
                                <div class="col-span-2">
                                    <p class="font-bold">PPN</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{number_format($data->ppn,2)}}</p>
                                </div>
                            </div>
                        </div>

                        @endif

                        <div>
                            <div class="grid grid-cols-5 p-4 print:p-0 text-sm">
                                <div class="col-span-2">
                                    <p class="font-bold">Items</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->total_items}}</p>
                                </div>
                            </div>
                        </div>


                        @if($data->user)

                        <div>
                            <div class="grid grid-cols-5 p-4 print:p-0 text-sm">
                                <div class="col-span-2">
                                    <p class="font-bold">User</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->user->username}}</p>
                                </div>
                            </div>
                        </div>

                        @endif

                        

                     
                        
                    </div>
                </div>
            </div>

        </div>

       
       

    </div>

    <section class="bg-gray-50 dark:bg-gray-900 mb-8">
        <div class="">
            <div class="relative overflow-hidden bg-white print:shadow-none shadow-md dark:bg-gray-800 sm:rounded-lg">
                <div class="print:hidden flex  px-4 py-3  flex-row items-center justify-between ">
                    <div class="flex items-center flex-1 space-x-4">
                        <button onClick="window.print()" type="button" class="flex items-center justify-center flex-shrink-0 px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            <svg  class="w-4 h-4 mr-2"  aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M8 3a2 2 0 0 0-2 2v3h12V5a2 2 0 0 0-2-2H8Zm-3 7a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h1v-4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v4h1a2 2 0 0 0 2-2v-5a2 2 0 0 0-2-2H5Zm4 11a1 1 0 0 1-1-1v-4h8v4a1 1 0 0 1-1 1H9Z" clip-rule="evenodd"/>
                            </svg>
                            Print
                        </button>
                        <a href="{{route('transaction.getDetail',[$data->id,'receipt'=>1])}}" class="flex items-center justify-center flex-shrink-0 px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            <svg class="w-4 h-4 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M5.617 2.076a1 1 0 0 1 1.09.217L8 3.586l1.293-1.293a1 1 0 0 1 1.414 0L12 3.586l1.293-1.293a1 1 0 0 1 1.414 0L16 3.586l1.293-1.293A1 1 0 0 1 19 3v18a1 1 0 0 1-1.707.707L16 20.414l-1.293 1.293a1 1 0 0 1-1.414 0L12 20.414l-1.293 1.293a1 1 0 0 1-1.414 0L8 20.414l-1.293 1.293A1 1 0 0 1 5 21V3a1 1 0 0 1 .617-.924ZM9 7a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2H9Zm0 4a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2H9Zm0 4a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2H9Z" clip-rule="evenodd"/>
                              </svg>
                              
                           
                            Receipt
                        </a>
                    </div>
                    <div class="flex items-center  justify-end">

                        @if ($detailC == 0)

                            <form action="{{route('transaction.poSuccess',$data->id)}}" method="post">

                                @csrf
                                @method('PATCH')

                                <button type="submit" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 me-2  dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Success</button>

                            </form>
                            
                        @endif

                        <a href="{{ route('transaction.poMove',$data->id) }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 me-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                           Move
                        </a>


                        <button type="button" id="deleteButton" data-modal-target="deleteModal" data-modal-toggle="deleteModal" class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 rounded-lg">
                            

                            <svg class="h-3.5 w-3.5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                            </svg>
                              
                           Delete
                        </button>

                      
                        
                        <!-- Main modal -->
                        <div id="deleteModal" tabindex="-1" aria-hidden="true" class=" hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
                            <div class="relative p-4 w-full max-w-md h-full md:h-auto">
                                <!-- Modal content -->
                                <div class="relative p-4 mt-40 md:mt-0 text-center bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                                    <button type="button" class="text-gray-400 absolute top-2.5 right-2.5 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="deleteModal">
                                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                    <svg class="text-gray-400 dark:text-gray-500 w-11 h-11 mb-3.5 mx-auto" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    <p class=" text-gray-500 dark:text-gray-300">Are you sure you want to delete this item?</p>
                                  
                                        <div class="flex justify-center space-x-2 mt-4">
                                            
                                            <button data-modal-toggle="deleteModal" type="button" class=" inline-block py-2 px-3 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                                No, cancel
                                            </button>

                                            <form action="{{route('transaction.podelete',$data->id)}}" method="post">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="inline-block py-2 px-3 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                                                    Yes, I'm sure
                                                </button>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                   
                        
                    
                </div>

                <div class="print:hidden flex flex-wrap px-4 py-3 space-y-2  items-center  lg:space-y-0 ">
                    <div class="w-full md:w-auto md:mr-6">
                        <p class="me-2">Show:</p>
                    </div>

                    <div class="mr-4">
                        <div class="flex items-center ">
                            <input checked id="image-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="image-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Image</label>
                        </div>
                    </div>

                    <div class="mr-4">
                        <div class="flex items-center ">
                            <input checked id="barcode-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="barcode-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Barcode</label>
                        </div>
                    </div>

                    <div class="mr-4">
                        <div class="flex items-center ">
                            <input id="sku-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="sku-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">SKU</label>
                        </div>
                    </div>

                    <div class="mr-4">
                        <div class="flex items-center ">
                            <input id="wh-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="wh-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Warehouse Stok</label>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full print:table-fixed text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs print:text-[10px] text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="image-col  px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Image</th>
                                <th scope="col" class="barcode-col  px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Barcode</th>
                                <th scope="col" class="sku-col hidden px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">SKU</th>
                                <th scope="col" class="px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Code</th>
                                <th scope="col" class="px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Name</th>
                                <th scope="col" class="px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Desc</th>
                                <th scope="col" class="sku-col hidden px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">NB</th>
                                <th scope="col" class="px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Quantity</th>
                                <th scope="col" class="px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Available</th>
                                <th scope="col" class="px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Price</th>
                                <th scope="col" class="px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Sub-Total</th>
                                <th scope="col" class="px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Status</th>
                                @foreach ($nameWh as $wh)
                                    <th scope="col" class=" wh-col hidden px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">{{$wh}}</th>
                                @endforeach
                               
                                
                            </tr>
                        </thead>
                        <tbody  id="accordion-collapse" data-accordion="collapse" class="print:text-[10px]">

                            @forelse ($data->transactionDetail as $itemTd)
                            @php
                               
                                $idItem = $itemTd->item?->id ?? '';
                                $url = $itemTd->item?->getImageUrl() ?? '';
                                
                             
                            @endphp
                            
                           
                            <tr class="border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                           
                                <th scope="row" id="" class="image-col  px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class=" mr-3">
                                        <img 
                                            src="{{ $url }}" 
                                            alt="Gambar" 
                                            class="w-20 h-auto print:w-10 print:h-auto object-contain rounded" 
                                            onerror="this.onerror=null; this.src='{{ asset('img/noimg.jpg') }}';"
                                        />
                                    </div>

                                </th>

                                @isset($itemTd->item)

                                  <td class="barcode-col  px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 print:whitespace-normal print:break-words  whitespace-nowrap dark:text-white">
                                    {{$itemTd->item->id ?? ''}}
                                </td>

                                <td class="sku-col hidden px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 print:whitespace-normal print:break-words  whitespace-nowrap dark:text-white">
                                    <a href="{{ $itemTd->item->getLink() ?? '' }}"></a>{{$itemTd->item->code ?? ''}}</a>
                                </td>


                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 print:whitespace-normal print:break-words  whitespace-nowrap dark:text-white">
                                    {{$itemTd->item->getItemCode() ?? ''}}
                                </td>
                               
                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 print:whitespace-normal print:break-words  whitespace-normal max-w-40 dark:text-white">
                                    <p class="min-w-40 print:min-w-0 print:whitespace-normal print:break-words ">{{$itemTd->item->getItemName() ?? ''}}</p>
                                </td>

                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900  whitespace-normal dark:text-white">
                                    <p class="min-w-40 print:min-w-0  print:whitespace-normal print:break-words ">{{$itemTd->item->group? $itemTd->item->group->description : $itemTd->item->description}}</p>
                                    
                                </td>
                                <td class="sku-col hidden px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{$itemTd->description2}}
                                </td>

                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{$itemTd->quantity}}
                                </td>

                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    
                                    @if ($itemTd->available_quantity > 0)

                                        {{$itemTd->available_quantity}}

                                    @else

                                        @if ($itemTd->status == 1)

                                              <!-- Modal toggle -->
                                            <button data-modal-target="invoice-model-{{$itemTd->id}}" data-modal-toggle="invoice-model-{{$itemTd->id}}" class="text-blue-500 hover:text-blue-600 hover:underline  font-medium " type="button">
                                               Update
                                             </button>
                                             
                                             <!-- Main modal -->
                                             <div id="invoice-model-{{$itemTd->id}}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                                 <div class="relative p-4 w-full max-w-md max-h-full">
                                                     <!-- Modal content -->
                                                     <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                         <!-- Modal header -->
                                                         <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                                             <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                                 Update <span class="font-bold">{{$itemTd->item->code}}</span>
                                                             </h3>
                                                             <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="invoice-model-{{$itemTd->id}}">
                                                                 <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                                 </svg>
                                                                 <span class="sr-only">Close modal</span>
                                                             </button>
                                                         </div>
 
                                                         <form action="{{route('transaction.PoUpdateItemQty',$itemTd->id)}}" method="post" class="space-y-4" id="form-update">
                                                         <!-- Modal body -->
                                                         @csrf
 
                                                         @method('PATCH')
 
                                                         <div class="p-4 md:p-5">
                                                             
                                                             <div>
                                                                 <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Available Quantity</label>
                                                                 <input  type="number"  name="qty" value="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >
                                                             </div>
 
                                                             
                                                                 
                                                                 
                                                                 
                                                         
                                                         </div>

                                                        </form>

                                                        <div class="flex justify-between p-4 md:p-5 border-t rounded-b border-gray-200  dark:border-gray-600">

                                                            <div>
                                                                <div class="flex items-center  ">
                                                                    <button id="btn-update" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                                                                    <button data-modal-hide="invoice-model-{{$itemTd->id}}" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Decline</button>
                                                                </div>

                                                            </div>

                                                        

                                                            <div>

                                                                <button id="btn-kosong" class="px-5 py-2.5 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                                                                    Kosong
                                                                </button>

                                                                

                                                            </div>

                                                        </div>
 
                                                        
 
                                                       
                                                     </div>

                                                     
                                                     <form action="{{route('transaction.updateItemKosong',$itemTd->id)}}" method="post" id="form-kosong">

                                                        @csrf
                                                        @method('PATCH')

                                                       
            
                                                       
                                                    </form>

                                                 </div>
                                             </div> 
 
                                        
                                        @else
                                            {{$itemTd->available_quantity}}
                                        @endif
                                        
                                    @endif

                                    
                                </td>

                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{Number::format($itemTd->price)}}
                                </td>

                               

                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{Number::format($itemTd->total)}}
                                </td>

                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    @if ($itemTd->status == 1)
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">Pending</span>

                                    @elseif ($itemTd->status == 2)
                                        <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Success</span>

                                    @else

                                        <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Not available
                                        </span>


                                    @endif
                                </td>

                                <x-transaction.warehouse-item  :idItem="$idItem"/>
                                    
                                @endisset

                                @empty($itemTd->item)
                                    
                             

                                <td class="barcode-col  px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 print:whitespace-normal print:break-words  whitespace-nowrap dark:text-white">
                                   
                                </td>

                                <td class="sku-col hidden px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 print:whitespace-normal print:break-words  whitespace-nowrap dark:text-white">
                                    
                                </td>


                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 print:whitespace-normal print:break-words  whitespace-nowrap dark:text-white">
                                    
                                </td>
                               
                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 print:whitespace-normal print:break-words  whitespace-normal max-w-40 dark:text-white">
                                    <p class="min-w-40 print:min-w-0 print:whitespace-normal print:break-words "></p>
                                </td>

                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900  whitespace-normal dark:text-white">
                                    <p class="min-w-40 print:min-w-0  print:whitespace-normal print:break-words "></p>
                                    
                                </td>
                                <td class="sku-col hidden px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    
                                </td>

                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                   
                                </td>

                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    
                                    @if ($itemTd->available_quantity > 0)

                                        {{$itemTd->available_quantity}}

                                    @else

                                        @if ($itemTd->status == 1)

                                              <!-- Modal toggle -->
                                            <button data-modal-target="invoice-model-{{$itemTd->id}}" data-modal-toggle="invoice-model-{{$itemTd->id}}" class="text-blue-500 hover:text-blue-600 hover:underline  font-medium " type="button">
                                               Update
                                             </button>
                                             
                                             <!-- Main modal -->
                                             <div id="invoice-model-{{$itemTd->id}}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                                 <div class="relative p-4 w-full max-w-md max-h-full">
                                                     <!-- Modal content -->
                                                     <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                         <!-- Modal header -->
                                                         <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                                             <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                                 Update <span class="font-bold">{{$itemTd->item->code}}</span>
                                                             </h3>
                                                             <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="invoice-model-{{$itemTd->id}}">
                                                                 <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                                 </svg>
                                                                 <span class="sr-only">Close modal</span>
                                                             </button>
                                                         </div>
 
                                                         <form action="{{route('transaction.PoUpdateItemQty',$itemTd->id)}}" method="post" class="space-y-4" id="form-update">
                                                         <!-- Modal body -->
                                                         @csrf
 
                                                         @method('PATCH')
 
                                                         <div class="p-4 md:p-5">
                                                             
                                                             <div>
                                                                 <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Available Quantity</label>
                                                                 <input  type="number"  name="qty" value="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >
                                                             </div>
 
                                                             
                                                                 
                                                                 
                                                                 
                                                         
                                                         </div>

                                                        </form>

                                                        <div class="flex justify-between p-4 md:p-5 border-t rounded-b border-gray-200  dark:border-gray-600">

                                                            <div>
                                                                <div class="flex items-center  ">
                                                                    <button id="btn-update" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                                                                    <button data-modal-hide="invoice-model-{{$itemTd->id}}" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Decline</button>
                                                                </div>

                                                            </div>

                                                        

                                                            <div>

                                                                <button id="btn-kosong" class="px-5 py-2.5 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                                                                    Kosong
                                                                </button>

                                                                

                                                            </div>

                                                        </div>
 
                                                        
 
                                                       
                                                     </div>

                                                     
                                                     <form action="{{route('transaction.updateItemKosong',$itemTd->id)}}" method="post" id="form-kosong">

                                                        @csrf
                                                        @method('PATCH')

                                                       
            
                                                       
                                                    </form>

                                                 </div>
                                             </div> 
 
                                        
                                        @else
                                            {{$itemTd->available_quantity}}
                                        @endif
                                        
                                    @endif

                                    
                                </td>

                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    
                                </td>

                               

                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                  
                                </td>

                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                  
                                </td>

                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                   
                                </td>

                                @endempty
                               
                            </tr>
                          
                                
                            @empty
                                
                            @endforelse
                            
                            
                        </tbody>
                    </table>
                </div>

                <div class="mt-2 hidden print:block">
                    

                    <div class="grid grid-cols-12">

                        <div class="col-span-4">
                            <p>Yang Mengetahui,</p>
                        </div>

                        <div class="col-span-4">
                            <p>Pemberi,</p>
                        </div>

                        <div class="col-span-4">
                            <p>Penerima,</p>
                        </div>

                    </div>
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


      @push('jsBody')

      <script>

        var formKosong = document.getElementById("form-kosong");

        document.getElementById("btn-kosong").addEventListener("click", function () {
            formKosong.submit();
        });

        var formUpdate = document.getElementById("form-update");

        document.getElementById("btn-update").addEventListener("click", function () {
            formUpdate.submit();
        });

        document.addEventListener('DOMContentLoaded', function() {
            var toggleNamaImage = document.getElementById('image-checkbox');
            var namaColumnImage = document.querySelectorAll('.image-col');
            var toggleNamaBarcode = document.getElementById('barcode-checkbox');
            var namaColumnBarcode = document.querySelectorAll('.barcode-col');
            var toggleNamaSku = document.getElementById('sku-checkbox');
            var namaColumnSku = document.querySelectorAll('.sku-col');
            var toggleNamaWh = document.getElementById('wh-checkbox');
            var namaColumnWh = document.querySelectorAll('.wh-col');

            toggleNamaImage.addEventListener('change', function() {
                if (toggleNamaImage.checked) {
                    namaColumnImage.forEach(function(barcode) {
                        barcode.classList.remove('hidden');
                    });
                } else {
                    namaColumnImage.forEach(function(image) {
                        image.classList.add('hidden');
                    });
                }
            });

            toggleNamaBarcode.addEventListener('change', function() {
                if (toggleNamaBarcode.checked) {
                    namaColumnBarcode.forEach(function(barcode) {
                        barcode.classList.remove('hidden');
                    });
                } else {
                    namaColumnBarcode.forEach(function(barcode) {
                        barcode.classList.add('hidden');
                    });
                }
            });

            toggleNamaSku.addEventListener('change', function() {
                if (toggleNamaSku.checked) {
                    namaColumnSku.forEach(function(sku) {
                        sku.classList.remove('hidden');
                    });
                } else {
                    namaColumnSku.forEach(function(sku) {
                        sku.classList.add('hidden');
                    });
                }
            });

            toggleNamaWh.addEventListener('change', function() {
                if (toggleNamaWh.checked) {
                    namaColumnWh.forEach(function(wh) {
                        wh.classList.remove('hidden');
                    });
                } else {
                    namaColumnWh.forEach(function(wh) {
                        wh.classList.add('hidden');
                    });
                }
            });
        });

        // $('#image-checkbox').change(function(){
        //     if (this.checked) {
        //        console.log('cek')
              
        //        document.getElementById("image-col").classList.remove("hidden");
               
        //     } else {
        //         document.getElementById("image-col").classList.add("hidden");
        //     }
        // });
      </script>
          
      @endpush

</x-layouts.layout>