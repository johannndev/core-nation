<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Jubelio Sync</p>

       
    </div>

    @if ($errors->any())
      <div class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
        <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
          <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <span class="sr-only">Danger</span>
        <div>
          <span class="font-medium">Ensure that these requirements are met:</span>
            <ul class="mt-1.5 list-disc list-inside">
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
              
          </ul>
        </div>
      </div>
    @endif

    <div id="alert-border-2">
      
    @if ((session('errorMessage')))

    <div  class="flex items-center p-4 mb-4 text-red-800 border-t-4 border-red-300 bg-red-50 dark:text-red-400 dark:bg-gray-800 dark:border-red-800" role="alert">
        <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
          <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <div class="ms-3 text-sm font-medium">
            {{session('errorMessage')}}
        </div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700"  data-dismiss-target="#alert-border-2" aria-label="Close">
          <span class="sr-only">Dismiss</span>
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
          </svg>
        </button>
    </div>
        
    @endif

</div>



 
<div>
  <form class="myForm" id="myForm" action="{{route('jubelio.sync.store')}}" method="post" enctype="multipart/form-data">

      @csrf
  
      <section class="bg-gray-50 dark:bg-gray-900 mb-8">
          <div class="mx-auto  ">
              <!-- Start coding here -->
              <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-4">

                  <div class="">

                      <div class="grid grid-cols-2 gap-4 mb-4">

                          <div class="col-span-2">
                              <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jubelio Location</label>
                              <select id="locationSelect" name="location_id" class="border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('location_id') bg-red-50  border-red-500 text-red-900 @else bg-gray-50  border-gray-300 text-gray-900 @enderror">
                                <option value="">Choose a location</option>

                                @foreach ($dataList['data'] as $item)
                                  <option {{old('location_id') == $item['location_id'] ? 'selected' : '' }} data-name="{{$item['location_name']}}" value="{{$item['location_id']}}">{{$item['location_name']}}</option>
                                @endforeach
                            
                             
                              </select>

                          </div>

                          <input type="text" id="locationName" name="locationName" placeholder="Nama Produk" hidden>

                         

                        
                      </div>

                      <x-partial.select-addr :dataProp='$dataListPropWarehouse' />

                      
                      <x-layout.submit-button />

                  </div>
              </div>
          </div>
      </section>

  </form>
</div>

    
   @push('jsBody')

   <script>
    document.getElementById("locationSelect").addEventListener("change", function() {
      let selectedOption = this.options[this.selectedIndex];
      let productName = selectedOption.getAttribute("data-name");

      document.getElementById("locationName").value = productName;
  });
   </script>
       
   @endpush


</x-layouts.layout>