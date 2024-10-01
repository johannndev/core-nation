<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Gaji karyawan</p>

       
    </div>

   

    <div class="mb-8">


        <section class="bg-gray-50 dark:bg-gray-900 ">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <form action="{{route('filter.get',['action' =>'gaji.index'])}}" method="post">

                        @csrf

                        <div class="flex flex-col md:flex-row items-end justify-between p-4">
                        
                            
                            <div class="w-full md:w-4/6">
                            
                                <div class="grid gap-4 grid-cols-5 items-end">
                                    <div class="col-span-5 md:col-span-1">
                                        <label for="bulan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bulan</label>
                                        <select id="bulan" name="bulan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('bulan') bg-red-50  border-red-500 text-red-900 @else bg-gray-50  border-gray-300 text-gray-900 @enderror">
                                          <option value="">Choose</option>

                                            @for ($i = 1; $i <= 12; $i++)

                                                <option {{Request('bulan',$bulanSelect) == $i ? 'selected' : ''  }} value="{{$i}}">{{$i}}</option>
                                                
                                            @endfor
              
                                            
                                        
                                        </select>
                                    </div>

                                    <div class="col-span-5 md:col-span-1">
                                        <label for="tahun" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tahun</label>
                                        <input type="text" id="tahun" name="tahun" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('tahun',$yearSelect)}}"/>
                                    </div>

                                    <div class="col-span-5 md:col-span-1">
                                        <label for="karyawan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Karyawan</label>
                                        <input type="text" id="karyawan" name="karyawan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('karyawan')}}"/>
                                    </div>

                                    {{-- <div class="col-span-1">
                                        <label for="tipe" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipe</label>
                                        <select id="tipe" name="tipe" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('tipe') bg-red-50  border-red-500 text-red-900 @else bg-gray-50  border-gray-300 text-gray-900 @enderror">
                                          <option value="">Choose</option>
              
                                            <option {{Request('tipe') == 1 ? 'selected' : ''  }} value="1">karyawanan</option>
                                            <option {{Request('tipe') == 2 ? 'selected' : ''  }} value="2">Sakit</option>
                                            <option {{Request('tipe') == 3 ? 'selected' : ''  }} value="3">Mendadak</option>
                                        
                                        </select>
                                    </div> --}}
                                   
                                  

                                   
                                   

                                </div>

                                    
                                
                            </div>
                            <div class="mt-4 w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                                <button type="submit" class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="h-4 w-4 mr-2 " viewbox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                                    </svg>
                                    Filter
                                </button>

                                <a href="{{route('gaji.index')}}" class="flex items-center justify-center py-2 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" >
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                      </svg>

                                      
                                    {{-- <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" c viewbox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                                    </svg> --}}

                                    Clear
                                </a>

                                {{-- <a href="{{route('gajih.create')}}" class="flex items-center justify-center text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">

                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 mr-2" >
                                        <path fill-rule="evenodd" d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                      </svg>

                                    Tambah
                                </a> --}}

                            
                            </div>

                            
                        </div>
                    </form>
                   
                    <div>
                      
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">Preiode</th>
                                        <th scope="col" class="px-4 py-3">Karyawan</th>
                                        <th scope="col" class="px-4 py-3">Bulanan</th> 
                                        <th scope="col" class="px-4 py-3">Total Harian</th> 
                                        <th scope="col" class="px-4 py-3">Premi</th> 
                                        <th scope="col" class="px-4 py-3">Potongan Cuti</th> 
                                        <th scope="col" class="px-4 py-3">Bonus</th> 
                                        <th scope="col" class="px-4 py-3">Sanksi</th> 
                                        <th scope="col" class="px-4 py-3">Total Gaji</th>
                                        <th scope="col" class="px-4 py-3">Account Bank</th>                                
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ( $gajihList as $item)
                    
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                                       
                                        <th class="px-4 py-3 normal-col">{{$item->bulan}}/{{$item->tahun}}</th>
                                        <td class="px-4 py-3 normal-col">{{$item->karyawan->nama}}</td>
                                        <td class="px-4 py-3 normal-col">{{Number::format($item->bulanan,0,0,'id')}}</td>
                                        <td class="px-4 py-3 normal-col">{{Number::format($item->harian,0,0,'id')}}</td>
                                        <td class="px-4 py-3 normal-col">
                                            @if ($item->potongan_cuti_premi > 0)
                                                0
                                            @else

                                            {{Number::format($item->premi,0,0,'id')}}

                                            @endif
                                           
                                        </td>
                                       

                                        <td class="px-4 py-3 normal-col">{{Number::format($item->potongan_cuti_bulanan,0,0,'id')}}</td>
                                        <td class="px-4 py-3 normal-col">{{Number::format($item->bonus,0,0,'id')}}</td>
                                        <td class="px-4 py-3 normal-col">{{Number::format($item->sanksi,0,0,'id')}}</td>
                                        <td class="px-4 py-3 normal-col">{{Number::format($item->total_gajih,0,0,'id')}}</td>
                                        <td class="px-4 py-3 normal-col">{{$item->bankSingle->name}}</td>
                                        
                                        
                                    </tr>
                                        
                                    @empty
                    
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                                       
                                        <td class="px-4 py-3 text-center" colspan="9">Data Empty</td>
                                       
                                        
                                        
                                    </tr>
                                        
                                    @endforelse ()
                                    
                                  
                                
                                </tbody>
                            </table>
                        </div>
                    
                        {{$gajihList->onEachSide(1)->links()}}
                    </div>
                   
                </div>

                <div class="mt-6">
                    <div class="grid md:grid-cols-5 gap-4">

                        @foreach ($gajiPerBank as $gaji)

                        <div >
                            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-4">
                                <p class="text-sm text-gray-500">{{ $gaji->bank->name }}</p>
                                <p class="text-lg font-bold">Rp {{ number_format($gaji->total_gaji,0,'','.') }}</p>
                            </div>
                        </div>
                            
                        @endforeach
                         
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