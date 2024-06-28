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
                                    <th scope="col" class="px-4 py-3 w-40">Invoice</th>
                                    <th scope="col" class="px-4 py-3"></th>
                                    
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

                                        <form action="{{route('setoran.postEditItem',$row->id)}}" method="post" id="form-code-{{$row->id}}">

                                            @csrf

                                            @method('PATCH')

                                           
                                            

                                            <input onkeyup="return handleCode(event,{{$row->id}})"  type="text" id="codeList{{$row->id}}" list="codeOption{{$row->id}}" name="code" value="{{$code}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" autocomplete="off">
                                            <datalist id="codeOption{{$row->id}}">
                                                <!-- Options akan diisi oleh jQuery AJAX -->
                                            </datalist>
                                        

                                            {{-- <input type="text" id="code" name="code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{$code}}"/> --}}

                                        </form>
                                            
                                        @endif

                                        
                                      

                                     

                                        
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        <p>{{$row->potong_date}}</p>
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
                                        <p class="">{{$row->jahit_date}}</p>
                                        @isset($row->jahit)
                                            <hr class="h-px my-1 bg-gray-400 border-0 dark:bg-gray-700">
                                            <p>{{$row->jahit->name}}</p>
                                        @endisset
                                    </td>
                                    <td class="px-4 py-3">

                                        

                                        @if ($row->status == $sg || $row->status == $sb)

                                            <a href="{{route('transaction.getDetail',$row->transaction_id)}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline" target="_blank">{{$row->invoice}}</a>
                                           
                                        @else

                                            <form action="{{route('setoran.postGudang',$row->id)}}" method="post" id="form-invoice-{{$row->id}}">

                                                @csrf

                                                @method('PATCH')

                                        
                                                    
                                                <input  type="text" id="invoiceList{{$row->id}}" list="invoiceOption{{$row->id}}" name="invoice" value="" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" autocomplete="off">
                                                <datalist id="invoiceOption{{$row->id}}">
                                                    @if ($row->item_id > 0)
                                                        <option value="{{$row->invoice}}">
                                                    @endif
                                                    
                                                    <!-- Options akan diisi oleh jQuery AJAX -->
                                                </datalist>
                                            </form>

                                        @endif
                                        
                                        
                                    </td>
                                    <td class="px-4 py-3 ">
                                        <div class="flex items-center justify-end h-full">

                                            <button id="action-dropdown-button-{{$row->id}}" data-dropdown-toggle="action-dropdown-{{$row->id}}" class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" type="button">
                                                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                </svg>
                                            </button>
                                            <div id="action-dropdown-{{$row->id}}" class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                                <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="action-dropdown-button-{{$row->id}}">
                                                    <li>
                                                        <button  onclick="document.getElementById('form-code-{{$row->id}}').submit();" class="block w-full text-left py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Update</button>
                                                    </li>
                                                    <li>
                                                        <button onclick="document.getElementById('form-invoice-{{$row->id}}').submit();"  class="block w-full text-left py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Gudang</button>
                                                    </li>
                                                </ul>
                                                <div class="py-1">
                                                    <a href="#" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
                                                </div>
                                            </div>

                                        </div>
                                       
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