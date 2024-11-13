<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Item List</p>

       
    </div>

    <div class="mb-8">


        <section class="bg-gray-50 dark:bg-gray-900 ">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <form action="{{route('item.filter')}}" method="get">
                        <div class="flex flex-col md:flex-row items-end justify-between p-4">
                        
                            
                            <div class="w-full md:w-4/6">
                            
                                <div class="grid gap-4 md:grid-cols-5">
                                    <div>
                                        <label for="code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Barcode/SKU</label>
                                        <input type="text" id="code" name="code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('code')}}"/>
                                    </div>
                                    <div>
                                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Long SKU</label>
                                        <input type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('name')}}"/>
                                    </div>
                                    <div>
                                        <label for="alias" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alias</label>
                                        <input type="text" id="alias" name="alias" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{Request('alias')}}" />
                                    </div>

                                    <div>
                                        <label for="desc" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Desc</label>
                                        <input type="text" id="desc" name="desc" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{Request('desc')}}" />
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

                                <a href="{{route('item.index')}}" class="flex items-center justify-center py-2 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">

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
                    <div class="flex flex-wrap px-4 py-3 space-y-2  items-center  lg:space-y-0">
                        <div class="w-full md:w-auto md:mr-6">
                            <p class="me-2">Show:</p>
                        </div>
    
                        <div class="mr-4">
                            <div class="flex items-center ">
                                <input id="image-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="image-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Image</label>
                            </div>
                        </div>
    
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class=" image-col hidden px-4 py-3">Image</th>
                                    <th scope="col" class="px-4 py-3">Barcode</th>
                                    <th scope="col" class="px-4 py-3">SKU</th>
                                    <th scope="col" class="px-4 py-3">Kode Produksi</th>
                                    <th scope="col" class="px-4 py-3">Alias</th>
                                    <th scope="col" class="px-4 py-3">Description</th>
                                    <th scope="col" class="px-4 py-3">Price</th>
                                    <th scope="col" class="px-4 py-3">NB</th>
                                    <th scope="col" class="px-4 py-3">Quantity</th>
                                    <th scope="col" class="px-4 py-3">Actions</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ( $dataList as $item)
                                    
                                @php
                                    $url = $item->item_image_path;
                                    
                                @endphp

                                <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                                    <th scope="row" id="" class="image-col hidden  px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">

                                        
                                        <div class="mr-3">
                                          
                                            <x-partial.image type="h-10" :url="$url" />
                                        </div>
    
                                    </th>
                                    <th scope="row" class="px-4 py-3  whitespace-nowrap ">
                                  

                                        {{-- {{$item->item_image_path}} --}}
                                        <a href="{{route('item.detail',$item->id)}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$item->id}}</a>

                                    </th>
                           
                                    <td class="px-4 py-3">{{$item->name}}</td>
                                    <td class="px-4 py-3">{{$item->code}}</td>
                                    @if ($item->type == \App\Models\Item::TYPE_ITEM)

                                    <td class="px-4 py-3">{{$item->group->alias}}</td>
                                    <td class="px-4 py-3">{{$item->group->description}}</td>
                                    <td class="px-4 py-3">{{number_format($item->price,2)}}</td>
                                    <td class="px-4 py-3">{{$item->group->description2}}</td>
                                    

                                    @else

                                    <td class="px-4 py-3">{{$item->alias}}</td>
                                    <td class="px-4 py-3">{{$item->description}}</td>
                                    <td class="px-4 py-3">{{number_format($item->price,2)}}</td>
                                    <td class="px-4 py-3">{{$item->description2}}</td>
                                        
                                    @endif
                                   
                                   
                                    <td class="px-4 py-3">{{$item->getItemInWarehouse($item->id)}}</td>
                                    <td class="px-4 py-3">
                                        <a href="{{route('item.edit',$item->id)}}" class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                            Edit
                                        </a>
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

                    {{$dataList->onEachSide(1)->links()}}

                   
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