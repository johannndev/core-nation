<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Get jubelio order</p>

       
    </div>

    <div id="alert-border-2">

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

    @if ($errors->any())


    <div class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
        <svg class="shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <span class="sr-only">Danger</span>
        <div>
            <span class="font-medium">Ensure that these requirements are met:</span>
            <ul class="mt-1.5 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>

    @endif

</div>

   

   

    @if ( !$data)

        <form action="{{route('jubelio.order.storegetall')}}" method="post" >

            @csrf
        

            <section class="bg-gray-50 dark:bg-gray-900 mb-8">
                <div class="mx-auto  ">
                    <!-- Start coding here -->
                    <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-4">

                        <div class="">

                            <div class="grid grid-cols-2 gap-4 mb-8">
                                <div>
                                    <label for="from" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal mulai</label>
                                    <input type="date" name="from" id="from" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="">

                                </div>

                                <div>
                                    <label for="to" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal akhir</label>
                                    <input type="date" name="to" id="to" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="">
                                </div>

                                
                            
                            </div>
    
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mt-4 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Submit</button>

                        </div>
                    </div>
                </div>
            </section>

        </form>

    @else

        <section class="bg-gray-50 dark:bg-gray-900 mb-8">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-4">

                    <div class="grid grid-cols-12">
                        <div class="col-span-10  flex items-center space-x-2">

                            <div>

                                @if ($data->status == 1)

                                 <div class="bg-green-50 p-2 rounded-xl">
                                    <div role="status">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"      stroke="currentColor"class="w-6 h-6 text-green-500  " >
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>

                                    </div>
                                </div>


                                @else

                                 <div class="bg-yellow-50 p-2 rounded-xl">
                                    <div role="status">
                                        <svg aria-hidden="true" class="w-6 h-6 text-yellow-200 animate-spin dark:text-gray-600 fill-yellow-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                        </svg>
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>

                                    
                                @endif
                            
                               
                            </div>
                            <div>
                                <p class="font-medium">Get order di jubelio tanggal {{$data->from}} s/d  {{$data->to}}</p>

                                
                                <div class="flex justify-between mt-1">
                                    <span class="text-sm font-medium text-blue-700 dark:text-white">Item: {{ $data->order_detail_count }}</span>
                                    <span class="text-sm font-medium text-blue-700 dark:text-white">{{ $persentase }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $persentase }}%"></div>
                                </div>


                                {{-- <div class="flex space-x-4 text-sm text-gray-500">
                                    <div class="flex space-x-2">
                                        <p>Page</p>
                                        <p class="text-black font-medium">{{$data->count}}</p>
                                    </div>

                                     <div class="flex space-x-2">
                                        <p>Item</p>
                                        <p class="text-black font-medium">{{ $data->order_detail_count }}</p>
                                    </div>
                                </div> --}}
                            </div>

                        </div>

                        <div class="col-span-2">
                            @if ($data->status == 1)
                                <div class="bg-green-50 font-medium text-green-600 p-2 rounded-lg text-center">
                                    <p>3/3 Finished</p>
                                </div>

                            @else

                                <div class="bg-yellow-50 font-medium text-yellow-600 p-2 rounded-lg text-center">
                                    @if ($data->step == 1)
                                        <p>1/3 Get item...</p>
                                    @elseif ($data->step == 2)
                                        <p>2/3 Analyzing...</p>
                                    @endif
                                   
                                </div>

                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

         @if ($data->status == 1)

            <div class="flex space-x-2 mb-2">

                    <form class="myForm" id="myForm" action="{{ route('jubelio.order.toLog') }}" method="post" >

                        @csrf
                        <x-layout.submit-button label="Generate Cron" />

                        
                    </form>


                   <form class="myForm" id="myForm" action="{{ route('jubelio.order.deleteAll') }}" method="post" >

                        @csrf

                        <x-layout.submit-button label="Hapus" color="red"  />

                        
                   </form>

            </div>

            <section class="bg-gray-50 dark:bg-gray-900 mb-8">
                <div class="mx-auto  ">
                    <!-- Start coding here -->
                    <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-4">

                        
                    <div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">Order ID</th>

                                        <th scope="col" class="px-4 py-3">Invoice</th>

                                        <th scope="col" class="px-4 py-3">Location</th>

                                        <th scope="col" class="px-4 py-3">Store Name</th>

                                        <th scope="col" class="px-4 py-3">Status</th>

                                        <th scope="col" class="px-4 py-3">Is Canceled</th>

                                        <th scope="col" class="px-4 py-3">Transaksi</th>

                                        <th scope="col" class="px-4 py-3">Log Jubelio</th>

                                        {{-- <th scope="col" class="px-4 py-3">Customer</th>

                                        <th scope="col" class="px-4 py-3">Bin</th>
                               
                                        <th scope="col" class="px-4 py-3">Actions</th> --}}
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ( $dataList as $item)
                                        
                                  
                    
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                                       
                                        <td scope="row" class="px-4 py-3  whitespace-nowrap ">
                                             <a target="_blank" href="https://v2.jubelio.com/sales/transactions/orders/detail/{{$item->order_id}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"> {{$item->order_id}}</a>
                                           
                    
                                        </td>

                                        <td scope="row" class="px-4 py-3  whitespace-nowrap ">
                                            
                                            {{$item->invoice}}
                    
                                        </td>

                                        <td scope="row" class="px-4 py-3  whitespace-nowrap ">
                                            
                                            {{$item->location_id}}
                    
                                        </td>

                                        <td scope="row" class="px-4 py-3  whitespace-nowrap ">
                                            
                                            {{$item->store_id}}
                    
                                        </td>

                                        
                                        <td scope="row" class="px-4 py-3  whitespace-nowrap ">
                                            
                                            {{$item->status}}
                    
                                        </td>

                                        <td scope="row" class="px-4 py-3  whitespace-nowrap ">
                                            
                                           @if ($item->is_canceled == 'Y')
                                                <span class="inline-flex items-center justify-center w-6 h-6 me-2 text-sm font-semibold text-green-800 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-300">
                                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                                                    </svg>

                                                    <span class="sr-only">Icon description</span>
                                                </span>
                                               
                                           @else

                                                <span class="inline-flex items-center justify-center w-6 h-6 me-2 text-sm font-semibold text-red-800 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                                    </svg>

                                                    
                                                    <span class="sr-only">Icon description</span>
                                                </span>
                                                
                                               
                                           @endif
                    
                                        </td>

                                         <td scope="row" class="px-4 py-3  whitespace-nowrap ">
                                            
                                            @isset($item->transaksi)
                                                <span class="inline-flex items-center justify-center w-6 h-6 me-2 text-sm font-semibold text-green-800 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-300">
                                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                                                    </svg>

                                                    <span class="sr-only">Icon description</span>
                                                </span>
                                            @endisset
                                        

                                            @empty($item->transaksi)

                                                <span class="inline-flex items-center justify-center w-6 h-6 me-2 text-sm font-semibold text-red-800 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                                    </svg>

                                                    
                                                    <span class="sr-only">Icon description</span>
                                                </span>
                                                
                                            @endempty
                    
                                        </td>

                                         <td scope="row" class="px-4 py-3  whitespace-nowrap ">
                                            
                                            @isset($item->logJubelio)
                                                <span class="inline-flex items-center justify-center w-6 h-6 me-2 text-sm font-semibold text-green-800 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-300">
                                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                                                    </svg>

                                                    <span class="sr-only">Icon description</span>
                                                </span>
                                            @endisset
                                        

                                            @empty($item->logJubelio)

                                                <span class="inline-flex items-center justify-center w-6 h-6 me-2 text-sm font-semibold text-red-800 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                                    </svg>

                                                    
                                                    <span class="sr-only">Icon description</span>
                                                </span>
                                                
                                            @endempty
                    
                                        </td>

                                        {{-- <td scope="row" class="px-4 py-3  whitespace-nowrap ">
                                            
                                            @isset($item->customer)
                                                <a href="" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$item->customer->name}}</a>
                    
                                            @endisset
                                          
                                        </td>

                                        
                                        <td scope="row" class="px-4 py-3  whitespace-nowrap ">
                                            
                                            {{$item->bin_id}}
                    
                                        </td>
                                        
                                     
                                        <td class="px-4 py-3 flex">

                                            

                                            <a href="{{route('jubelio.sync.getBin',$item->id)}}" class=" items-center justify-center text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 me-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-primary-800">
                                                Set Bin
                                            </a>

                                            
                                            <a href="{{route('jubelio.sync.edit',$item->id)}}" class=" items-center justify-center text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 me-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-primary-800">
                                                Edit
                                            </a>

                                            <form action="{{route('jubelio.sync.delete',$item->id)}}" method="post">

                                                @csrf

                                                @method('DELETE')
                                              

                                                <button type="submit" class=" items-center justify-center text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800">
                                                   Delete
                                                    
                                                </button>

                                            </form>

                                        </td> --}}
                                        
                                        
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

           
            

         @endif
        
    @endif

  


    @push('jsBody')

     

    @endpush
   


</x-layouts.layout>