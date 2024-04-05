var itemLineLabel;
var itemLineId = 1;
var i = 0;
var selctedElm;
var kolomItemId;
var price = 0;

$(document).on("keypress", function(e){
    if(e.which == 13){
        selctedElm == $(this);
        itemLineLabel = e.target.ariaLabel;
        itemLineId = e.target.ariaValueText;

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


            getTotalItem(valPrice,valQty,itemLineId);
            document.getElementById("price"+itemLineId).focus();
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
});

function addLine(itemLineId) {

    i = itemLineId;
    $("#dynamicAddRemove").append('<div class="grid gap-6 mb-6 md:grid-cols-8 items-end addField'+i+' "> <div> <label for="code" class="block mb-2 text-sm font-medium text-gray-900 ">Code</label> <input type="text" name="addMoreInputFields['+i+'][code]"  id="code'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  aria-valuetext="'+i+'" aria-label="code"/> </div> <div> <label for="name" class="block mb-2 text-sm font-medium text-gray-900 ">Name</label> <input type="text" name="addMoreInputFields['+i+'][name]"  id="name'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  aria-valuetext="'+i+'" aria-label="name" /> </div> <div> <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 ">Quantity </label> <input type="text" name="addMoreInputFields['+i+'][quantity]"  id="quantity'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  aria-valuetext="'+i+'" aria-label="quantity"/> </div> <div> <label for="company" class="block mb-2 text-sm font-medium text-gray-900 ">Warehouse</label> <input type="text" name="addMoreInputFields['+i+'][company]"   id="company'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" aria-valuetext="'+i+'" aria-label="warehouse" /> </div> <div> <label for="price" class="block mb-2 text-sm font-medium text-gray-900 ">Price</label> <input type="text" name="addMoreInputFields['+i+'][price]"  id="price'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  aria-valuetext="'+i+'" aria-label="price"/> </div> <div> <label for="discount" class="block mb-2 text-sm font-medium text-gray-900 ">Discount</label> <input type="text" name="addMoreInputFields['+i+'][discount]"   id="discount'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" aria-valuetext="'+i+'" aria-label="discount" /> </div> <div> <label for="subtotal" class="block mb-2 text-sm font-medium text-gray-900 ">Subtotal</label> <input type="text" name="addMoreInputFields['+i+'][subtotal]"  id="subtotal'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" aria-valuetext="'+i+'" aria-label="subtotal" /> </div> <div> <button  onclick="remove('+i+')" type="button" class="text-red-600 inline-flex items-center hover:text-white border border-red-600 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900"> <svg class="mr-1 -ml-1 w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" > <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /> </svg> Remove </button> </div> </div>'
    );
}



function remove(val) {
    $('.addField'+val).remove();
}

function getItem(itemId,itemLineId){

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
            
            getTotalItem(res.price,1,itemLineId);
            

        
        }
    });

}

function getTotalItem(price,qty,itemLineId){

    price = price;

    var total = price*qty;

    $('#subtotal'+itemLineId).val(total);

}

