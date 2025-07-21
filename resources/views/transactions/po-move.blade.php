<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">PO Move Transaction #{{$data->id}}</p>

       
    </div>

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
            
    @endif

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

                       <button type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" onclick="submitBatchDelete()">Hapus Terpilih</button>


                        <form action="{{route('transaction.poMovePost',['id'=>$data->id,'sender'=>Request('sender'),'recaiver'=>Request('recaiver')])}}" method="post">

                            @csrf
                            

                            <button type="submit" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 me-2  dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Submit</button>

                        </form>
                            

                    </div>
                   
                        
                    
                </div>

              
                <form action="{{ route('transaction.poUpdateQty',$data->id) }}" method="post">

                    @csrf

                    @method('PATCH')

                    <div class="overflow-x-auto">
                        <table class="w-full print:table-fixed text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs print:text-[10px] text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th></th>
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

                                @forelse ($data->transactionDetail as $index => $itemTd)
                                @php
                                     $idItem = $itemTd->item?->id ?? '';
                                    $url = $itemTd->item?->getImageUrl() ?? '';
                                @endphp
                                
                            
                                <tr class="border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    @if($itemTd->item)
                                        <td class="pl-4 py-2">
                                            <input  value="{{ $itemTd->id }}" id="checked-checkbox" type="checkbox" value="" class="produk-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        </td>
                                
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
                                            {{-- {{$itemTd->quantity}} --}}
                                            <input type="text" name="detail[{{ $index }}][id]" value="{{ $itemTd->id }}" hidden>
                                            <div class="relative flex items-center">
                                                <button type="button" id="decrement-button" data-input-counter-decrement="counter-input-{{ $itemTd->id }}" class="shrink-0 bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 inline-flex items-center justify-center border border-gray-300 rounded-md h-5 w-5 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none">
                                                    <svg class="w-2.5 h-2.5 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16"/>
                                                    </svg>
                                                </button>
                                                
                                                <input type="text" name="detail[{{ $index }}][quantity]" data-input-counter data-input-counter-min="1" id="counter-input-{{ $itemTd->id }}" data-input-counter class="shrink-0 text-gray-900 dark:text-white border-0 bg-transparent text-sm font-normal focus:outline-none focus:ring-0 max-w-[2.5rem] text-center" placeholder="" value="{{$itemTd->quantity}}"  required />
                                                <button type="button" id="increment-button" data-input-counter-increment="counter-input-{{ $itemTd->id }}" class="shrink-0 bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 inline-flex items-center justify-center border border-gray-300 rounded-md h-5 w-5 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none">
                                                    <svg class="w-2.5 h-2.5 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
                                                    </svg>
                                                </button>
                                            </div>

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

                                    @else
                                        <td class="pl-4 py-2">
                                          
                                        </td>

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

                                    

                                        <td class="barcode-col  px-4 py-2 print:px-0 print:py-0 font-medium text-red-500 print:whitespace-normal print:break-words  whitespace-nowrap dark:text-white">
                                            {{$itemTd->item_id }}
                                        </td>


                                        <td colspan="20" class="barcode-col  px-4 py-2 print:px-0 print:py-0 font-medium text-red-500 print:whitespace-normal print:break-words  whitespace-nowrap dark:text-white">Item tidak ada</td>
                                        
                                    @endif

                

                                
                                </tr>
                            
                                    
                                @empty
                                    
                                @endforelse
                                
                                
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4">
                        <button type="submit" class="text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800">Update</button>
                    </div>
                </form>

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
            function submitBatchDelete() {
               
                let ids = [];

                $('.produk-checkbox:checked').each(function() {
                    ids.push(parseInt($(this).val()));
                });

                console.log(ids); // hasil: [1, 2, 4, 6]

                if (ids.length === 0) {
                    alert('Pilih produk yang ingin dihapus.');
                    return;
                }

                if (!confirm('Yakin ingin menghapus produk terpilih?')) {
                    return;
                }

                $.ajax({
                    url: '{{ route("transaction.poBatchDelete") }}',
                    type: 'POST',
                    data:  {
                        item_id: ids,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function(response) {
                        alert(response.message);
                        location.reload();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('Terjadi kesalahan saat menghapus.');
                    }
                });
            }
            </script>
        
          
      @endpush

</x-layouts.layout>