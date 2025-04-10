<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Detail Item #{{$data->code}}</p>

       
    </div>

    <div class="mb-8">
    
        

        <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px">
            
                <li class="me-2">
                    <a href="{{route('item.detail',$tid)}}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" aria-current="page">Detail</a>
                </li>
                <li class="me-2">
                    <a href="{{route('item.transaction',$tid)}}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Transaction</a>
                </li>
                <li class="me-2">
                    <a href="{{route('item.stat',$tid)}}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Stats</a>
                </li>

                <li class="me-2">
                    <a href="{{route('item.jubelio',$tid)}}" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500">Jubelio</a>
                </li>
                
            </ul>
        </div>
    </div>
    <div class="mb-8">

        <div class="grid md:grid-cols-2 gap-4">

            <div>
                <img src=" {{$data->item_image_path}}" class="w-full" alt="" srcset="">
           
            </div>

            <div>
            
                
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden mt-4">
                    <div class="grid grid-cols-1 divide-y">
                        <div>
                            <div class="grid grid-cols-5 p-4">
                                <div  class="col-span-2">
                                    <p class="font-bold">Jubelio Sync</p>
                                </div>
                                <div class="col-span-3">
                                    <a href="{{route('item.jubelioGetItem',$data->id)}}" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Get Item</a>
                                </div>
                            </div>

                            <div class="grid grid-cols-5 p-4">
                                <div  class="col-span-2">
                                    <p class="font-bold">Jubelio Item ID</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->jubelio_item_id ?? ''}}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-5 p-4">
                                <div  class="col-span-2">
                                    <p class="font-bold">Jubelio Item Name</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$dataJubelio['item_name'] ?? ''}}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-5 p-4">
                                <div  class="col-span-5">
                                    <p class="font-bold">Jubelio total stock</p>
                                </div>
                                
                            </div>

                            <div class="grid grid-cols-5 p-4">
                                <div  class="col-span-2">
                                    <p class="font-bold ml-2 md:ml-4">On hand</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$dataJubelio['total_stocks']['on_hand'] ?? ''}}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-5 p-4">
                                <div  class="col-span-2">
                                    <p class="font-bold ml-2 md:ml-4">On order</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$dataJubelio['total_stocks']['on_order'] ?? ''}}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-5 p-4">
                                <div  class="col-span-2">
                                    <p class="font-bold ml-2 md:ml-4">Available</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$dataJubelio['total_stocks']['available'] ?? ''}}</p>
                                </div>
                            </div>
                        </div>

                        
                    </div>
                </div>
            </div>
        </div>

        

       



      
       

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