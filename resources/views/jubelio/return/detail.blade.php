<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Return from jubelio</p>

       
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

   

   


    <form class="myForm" id="myForm" action="{{route('jubelio.return.store',$rid)}}" method="post" >

        @csrf

        <section class="bg-gray-50 dark:bg-gray-900 mb-8">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-4">

                    <div class="">

                        <div class="grid grid-cols-3 gap-4 mb-8">
                            <div>
                                <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Invoice</label>
                                <p class="font-bold">{{$data->invoice}}</p>

                            </div>

                            <div>
                                <label for="due" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sender</label>
                                <p class="font-bold">{{$data->sender->name}}</p>

                            </div>

                            <div>
                                <label for="due" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Recaiver</label>
                                <p class="font-bold">{{$data->receiver->name}}</p>

                            </div>

                           

                        </div>

                        <div>
                            <p class="font-bold mb-2 mt-6">Pilih item yang di return</p>
                            <ul class="grid w-full gap-6 grid-cols-1">

                                @forelse ($data->transactionDetail as $itemTd)

                                @php
                                    $idItem = $itemTd->item->id;
                                    $url = $itemTd->item->getImageUrl();
                                @endphp

                                
                            

                                <li>
                                    <input type="checkbox" id="{{$itemTd->id}}" name="return_item[]" value="{{$itemTd->item_id}}" data-harga="{{$itemTd->total}}" class="hidden peer harga-checkbox" />
                                    <label for="{{$itemTd->id}}" class="inline-flex items-center w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 dark:peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700"> 
                                        
                                        <div class=" mr-3 w-14">
                                            <x-partial.image type="h-14 w-14 print:h-10 print:w-10" :url="$url" />
                                        </div>
                                        <div class="block">
                                            <div class="w-full font-semibold">
                                                <a href="{{route('item.detail',$itemTd->item->id)}}">{{$itemTd->item->code}}</a>
                                            </div>
                                            <div class="w-full text-sm">
                                                {{$itemTd->quantity}} x Rp {{Number::format($itemTd->price)}}
                                            </div>

                                            <div class="w-full mt-2">
                                                Rp {{Number::format($itemTd->total)}}
                                            </div>
                                        </div>
                                       
                                    </label>
                                </li>

                                @empty
                                
                                @endforelse

                               
                            </ul>
                        </div>

                        <div class="mt-4">
                            <div class="grid grid-cols-2 gap-4 mb-8">

                                <div >
                                    <label for="adjustment" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Adjustment</label>
                                    <input type="number" value="0" name="adjustment" id="adjustment" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    
                                </div>

                                <div >
                                    <label for="total" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total</label>
                                    Rp <span id="total" class="text-lg font-bold">0</span>
    
                                </div>
                            </div>

                        </div>

                     
                      

                        <button data-modal-target="popup-modal-manual" data-modal-toggle="popup-modal-manual" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800" type="button">
                            Submit
                        </button>

                        <div id="popup-modal-manual" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative p-4 w-full max-w-md max-h-full">
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal-manual">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                    <div class="p-4 md:p-5 text-center">
                                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Kamu yakin ingin membuat transaksi ini ?</h3>
                    
                                      
                                        <x-layout.submit-button />
                                      
                                        <button data-modal-hide="popup-modal-manual" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Tidak, batalkan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    

                    </div>
                </div>
            </div>
        </section>

    </form>

   
    @push('jsBody')

    <script>
        function hitungTotal() {
            let total = 0;

            // Menjumlahkan harga dari checkbox yang dicentang
            document.querySelectorAll('.harga-checkbox:checked').forEach(checkbox => {
                total += parseInt(checkbox.getAttribute('data-harga'));
            });

            // Menambahkan nilai adjustment
            let adjustment = parseInt(document.getElementById('adjustment').value) || 0;
            total += adjustment;

            // Menampilkan total
            document.getElementById('total').textContent = total.toLocaleString('id-ID');
        }

        // Event listener untuk checkbox dan input adjustment
        document.querySelectorAll('.harga-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', hitungTotal);
        });

        document.getElementById('adjustment').addEventListener('input', hitungTotal);
    </script>
        
    @endpush

</x-layouts.layout>