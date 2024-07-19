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
                    <p class="font-bold">{{$data->receiver ? $data->receiver->name : '' }}</p>
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
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="grid grid-cols-1 divide-y">
                        <div>
                            <div class="grid grid-cols-5 p-4">
                                <div class="col-span-2">
                                    <p class="font-bold">From</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->sender->name}}</p>
                                </div>
                            </div>
                        </div>
                       
                        @if ($data->type != 8)

                        <div>
                            <div class="grid grid-cols-5 p-4">
                                <div class="col-span-2">
                                    <p class="font-bold">To</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->receiver ? $data->receiver->name : '' }}</p>
                                </div>
                            </div>
                        </div>
                            
                        @endif
                        

                        @if ($data->description)

                        <div>
                            <div class="grid grid-cols-5 p-4">
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
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="grid grid-cols-1 divide-y">
                        <div>
                            <div class="grid grid-cols-5 p-4">
                                <div class="col-span-2">
                                    <p class="font-bold">Date</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{\Carbon\Carbon::parse($data->date)->format('d/m/Y')}}</p>
                                </div>
                            </div>
                        </div>
                       
                        <div>
                            <div class="grid grid-cols-5 p-4">
                                <div class="col-span-2">
                                    <p class="font-bold">Due</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->due == "0000-00-00" ? "-" : \Carbon\Carbon::parse($data->due)->format('d/m/Y')}}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="grid grid-cols-5 p-4">
                                <div class="col-span-2">
                                    <p class="font-bold">Invoice Discount</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->discount}}%</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="grid grid-cols-5 p-4">
                                <div class="col-span-2">
                                    <p class="font-bold">Adjustment</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{number_format($data->adjustment,2)}}</p>
                                </div>
                            </div>
                        </div>

                        @if($data->ppn > 0)

                        <div>
                            <div class="grid grid-cols-5 p-4">
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
                            <div class="grid grid-cols-5 p-4">
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
                            <div class="grid grid-cols-5 p-4">
                                <div class="col-span-2">
                                    <p class="font-bold">User</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->user->username}}</p>
                                </div>
                            </div>
                        </div>

                        @endif

                        <div>
                            <div class="grid grid-cols-5 p-4">
                                <div class="col-span-2">
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

                <div class="print:hidden  flex flex-wrap px-4 py-3 space-y-2  items-center  lg:space-y-0">
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
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="image-col  px-4 py-3">Image</th>
                                <th scope="col" class="barcode-col  px-4 py-3">Barcode</th>
                                <th scope="col" class="sku-col hidden px-4 py-3">SKU</th>
                                <th scope="col" class="px-4 py-3">Code</th>
                                <th scope="col" class="px-4 py-3">Name</th>
                                <th scope="col" class="px-4 py-3">Desc</th>
                                <th scope="col" class="sku-col hidden px-4 py-3">NB</th>
                                <th scope="col" class="px-4 py-3">Quantity</th>
                                <th scope="col" class="px-4 py-3">Price</th>
                                <th scope="col" class="px-4 py-3">Discount(%)</th>
                                <th scope="col" class="px-4 py-3">Sub-Total</th>
                                @foreach ($nameWh as $wh)
                                    <th scope="col" class=" wh-col hidden px-4 py-3">{{$wh}}</th>
                                @endforeach
                               
                                
                            </tr>
                        </thead>
                        <tbody  id="accordion-collapse" data-accordion="collapse">

                            @forelse ($data->transactionDetail as $itemTd)
                            @php
                              $idItem = $itemTd->item->id;
                              $url = $itemTd->item->getImageUrl();
                            @endphp
                            
                           
                            <tr class="border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                           
                                <th scope="row" id="" class="image-col  px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="h-20 w-20 mr-3">
                                       
                                        <x-partial.image type="h-20 w-20 print:h-10 print:w-10" :url="$url" />
                                    </div>

                                </th>

                                <td class="barcode-col  px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{$itemTd->item->id}}
                                </td>

                                <td class="sku-col hidden px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{$itemTd->item->code}}
                                </td>


                                <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{$itemTd->item->getItemCode()}}
                                </td>
                               
                                <td class="px-4 py-2 font-medium text-gray-900 whitespace-normal max-w-40 dark:text-white">
                                    <p class="min-w-40">{{$itemTd->item->getItemName()}}</p>
                                </td>

                                <td class="px-4 py-2 font-medium text-gray-900 whitespace-normal dark:text-white">
                                    <p class="min-w-40">{{$itemTd->item->group? $itemTd->item->group->description : $itemTd->item->description}}</p>
                                    
                                </td>
                                <td class="sku-col hidden px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{$itemTd->description2}}
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

                                <x-transaction.warehouse-item  :idItem="$idItem"/>
                               
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

      @push('jsBody')

      <script>
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