<x-layouts.layout>

    <x:partial.scan-modal/>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">New Sell</p>

       
    </div>

  

    <div id="alert-border-2">
    
        <div  class="error-wrapper hidden items-center p-4 mb-4 text-red-800 border-t-4 border-red-300 bg-red-50 dark:text-red-400 dark:bg-gray-800 dark:border-red-800" role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <div class="ms-3 text-sm font-medium error-text">
            
            </div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700"  data-dismiss-target="#alert-border-2" aria-label="Close">
            <span class="sr-only">Dismiss</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            </button>
        </div> 
        
        
    </div>



    <form class="myForm" id="myForm">

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

                            <div>
                                <label for="due" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Due</label>
                                <input type="date" name="due" id="due" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >

                            </div>

                            <x-partial.select-addr :dataProp='$dataListPropRecaiver' />

                            <x-partial.select-addr :dataProp='$dataListPropSender' />

                            <div class="col-span-2">
                                <label for="invoice" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Invoice</label>
                                <input type="text" name="invoice" id="invoice" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{old('invoice')}}">

                            </div>
                            <div class="col-span-2">
                                <label for="note" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Note</label>
                                <textarea name="note" id="note" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >{{old('note')}}</textarea>


                            </div>
                        </div>

                        <div class="grid grid-cols-4 md:grid-cols-4 gap-4 mb-4 ">

                            <div class="col-span-3 md:col-span-1">
                                <p class="font-bold">Paid for this transaction</p>
                            </div>

                            <div class="col-span-1 md:col-span-1">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input name="paid" id="paid" type="checkbox" value="1" class="sr-only peer">
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                    
                                </label>
                            </div>

                        </div>

                        <div id="inputPaid" class=" mb-8 hidden">

                            <div class="grid grid-cols-2 gap-4  ">
                                <div>
                                    <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Account</label>
                                    <select id="account" name="account"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option selected>Choose a country</option>

                                        @foreach ($bankList as $item)
                                            <option {{$item->id == "2704" ? 'selected' : ''}} value="{{$item->id}}">{{$item->name}}</option>       
                                        @endforeach
                                    
                                        
                                    </select>
                    
                                </div>
                    
                                <div>
                                    <label for="amount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Amount</label>
                                    <input type="number" name="amount" id="amount" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    
                                </div>
                            </div>

                        </div>

                        
                    

                        <div class="mb-6">
                            <p class="text-lg font-bold mb-4" >Item</p>

                        

                            <div id="dynamicAddRemove">
                               
                                <div class="grid gap-6 mb-6 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-9 items-end addField0 hidden" id="gridItemLoading0">
                                    <div>
                                        <label for="code" class="block mb-2 text-sm font-medium text-gray-900 ">Code </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                                <svg aria-hidden="true" class="inline w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                                </svg>
                                            </div>
                                            <input type="text" id="email-address-icon"  aria-label="disabled input" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled >
                                        </div>
                                    </div>
                                    <div>
                                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 ">Name</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                                <svg aria-hidden="true" class="inline w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                                </svg>
                                            </div>
                                            <input type="text" id="email-address-icon"  aria-label="disabled input" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled >
                                        </div>
                                    </div>
                                    <div>
                                        <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 ">Quantity </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                                <svg aria-hidden="true" class="inline w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                                </svg>
                                            </div>
                                            <input type="text" id="email-address-icon"  aria-label="disabled input" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled >
                                        </div>
                                    </div>  
                                    <div>
                                        <label for="company" class="block mb-2 text-sm font-medium text-gray-900 ">Warehouse</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                                <svg aria-hidden="true" class="inline w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                                </svg>
                                            </div>
                                            <input type="text" id="email-address-icon"  aria-label="disabled input" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled >
                                        </div>
                                    </div>  
                                
                                    <div>
                                        <label for="price" class="block mb-2 text-sm font-medium text-gray-900 ">Price</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                                <svg aria-hidden="true" class="inline w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                                </svg>
                                            </div>
                                            <input type="text" id="email-address-icon"  aria-label="disabled input" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled >
                                        </div>
                                    </div> 
                                    
                                    <div>
                                        <label for="discount" class="block mb-2 text-sm font-medium text-gray-900 ">Discount</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                                <svg aria-hidden="true" class="inline w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                                </svg>
                                            </div>
                                            <input type="text" id="email-address-icon"  aria-label="disabled input" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled >
                                        </div>
                                    </div> 
                                
                                    <div>
                                        <label for="subtotal" class="block mb-2 text-sm font-medium text-gray-900 ">Subtotal</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                                <svg aria-hidden="true" class="inline w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                                </svg>
                                            </div>
                                            <input type="text" id="email-address-icon"  aria-label="disabled input" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled >
                                        </div>
                                    </div> 
                                
                                    <div>
                                        
                                
                                    </div> 
                                
                                    
                                </div>
                          
                                <div class="grid gap-6 mb-6 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-9 items-end addField0 "id="gridItem0">
                                    <div class="flex items-end w-full ">

                                        <div class="w-full">

                                            <input type="checkbox" id="myCheckbox0" hidden class="hidden">

                                            <input type="text" name="addMoreInputFields[0][itemId]"  id="id0"  placeholder=""  aria-valuetext="0" aria-label="id" hidden/>
                                
                                            <label for="code" class="block mb-2 text-sm font-medium text-gray-900 ">Code</label>

                                            <div class="flex">

                                                {{-- md:hidden --}}

                                                <div class="block md:hidden">
                                                    <button onclick="starScanButton(0)" id="openModalButton" type="button" class="focus:outline-none inline-flex items-center text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-3 py-2.5 mr-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                                    
                        
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M8,2A1,1,0,0,1,8,4H4V8A1,1,0,0,1,2,8V3A1,1,0,0,1,3,2ZM8,20H4V16a1,1,0,0,0-2,0v5a1,1,0,0,0,1,1H8a1,1,0,0,0,0-2Zm13-5a1,1,0,0,0-1,1v4H16a1,1,0,0,0,0,2h5a1,1,0,0,0,1-1V16A1,1,0,0,0,21,15Zm0-6a1,1,0,0,0,1-1V3a1,1,0,0,0-1-1H16a1,1,0,0,0,0,2h4V8A1,1,0,0,0,21,9Zm1,2H2a1,1,0,0,0,0,2H22a1,1,0,0,0,0-2Z"></path></g></svg>
                        
                                                       
                                                    </button>
                                                </div>

                                                <input type="search" data-input="value" data-name="code" data-id="0" onkeydown="return handleCode(event,0)" name="addMoreInputFields[0][code]"  id="code0" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" inputmode="search" />

                                            </div>

                                            

                                        </div>

                                      

                                        
                                    </div>
                                    <div class="">
                                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 ">Name</label>
                                        
                                        <div class="flex items-end w-full ">

                                        
                                            <div class=" w-full">

                                                <div class="relative ">
                                                    <select class="select2-ajax-item" id="name0" name="addMoreInputFields[0][name]" data-customId="0">
                                                        
                                                        <option ></option>
                                                    </select>
                                    
                                                    @error('')
                                                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                                    @enderror
                                                    
                                                </div>
                                            </div>
                                            
                                        </div>
                                   
                                
                                    </div>
                                    <div class="">
                                        <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 ">Quantity </label>

                                        <div class="flex items-end w-full">

                                            <div class="w-full">
                                                
                                                <input data-name="quantity" data-id="0"  onkeyup="return handleQty(event,0)" type="search" name="addMoreInputFields[0][quantity]"  id="quantity0" class="qty register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" inputmode="search"/>
                                            </div>

                                           
                                        </div>
                                    </div>  
                                    <div class="">
                                        <label for="wh" class="block mb-2 text-sm font-medium text-gray-900 ">Warehouse</label>
                                        <input  type="text" name="addMoreInputFields[0][wh]"   id="wh0"class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" disabled/>
                                    </div>  
                                
                                    <div class="">
                                        <label for="price" class="block mb-2 text-sm font-medium text-gray-900 ">Price</label>

                                        <div class="flex items-end w-full">

                                            <div class="w-full">
                                                <input data-name="price" data-id="0"  onkeyup="return handlePrice(event,0)" type="search" name="addMoreInputFields[0][price]"  id="price0" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" inputmode="search"/>
                                            </div>

                                        </div>
                                        
                                    </div> 
                                    
                                    <div class="">
                                        <label for="discount" class="block mb-2 text-sm font-medium text-gray-900 ">Discount</label>

                                        <div class="flex items-end w-full">

                                            <div class="w-full">
                                                <input data-name="discount" data-id="0" onkeyup="return handleDisc(event,0)" type="search"   name="addMoreInputFields[0][discount]"   id="discount0" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" inputmode="search" />
                                            </div>

                                        </div>
                                        

                                    </div> 
                                
                                    <div class="">
                                        <label for="subtotal" class="block mb-2 text-sm font-medium text-gray-900 ">Subtotal</label>
                                        <input type="text" name="addMoreInputFields[0][subtotal]"  id="subtotal0" class="sto register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" aria-valuetext="0" aria-label="subtotal" />
                                    </div> 
                                
                                    <div class="">
                                        <button  onclick="remove('0')" type="button" class="text-red-500 inline-flex items-center hover:text-white border border-red-500 hover:bg-red-500 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-500 dark:focus:ring-red-900">
                                
                                            <svg class="mr-1 -ml-1 w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" >
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                            </svg>
                                            Remove
                                        </button>
                                
                                    </div> 
                                
                                    
                                </div>

                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">

                            <div class="col-span-2">
                                <label for="disc" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Discount %</label>
                                <input type="number" value="0" name="disc" id="disc" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                            </div>

                            
                            <div >
                                <label for="adjustment" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Adjustment</label>
                                <input type="number" value="0" name="adjustment" id="adjustment" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                            </div>

                            <div >
                                <label for="totqty" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total Quantity</label>
                                <input type="text" name="totqty" id="totqty" aria-describedby="helper-text-explanation" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled>

                            </div>

                            <div >
                                <label for="total" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total</label>
                                <input type="text" name="total" id="total" aria-describedby="helper-text-explanation" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled>

                            </div>

                            <div >
                                <label for="tbc" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total Before Disc</label>
                                <input type="text" name="tbc" id="tbc" aria-describedby="helper-text-explanation" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled>

                            </div>

                            <div >
                                <label for="ppn" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">PPN</label>
                                <input type="text" name="ppn" id="ppn" aria-describedby="helper-text-explanation" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled>

                            </div>

                            <div >
                                <label for="tbppn" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total before PPN</label>
                                <input type="text" name="tbppn" id="tbppn" aria-describedby="helper-text-explanation" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled>

                            </div>

                            <div class="col-span-2">
                                <label for="ongkir" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ongkir</label>
                                <input type="number" value="0" name="ongkir" id="ongkir" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                            </div>

                        </div>

                        <x-layout.submit-button />

                    </div>
                </div>
            </div>
        </section>

    </form>



    
@push('jsBody')

    <script>
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth' // gunakan 'auto' jika tidak ingin animasi
            });
        }

        $(document).ready(function () {
            $('#myForm').on('submit', function (e) {
                e.preventDefault();

                
                let date= $('#date').val();
                let due= $('#due').val();
                let customer= $('#customer').val();
                let warehouse= $('#warehouse').val();
                let invoice= $('#invoice').val();
                let note= $('#note').val();
                let account= null;
                let paid = null;
                let ongkir= $('#ongkir').val();
                let disc= $('#disc').val();
                let adjustment= $('#adjustment').val();
                let dataItems = [];

                const paidCheck = document.getElementById('paid');

                if (paidCheck.checked) {
                    paid = paidCheck.value;
                    account= $('#account').val()
                 
                }


                $('input[name^="addMoreInputFields"], select[name^="addMoreInputFields"]').each(function () {
                    let nameAttr = $(this).attr('name'); // contoh: addMoreInputFields[0][code]
                    let matches = nameAttr.match(/addMoreInputFields\[(\d+)\]\[(\w+)\]/);

                    if (matches) {
                        let index = matches[1];  // ambil index 0, 1, dst
                        let field = matches[2];  // ambil nama field: itemId, code, etc

                        if (!dataItems[index]) {
                            dataItems[index] = {};
                        }

                        dataItems[index][field] = $(this).val();
                    }
                });

                console.log(dataItems);
                            
            
                

                // Bersihkan error sebelumnya
                $('.text-red-600').remove();
                $('#error-global').html('');

                $.ajax({
                    url: "{{route('transaction.postSell')}}",
                    type: "POST",
                    data: {
                        date:date,
                        due:due,
                        customer:customer,
                        warehouse:warehouse,
                        invoice:invoice,
                        note:note,
                        paid:paid,
                        account:account,
                        disc:disc,
                        ongkir:ongkir,
                        adjustment:adjustment,
                        addMoreInputFields:dataItems,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (res) {

                        if(res.status == 'error'){

                            scrollToTop();

                            $('.error-wrapper')
                                .removeClass('hidden')
                                .addClass('flex');

                            $('.error-text')
                                .text(res.message);

                            $(".submit-btn").removeClass('hidden');
                            $(".loading-btn").addClass('hidden');
                        

                        }

                        if(res.status == 'ok'){

                            let redirectUrl = "{{ route('transaction.success', ':id') }}".replace(':id', res.trx);
                            window.location.href = redirectUrl;

                        }
                        console.log(res);
                    
                    },
                
                });

            
            });
        });
    </script>

            
@endpush


    @include('layouts.js.jsutama')

    @push('jsBody')
        
        
    @endpush
   


</x-layouts.layout>