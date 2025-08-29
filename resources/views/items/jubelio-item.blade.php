<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Detail Item #{{$itemProd->code}}</p>

       
    </div>

   
    <div class="mb-8">

        <div class="">

            <div>
            
                <form  class="myForm" id="myForm" action="{{ route('item.updateJubelioId',$itemProd->id) }}" method="post">

                    @csrf

                    @method('PATCH')


                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden mt-4">
                    <div class="p-4">
                        <p class="mb-2 font-bold">Pilih item id jubelio</p>
                        @foreach ($dataList as $item)
                            <div class="mb-2">
                                
                                <div class="flex">
                                    <div class="flex items-center h-5">
                                        <input {{ $itemProd->jubelio_item_id == $item['item_id'] ? 'checked' : '' }} id="helper-radio" aria-describedby="helper-radio-text" type="radio" name="jubelio_item_id" value="{{ $item['item_id'] }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    </div>
                                    <div class="ms-2 text-sm">
                                        <label for="helper-radio" class="font-medium text-gray-900 dark:text-gray-300">{{ $item['item_id'] }}</label>
                                        <p id="helper-radio-text" class="text-xs font-normal text-gray-500 dark:text-gray-300">{{ $item['item_code'] }}</p>
                                    </div>
                                </div>

                            </div>
                        @endforeach

                        <div class="mt-4">
                            <x-layout.submit-button />
                        </div>
                    </div>
                </div>

                

                </form>
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