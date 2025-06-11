<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">PO Move Transaction #{{$data->id}}</p>

       
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
    
    <form class="myForm" id="myForm" action="" method="get" >


          <section class="bg-gray-50 dark:bg-gray-900 mb-8">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-4">

                    <div class="">

                        <div class="grid grid-cols-2 gap-4 mb-8">

                            <div>
                                <x-partial.select-addr :dataProp='$dataListPropSender' />
                            </div>
                          
                            <div>
                                 <x-partial.select-addr :dataProp='$dataListPropRecaiver' />
                            </div>
                           
                        </div>

                        <x-layout.submit-button label="Cek" />


                    </div>
                </div>
            </div>
        </section>

    </form>

    @if (Request('sender') && Request('recaiver'))
        
   

    <section class="bg-gray-50 dark:bg-gray-900 mb-8">
        <div class="">
            <div class="relative overflow-hidden bg-white print:shadow-none shadow-md dark:bg-gray-800 sm:rounded-lg">
                <div class="print:hidden flex  px-4 py-3  flex-row items-center justify-between ">
                    <div class="flex items-center flex-1 space-x-4">
                        
                       
                    </div>
                    <div class="flex items-center  justify-end">

                       

                        <form action="{{route('transaction.poMovePost',['id'=>$data->id,'sender'=>Request('sender'),'recaiver'=>Request('recaiver')])}}" method="post">

                            @csrf
                            

                            <button type="submit" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 me-2  dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Submit</button>

                        </form>
                            

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
                                <th scope="col" class="px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Quantity</th>
                                <th scope="col" class="px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Available</th>
                                <th scope="col" class="px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Price</th>
                                <th scope="col" class="px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Sub-Total</th>
                            
                              
                               
                                
                            </tr>
                        </thead>
                        <tbody  id="accordion-collapse" data-accordion="collapse" class="print:text-[10px]">

                            @forelse ($data->transactionDetail as $itemTd)
                            @php
                                $idItem = $itemTd->item->id;
                                $url = $itemTd->item->getImageUrl();
                            @endphp
                            
                           
                            <tr class="border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                           
                                <th scope="row" id="" class="image-col  px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class=" mr-3">
                                        <x-partial.image type="h-20 w-20 print:h-10 print:w-10" :url="$url" />
                                    </div>

                                </th>

                                <td class="barcode-col  px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 print:whitespace-normal print:break-words  whitespace-nowrap dark:text-white">
                                    {{$itemTd->item->id}}
                                </td>

                                <td class="sku-col hidden px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 print:whitespace-normal print:break-words  whitespace-nowrap dark:text-white">
                                    <a href="{{ $itemTd->item->getLink() }}"></a>{{$itemTd->item->code}}</a>
                                </td>


                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 print:whitespace-normal print:break-words  whitespace-nowrap dark:text-white">
                                    {{$itemTd->item->getItemCode()}}
                                </td>
                               
                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 print:whitespace-normal print:break-words  whitespace-normal max-w-40 dark:text-white">
                                    <p class="min-w-40 print:min-w-0 print:whitespace-normal print:break-words ">{{$itemTd->item->getItemName()}}</p>
                                </td>


                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{$itemTd->quantity}}
                                </td>

                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    
                                    @if (array_key_exists($itemTd->item_id, $whItem))

                                    {{ $whItem[$itemTd->item_id] }}
 
                                    @else

                                    N/A
                                        
                                    @endif
                                   
                                   
                                    
                                </td>

                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{Number::format($itemTd->price)}}
                                </td>

                               

                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{Number::format($itemTd->total)}}
                                </td>

             

                               
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
             
            </div>
        </div>
    </section>

     @endif


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