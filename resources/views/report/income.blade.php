<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Report Income</p>

       
    </div>

    <div class="mb-8">


        <section class="bg-gray-50 dark:bg-gray-900 ">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <form action="{{route('filter.get',['action' =>'report.income'])}}" method="post">

                        @csrf

                        <div class="flex flex-col md:flex-row items-end justify-between p-4">
                        
                            
                            <div class="w-full md:w-4/6">
                            
                                <div class="grid gap-4 md:grid-cols-5">
                                    <div>
                                        <label for="bulan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bulan</label>
                                        <input type="text" id="bulan" name="bulan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('bulan')}}"/>
                                    </div>
                                    <div>
                                        <label for="tahun" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tahun</label>
                                        <input type="text" id="tahun" name="tahun" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('tahun')}}"/>
                                    </div>
                                   

                                    <div>
                                        <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Type</label>
                                        <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="">Choose a type</option>

                                            <option  {{Request('type') == 6 ? 'selected' : 'null'}} value="6">6 bulan</option>
                                            <option  {{Request('type') == 12 ? 'selected' : 'null'}} value="12">12 bulan</option>

                                          </select>
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

                                <a href="{{route('statsale.index')}}" class="flex items-center justify-center py-2 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" >
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                      </svg>

                                 

                                    Clear
                                </a>

                            
                            </div>

                            
                        </div>
                    </form>
                    
                    <div class="overflow-x-auto mr-2">

                        <div class="flex text-xs p-4 ">
                            <div class="">
                                <div class="p-1 w-32   font-semibold">
                                    
                                    
                                
                                </div>
                            </div>

                            <div class="">

                                <div class="flex ">

                                    @foreach ($dateList as $i => $item)
                                        <div class="">
                                            <div class="p-1 w-32  text-center font-semibold">
                                                <p>ACTUAL</p>
                                                <p>{{ $i }}</p>
                                                
                                            </div>
                                            
                                        </div>
                                    @endforeach

                                </div>

                            </div>

                        </div>

                        <div class="px-4">
                            <p class="font-semibold">INCOME REVENUE</p>
                        </div>

                         <div class="flex text-xs p-4">
                            <div class="">
                                
                                <div class="p-1 w-32 border-b ">
                                    Sell Offline
                                </div>
                                <div class="p-1 w-32 border-b ">
                                    Sell Online
                                </div>

                                <div class="p-1 w-32 border-b ">
                                    Return Offline
                                </div>

                                <div class="p-1 w-32 border-b ">
                                    Return online
                                </div>

                                <div class="p-1 w-32 border-b  font-bold">
                                    Nett Revenue
                                </div>

                                
                            </div>

                            <div class="">

                                <div class="flex  ">

                                    @foreach ($income as $i => $item)
                                        <div class="">
                                            
                                            <div class="p-1 w-32 text-right border-b">
                                                {{ number_format($item['sell_offline'],2) }}
                                            </div>
                                            <div class="p-1 w-32 text-right border-b">
                                                {{ number_format($item['sell_online'],2) }}
                                            </div>
                                            <div class="p-1 w-32 text-right border-b">
                                                {{ number_format($item['return_offline'],2) }}
                                            </div>

                                            <div class="p-1 w-32 text-right border-b">
                                                {{ number_format($item['return_online'],2) }}
                                            </div>

                                            

                                            <div class="p-1 w-32 text-right border-b font-bold">
                                                {{ number_format($item['nett_revenue'],2) }}
                                            </div>

                                           
                                        </div>
                                    @endforeach

                                </div>

                            </div>

                        </div>

                        <div class="px-4">
                            <p class="font-semibold">CASH FLOW</p>
                        </div>

                         <div class="flex text-xs p-4">
                            <div class="">
                                

                                <div class="p-1 w-32 border-b ">
                                    Cash In Offline
                                </div>

                                <div class="p-1 w-32 border-b ">
                                    Cash In Online
                                </div>

                                 <div class="p-1 w-32 border-b ">
                                   Cash In Journal
                                </div>

                                 <div class="p-1 w-32 border-b font-bold">
                                    Nett Cash In
                                </div>
                            </div>

                            <div class="">

                                <div class="flex ">

                                    @foreach ($cashIn as $i => $item)
                                        <div class="">
                                           
                                            <div class="p-1 w-32 text-right border-b">
                                                {{ number_format($item['cash_in_offline'],2) }}
                                            </div>

                                            <div class="p-1 w-32 text-right border-b">
                                                {{ number_format($item['cash_in_online'],2) }}
                                            </div>

                                             <div class="p-1 w-32 text-right border-b">
                                                {{ number_format($item['cash_in_journal'],2) }}
                                            </div>

                                             <div class="p-1 w-32 text-right border-b font-bold">
                                                {{ number_format($item['nett_cash_in'],2) }}
                                            </div>

                                        </div>
                                    @endforeach

                                </div>

                            </div>

                        </div>

                        <div class="px-4">
                            <p class="font-semibold">EXPANSE</p>
                        </div>
                        <div class="flex text-xs p-4">
                            <div class="">
                               

                                <div class="p-1 w-32 border-b">
                                    Cash Out Offline
                                </div>

                                 <div class="p-1 w-32 border-b">
                                    Cash Out Online
                                </div>

                                 <div class="p-1 w-32 border-b">
                                   Cash Out Journal
                                </div>

                                <div class="p-1 w-32 border-b font-bold">
                                    Nett Cash Out
                                </div>

                            </div>

                            <div class="">

                                <div class="flex">

                                    @foreach ($cashOut as $i => $item)
                                        <div class="">
                                            
                                            <div class="p-1 w-32 text-right border-b">
                                                {{ number_format($item['cash_out_offline'],2) }}
                                            </div>

                                            <div class="p-1 w-32 text-right border-b">
                                                {{ number_format($item['cash_out_online'],2) }}
                                            </div>

                                             <div class="p-1 w-32 text-right border-b">
                                                {{ number_format($item['cash_out_journal'],2) }}
                                            </div>

                                             <div class="p-1 w-32 text-right border-b font-bold">
                                                {{ number_format($item['nett_cash_out'],2) }}
                                            </div>

                                        </div>
                                    @endforeach

                                </div>

                            </div>

                        </div>

                         <div class="flex text-sm px-4">
                            <div class="">
                               

                                <div class="pb-4 px-1 w-32 border-b font-bold">
                                   Total
                                </div>

                                 

                            </div>

                            <div class="">

                                <div class="flex">

                                    @foreach ($cashTotal as $i => $item)
                                        <div class="">
                                            <div class="pb-4 px-1 w-32 text-right border-b font-bold text-sm">
                                                {{ number_format($item['nett_cash'],2) }}
                                            </div>
                                        </div>
                                    @endforeach

                                </div>

                            </div>

                        </div>

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