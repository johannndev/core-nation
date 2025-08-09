<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Barang sudah setor</p>

       
    </div>

          @if ((session('errorMessage')))
          <div id="alert-2" class="flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">Info</span>
            <div class="ms-3 text-sm font-medium">
              {{session('errorMessage')}}
            </div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-2" aria-label="Close">
              <span class="sr-only">Close</span>
              <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
              </svg>
            </button>
          </div>
          
      @endif


    <div class="mb-8">


        <section class="bg-gray-50 dark:bg-gray-900 ">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                   

                    <div class="flex justify-end p-4">
                        <button type="submit" class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800" data-modal-target="filterModal" data-modal-toggle="filterModal">
                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="h-4 w-4 mr-2 " viewbox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                            </svg>
                            Filter
                        </button>

                    </div>

                    <form action="{{route('filter.get',['action' =>'setoran.index'])}}" method="post" id="filterModal" tabindex="-1" aria-hidden="true"
                        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">

                        @csrf

                        <div class="relative w-full h-full max-w-xl md:h-auto">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                                <!-- Modal header -->
                                <div class="flex items-start justify-between px-6 py-4 rounded-t">
                                    <h3 class="text-lg font-normal text-gray-500 dark:text-gray-400">
                                        Filter barang sudah setor
                                    </h3>
                                    <button type="button"
                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                        data-modal-toggle="filterModal">
                                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <div class="px-4 md:px-6 mb-6">
                                    <div class="col-span-12">
                                        <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date</label>
                                    </div>

                                    <div class="grid grid-cols-12 gap-2  ">
                                    
                                        <div class="col-span-5">
                                            <div>
                                               
                                                <input type="date" id="from" name="from" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('from')}}"/>
                                            </div>
                                        </div>
    
                                        <div class="col-span-2">
                                            <div class="flex items-center justify-center h-full">
                                                <p class="font-medium text-gray-500">to</p>
                                            </div>
                                        </div>
    
                                        <div class="col-span-5">
                                            <div>
                                                
                                                <input type="date" id="to" name="to" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('to')}}"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              
                                <div class="grid grid-cols-2 gap-2 px-4 md:px-6 md:grid-cols-3">

                                    <div>
                                        <label for="potong" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Potong Worker</label>
                                        <select id="potong" name="potong_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="" >Choose</option>
                                           
                                            @foreach ($potongList as $item)
                                                <option {{Request('potong_id') == $item->id ? "selected" : ""}} value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                           
                                          </select>
                                    </div>

                                    <div>
                                        <label for="jahit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jahit Worker</label>
                                        <select id="jahit" name="jahit_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="">Choose</option>
                                           
                                            @foreach ($jahitList as $item)
                                                <option {{Request('jahit_id') == $item->id ? "selected" : ""}} value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                           
                                          </select>
                                    </div>

                                    <div>
                                        <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                        <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="">Choose</option>
                                           
                                            @foreach ($statusList as $item)
                                                <option {{Request('status') == $item['id'] ? "selected" : ""}} value="{{$item['id']}}">{{$item['name']}}</option>
                                            @endforeach
                                           
                                          </select>
                                    </div>

                                   
                                    <div>
                                        <label for="serial" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Serial</label>
                                        <input type="text" id="serial" name="serial" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('serial')}}"/>
                                    </div>

                                    <div>
                                        <label for="kode" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode</label>
                                        <input type="text" id="kode" name="kode" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('kode')}}"/>
                                    </div>

                                    <div>
                                        <label for="surat_jalan_potong" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">SJP</label>
                                        <input type="text" id="surat_jalan_potong" name="surat_jalan_potong" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('surat_jalan_potong')}}"/>
                                    </div>

                                    <div>
                                        <label for="warna" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Warna</label>
                                        <input type="text" id="warna" name="warna" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('warna')}}"/>
                                    </div>

                                    <div>
                                        <label for="customer" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Customer</label>
                                        <input type="text" id="customer" name="customer" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('customer')}}"/>
                                    </div>

                                    <div>
                                        <label for="invoice" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Invoice</label>
                                        <input type="text" id="invoice" name="invoice" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('invoice')}}"/>
                                    </div>
                                    
                                    
                                </div>
                                <!-- Modal footer -->
                                <div class="flex items-center p-6 space-x-4 rounded-b dark:border-gray-600">
                                    <button type="submit"
                                        class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-700 dark:hover:bg-primary-800 dark:focus:ring-primary-800">
                                        Apply
                                    </button>
                                    <a href="{{route('setoran.index')}}"
                                        class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Serial</th>
                                    <th scope="col" class="px-4 py-3 w-40">Kode</th>
                                    <th scope="col" class="px-4 py-3">Potong</th>
                                    <th scope="col" class="px-4 py-3">SJP</th>
                                    <th scope="col" class="px-4 py-3">Jumlah</th>
                                    <th scope="col" class="px-4 py-3">Size</th>
                                    <th scope="col" class="px-4 py-3">Warna</th>
                                    <th scope="col" class="px-4 py-3">Costumer</th>
                                    <th scope="col" class="px-4 py-3">Jahit</th>
                                     <th scope="col" class="px-4 py-3 ">QC</th>
                                    <th scope="col" class="px-4 py-3 w-40">Invoice</th>
                                    {{-- <th scope="col" class="px-4 py-3"></th> --}}
                                    
                                </tr>
                            </thead>
                            <tbody class="">
                                @forelse ( $produksi as $row)
                                    
                                

                                <tr class="border-b dark:border-gray-700  @if ($row->status == $sg)bg-[#b2ebf2] @elseif($row->status == $sb)bg-[#c5e1a5] @else hover:bg-gray-100 @endif">
                                    <th scope="row" class="px-4 py-3  whitespace-nowrap ">
                                        
                                        <a href="{{route('setoran.detail',$row->id)}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$row->serial()}}</a>

                                        </th>
                                    <td class="px-4 py-3 ">
                                        @php

                                            if ($row->item_id > 0){

                                                 $code = $row->item->getItemCode();
                                            }else{
                                                $code =$row->temp_name;
                                            }

                                           
                                                                                        
                                        @endphp

                                        @if ($row->status == $sg || $row->status == $sb)
                                            {{$code}}
                                        @else


                                            @if ($row->item_id)
                                                {{$code}}
                                            @else

                                                <!-- Modal toggle -->
                                                <button data-modal-target="kode-model-{{$row->id}}" data-modal-toggle="kode-model-{{$row->id}}" class="text-blue-500 hover:text-blue-600 hover:underline  font-medium " type="button">
                                                    {{$code}}
                                                </button>
                                                
                                                <!-- Main modal -->
                                                <div id="kode-model-{{$row->id}}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                                    <div class="relative p-4 w-full max-w-md max-h-full">
                                                        <!-- Modal content -->
                                                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                            <!-- Modal header -->
                                                            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                                    Update item for serial <span class="font-bold">{{$row->serial()}}</span>
                                                                </h3>
                                                                <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="kode-model-{{$row->id}}">
                                                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                                    </svg>
                                                                    <span class="sr-only">Close modal</span>
                                                                </button>
                                                            </div>

                                                            <form action="{{route('setoran.postEditItem',$row->id)}}" method="post" class="space-y-4" >
                                                            <!-- Modal body -->
                                                            @csrf

                                                            @method('PATCH')

                                                            <div class="p-4 md:p-5">
                                                                
                                                                <div>
                                                                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Old Item</label>
                                                                    <p>{{$code}}</p>
                                                                </div>

                                                                <div class="mt-4">
                                                                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">New Item</label>
                                                                    <div class=" w-full">

                                                                        <div class="relative ">
                                                                            <select class="select2-ajax-item" id="code{{$row->id}}" name="code" data-customId="0">
                                                                                
                                                                                <option ></option>
                                                                            </select>
                                                            
                                                                            @error('')
                                                                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                                                            @enderror
                                                                            
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                    
                                                                    
                                                                    
                                                            
                                                            </div>

                                                            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                                                <button  type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                                                                <button data-modal-hide="kode-model-{{$row->id}}" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Decline</button>
                                                            </div>

                                                            </form>
                                                        </div>
                                                    </div>
                                                </div> 

                                                @push('jsBody')

                                                <script>
                                                    $(document).ready(function() {
                
                                                        $('#code{{$row->id}}').select2({
                                                            width: '100%',
                                                            placeholder: "Pilih ",
                                                            minimumInputLength:2,
                                                            ajax: {
                                                                url: '{{ route("ajax.getItemSetoran") }}',
                                                                dataType: "json",
                                                                data: (params) => {
                                                                    let query = {
                                                                        search: params.term,
                                                                        page: params.page || 1,
                                                                    };
                                                                    return query;
                                                                },
                                                                processResults: data => {
                                                                    return {
                                                                        results: data.data.map((row) => {
                                                                            return { text: row.name, id: row.id };
                                                                        }),
                                                                        pagination: {
                                                                            more: data.current_page < data.last_page,
                                                                        },
                                                                    };
                                                                },
                                                            },
                                                        });


                                                    })
                                                </script>

                                                @endpush
  
                                            @endif
                                      
                                            
                                        @endif

                                        
                                      

                                     

                                        
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        <p class="text-nowrap">{{$row->potong_date}}</p>
                                        @isset($row->potong)
                                        <hr class="h-px my-1 bg-gray-400 border-0 dark:bg-gray-700">
                                        <p>{{$row->potong->name}}</p>
                                        @endisset
                                    </td>
                                  
                                   
                                    <td class="px-4 py-3">{{$row->surat_jalan_potong}}</td>
                                    <td class="px-4 py-3">{{$row->quantity}}</td>
                                    <td class="px-4 py-3">
                                        {{$row->size->name}}
                                    </td>
                                    <td class="px-4 py-3">{{$row->warna}}</td>
                                    <td class="px-4 py-3">{{$row->customer}}</td>
                                    <td class="px-4 py-3 text-center ">
                                        <p class="text-nowrap">{{$row->jahit_date}}</p>
                                        @isset($row->jahit)
                                            <hr class="h-px my-1 bg-gray-400 border-0 dark:bg-gray-700">
                                            <p>{{$row->jahit->name}}</p>
                                        @endisset
                                    </td>
                                    <td class="px-4 py-3">
                                    
                                        @if ($row->qc_id == 0)
                                            0
                                        @else
                                            {{ $row->qc->name }}
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">

                                        

                                        @if ($row->status == $sg || $row->status == $sb)

                                            <a href="{{route('transaction.getDetail',$row->transaction_id)}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline" target="_blank">{{$row->invoice}}</a>
                                           
                                        @else

                                         <!-- Modal toggle -->
                                            <button data-modal-target="invoice-model-{{$row->id}}" data-modal-toggle="invoice-model-{{$row->id}}" class="text-blue-500 hover:text-blue-600 hover:underline  font-medium " type="button">
                                               To Gudang
                                            </button>
                                            
                                            <!-- Main modal -->
                                            <div id="invoice-model-{{$row->id}}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                                <div class="relative p-4 w-full max-w-md max-h-full">
                                                    <!-- Modal content -->
                                                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                        <!-- Modal header -->
                                                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                                Update invoice for serial <span class="font-bold">{{$row->serial()}}</span>
                                                            </h3>
                                                            <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="invoice-model-{{$row->id}}">
                                                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                                </svg>
                                                                <span class="sr-only">Close modal</span>
                                                            </button>
                                                        </div>

                                                        <form action="{{route('setoran.postGudang',$row->id)}}" method="post" class="space-y-4" >
                                                        <!-- Modal body -->
                                                        @csrf

                                                        @method('PATCH')

                                                        <div class="p-4 md:p-5">
                                                            
                                                            <div>
                                                                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Invoice</label>
                                                                <input  type="text"  name="invoice" value="" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >
                                                            </div>

                                                            
                                                                
                                                                
                                                                
                                                        
                                                        </div>

                                                        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                                            <button  type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                                                            <button data-modal-hide="invoice-model-{{$row->id}}" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Decline</button>
                                                        </div>

                                                        </form>
                                                    </div>
                                                </div>
                                            </div> 

                                           

                                        @endif
                                        
                                        
                                    </td>
                                    {{-- <td class="px-4 py-3 ">
                                        <div class="">
                                            <button type="button" id="deleteButton" data-modal-target="deleteModal{{$row->id}}" data-modal-toggle="deleteModal{{$row->id}}" class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 rounded-lg">
                                                
                    
                                                <svg class="h-3.5 w-3.5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                    <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                                                </svg>
                                                  
                                               Delete
                                            </button>
                    
                                          
                                            
                                            <!-- Main modal -->
                                            <div id="deleteModal{{$row->id}}" tabindex="-1" aria-hidden="true" class=" hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
                                                <div class="relative p-4 w-full max-w-md h-full md:h-auto">
                                                    <!-- Modal content -->
                                                    <div class="relative p-4 mt-40 md:mt-0 text-center bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                                                        <button type="button" class="text-gray-400 absolute top-2.5 right-2.5 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="deleteModal{{$row->id}}">
                                                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                                            <span class="sr-only">Close modal</span>
                                                        </button>
                                                        <svg class="text-gray-400 dark:text-gray-500 w-11 h-11 mb-3.5 mx-auto" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                                        <p class=" text-gray-500 dark:text-gray-300">Are you sure you want to delete this item?</p>
                                                       
                                                        <div class="flex justify-center items-center space-x-4 mt-4">
                                                            <button data-modal-toggle="deleteModal{{$row->id}}" type="button" class="py-2 px-3 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                                                No, cancel
                                                            </button>
                    
                                                            <form action="{{route('transaction.destroy',$row->id)}}" method="post">
                    
                                                                @csrf
                                                                @method('DELETE')
                    
                                                                <button type="submit" class="py-2 px-3 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                                                                    Yes, I'm sure
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                       
                                            
                                        </div>
                                       
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

                    {{$produksi->onEachSide(1)->links()}}

                   
                </div>
            </div>
        </section>
       

    </div>

    @push('jsBody')

    <script>

        function handleCode(event, id) {
            // console.log(id);

            $('#codeList'+id).on('input', function() {
            var search = $(this).val();
            
                if(search.length >= 2) {
                    $.ajax({
                        url: '{{ route("ajax.getitemId") }}',
                        type: 'GET',
                        data: { search: search },
                        dataType: 'json',
                        success: function(data) {
                            var options = '';
                            data.forEach(function(row) {
                                options += '<option value="' + row.id + '" data-id="' + row.id + '">';
                            });
                            $('#codeOption'+id).html(options);

                            
                        }
                    });
                } else {
                    $('#codeOption'+id).empty();
                }
            });
            
            return true; // Memastikan bahwa input lainnya tetap berfungsi normal
        }

        function handleInvoice(event, id) {
            // console.log(id);

            $('#invoiceList'+id).on('input', function() {
            var search = $(this).val();
            
                if(search.length >= 2) {
                    $.ajax({
                        url: '{{ route("ajax.getInvoice") }}',
                        type: 'GET',
                        data: { search: search, id:id },
                        dataType: 'json',
                        success: function(data) {
                            var options = '';
                            data.forEach(function(row) {
                                options += '<option value="' + row.invoice + '" data-id="' + row.invoice + '">';
                            });
                            $('#invoiceOption'+id).html(options);

                            
                        }
                    });
                } else {
                    $('#invoiceOption'+id).empty();
                }
            });
            
            return true; // Memastikan bahwa input lainnya tetap berfungsi normal
        }
       

    </script>

    @endpush

</x-layouts.layout>