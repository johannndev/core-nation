<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12" onkeydown="javascript:if(window.event.keyCode == 13) window.event.keyCode = 9;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div id="dynamicAddRemove">
                        <div class="grid gap-6 mb-6 md:grid-cols-8 addField">
                            <div>
                                <label for="code" class="block mb-2 text-sm font-medium text-gray-900 ">Code</label>
                                <input type="text"  id="code1" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  aria-valuetext="1" aria-label="code"/>
                            </div>
                            <div>
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 ">Name</label>
                                <input type="text"  id="name1" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  aria-valuetext="1" aria-label="name" />
                            </div>
                            <div>
                                <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 ">Quantity </label>
                                <input type="text"  id="quantity1" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  aria-valuetext="1" aria-label="quantity"/>
                            </div>  
                            <div>
                                <label for="company" class="block mb-2 text-sm font-medium text-gray-900 ">Warehouse</label>
                                <input type="text"   id="company1" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" aria-valuetext="1" aria-label="warehouse" />
                            </div>  

                            <div>
                                <label for="price" class="block mb-2 text-sm font-medium text-gray-900 ">Price</label>
                                <input type="text"  id="price1" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  aria-valuetext="1" aria-label="price"/>
                            </div> 
                            
                            <div>
                                <label for="discount" class="block mb-2 text-sm font-medium text-gray-900 ">Discount</label>
                                <input type="text"  id="discount1" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" aria-valuetext="1" aria-label="discount" />
                            </div> 

                            <div>
                                <label for="subtotal" class="block mb-2 text-sm font-medium text-gray-900 ">Subtotal</label>
                                <input type="text"  id="subtotal1" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" aria-valuetext="1" aria-label="subtotal" />
                            </div> 

                            <div>
                                
                                <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">X</a>

                            </div> 
                        
                            
                        </div>

                      

                        
                    </div>


                    {{-- <div class="grid gap-6 mb-6 md:grid-cols-8 addField1"> <div> <label for="code" class="block mb-2 text-sm font-medium text-gray-900 ">Code</label> <input type="text"  id="code1" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  aria-valuetext="1" aria-label="code"/> </div> <div> <label for="name" class="block mb-2 text-sm font-medium text-gray-900 ">Name</label> <input type="text"  id="name1" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  aria-valuetext="1" aria-label="name" /> </div> <div> <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 ">Quantity </label> <input type="text"  id="quantity1" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  aria-valuetext="1" aria-label="quantity"/> </div> <div> <label for="company" class="block mb-2 text-sm font-medium text-gray-900 ">Warehouse</label> <input type="text"   id="company1" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" aria-valuetext="1" aria-label="warehouse" /> </div> <div> <label for="price" class="block mb-2 text-sm font-medium text-gray-900 ">Price</label> <input type="text"  id="price1" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  aria-valuetext="1" aria-label="price"/> </div> <div> <label for="discount" class="block mb-2 text-sm font-medium text-gray-900 ">Discount</label> <input type="text"  id="discount1" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" aria-valuetext="1" aria-label="discount" /> </div> <div> <label for="subtotal" class="block mb-2 text-sm font-medium text-gray-900 ">Subtotal</label> <input type="text"  id="subtotal1" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" aria-valuetext="1" aria-label="subtotal" /> </div> <div> <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">X</a> </div> </div> --}}
                    
                    
                    {{-- <div>
                        <input type="text" id="txt1" aria-valuetext="1" aria-label="code"/>
                        <input type="text" id="Text2" aria-valuetext="2" aria-label="code"/>
                        <input type="text" id="Text3" aria-valuetext="3" aria-label="code"/>
                        <br/><br/>
                        <input type="button" value="Add" id="btnAdd" />
                    </div> --}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
