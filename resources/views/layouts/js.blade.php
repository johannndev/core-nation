@push('jsBody')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/collect.js/4.36.1/collect.min.js"></script>

    <script>
        

        $(document).ready(function(){
            var data = collect([1, 2, 3]).all();

            console.log(data)
        });
    </script>

    <script>
        var itemLineLabel;
        var itemLineId = 1;
        var i = 0;
        var selctedElm;
        var kolomItemId;
        var price = 0;

        var tbc = 0
        var tqty = 0

        $(document).on("keypress", function(e){

            itemLineLabel = e.target.ariaLabel;
            itemLineId = e.target.ariaValueText;

            if(e.which == 13){
                selctedElm == $(this);
                

                kolomItemId =itemLineLabel+itemLineId
                console.log(kolomItemId);

                if(itemLineLabel == 'code'){

                    var valCode = $('#'+kolomItemId).val();

                    console.log(valCode);

                    getItem(valCode,itemLineId)

                    document.getElementById("quantity"+itemLineId).focus();
                }

                if(itemLineLabel == 'quantity'){
                    var valQty = $('#quantity'+itemLineId).val();
                    var valPrice = $('#price'+itemLineId).val();


                    getTotalItem(1,itemLineId);

                    document.getElementById("price"+itemLineId).focus();
                    totalQty();
                }

                if(itemLineLabel == 'price'){
                    document.getElementById("discount"+itemLineId).focus();
                    
                }

                if(itemLineLabel == 'discount'){
                    ++itemLineId 

                    console.log(itemLineId)

                    addLine(itemLineId);
            
                    document.getElementById("code"+itemLineId).focus();
                }

            }

            $('#discount'+itemLineId).keyup(function () {
                var discVal = $('#discount'+itemLineId).val()

                getTotalItem(2,itemLineId,discVal);

                console.log(discVal)

               
            });

         

           
        });

       

       

        function addLine(itemLineId) {

            i = itemLineId;
            $("#dynamicAddRemove").append('<div class="grid gap-6 mb-6 md:grid-cols-8 items-end addField0 hidden" id="gridItemLoading'+i+'"> <div> <label for="code" class="block mb-2 text-sm font-medium text-gray-900 ">Code</label> <div class="relative"> <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none"> <svg aria-hidden="true" class="inline w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/> <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/> </svg> </div> <input type="text" id="email-address-icon"  aria-label="disabled input" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled > </div> </div> <div> <label for="name" class="block mb-2 text-sm font-medium text-gray-900 ">Name</label> <div class="relative"> <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none"> <svg aria-hidden="true" class="inline w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/> <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/> </svg> </div> <input type="text" id="email-address-icon"  aria-label="disabled input" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled > </div> </div> <div> <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 ">Quantity </label> <div class="relative"> <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none"> <svg aria-hidden="true" class="inline w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/> <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/> </svg> </div> <input type="text" id="email-address-icon"  aria-label="disabled input" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled > </div> </div> <div> <label for="company" class="block mb-2 text-sm font-medium text-gray-900 ">Warehouse</label> <div class="relative"> <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none"> <svg aria-hidden="true" class="inline w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/> <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/> </svg> </div> <input type="text" id="email-address-icon"  aria-label="disabled input" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled > </div> </div> <div> <label for="price" class="block mb-2 text-sm font-medium text-gray-900 ">Price</label> <div class="relative"> <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none"> <svg aria-hidden="true" class="inline w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/> <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/> </svg> </div> <input type="text" id="email-address-icon"  aria-label="disabled input" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled > </div> </div> <div> <label for="discount" class="block mb-2 text-sm font-medium text-gray-900 ">Discount</label> <div class="relative"> <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none"> <svg aria-hidden="true" class="inline w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/> <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/> </svg> </div> <input type="text" id="email-address-icon"  aria-label="disabled input" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled > </div> </div> <div> <label for="subtotal" class="block mb-2 text-sm font-medium text-gray-900 ">Subtotal</label> <div class="relative"> <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none"> <svg aria-hidden="true" class="inline w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/> <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/> </svg> </div> <input type="text" id="email-address-icon"  aria-label="disabled input" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled > </div> </div> <div> </div> </div><div class="grid gap-6 mb-6 md:grid-cols-8 items-end addField'+i+' " id="gridItem'+i+'"> <div> <label for="code" class="block mb-2 text-sm font-medium text-gray-900 ">Code</label> <input type="text" name="addMoreInputFields['+i+'][code]"  id="code'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  aria-valuetext="'+i+'" aria-label="code"/> </div> <div> <label for="name" class="block mb-2 text-sm font-medium text-gray-900 ">Name</label> <input type="text" name="addMoreInputFields['+i+'][name]"  id="name'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  aria-valuetext="'+i+'" aria-label="name" /> </div> <div> <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 ">Quantity </label> <input type="text" name="addMoreInputFields['+i+'][quantity]"  id="quantity'+i+'" class="qty register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  aria-valuetext="'+i+'" aria-label="quantity"/> </div> <div> <label for="company" class="block mb-2 text-sm font-medium text-gray-900 ">Warehouse</label> <input type="text" name="addMoreInputFields['+i+'][company]"   id="company'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" aria-valuetext="'+i+'" aria-label="warehouse" /> </div> <div> <label for="price" class="block mb-2 text-sm font-medium text-gray-900 ">Price</label> <input type="text" name="addMoreInputFields['+i+'][price]"  id="price'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  aria-valuetext="'+i+'" aria-label="price"/> </div> <div> <label for="discount" class="block mb-2 text-sm font-medium text-gray-900 ">Discount</label> <input type="text" name="addMoreInputFields['+i+'][discount]"   id="discount'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" aria-valuetext="'+i+'" aria-label="discount" /> </div> <div> <label for="subtotal" class="block mb-2 text-sm font-medium text-gray-900 ">Subtotal</label> <input type="text" name="addMoreInputFields['+i+'][subtotal]"  id="subtotal'+i+'" class="sto register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" aria-valuetext="'+i+'" aria-label="subtotal" /> </div> <div> <button  onclick="remove('+i+')" type="button" class="text-red-600 inline-flex items-center hover:text-white border border-red-600 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900"> <svg class="mr-1 -ml-1 w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" > <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /> </svg> Remove </button> </div> </div>'
            );
        }



        function remove(val) {
            $('.addField'+val).remove();
        }

        function getItem(itemId,itemLineId){

            document.getElementById("gridItemLoading"+itemLineId).classList.remove("hidden");
            document.getElementById("gridItem"+itemLineId).classList.add("hidden");

            $.ajax({
                url: "{{route('ajax.getitem')}}",
                type: "GET",
                data: {
                    item_id:itemId,
                    
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (res) {

                    console.log(res);
                    
                

                    $('#code'+itemLineId).val(res.code);
                    $('#name'+itemLineId).val(res.name);
                    $('#price'+itemLineId).val(res.price);
                    $('#discount'+itemLineId).val(0);

                    
                    document.getElementById("gridItemLoading"+itemLineId).classList.add("hidden");
                    document.getElementById("gridItem"+itemLineId).classList.remove("hidden");
                    document.getElementById("quantity"+itemLineId).focus();
                    
                    getTotalItem(1,itemLineId,0);
                    

                
                }
            });


        }

        function getTotalItem(type,itemLineId,discVal){

         
            var diskon = 0;

            var price = $('#price'+itemLineId).val();
            var qty = $('#quantity'+itemLineId).val();

            var subtotal = price*qty;

            if(type == 2){

                var diskonDesimal = discVal/100;
                var diskon = diskonDesimal*subtotal; 
              
                
            }

            console.log(subtotal);
            console.log(diskon);
           

            var total = subtotal-diskon;

            $('#subtotal'+itemLineId).val(total);

            var arraySto = $(".sto").map(function(){return $(this).val();}).get();
           

            

            tbc = collect(arraySto).sum()
            

            $('#tbc').val(tbc)
           
            // console.log(arraySto);
            // console.log(data);
        }

        function totalQty(){

            var arrayQty = $(".qty").map(function(){return $(this).val();}).get();
            tqty = collect(arrayQty).sum()

            $('#totqty').val(tqty)

            console.log(arrayQty)

        }

        // function getSubTotalAfterDiscount(discVal,itemLineId){
            
       
            

           
            
        //     console.log(price);
        //     console.log(qty);
        //     // console.log(diskon);
        //     // console.log(subtotal);
            

        //     // var subtotal = $('#subtotal'+itemLineId).val(diskon);
        // }

        $('#paid').on('click', function(){

            isChecked = $(this).is(':checked')
        
            if(isChecked){ 
                // $('html').css('background-color','green')

                document.getElementById("inputPaid").classList.remove("hidden");


                console.log('aktif')
            }
            else{ 
                console.log('mati')
                document.getElementById("inputPaid").classList.add("hidden");

                // $('html').removeAttr('style')
            }
        })


    </script>
@endpush