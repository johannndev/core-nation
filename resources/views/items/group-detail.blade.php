<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Detail {{$data->name}}</p>

       
    </div>

   
    <div class="mb-8">

        <div class="grid md:grid-cols-2 gap-4">

            <div>
                <x-partial.image type="w-full" :url="$urlImage" />
            </div>

            <div>
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="grid grid-cols-1 divide-y">
                        <div>
                            <div class="grid grid-cols-4 p-4">
                                <div>
                                    <p class="font-bold">Name</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->name}}</p>
                                </div>
                            </div>
                        </div>
                       
                        <div>
                            <div class="grid grid-cols-4 p-4">
                                <div>
                                    <p class="font-bold">Master</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->master }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="grid grid-cols-4 p-4">
                                <div>
                                    <p class="font-bold">Variant</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->variant }}</p>
                                </div>
                            </div>
                        </div>


                        <div>
                            <div class="grid grid-cols-4 p-4">
                                <div>
                                    <p class="font-bold">Stats</p>
                                </div>
                                <div class="col-span-3">
                                    <a href="{{route('item.statGroup',$data->id)}}" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Stats</a>
                                </div>
                            </div>
                        </div>



                        <div>
                            <div class="grid grid-cols-4 p-4">
                                <div>
                                    <p class="font-bold">Show 0</p>
                                </div>
                                <div class="col-span-3">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" id="image-checkbox">
                                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                      
                                      </label>
                                </div>
                            </div>
                        </div>

                     
                        
                    </div>
                </div>
            </div>
        </div>

        

       



      
       

    </div>

    
@foreach($items as $item)
    <div class="w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center  border-b border-gray-200 rounded-t-lg bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:bg-gray-800">
            <div class="pl-4">
                <h2 class=" md:text-xl font-extrabold tracking-tight text-gray-900 dark:text-white"> {{ $item->id }}-{{ $item->code }}-<a href="{{route('item.detail',$item->id)}}" class="text-blue-500">{{ $item->name }}</a> </h2>
            </div>

            <div>
                <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500" id="defaultTab" data-tabs-toggle="#defaultTabContent" role="tablist">
                    <li class="ms-2">
                        <a href="{{route('item.transaction',$item->id)}}" class="inline-block p-4 text-blue-600  hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-blue-500">Transactions</a>
                    </li>
                    <li class="ms-2">
                        <a href="{{route('item.stat',$item->id)}}" class="inline-block p-4 text-blue-600  hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-blue-500">Stats</a>
                    </li>
                    <li class="ms-2">
                        <a href="{{route('item.edit',$item->id)}}" class="inline-block p-4 text-blue-600  hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-blue-500">Edit</a>
                    </li>
                </ul>
            </div>

        </div>
        
        <div >
            <div class=" px-4 bg-white rounded-lg md:px-8 dark:bg-gray-800" >

                @php
                $quantity = 0;
                @endphp

                <div class="divide-y ">
                    @foreach($warehouse[$item->id] as $w)
                    
                    @if($w->quantity > 0)
                        @php
                            $quantity += $w->quantity;
                        @endphp
                        <div class="flex justify-between py-4 {{ $w->quantity < 1 ? 'image-col hidden' : ''}}">
                            <div>
                                <a href="#" class="text-blue-500"> {{ $w->warehouse->name }}</a>
                               
                            </div>
                            <div>
                                {{ $w->quantity }}
                            </div>
                            
                        </div>
                    @endif

                    
                    
                    @endforeach
                </div>

                <hr class="h-px  bg-gray-200 border-0 dark:bg-gray-700">

                <div class="flex justify-between font-bold py-4">
                    <div>
                        Total
                    </div>
                    <div>
                        {{ $quantity }}
                    </div>
                    
                </div>
                
            </div>

        </div>
    </div>

@endforeach


    

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