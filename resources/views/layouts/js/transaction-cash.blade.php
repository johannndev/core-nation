<script>
    
    $(document).on("keypress", function(e){
        if(e.which == 13){e.preventDefault();}
    })

    function handleName(event, id) {
       
        // Menggunakan debounce teknik untuk menunda eksekusi
        clearTimeout(window.inputTimeout);

        window.inputTimeout = setTimeout(() => {
            const query = $('#name'+id).val();

            if (query.length > 0) {
                $.ajax({
                    url: '{{ route("ajax.getCostumerCash") }}',
                    type: 'GET',
                    data: { q: query },
                    success: function (data) {
                        $('#name-list'+id).empty();
                        data.forEach(function (data) {
                            $('#name-list'+id).append( '<option value="' + data.name + '" data-id="' + data.id + '">');
                        });
                    }
                });
            }
        }, 300); // Delay 300 milidetik

       

        if (event.key === "Enter") {

            console.log('masuk')

           
            var selectedName =  $('#name'+id).val();
            var selectedId = $('#name-list'+id+' option').filter(function() {
                return $(this).val() === selectedName;
            }).data('id');

            console.log('selectedId');

            $('#customer'+id).val(selectedId);
            console.log('Selected: ' + selectedId); // Lakukan sesuatu dengan ID ini
            
           

            document.getElementById("invoice"+id).focus();

    
            return false; // Mengembalikan false untuk mencegah aksi default
        }
        return true; // Memastikan bahwa input lainnya tetap berfungsi normal
        

        
    }

    function handleInvoice(event, id) {

        if (event.key === "Enter") {

            console.log('dienter')

            document.getElementById("description"+id).focus();

            // event.preventDefault(); // Mencegah form dari submitting
            // codeFunction(id);
            return false; // Mengembalikan false untuk mencegah aksi default
        }
        return true; // Memastikan bahwa input lainnya tetap berfungsi normal
    }

    function handleDescription(event, id) {

        if (event.key === "Enter") {

            console.log('dienter')

            document.getElementById("total"+id).focus();

            // event.preventDefault(); // Mencegah form dari submitting
            // codeFunction(id);
            return false; // Mengembalikan false untuk mencegah aksi default
        }
        return true; // Memastikan bahwa input lainnya tetap berfungsi normal
    }

    function handletTotal(event, id) {

        if (event.key === "Enter") {

            console.log('dienter')

            ++id 

            addLine(id);

            document.getElementById("name"+id).focus();
            // event.preventDefault(); // Mencegah form dari submitting
            // codeFunction(id);
            return false; // Mengembalikan false untuk mencegah aksi default
        }
        return true; // Memastikan bahwa input lainnya tetap berfungsi normal
    }

    function addLine(itemLineId) {

        var addHtml = "";

        i = itemLineId;

        addHtml = '<div class="grid gap-6 mb-6 grid-cols-1 md:grid-cols-5 items-end addField'+i+' "id="gridItem'+i+'"> <div> <label for="name" class="block mb-2 text-sm font-medium text-gray-900 ">Name</label> <div class=""> <input id="customer'+i+'" name="addMoreInputFields['+i+'][customer]" hidden> <input id="name'+i+'" list="name-list'+i+'" onkeyup="handleName(event,'+i+')" autocomplete="off" type="text" name=""  class="nameList register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""   /> <datalist id="name-list'+i+'"></datalist> </div> </div> <div> <label for="invoice" class="block mb-2 text-sm font-medium text-gray-900 ">Invoice </label> <input onkeyup="return handleInvoice(event,'+i+')" type="text" name="addMoreInputFields['+i+'][invoice]"  id="invoice'+i+'" class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" /> </div> <div> <label for="description" class="block mb-2 text-sm font-medium text-gray-900 ">Note</label> <input onkeyup="return handleDescription(event,'+i+')" type="text" name="addMoreInputFields['+i+'][description]"  id="description'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" /> </div> <div> <label for="total" class="block mb-2 text-sm font-medium text-gray-900 ">Total</label> <input onkeyup="return handletTotal(event,'+i+')" type="text" name="addMoreInputFields['+i+'][total]"   id="total'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" /> </div> <div> <button  onclick="remove('+i+')" type="button" class="text-red-600 inline-flex items-center hover:text-white border border-red-600 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900"> <svg class="mr-1 -ml-1 w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" > <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /> </svg> Remove </button> </div> </div>'
      



        $("#dynamicAddRemove").append(addHtml);

    }

    function remove(val) {
        $('.addField'+val).remove();
    }


</script>