<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Detail Transaction #{{$data->id}}</p>

       
    </div>

    <div class="mb-8">


        <div class="flex justify-between">
            <div class="flex items-center">
                <div>
                    <img src="{{ asset('img/logo.png') }}" alt="" srcset="">
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-500">Invoice #{{$data->invoice}}</p>
                    <p class="font-bold">
                    @isset($data->receiver)
                      <a href="{{$data->receiver->getDetailLink()}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$data->receiver->name}}</a>
                    @endisset
                </div>
            </div>
            <div>

                <div class="text-right">
                    <p class="text-sm text-gray-500">Total</p>
                    <p class="font-bold text-lg md:text-xl">{{number_format($data->total,2)}}</p>
                </div>

            </div>
        </div>

        <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">

        <div class="grid md:grid-cols-2 gap-4">

            <div>
                <div class="bg-white dark:bg-gray-800 relative print:shadow-none shadow-md sm:rounded-lg overflow-hidden">
                    <div class="grid grid-cols-1 divide-y print:divide-y-0">
                        @isset($data->sender)

                        <div>
                            <div class="grid grid-cols-5 p-4 print:p-0 text-sm">
                                <div class="col-span-2">
                                    <p class="font-bold">From</p>
                                </div>
                                <div class="col-span-3">
                                    <a href="{{$data->sender->getDetailLink()}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$data->sender->name}}</a>
                                </div>
                            </div>
                        </div>

                        @endisset
                       
                        @if ($data->type != 8)

                        <div>
                            <div class="grid grid-cols-5 p-4 print:p-0 text-sm">
                                <div class="col-span-2">
                                    <p class="font-bold">To</p>
                                </div>
                                <div class="col-span-3">
                                  @isset($data->receiver)
                                    <a href="{{$data->receiver->getDetailLink()}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$data->receiver->name}}</a>
                                  @endisset
                                </div>
                            </div>
                        </div>
                            
                        @endif

                       
                        

                        @if ($data->description)

                        <div>
                            <div class="grid grid-cols-5 p-4 print:p-0 text-sm">
                                <div class="col-span-2">
                                    <p class="font-bold">Note</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->description}}</p>
                                </div>
                            </div>
                        </div>
                            
                        @endif

                        @if ($data->submit_type == 1 || $data->submit_type == 1)

                        <div>
                            <div class="grid grid-cols-5 p-4 print:p-0 text-sm">
                                <div class="col-span-2">
                                    <p class="font-bold">System Submit</p>
                                </div>
                                <div class="col-span-3">
                                    @if ($data->submit_type == 1)
                                        Aria
                                    @elseif ($data->submit_type == 2)
                                        Jubelio Webhook
                                    @else
                                    
                                    @endif
                                
                                </div>
                            </div>
                        </div>
                            
                        @endif
                        
                    </div>
                </div>
            </div>

            <div>
                <div class="bg-white dark:bg-gray-800 relative  print:shadow-none shadow-md sm:rounded-lg overflow-hidden">
                    <div class="grid grid-cols-1 divide-y print:divide-y-0">
                        <div>
                            <div class="grid grid-cols-5 p-4 print:p-0 text-sm">
                                <div class="col-span-2">
                                    <p class="font-bold">Date</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{\Carbon\Carbon::parse($data->date)->format('d/m/Y')}}</p>
                                </div>
                            </div>
                        </div>
                       
                        <div>
                            <div class="grid grid-cols-5 p-4 print:p-0 text-sm">
                                <div class="col-span-2">
                                    <p class="font-bold">Due</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->due == "0000-00-00" ? "-" : \Carbon\Carbon::parse($data->due)->format('d/m/Y')}}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="grid grid-cols-5 p-4 print:p-0 text-sm">
                                <div class="col-span-2">
                                    <p class="font-bold">Invoice Discount</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->discount}}%</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="grid grid-cols-5 p-4 print:p-0 text-sm">
                                <div class="col-span-2">
                                    <p class="font-bold">Adjustment</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{number_format($data->adjustment,2)}}</p>
                                </div>
                            </div>
                        </div>

                        @if($data->ppn > 0)

                        <div>
                            <div class="grid grid-cols-5 p-4 print:p-0 text-sm">
                                <div class="col-span-2">
                                    <p class="font-bold">PPN</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{number_format($data->ppn,2)}}</p>
                                </div>
                            </div>
                        </div>

                        @endif

                        <div>
                            <div class="grid grid-cols-5 p-4 print:p-0 text-sm">
                                <div class="col-span-2">
                                    <p class="font-bold">Items</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->total_items}}</p>
                                </div>
                            </div>
                        </div>


                        @if($data->user)

                        <div>
                            <div class="grid grid-cols-5 p-4 print:p-0 text-sm">
                                <div class="col-span-2">
                                    <p class="font-bold">User</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{$data->user->username}}</p>
                                </div>
                            </div>
                        </div>

                        @endif

                        <div>
                            <div class="grid grid-cols-5 p-4 print:p-0 text-sm">
                                <div class="col-span-2">
                                    <p class="font-bold">Total Before Disc</p>
                                </div>
                                <div class="col-span-3">
                                    <p>{{number_format($data->real_total,2)}}</p>
                                </div>
                            </div>
                        </div>

                        @if ($cekJubelio > 0  && $data->submit_type == 1)
                            
                            <div>
                                <div class="grid grid-cols-5 p-4 print:p-0 text-sm">
                                    <div class="col-span-2">
                                        <p class="font-bold">Jubelio</p>
                                    </div>
                                    <div class="col-span-3">
                                        
                                        @if ($notNullCount > 0)
                                            Adjustment by {{ $submitBy }}
                                        @else
                                            -
                                        @endif

                                      
                                      
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($data->jubelio_return > 0)

                        <div>
                            <div class="grid grid-cols-5 p-4 print:p-0 text-sm">
                                <div class="col-span-2">
                                    <p class="font-bold">Return from jubelio</p>
                                </div>
                                <div class="col-span-3">
                                    @if ($data->jubelio_return == 1)
                                        <a href="{{route('transaction.jubelioReturn',$data->id)}}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium     rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Return Detail</a>

                                    @elseif ($data->jubelio_return == 2)
                                        <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-green-900 dark:text-green-300">Returned</span>

                                    @else

                                    @endif

                                </div>
                            </div>
                        </div>

                        @endif

                        <div>
                            <div class="grid grid-cols-5 p-4 print:p-0 text-sm">
                                <div class="col-span-2">
                                    <p class="font-bold">Submit Type</p>
                                </div>
                                <div class="col-span-3">
                                    {{ $data->submit_type }}
                                 

                                </div>
                            </div>
                        </div>

                     
                        
                    </div>
                </div>
            </div>

        </div>

       
       

    </div>

    <section class="bg-gray-50 dark:bg-gray-900 mb-8">
        <div class="">
            <div class="relative overflow-hidden bg-white print:shadow-none shadow-md dark:bg-gray-800 sm:rounded-lg">
                <div class="print:hidden flex  px-4 py-3  flex-row items-center justify-between ">
                    <div class="flex items-center flex-1 space-x-4">
                        <button onClick="window.print()" type="button" class="flex items-center justify-center flex-shrink-0 px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            <svg  class="w-4 h-4 mr-2"  aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M8 3a2 2 0 0 0-2 2v3h12V5a2 2 0 0 0-2-2H8Zm-3 7a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h1v-4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v4h1a2 2 0 0 0 2-2v-5a2 2 0 0 0-2-2H5Zm4 11a1 1 0 0 1-1-1v-4h8v4a1 1 0 0 1-1 1H9Z" clip-rule="evenodd"/>
                            </svg>
                            Print
                        </button>
                    </div>
                    <div class="flex items-center flex-1 space-x-4 justify-end">

                        @if ($pdfFile > 0)
                            
                            <button type="button" id="send-wa" data-modal-target="sendWaModal" data-modal-toggle="sendWaModal" class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 rounded-lg">
                                
                            Send WhatsApp

                            </button>

                        
                            
                            <!-- Main modal -->
                            <div id="sendWaModal" tabindex="-1" aria-hidden="true" class=" hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
                                <div class="relative p-4 w-full max-w-md h-full md:h-auto">
                                    <!-- Modal content -->
                                    <div class="relative p-4 mt-40 md:mt-0 text-center bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                                        <button type="button" class="text-gray-400 absolute top-2.5 right-2.5 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="sendWaModal">
                                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>

                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="text-gray-400 dark:text-gray-500 w-11 h-11 mb-3.5 mx-auto">
                                            <path d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625Z" />
                                            <path d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                                        </svg>
                                          

                                        <p class=" text-gray-500 dark:text-gray-300">Kirim link PDF via WhatsApp</p>

                                        <form action="{{route('transaction.sendToWhatsapp',$data->id)}}" method="post">

                                            @csrf
                                           

                                            <div class="col-span-2 mt-4">
                                                <label for="wa" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white ">No. WhatsApp</label>
                                                <input type="text" name="wa" id="wa" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="+62811111111111" value="{{old('wa')}}">
                
                                            </div>
                                        
                                            <div class="flex justify-center items-center space-x-4 mt-4">
                                                <button data-modal-toggle="sendWaModal" type="button" class="py-2 px-3 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                                    No, cancel
                                                </button>

                                            

                                                <button type="submit" class="py-2 px-3 text-sm font-medium text-center text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-900">
                                                    Send now
                                                </button>
                                        
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>

                        @else

                        <a href="{{ route('transaction.genereteInvoice', $data->id) }}" class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg">
                            Generate  PDF
                        </a>


                        @endif

                        @if ($cekJubelio > 0 && $countAll != $limitShow && $data->submit_type == 1)

                        <a href="{{ route('transaction.detailJubelioSync', $data->id) }}" class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg">
                            <svg class="h-3.5 w-3.5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                                <path fill-rule="evenodd" d="M4.755 10.059a7.5 7.5 0 0 1 12.548-3.364l1.903 1.903h-3.183a.75.75 0 1 0 0 1.5h4.992a.75.75 0 0 0 .75-.75V4.356a.75.75 0 0 0-1.5 0v3.18l-1.9-1.9A9 9 0 0 0 3.306 9.67a.75.75 0 1 0 1.45.388Zm15.408 3.352a.75.75 0 0 0-.919.53 7.5 7.5 0 0 1-12.548 3.364l-1.902-1.903h3.183a.75.75 0 0 0 0-1.5H2.984a.75.75 0 0 0-.75.75v4.992a.75.75 0 0 0 1.5 0v-3.18l1.9 1.9a9 9 0 0 0 15.059-4.035.75.75 0 0 0-.53-.918Z" clip-rule="evenodd" />
                            </svg>
                              
                           Jubelio Adjustment
                        </a>
                            
                        @endif
                       

                        <button type="button" id="deleteButton" data-modal-target="deleteModal" data-modal-toggle="deleteModal" class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 rounded-lg">
                            

                            <svg class="h-3.5 w-3.5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                            </svg>
                              
                           Delete
                        </button>

                      
                        
                        <!-- Main modal -->
                        <div id="deleteModal" tabindex="-1" aria-hidden="true" class=" hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
                            <div class="relative p-4 w-full max-w-md h-full md:h-auto">
                                <!-- Modal content -->
                                <div class="relative p-4 mt-40 md:mt-0 text-center bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                                    <button type="button" class="text-gray-400 absolute top-2.5 right-2.5 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="deleteModal">
                                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                    <svg class="text-gray-400 dark:text-gray-500 w-11 h-11 mb-3.5 mx-auto" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    <p class=" text-gray-500 dark:text-gray-300">Are you sure you want to delete this item?</p>
                                   
                                    <div class="flex justify-center items-center space-x-4 mt-4">
                                        <button data-modal-toggle="deleteModal" type="button" class="py-2 px-3 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                            No, cancel
                                        </button>

                                        <form action="{{route('transaction.destroy',$data->id)}}" method="post">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="py-2 px-3 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                                                Yes, I'm sure
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                   
                        
                    </div>
                </div>

                <div class="print:hidden flex flex-wrap px-4 py-3 space-y-2  items-center  lg:space-y-0 ">
                    <div class="w-full md:w-auto md:mr-6">
                        <p class="me-2">Show:</p>
                    </div>

                    <div class="mr-4">
                        <div class="flex items-center ">
                            <input checked id="image-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="image-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Image</label>
                        </div>
                    </div>

                    <div class="mr-4">
                        <div class="flex items-center ">
                            <input checked id="barcode-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="barcode-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Barcode</label>
                        </div>
                    </div>

                    <div class="mr-4">
                        <div class="flex items-center ">
                            <input id="sku-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="sku-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">SKU</label>
                        </div>
                    </div>

                    <div class="mr-4">
                        <div class="flex items-center ">
                            <input id="wh-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="wh-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Warehouse Stok</label>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full print:table-fixed text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs print:text-[10px] text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="image-col  px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Image</th>
                                <th scope="col" class="barcode-col  px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Barcode</th>
                                <th scope="col" class="sku-col hidden px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">SKU</th>
                                <th scope="col" class="px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Code</th>
                                <th scope="col" class="px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Name</th>
                                <th scope="col" class="px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Desc</th>
                                <th scope="col" class="sku-col hidden px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">NB</th>
                                <th scope="col" class="px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Quantity</th>
                                <th scope="col" class="px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Price</th>
                                <th scope="col" class="px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Discount(%)</th>
                                <th scope="col" class="px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">Sub-Total</th>
                                @foreach ($nameWh as $wh)
                                    <th scope="col" class=" wh-col hidden px-4 py-3 print:px-0 print:py-0 print:break-words print:text-wrap">{{$wh}}</th>
                                @endforeach
                               
                                
                            </tr>
                        </thead>
                        <tbody  id="accordion-collapse" data-accordion="collapse" class="print:text-[10px]">

                            @forelse ($data->transactionDetail as $itemTd)
                            @php
                                $idItem = $itemTd->item->id;
                                $url = $itemTd->item->getImageUrl();
                            @endphp
                            
                           
                            <tr class="border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                           
                                <th scope="row" id="" class="image-col  px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class=" mr-3">
                                        <x-partial.image type="h-20 w-20 print:h-10 print:w-10" :url="$url" />
                                    </div>

                                </th>

                                <td class="barcode-col  px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 print:whitespace-normal print:break-words  whitespace-nowrap dark:text-white">
                                  <a href="{{route('item.detail',$itemTd->item->id)}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$itemTd->item->id}}</a>
                                </td>

                                <td class="sku-col hidden px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 print:whitespace-normal print:break-words  whitespace-nowrap dark:text-white">
                                    <a href="{{route('item.detail',$itemTd->item->id)}}">{{$itemTd->item->code}}</a>
                                </td>


                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 print:whitespace-normal print:break-words  whitespace-nowrap dark:text-white">
                                    {{$itemTd->item->getItemCode()}}
                                </td>
                               
                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 print:whitespace-normal print:break-words  whitespace-normal max-w-40 dark:text-white">
                                    <p class="min-w-40 print:min-w-0 print:whitespace-normal print:break-words ">{{$itemTd->item->getItemName()}}</p>
                                </td>

                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900  whitespace-normal dark:text-white">
                                    <p class="min-w-40 print:min-w-0  print:whitespace-normal print:break-words ">{{$itemTd->item->group? $itemTd->item->group->description : $itemTd->item->description}}</p>
                                    
                                </td>
                                <td class="sku-col hidden px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{$itemTd->description2}}
                                </td>

                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{$itemTd->quantity}}
                                </td>

                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{Number::format($itemTd->price)}}
                                </td>

                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{Number::format($itemTd->discount)}}
                                </td>

                                <td class="px-4 py-2 print:px-0 print:py-0 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{Number::format($itemTd->total)}}
                                </td>

                                <x-transaction.warehouse-item  :idItem="$idItem"/>
                               
                            </tr>
                          
                                
                            @empty
                                
                            @endforelse
                            
                            
                        </tbody>
                    </table>
                </div>

                <div class="mt-2 hidden print:block">
                    

                    <div class="grid grid-cols-12">

                        <div class="col-span-4">
                            <p>Yang Mengetahui,</p>
                        </div>

                        <div class="col-span-4">
                            <p>Pemberi,</p>
                        </div>

                        <div class="col-span-4">
                            <p>Penerima,</p>
                        </div>

                    </div>
                </div>
                {{-- <nav class="flex flex-col items-start justify-between p-4 space-y-3 md:flex-row md:items-center md:space-y-0" aria-label="Table navigation">
                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                        Showing
                        <span class="font-semibold text-gray-900 dark:text-white">1-10</span>
                        of
                        <span class="font-semibold text-gray-900 dark:text-white">1000</span>
                    </span>
                    <ul class="inline-flex items-stretch -space-x-px">
                        <li>
                            <a href="#" class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                <span class="sr-only">Previous</span>
                                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-center px-3 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">1</a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-center px-3 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">2</a>
                        </li>
                        <li>
                            <a href="#" aria-current="page" class="z-10 flex items-center justify-center px-3 py-2 text-sm leading-tight border text-primary-600 bg-primary-50 border-primary-300 hover:bg-primary-100 hover:text-primary-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">3</a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-center px-3 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">...</a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-center px-3 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">100</a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                <span class="sr-only">Next</span>
                                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </li>
                    </ul>
                </nav> --}}
            </div>
        </div>
      </section>


      @push('jsBody')

      <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toggleNamaImage = document.getElementById('image-checkbox');
            var namaColumnImage = document.querySelectorAll('.image-col');
            var toggleNamaBarcode = document.getElementById('barcode-checkbox');
            var namaColumnBarcode = document.querySelectorAll('.barcode-col');
            var toggleNamaSku = document.getElementById('sku-checkbox');
            var namaColumnSku = document.querySelectorAll('.sku-col');
            var toggleNamaWh = document.getElementById('wh-checkbox');
            var namaColumnWh = document.querySelectorAll('.wh-col');

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

            toggleNamaBarcode.addEventListener('change', function() {
                if (toggleNamaBarcode.checked) {
                    namaColumnBarcode.forEach(function(barcode) {
                        barcode.classList.remove('hidden');
                    });
                } else {
                    namaColumnBarcode.forEach(function(barcode) {
                        barcode.classList.add('hidden');
                    });
                }
            });

            toggleNamaSku.addEventListener('change', function() {
                if (toggleNamaSku.checked) {
                    namaColumnSku.forEach(function(sku) {
                        sku.classList.remove('hidden');
                    });
                } else {
                    namaColumnSku.forEach(function(sku) {
                        sku.classList.add('hidden');
                    });
                }
            });

            toggleNamaWh.addEventListener('change', function() {
                if (toggleNamaWh.checked) {
                    namaColumnWh.forEach(function(wh) {
                        wh.classList.remove('hidden');
                    });
                } else {
                    namaColumnWh.forEach(function(wh) {
                        wh.classList.add('hidden');
                    });
                }
            });
        });

        // $('#image-checkbox').change(function(){
        //     if (this.checked) {
        //        console.log('cek')
              
        //        document.getElementById("image-col").classList.remove("hidden");
               
        //     } else {
        //         document.getElementById("image-col").classList.add("hidden");
        //     }
        // });
      </script>
          
      @endpush

</x-layouts.layout>
