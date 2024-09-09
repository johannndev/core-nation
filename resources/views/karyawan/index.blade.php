<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Karyawan List</p>

       
    </div>

    <div class="mb-8">

        <section class="bg-gray-50 dark:bg-gray-900 ">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <form action="{{route('filter.get',['action' =>'karyawan.index'])}}" method="post">
                        @csrf

                        <div class="flex flex-col md:flex-row items-end justify-between p-4">
                        
                            
                            <div class="w-full md:w-4/6">
                            
                                <div class="grid gap-4 md:grid-cols-5 items-end">
                                    <div>
                                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                                        <input type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('name')}}"/>
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

                                <a href="{{route('karyawan.index')}}" class="flex items-center justify-center py-2 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">

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

                    <div class="px-4 pb-4">
                        <div class="flex flex-col md:flex-row md:space-x-3">
                            <div class="flex items-center space-x-1">
                                <div>
                                    <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                                </div>
                                <p>Cuti Tahunan</p>
                            </div>

                            <div class="flex items-center space-x-1">
                                <div>
                                    <div class="w-3 h-3 rounded-full bg-yellow-300"></div>
                                </div>
                                <p>Cuti Sakit</p>
                            </div>

                            <div class="flex items-center space-x-1">
                                <div>
                                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                </div>
                                <p>Cuti Mendadak</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">Name</th>
                                                            
                                        <th scope="col" class="px-4 py-3">No. Telpon</th>
                    
                                        <th scope="col" class="px-4 py-3">Gajih {{$now->month}}/{{$now->year}}</th>
                                        
                                        <th scope="col" class="px-4 py-3">Cuti {{$now->year}}</th>

                                        <th scope="col" class="px-4 py-3">Actions</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ( $dataList as $item)
                                        
                                  
                    
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                                       
                                        <th scope="row" class="px-4 py-3  whitespace-nowrap ">
                                            
                                            <a href="{{route('karyawan.detail',$item->id)}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$item->nama}}</a>
                    
                                        </th>
                                      
                    
                                        
                    
                                        <td class="px-4 py-3">
                                            {{$item->no_telp}}
                                        </td>
                    
                                        <td class="px-4 py-3">
                                            @isset($item->gajihSingle)

                                                {{Number::format($item->gajihSingle->total_gajih,0,0,'id')}}

                                            @endisset

                                            @empty($item->gajihSingle)
                                                Belum dibuat
                                            @endempty
                                        </td>

                                        <td class="px-4 py-3">
                                            {{-- @php
                                                dd($item->gajih)
                                            @endphp --}}

                                           

                                            @if (count($item->gajih) > 0)
                                                
                                          
                                                @foreach ($item->gajih as $g)

                                                    <div class="flex space-x-1">
                                                        <div class="h-7 w-7 bg-blue-500 rounded-full text-sm font-medium text-white flex items-center justify-center">
                                                            <p>{{$g->total_cuti_tahunan}}</</p>
                                                        </div>

                                                        <div class="h-7 w-7 bg-yellow-300 rounded-full text-sm font-medium text-white flex items-center justify-center">
                                                            <p>{{$g->total_cuti_sakit}}</</p>
                                                        </div>

                                                        <div class="h-7 w-7 bg-red-500 rounded-full text-sm font-medium text-white flex items-center justify-center">
                                                            <p>{{$g->total_cuti_mendadak}}</</p>
                                                        </div>
                                                    </div>
                                                    
                                                @endforeach

                                            @else

                                                <div class="flex space-x-1">
                                                    <div class="h-7 w-7 bg-blue-500 rounded-full text-sm font-medium text-white flex items-center justify-center">
                                                        <p>0</p>
                                                    </div>

                                                    <div class="h-7 w-7 bg-yellow-300 rounded-full text-sm font-medium text-white flex items-center justify-center">
                                                        <p>0</p>
                                                    </div>

                                                    <div class="h-7 w-7 bg-red-500 rounded-full text-sm font-medium text-white flex items-center justify-center">
                                                        <p>0</p>
                                                    </div>
                                                </div>

                                            @endif
                                         </td>

                                     
                                        <td class="px-4 py-3 flex">
                                            <button id="more-{{$item->id}}" data-dropdown-toggle="more-togle-{{$item->id}}" class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" type="button">
                                                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                </svg>
                                            </button>
                                            <div id="more-togle-{{$item->id}}" class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                                <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="more-{{$item->id}}">
                                                    <li>
                                                        <a href="{{route('cuti.create',$item->id)}}" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Tambah Cuti</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{route('gajih.create',$item->id)}}" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Tambah Gajih</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{route('karyawan.edit',$item->id)}}" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a>
                                                    </li>
                                                </ul>
                                                
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
                    
                        {{$dataList->onEachSide(1)->links()}}
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