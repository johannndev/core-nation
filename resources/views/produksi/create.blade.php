<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">New Buy</p>

       
    </div>

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

   

   


    <form action="{{route('produksi.store')}}" method="post" >

        @csrf

        <section class="bg-gray-50 dark:bg-gray-900 mb-8">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-4">

                    <div class="">

                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <div>
                                <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date</label>
                                <input type="date" name="date" id="date" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{date('Y-m-d')}}">

                            </div>

                            <div class="col-span-2">
                                <label for="potong" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Potong</label>
                                <select id="potong" name="potong" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option selected>Choose</option>
                                    @foreach ($potongList as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                   
                                
                                </select>

                            </div>

                            <div class="col-span-2">
                                <label for="surat_jalan_potong" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Surat Jalan Potong</label>
                                <input type="text" name="surat_jalan_potong" id="surat_jalan_potong" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{old('surat_jalan_potong')}}">

                            </div>
                         
                        </div>

                     
                    

                        <div class="mb-6">
                            <p class="text-lg font-bold mb-4" >Item</p>

                        

                            <div id="dynamicAddRemove">
                               
                               
                                
                                <div class="grid gap-6 mb-6 md:grid-cols-6 items-end addField0 "id="gridItem0">
                                    <div>
                                        
                                
                                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 ">Nama</label>
                                        <input  onkeydown="return handleName(event,0)" type="text" name="addMoreInputFields[0][name]"  id="name0" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  />
                                    </div>

                                    <div>
                                        
                                
                                        <label for="size" class="block mb-2 text-sm font-medium text-gray-900 ">Size</label>
                                        <select onkeydown="return handleSize(event,0)" id="size0" name="addMoreInputFields[0][size]"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="">Choose</option>

                                            @foreach ($sizeList as $index => $item)

                                            <option value="{{$index}}">{{$item}}</option>
                                                
                                            @endforeach
                                          
                                            
                                          </select>
                                        
                                    </div>

                                    <div>

                                        <label for="qty" class="block mb-2 text-sm font-medium text-gray-900 ">Quantity</label>
                                        <input  onkeydown="return handleQty(event,0)" type="text" name="addMoreInputFields[0][qty]"  id="qty0" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  />
                                    </div>

                                    <div>

                                        <label for="customer" class="block mb-2 text-sm font-medium text-gray-900 ">Customer</label>
                                        <input  onkeydown="return handleCustomer(event,0)" type="text" name="addMoreInputFields[0][customer]"  id="customer0" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  />
                                    </div>

                                    <div>

                                        <label for="warna" class="block mb-2 text-sm font-medium text-gray-900 ">Warna</label>
                                        <input  onkeydown="return handleWarna(event,0)" type="text" name="addMoreInputFields[0][warna]"  id="warna0" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  />
                                    </div>

                                   
                                    <div>
                                        <button  onclick="remove('0')" type="button" class="text-red-600 inline-flex items-center hover:text-white border border-red-600 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">
                                
                                            <svg class="mr-1 -ml-1 w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" >
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                            </svg>
                                            Remove
                                        </button>
                                
                                    </div> 
                                
                                    
                                </div>

                            </div>
                        </div>

                      

                        
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Submit</button>

                    </div>
                </div>
            </div>
        </section>

    </form>


    @push('jsBody')

        <script>

            $(document).on("keypress", function(e){
                if(e.which == 13){e.preventDefault();}
            })

            function handleName(event, id) {
                console.log(event)
                if (event.key === "Enter") {

                    document.getElementById("size"+id).focus();

                    
                    
                    return false; // Mengembalikan false untuk mencegah aksi default
                }
                return true; // Memastikan bahwa input lainnya tetap berfungsi normal
            }

            function handleSize(event, id) {
                if (event.key === "Enter") {

                    document.getElementById("qty"+id).focus();
                    
                    return false; // Mengembalikan false untuk mencegah aksi default
                }
                return true; // Memastikan bahwa input lainnya tetap berfungsi normal
            }

            function handleQty(event, id) {
                if (event.key === "Enter") {

                    document.getElementById("customer"+id).focus();
                    
                    return false; // Mengembalikan false untuk mencegah aksi default
                }
                return true; // Memastikan bahwa input lainnya tetap berfungsi normal
            }

            function handleCustomer(event, id) {
                if (event.key === "Enter") {

                    document.getElementById("warna"+id).focus();
                    
                    return false; // Mengembalikan false untuk mencegah aksi default
                }
                return true; // Memastikan bahwa input lainnya tetap berfungsi normal
            }

            function handleWarna(event, id) {
                if (event.key === "Enter") {

                    ++id 

                    console.log(id)

                    addLine(id);

                    document.getElementById("name"+id).focus();
                    
                    return false; // Mengembalikan false untuk mencegah aksi default
                }
                return true; // Memastikan bahwa input lainnya tetap berfungsi normal
            }

            function addLine(itemLineId) {
                i = itemLineId;

 

                $("#dynamicAddRemove").append('<div class="grid gap-6 mb-6 md:grid-cols-6 items-end addField'+i+' "id="gridItem'+i+'"> <div> <label for="name" class="block mb-2 text-sm font-medium text-gray-900 ">Nama</label> <input  onkeydown="return handleName(event,'+i+')" type="text" name="addMoreInputFields['+i+'][name]"  id="name'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  /> </div> <div> <label for="size" class="block mb-2 text-sm font-medium text-gray-900 ">Size</label> <select onkeydown="return handleSize(event,'+i+')" id="size'+i+'" name="addMoreInputFields['+i+'][size]"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"> <option value="">Choose</option> @foreach ($sizeList as $index => $item) <option value="{{$index}}">{{$item}}</option> @endforeach </select> </div> <div> <label for="qty" class="block mb-2 text-sm font-medium text-gray-900 ">Quantity</label> <input  onkeydown="return handleQty(event,'+i+')" type="text" name="addMoreInputFields['+i+'][qty]"  id="qty'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  /> </div> <div> <label for="customer" class="block mb-2 text-sm font-medium text-gray-900 ">Customer</label> <input  onkeydown="return handleCustomer(event,'+i+')" type="text" name="addMoreInputFields['+i+'][customer]"  id="customer'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  /> </div> <div> <label for="warna" class="block mb-2 text-sm font-medium text-gray-900 ">Warna</label> <input  onkeydown="return handleWarna(event,'+i+')" type="text" name="addMoreInputFields['+i+'][warna]"  id="warna'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  /> </div> <div> <button  onclick="remove('+i+')" type="button" class="text-red-600 inline-flex items-center hover:text-white border border-red-600 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900"> <svg class="mr-1 -ml-1 w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" > <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /> </svg> Remove </button> </div> </div>');
            
            }

            function remove(val) {
                $('.addField'+val).remove();
            }


           
        </script>

    @endpush
   


</x-layouts.layout>