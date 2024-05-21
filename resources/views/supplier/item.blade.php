<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">{{$nameCustomer}} | Items</p>

       
    </div>

    <div class="mb-8">
        <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px">
            
              
                <li class="me-2">
                    <a href="{{route('supplier.detail',$cid)}}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Detail</a>
                </li>
                <li class="me-2">
                    <a href="{{route('supplier.transaction',$cid)}}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" aria-current="page">Transaction</a>
                </li>

                <li class="me-2">
                    <a href="{{route('supplier.items',$cid)}}" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500">Items</a>
                </li>

                <li class="me-2">
                    <a href="{{route('supplier.stat',$cid)}}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Stats</a>
                </li>
                
            </ul>
        </div>
    </div>

    <div class="mb-8">


        <section class="bg-gray-50 dark:bg-gray-900 ">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <form action="{{route('filter.get',['id' => $cid, 'action' =>'supplier.items'])}}" method="post">

                        @csrf

                        <div class="flex flex-col md:flex-row items-end justify-between p-4">
                        
                            
                            <div class="w-full md:w-4/6">
                            
                                <div class="grid gap-4 md:grid-cols-5 items-end">
                                    <div>
                                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                                        <input type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('name')}}"/>
                                    </div>
                                    <div>

                                        <label for="sort" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sort</label>
                                        <select id="sort" name="sort" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                                        
                                            <option  value="">Quantity Descending</option>
                                            <option {{Request('sort') == 'qtyasc' ? 'selected' : ''}} value="qtyasc">	Quantity Ascending</option>
                                            <option {{Request('sort') == 'codedesc' ? 'selected' : ''}} value="codedesc">	Code Descending</option>
                                            <option {{Request('sort') == 'codeasc' ? 'selected' : ''}} value="codeasc">	Code Ascending</option>
                                            <option {{Request('sort') == 'namedesc' ? 'selected' : ''}} value="namedesc">	Name Descending</option>
                                            <option {{Request('sort') == 'nameasc' ? 'selected' : ''}} value="nameasc">	Name Ascending</option>
                                            
                                            
                                          
                                        </select>

                                    </div>
                                    <div class="col-span-2">
                                        <label class="inline-flex items-center cursor-pointer py-2.5">
                                            <span class="me-3 text-sm font-medium text-gray-900 dark:text-gray-300">Hide 0</span>
                                            <input type="checkbox" value="show" name="show0" class="sr-only peer"  {{Request('show0') ? 'checked' : ''}} >
                                            <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                            <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Show 0</span>
                                        </label>
                                    </div>

                                   
                                   

                                </div>

                                    
                                
                            </div>
                            <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                                <button type="submit" class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="h-4 w-4 mr-2 " viewbox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                                    </svg>
                                    Filter
                                </button>

                                <a href="{{route('supplier.items',$cid)}}" class="flex items-center justify-center py-2 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">

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
                   
                    <x-customer.items :cid='$cid' />
                   
                </div>
            </div>
        </section>
       

    </div>

    @push('jsBody')

    <script>
      document.addEventListener('DOMContentLoaded', function() {
          var toggleNamaImage = document.getElementById('image-checkbox');
          var namaColumnImage = document.querySelectorAll('.image-col');
          var toggleNamaOnline = document.getElementById('online-checkbox');
          var namaColumnOnline = document.querySelectorAll('.online-col');
          var namaColumnNormal = document.querySelectorAll('.normal-col');
    

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


          toggleNamaOnline.addEventListener('change', function() {
              if (toggleNamaOnline.checked) {
                  namaColumnOnline.forEach(function(online) {
                      online.classList.remove('hidden');
                  });
                  namaColumnNormal.forEach(function(normal) {
                    normal.classList.add('hidden');
                  });
              } else {
                  namaColumnOnline.forEach(function(online) {
                    online.classList.add('hidden');
                  });

                  namaColumnNormal.forEach(function(normal) {
                      normal.classList.remove('hidden');
                  });
              }
          });
      });

     
    </script>
        
    @endpush

</x-layouts.layout>