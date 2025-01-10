<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">{{$data->name}}</p>

       
    </div>

    <div class="mb-8">
    
        

        <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px">
            
                <li class="me-2">
                    <a href="{{route('asetLancar.detail',$tid)}}" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500" aria-current="page">Detail</a>
                </li>
                <li class="me-2">
                    <a href="{{route('asetLancar.transaction',$tid)}}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Transaction</a>
                </li>
                <li class="me-2">
                    <a href="{{route('asetLancar.stat',$tid)}}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Stats</a>
                </li>
                
            </ul>
        </div>
    </div>
    <div class="mb-8">

        <div class="grid md:grid-cols-2 gap-4">

            <div>
                <img src=" {{$data->lancar_image_path}}" class="w-full" alt="" srcset="">
            </div>

            <div>
              
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="grid grid-cols-1 divide-y">
                        <div>
                            <div class="grid grid-cols-5 p-4">
                                <div  class="col-span-2">
                                    <p class="font-bold">Name</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->name}}</p>
                                </div>
                            </div>
                        </div>
                       
                        <div>
                            <div class="grid grid-cols-5 p-4">
                                <div  class="col-span-2">
                                    <p class="font-bold">Type</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->type == 1 ? 'Item' : 'Non item'}}</p>
                                </div>
                            </div>
                        </div>

                        @if($data->type == \App\Models\Item::TYPE_ITEM)
                        <div>
                            <div class="grid grid-cols-5 p-4">
                                <div  class="col-span-2">
                                    <p class="font-bold">Alias</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->group->alias}}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="grid grid-cols-5 p-4">
                                <div  class="col-span-2">
                                    <p class="font-bold">Description</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{ $data->group->description }}</p>
                                </div>
                            </div>
                        </div>

                        @else

                        <div>
                            <div class="grid grid-cols-5 p-4">
                                <div  class="col-span-2">
                                    <p class="font-bold">Alias</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->alias}}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="grid grid-cols-5 p-4">
                                <div class="col-span-2">
                                    <p class="font-bold">Description</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{ $data->description }}</p>
                                </div>
                            </div>
                        </div>


                        @endif

                      

                        <div>
                            <div class="grid grid-cols-5 p-4">
                                <div  class="col-span-2">
                                    <p class="font-bold">NB</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{ $data->printDescription2() }}</p>
                                </div>
                            </div>
                        </div>

                        

                        <div>
                            <div class="grid grid-cols-5 p-4">
                                <div  class="col-span-2">
                                    <p class="font-bold">Price</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{ ($data->price) }}</p>
                                </div>
                            </div>
                        </div>


                        
                     

                       

                        <div>
                            <div class="grid grid-cols-5 p-4">
                                <div class="col-span-2">
                                    <p class="font-bold">Cost</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{ ($data->cost) }}</p>
                                </div>
                            </div>
                        </div>
                      

                        
                        @if($data->group)
                        <div>
                            <div class="grid grid-cols-5 p-4">
                                <div  class="col-span-2">
                                    <p class="font-bold">Group	</p>
                                </div>
                                <div class="col-span-3">
                                    <p><a href="" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ $data->group->name }}</a></p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div>
                            <div class="grid grid-cols-5 p-4">
                                <div  class="col-span-2">
                                    <p class="font-bold">Edit</p>
                                </div>
                                <div class="col-span-3">
                                    <a href="{{route('asetLancar.edit',$data->id)}}" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Edit</a>
                                    <a href="{{route('asetLancar.duplicate',$data->id)}}" class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Duplicate</a>
                                </div>
                            </div>
                        </div>

                      
                        <div>
                            <div class="grid grid-cols-5 p-4">
                                <div  class="col-span-2">
                                    <p class="font-bold">Tags</p>
                                </div>
                                <div class="col-span-3">
                                    @if(isset($data->tags))
                                        @foreach($data->tags as $tag)
                                            <a href="http://" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$tag->name}}</a>
                                            @if ($loop->remaining),@endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        

                        <div>
                            <div class="grid grid-cols-5 p-4">
                                <div  class="col-span-2">
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

    <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden mb-10">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Warehouse
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Quantity
                    </th>
                   
                </tr>
            </thead>
            <tbody>

                @forelse ($whList as $item)

                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 {{$item->quantity < 1 ? "image-col hidden" : ""}}">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{$item->warehouse->name}}
                        </th>
                        <td class="px-6 py-4">
                            {{$item->quantity}}
                        </td>
                        
                    </tr>
                    
                @empty
                    
                @endforelse
               
                
            </tbody>
        </table>
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