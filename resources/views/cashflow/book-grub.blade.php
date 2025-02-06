
<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Cash Flow</p>

       
    </div>

    <div class="mb-8">

        <section class="bg-gray-50 dark:bg-gray-900 ">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <form action="{{route('filter.get',['action' =>'cashflow.book'])}}" method="post">
                        @csrf

                        <div class="flex flex-col md:flex-row items-end justify-between p-4">
                        
                            
                            <div class="w-full md:w-4/6">
                            
                                <div class="grid gap-4 md:grid-cols-6 items-end">
                                    <div>
                                        <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Type</label>
                                        <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="">Choose a type</option>
                                            <option  {{Request('type') == 'sender'? 'selected' : 'null'}} value="sender">Sender</option>
                                            <option  {{Request('type') == 'receiver'? 'selected' : 'null'}} value="receiver">Receiver</option>
                                           
                                           
                                          </select>
                                    </div>

                                    <div>
                                        <label for="book" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Addr Book</label>
                                        <select id="book" name="book" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="">Choose a addr book</option>
                                            <option  {{Request('book') == '1'? 'selected' : 'null'}} value="1">Customer</option>
                                            <option  {{Request('book') == '7'? 'selected' : 'null'}} value="7">Reseller</option>
                                            <option  {{Request('book') == '3'? 'selected' : 'null'}} value="3">Bank</option>
                                            <option  {{Request('book') == '8'? 'selected' : 'null'}} value="8">Account</option>
                                           
                                           
                                          </select>
                                    </div>

                                    <div>
                                        <label for="bukan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bulan</label>
                                        <input type="text" id="bukan" name="bulan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('bulan',0)}}"/>
                                    </div>

                                    <div>
                                        <label for="tahun" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tahun</label>
                                        <input type="text" id="tahun" name="tahun" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('tahun', $currentYear)}}"/>
                                    </div>

                                    <div class="col-span-2">
                                        <label for="sort" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sort</label>
                                        <div class="flex space-x-4">

                                            <div>
                                                <select id="sort_type" name="sort_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                    <option value="">Choose a sort type</option>
                                                    <option  {{Request('sort_type') == 'cash_in_total'? 'selected' : 'cash_in_total'}} value="cash_in_total">Cash In</option>
                                                    <option  {{Request('sort_type') == 'cash_out_total'? 'selected' : 'cash_out_total'}} value="cash_out_total">Cash Out</option>
                                                    <option  {{Request('sort_type') == 'sell_total'? 'selected' : 'sell_total'}} value="sell_total">Sell</option>
                                                    <option  {{Request('sort_type') == 'return_total'? 'selected' : 'return_total'}} value="return_total">Return</option>
                                                    <option  {{Request('sort_type') == 'buy_total'? 'selected' : 'buy_total'}} value="buy_total">Buy</option>
                                                    <option  {{Request('sort_type') == 'return_suplier'? 'selected' : 'return_suplier'}} value="return_suplier">Return Suplier</option>
                                                   
                                                   
                                                  </select>
                                            </div>

                                            <div>
                                                <select id="sort" name="sort" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                    <option value="">Choose a sort</option>
                                                    <option  {{Request('sort') == 'desc'? 'selected' : 'null'}} value="desc">Descending</option>
                                                    <option  {{Request('sort') == 'asc'? 'selected' : 'null'}} value="asc">Ascending</option>
                                                   
                                                   
                                                  </select>
                                            </div>

                                        </div>


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

                                <a href="{{route('customer.index')}}" class="flex items-center justify-center py-2 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">

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

                 

                   
                </div>

               

                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left">Type</th>
                                        <th scope="col" class="px-4 py-3">Cash In</th>
                                        <th scope="col" class="px-4 py-3">Cash Out</th>
                                        <th scope="col" class="px-4 py-3">Sell</th>
                                        <th scope="col" class="px-4 py-3">Return</th>
                                        <th scope="col" class="px-4 py-3">Buy</th>
                                        <th scope="col" class="px-4 py-3">Return Suplier</th>
                        
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ( $transactions as $item)
                                        
                                  
                    
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                                       
                                        <th scope="row" class="px-4 py-3  whitespace-nowrap text-left">
                                            
                                            @if (Request('type') == 'sender')

                                                <a href="" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ucfirst($item->sender->name)}}</a>

                                            @elseif (Request('type') == 'receiver')

                                                <a href="" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ucfirst($item->receiver->name)}}</a>
                                                
                                            @else
                                                
                                            @endif
                                          
                    
                                        </th>

                                       
                                       
                                        <td class="px-4 py-3 {{ $item->getPriceColor($item->cash_in_total) }}">{{Number::format($item->cash_in_total,2)}}</td>
                                        <td class="px-4 py-3 {{ $item->getPriceColor($item->cash_out_total) }}">{{Number::format($item->cash_out_total,2)}}</td>
                                        <td class="px-4 py-3 {{ $item->getPriceColor($item->sell_total) }}">{{Number::format($item->sell_total,2)}}</td>
                                        <td class="px-4 py-3 {{ $item->getPriceColor($item->return_total) }}">{{Number::format($item->return_total,2)}}</td>
                                        <td class="px-4 py-3 {{ $item->getPriceColor($item->buy_total) }}">{{Number::format($item->buy_total,2)}}</td>
                                        <td class="px-4 py-3 {{ $item->getPriceColor($item->return_suplier) }}">{{Number::format($item->return_suplier,2)}}</td>
                                    
                                      
                    
                                     
                                        
                                        
                                        
                                    </tr>
                                        
                                    @empty
                    
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                                       
                                        <td class="px-4 py-3 text-center" colspan="9">Data Empty</td>
                                       
                                        
                                        
                                    </tr>
                                        
                                    @endforelse ()
                                    
                                  
                                
                                </tbody>
                            </table>
                        </div>

                        {{$transactions->onEachSide(1)->links()}}
                    
                    </div>
                </div>

            </div>
        </section>
       

    </div>

    @push('jsBody')

    <script>
      document.addEventListener('DOMContentLoaded', function() {
          var toggleNamaImage = document.getElementById('image-checkbox');
          var namaColumnImage = document.querySelectorAll('.image-col');
    

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

      });

     
    </script>
        
    @endpush

</x-layouts.layout>