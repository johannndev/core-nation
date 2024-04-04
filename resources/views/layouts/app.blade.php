<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script src="{{asset('js/jquery-2.1.4.min.js')}}"></script>

    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

    

        <script>
            var itemLineLabel;
            var itemLineId = 1;
            var i = 0;
            var selctedElm;
            var kolomItemId;
            var price = 0;

            // $(function() {
            //     $(".mGrid > div > input").on('keypress', function(e) {



            //         if (e.which === 13) {
            //         $(this).next('input').focus();
            //         }
            //     });
            // });


             $(document).on("keypress", function(e){
                if(e.which == 13){
                    selctedElm == $(this);
                    itemLineLabel = event.target.ariaLabel;
                    itemLineId = event.target.ariaValueText;

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

                    

                    // $(this).next('input').focus();
                    // console.log(e)
                }
            });

            // $(document).on('click', '#btnAdd', function () {
            //     selctedElm == $(this);
            //     console.log(selctedElm.context.ariaLabel);
            //     console.log(selctedElm.context.ariaValueText);

            // });

            // $(document).on('click', 'input[type=text]', function() {
            //     selctedElm = $(this);

                
            // });

            // var allFields = document.querySelectorAll(".register_form");

            // for (var i = 0; i < allFields.length; i++) {

            //     allFields[i].addEventListener("keyup", function(event) {

            //         if (event.keyCode === 13) {
            //             console.log('Enter clicked')

            //             console.log(event.target.ariaLabel)
            //             console.log(event.target.ariaValueText)

            //             itemLineLabel = event.target.ariaLabel;
            //             itemLineId = event.target.ariaValueText

            //             if(itemLineLabel == 'discount'){
            //                 addLine();
            //             }
                       

            //             // selctedElm == $(this);
            //             // console.log(selctedElm.context.ariaLabel);
            //             // console.log(selctedElm.context.ariaValueText);
                        
            //             event.preventDefault();
            //             if (this.parentElement.nextElementSibling.querySelector('input')) {
            //                 this.parentElement.nextElementSibling.querySelector('input').focus();
            //             }
            //         }
            //     });

            // }

            function addLine(itemLineId) {
 
                i = itemLineId;
                $("#dynamicAddRemove").append('<div class="grid gap-6 mb-6 md:grid-cols-8 addField'+i+'"> <div> <label for="code" class="block mb-2 text-sm font-medium text-gray-900 ">Code</label> <input type="text"  id="code'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  aria-valuetext="'+i+'" aria-label="code"/> </div> <div> <label for="name" class="block mb-2 text-sm font-medium text-gray-900 ">Name</label> <input type="text"  id="name'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  aria-valuetext="'+i+'" aria-label="name" /> </div> <div> <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 ">Quantity </label> <input type="text"  id="quantity'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  aria-valuetext="'+i+'" aria-label="quantity"/> </div> <div> <label for="company" class="block mb-2 text-sm font-medium text-gray-900 ">Warehouse</label> <input type="text"   id="company'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" aria-valuetext="'+i+'" aria-label="warehouse" /> </div> <div> <label for="price" class="block mb-2 text-sm font-medium text-gray-900 ">Price</label> <input type="text"  id="price'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  aria-valuetext="'+i+'" aria-label="price"/> </div> <div> <label for="discount" class="block mb-2 text-sm font-medium text-gray-900 ">Discount</label> <input type="text"  id="discount'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" aria-valuetext="'+i+'" aria-label="discount" /> </div> <div> <label for="subtotal" class="block mb-2 text-sm font-medium text-gray-900 ">Subtotal</label> <input type="text"  id="subtotal'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" aria-valuetext="'+i+'" aria-label="subtotal" /> </div> <div> <a onclick="remove('+i+')" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">X</a> </div> </div>'
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
                        

                    
                        // $('#paket_kurir').html('<option value="">Pilih Paket</option>');

                        // $.each(res, function (key, value) {

                        //     $("#paket_kurir").append('<option value="' + value
                        //         .service + ',' + value.price + '">' + value['service'] +' Rp '+ value.price.toLocaleString('id-ID')+  '</option>');
                        // });
                    }
                });

            }

            function getTotalItem(price,qty,itemLineId){

                price = price;

                var total = price*qty;

                $('#subtotal'+itemLineId).val(total);

            }

            // $('#kurir').on('change', function () {

            //     var idKurir = this.value;

            //     console.log(idKurir);

            //     var getKecId = $('#kec').find(":selected").val();

            //     console.log(getKecId);

            //     $("#paket_kurir").html('<option value="">loading...</option>');
                
            // });
        </script>
    </body>
</html>
