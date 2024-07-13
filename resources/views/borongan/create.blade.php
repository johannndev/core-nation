<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">New Borongan</p>

       
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

   

   


    <form action="{{route('borongan.postAdd')}}" method="post" >

        @csrf

        <section class="bg-gray-50 dark:bg-gray-900 mb-8">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-4">

                    <div class="">

                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <div>
                                <label for="from" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">From</label>
                                <input type="date" name="from" id="from" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{old('from',$from)}}">

                            </div>

                            <div>
                                <label for="to" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">To</label>
                                <input type="date" name="to" id="to" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{old('to',$to)}}">

                            </div>

                            <div class="col-span-2">
                                <label for="jahit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jahit</label>
                                <select id="jahit" name="jahit"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option selected>Choose</option>
                                    @foreach ($jahitList as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                   
                                
                                </select>

                            </div>


                            <p class="text-xl font-medium mt-4 mb-2">Item</p>

                            <div class="col-span-2 mb-6">
                                <div class="overflow-x-auto ">
                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" class="px-4 py-3">Serial</th>
                                                <th scope="col" class="px-4 py-3">Code</th>
                                                <th scope="col" class="px-4 py-3">Quantity</th>
                                                <th scope="col" class="px-4 py-3">Customer</th>
                                                <th scope="col" class="px-4 py-3">Warna</th>
                                                <th scope="col" class="px-4 py-3">Jahit</th>
                                                <th scope="col" class="px-4 py-3">Total</th>
                                        
                                                
                                            </tr>
                                        </thead>
                                        <tbody id="data-list">
                                          
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-span-2">
                                <label for="permak" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Permak</label>
                                <input type="number" name="permak" id="permak" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{old('permak',0)}}">

                            </div>

                            <div class="col-span-2">
                                <label for="tres" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tres</label>
                                <input type="number" name="tres" id="tres" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{old('tres',0)}}">

                            </div>

                            <div class="col-span-2">
                                <label for="lain2" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Lain2</label>
                                <input type="number" name="lain2" id="lain2" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{old('lain2',0)}}">

                            </div>

                            <div class="col-span-2">
                                <label for="tb" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total</label>
                                <input type="number" name="tb" id="tb" aria-describedby="helper-text-explanation" class="mb-6 bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled value="0">

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

        var totalBorong = 0;
        var permak = 0;
        var tres = 0;
        var lain = 0;
        var total = 0;

        $(document).ready(function() {

            $('#data-list').html('<tr><th colspan="7" class="px-4 py-3 text-center" >Data belum ada</th></tr>');
            
            $('#jahit').on('change', function() {
                var selectedjahit = $(this).val();
                var from = $('#from').val();
                var to = $('#to').val();

                $.ajax({
                    url: "{{route('borongan.ajax')}}",
                    type: 'GET',
                    data: {
                        from:from,
                        to:to,
                        jahit:selectedjahit,
                        _token: '{{csrf_token()}}'
                    },
                    success: function(response) {
                       

                        totalItem(response)

                        // Tampilkan data pengguna di dalam div #user-display
                        var usersHtml = '';
                        if(response.length > 0){
                            response.forEach(function(row) {
                                usersHtml  += `<tr class="border-b dark:border-gray-700 hover:bg-gray-100"> <th scope="row" class="px-4 py-3  whitespace-nowrap "> <a href="`+row.edit_link+`" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">`+row.serial+`</a> </th> <td class="px-4 py-3">`+row.code+`</td> <td class="px-4 py-3">`+row.quantity+`</td> <td class="px-4 py-3">`+row.customer+`</td> <td class="px-4 py-3">`+row.warna+`</td> <td class="px-4 py-3">`+row.ongkos+`</td> <td class="px-4 py-3">`+row.total+`</td> </tr>`;
                            
                            });

                        }else{

                            usersHtml = '<tr><th colspan="7" class="px-4 py-3 text-center" >Data tidak ditemukan</th></tr>';

                        }
                        $('#data-list').html(usersHtml);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $('#permak').on('input', function() {
                 permak = $(this).val();
                 totalBayar()
            });

            $('#tres').on('input', function() {
                 tres = $(this).val();
                 totalBayar()
            });

            $('#lain2').on('input', function() {
                 lain = $(this).val();
                 totalBayar()
            });
        });
        

      

        function totalItem(data){

            // console.log(data);

            const total = data.reduce((accumulator, currentValue) => accumulator + currentValue.total, 0);
           
            totalBorong = total;

            totalBayar()

        }

        function totalBayar(){
            total = parseInt(totalBorong)+parseInt(permak)+parseInt(tres)+parseInt(lain);

            $('#tb').val(total)

            console.log(total);
        }

       
        
    </script>

           
        

    @endpush
   


</x-layouts.layout>

