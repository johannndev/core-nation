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
                    <p class="text-sm text-gray-500">Invoice #{{$data->invoice}}</p>
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
                        @isset($data->sender)

                        <div>
                            <div class="grid grid-cols-5 p-4 print:p-0 text-sm">
                                <div class="col-span-2">
                                    <p class="font-bold">From</p>
                                </div>
                                <div class="col-span-3">
                                    <a href="{{$data->sender->getDetailLink()}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$data->sender->name}}</a>
                                </div>
                            </div>
                        </div>

                        @endisset
                       
                        @if ($data->type != 8)

                        <div>
                            <div class="grid grid-cols-5 p-4 print:p-0 text-sm">
                                <div class="col-span-2">
                                    <p class="font-bold">To</p>
                                </div>
                                <div class="col-span-3">
                                  @isset($data->receiver)
                                    <a href="{{$data->receiver->getDetailLink()}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$data->receiver->name}}</a>
                                  @endisset
                                </div>
                            </div>
                        </div>
                            
                        @endif
                        

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

         

        </div>

       
       

    </div>

    <section class="bg-gray-50 dark:bg-gray-900 mb-8">
        <div class="">
            <div class="relative overflow-hidden bg-white print:shadow-none shadow-md dark:bg-gray-800 sm:rounded-lg">
           
                <div class="overflow-x-auto">
                    <table class="w-full print:table-fixed text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs print:text-[10px] text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="image-col  px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Image</th>
                                <th scope="col" class="barcode-col  px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Barcode</th>
                                <th scope="col" class="px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Code</th>
                                <th scope="col" class="px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Quantity</th>
                                <th scope="col" class="px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Jubelio</th>
                               
                               
                                
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
                                        <img 
                                            src="{{ $url }}" 
                                            alt="Gambar" 
                                            class="w-20 h-auto print:w-10 print:h-auto object-contain rounded" 
                                            onerror="this.onerror=null; this.src='{{ asset('img/noimg.jpg') }}';"
                                        />
                                   

                                      
                                    </div>

                                </th>

                                <td class="barcode-col  px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 print:whitespace-normal print:break-words  whitespace-nowrap dark:text-white">
                                  <a href="{{route('item.detail',$itemTd->item->id)}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$itemTd->item->id}}</a>
                                </td>

                            


                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 print:whitespace-normal print:break-words  whitespace-nowrap dark:text-white">
                                    {{$itemTd->item->getItemCode()}}
                                </td>
                               
                               

                               
                              

                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{$itemTd->quantity}}
                                </td>

                               

                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">

                                    @php
                                        $status = $itemTd->item->jubelio_item_id ?? null;
                                    @endphp
                                    
                                    @if (is_null($status))
                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-gray-300">Belum Cek</span>

                                    @elseif ($status === 0)
                                        <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-red-900 dark:text-red-300">Tidak Ada</span>
                                    @elseif ($status > 0)
                                        <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-green-900 dark:text-green-300">{{ $status }}</span>
                                    @endif
                                    


                                
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

    @if ($data->item_with_jubelio_count == 0)

        @if ($adJustTypeA > 0)
         
            <div id="alert-additional-content-1" class="p-4 mb-4 text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800" role="alert">
                    
                <div class="mt-2 mb-4 text-sm">
                    <p>
                        Adjustmen stok sebanyak 
                        <span class="font-bold {{ $adJustTypeA == 2 ? 'text-red-500' : 'text-green-500' }}">
                            {{ $adJustTypeA == 2 ? '-' : '+' }}{{ $data->total_items }}
                        </span> 
                        pada warehouse 
                        <span class="font-bold">{{ $JubelioA }}</span> 
                        di jubelio
                        @if ($data->a_submit_by)
                            , Disubmit oleh <span class="font-bold">{{ $data->submitByA->name }}</span>
                        @endif
                    </p>
                </div>

                @empty($data->a_submit_by)

                    <form class="myForm  " action="{{ route('jubelio.adjustStok',['id' => $data->id, 'whType' => $whA, 'adjustType' => $adJustTypeA,'side' => 1]) }}" method="post">

                        @csrf

                        <x-layout.submit-button />
            
                    </form>
                        
                @endempty

              
                
            </div>
       
        @endif

        @if ($adJustTypeB > 0)

        <div id="alert-additional-content-1" class="p-4 mb-4 text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800" role="alert">
                    
            <div class="mt-2 mb-4 text-sm">
                <p>
                    Adjustmen stok sebanyak 
                    <span class="font-bold {{ $adJustTypeB == 2 ? 'text-red-500' : 'text-green-500' }}">
                        {{ $adJustTypeB == 2 ? '-' : '+' }}{{ $data->total_items }}
                    </span> 
                    pada warehouse 
                    <span class="font-bold">{{ $JubelioB }}</span> 
                    di jubelio
                    @if ($data->b_submit_by)
                        , Disubmit oleh <span class="font-bold">{{ $data->submitByB->name }}</span>
                    @endif
                </p>
            </div>

            @empty($data->b_submit_by)

            <form class="myForm  " action="{{ route('jubelio.adjustStok',['id' => $data->id, 'whType' => $whB, 'adjustType' => $adJustTypeB,'side' => 2]) }}" method="post">

                @csrf

                <x-layout.submit-button />
    
            </form>

            @endempty

            
        </div>


        @endif
        


    @endif

      


      @push('jsBody')

      <script>
     
      </script>
          
      @endpush

</x-layouts.layout>