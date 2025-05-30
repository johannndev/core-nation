<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">New Adjust</p>

       
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

   

   


    <form class="myForm" id="myForm" action="{{route('transaction.postAdjust')}}" method="post" >

        @csrf

        <section class="bg-gray-50 dark:bg-gray-900 mb-8">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-4">

                    <div class="">

                        <div class="grid grid-cols-1 gap-4 mb-6">
                            <div>
                                <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date</label>
                                <input type="date" name="date" id="date" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{date('Y-m-d')}}">

                            </div>

                            <div class="">
                                <label for="invoice" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Invoice</label>
                                <input type="text" name="invoice" id="invoice" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{old('invoice')}}">

                            </div>

                           
                           
                            
                           

                           
                        </div>

                        <div class="mb-6">

                                <div class="grid gap-6 mb-6 grid-cols-1 md:grid-cols-2 items-end ">
                                   
                                    <div>
                                        <label for="receiver" class="block mb-2 text-sm font-medium text-gray-900 ">Credit(+)</label>
                                       
                                        <div class="">
                                            <div class="relative ">
                                                <select class="select2-ajax-item" id="receiver" name="receiver" data-customId="0">
                                                    
                                                    <option></option>
                                                </select>
                                
                                                @error('')
                                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                                @enderror
                                                
                                            </div>

                                        </div>
                                   
                                
                                    </div>
                                    <div>
                                        <label for="value-receiver" class="block mb-2 text-sm font-medium text-gray-900 ">value </label>
                                        <input type="text" name="value-receiver"  id="value-receiver" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled />
                                    </div>    

                                    <div>
                                        <label for="sender" class="block mb-2 text-sm font-medium text-gray-900 ">Debit(+)</label>
                                       
                                        <div class="">
                                            <div class="relative ">
                                                <select class="select2-ajax-item" id="sender" name="sender" data-customId="0">
                                                    
                                                    <option></option>
                                                </select>
                                
                                                @error('')
                                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                                @enderror
                                                
                                            </div>

                                        </div>
                                   
                                
                                    </div>
                                    <div>
                                        <label for="value-sender" class="block mb-2 text-sm font-medium text-gray-900 ">value </label>
                                        <input type="text" name="value-sender"  id="value-sender" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled />
                                    </div>    
                                    
                                </div>

                            
                        </div>

                        <div class="grid grid-cols-1 gap-4 mb-8">
                            <div class="">
                                <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Note</label>
                                <input type="text" name="description" id="description" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{old('description')}}">

                            </div>

                            <div class="">
                                <label for="total" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total</label>
                                <input type="text" name="total" id="total" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{old('total')}}">

                            </div>
                           
                        </div>

                       
                        
                        <x-layout.submit-button />


                    </div>
                </div>
            </div>
        </section>

    </form>


   
    @include('layouts.js.transaction-adjust')

   


</x-layouts.layout>