@push('jsBody')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/collect.js/4.36.1/collect.min.js"></script>

    
   
    <script>
    
        // target element that will be dismissed
        const $targetEl = document.getElementById('alert-border-2');

        // optional trigger element
        const $triggerEl = document.getElementById('triggerElement');

        // options object
        const options = {
        transition: 'transition-opacity',
        duration: 300,
        timing: 'ease-out',

            @if ((session('errorMessage')))
        // callback functions
            onHide: (context, targetEl) => {
                console.log('element has been dismissed')
                // console.log(targetEl)
                
            }

            @endif
        };

        // instance options object
        const instanceOptions = {
            id: 'alert-border-2',
            override: true
        };

        

    

        
    </script>

    <script>
        
        var adjustment = 0
        var discAll = 0

        $(document).ready(function(){
            $('#disc').val(discAll)
            $('#adjustment').val(adjustment)
        });
    </script>

    <script>
        var itemLineLabel;
        var itemLineId = 0;
        var i = 0;
        var selctedElm;
        var kolomItemId;
        var price = 0;

        var tbc = 0
        var tqty = 0

        function debounce(func, delay) {
            let debounceTimer;
            return function() {
                const context = this;
                const args = arguments;
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => func.apply(context, args), delay);
            };
        }

        $(document).ready(function() {
            var currentSelection = -1;
            var handleInput = debounce(function() {
                var search = $('.nameList').val();
                

                console.log(search);

                if (search.length > 0) {
                    $.ajax({
                        url: '{{ route("ajax.getitemName") }}',
                        data: { q: search },
                        success: function(users) {
                            console.log(users['data']);
                            var popup = $('#datalistOptionsName');
                            popup.empty().show();
                            users['data'].forEach(function(user) {
                                popup.append('<div class="relative cursor-default select-none py-2 pl-3 pr-9 text-gray-900" data-id="' + user.id + '">' + user.name + '</div>');
                            });
                        }
                    });
                } else {
                    $('#datalistOptionsName').hide();
                    currentSelection = -1;
                }
            }, 1000); // Delay 300 milidetik

            $('.nameList').on('input', handleInput);

            $('.nameList').keydown(function(e) {
            var $popup = $('#datalistOptionsName');
                var $divs = $popup.find('div');
                if (e.keyCode === 40) { // Arrow Down
                    if (currentSelection + 1 < $divs.length) {
                        currentSelection++;
                        $divs.removeClass('bg-gray-100');
                        $divs.eq(currentSelection).addClass('bg-gray-100');
                    }
                    return false;
                } else if (e.keyCode === 38) { // Arrow Up
                    if (currentSelection > 0) {
                        currentSelection--;
                        $divs.removeClass('bg-gray-100');
                        $divs.eq(currentSelection).addClass('bg-gray-100');
                    }
                    return false;
                } else if (e.keyCode === 13) { // Enter
                    if (currentSelection > -1) {
                        var selectedText = $divs.eq(currentSelection).text();
                        var selectedId = $divs.eq(currentSelection).data('id');
                        $('.nameList').val(selectedText);
                        $('#datalistOptionsName').hide();
                        console.log('Selected user ID: ' + selectedId); // Optional: do something with the ID
                        currentSelection = -1;
                        return false;
                    }
                }
            });

            $('#datalistOptionsName').on('click', 'div', function() {
                var name = $(this).text();
                var id = $(this).data('id');
                $('.nameList').val(name);
                $('#datalistOptionsName').hide();
                console.log('Selected user ID: ' + id); // Lakukan sesuatu dengan ID ini
            });

            $('#datalistOptionsName').on('click', 'div', function() {
                var name = $(this).text();
                var id = $(this).data('id');
                $('.nameList').val(name);
                $('#datalistOptionsName').hide();
                currentSelection = -1;
                console.log('Selected user ID: ' + id); // Optional: do something with the ID
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#datalistContainer').length) {
                    $('#datalistOptionsName').hide();
                    currentSelection = -1;
                }
            });
        });

        // $(document).ready(function(e){

        //     console.log(e);

        //     $(document).on("keydown", function(e){

        //         itemLineLabel = e.target.ariaLabel;
        //         itemLineId = e.target.ariaValueText;

        //         console.log(itemLineLabel)

        //         dataList();

        //     })

            
        // });

        // function dataList(){

        //     $('.nameList').on('input', function() {
        //         var search = $(this).val();
        //         $.ajax({
        //             url: '{{ route("ajax.getitemName") }}',
        //             data: { search: search },
        //             success: function(item) {
        //                 var options = '';
        //                 item['data'].forEach(function(user) {
        //                     options += '<option value="' + user.name + '" data-id="' + user.id + '">';
        //                 });
        //                 $('#datalistOptionsName').html(options);
        //             }
        //         });
        //     });


        // }

        

        $(document).on("keypress", function(e){
         

            itemLineLabel = e.target.ariaLabel;
            itemLineId = e.target.ariaValueText;

            // const dinput = document.getElementById('name'+itemLineId)
            // let eventSource = '';

            
            // dinput.addEventListener('keydown', (e) => {
            //     eventSource = e.key ? 'typed' : 'clicked';
            // });

            // dinput.addEventListener('input',(e) => {

               
            //     console.log(eventSource)

            //     if (eventSource === 'clicked') {
            //         console.log('CLICKED! ' + e.target.value);
            //         console.log(eventSource)

            //         var valCode = e.target.value;
            //         var whId = $('.warehouse-select').val();

            //         getItem(valCode,itemLineId,whId)

            //         document.getElementById("quantity"+itemLineId).focus();

                
            //     }       
            // })



            if(e.which == 13){
                e.preventDefault();
                selctedElm == $(this);
                

                kolomItemId =itemLineLabel+itemLineId
                console.log(kolomItemId);

                if(itemLineLabel == 'code'){

                    var valCode = $('#'+kolomItemId).val();

                    var whId = $('.warehouse-select').val();

                    console.log(whId);

                    getItem(valCode,itemLineId,whId)

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

            $('#price'+itemLineId).keyup(function () {
                var price = $('#price'+itemLineId).val()

                getTotalItem(1,itemLineId,0);

              

               
            });

            $('#quantity'+itemLineId).keyup(function () {

                getTotalItem(1,itemLineId);

                totalQty()               
            });

            $('#name'+itemLineId).on('change', function() {
                var selectedName = $(this).val();
              
                console.log('Selected: ' + selectedName); // Lakukan sesuatu dengan ID ini
            });

           

         

           
        });

        function dataListItem(itemLineId)
        {
            $('#name'+itemLineId).on('input', function() {
                var search = $(this).val();
                $.ajax({
                    url: '{{ route("ajax.getitemName") }}',
                    data: { search: search },
                    success: function(item) {
                        var options = '';
                        item.forEach(function(user) {
                            options += '<option value="' + user.name + '" data-id="' + user.id + '">';
                        });
                        $('#datalistOptionsName'+itemLineId).html(options);
                    }
                });
            });
            // Jika Anda perlu menggunakan ID pengguna ketika opsi dipilih
            // $('#datalistWh').on('change', function() {
            //     var selectedName = $(this).val();
            //     var selectedId = $('#datalistOptionsWh option').filter(function() {
            //         return $(this).val() === selectedName;
            //     }).data('id');

            //     $('#warehouse').val(selectedId);
            //     console.log('Selected: ' + selectedId); // Lakukan sesuatu dengan ID ini
            // });
        }
        
// <input autocomplete="off" list="datalistOptionsName" type="text" name="addMoreInputFields['+i+'][name]"  id="name'+i+'" class="nameList register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  aria-valuetext="'+i+'" aria-label="name" /> <datalist id="datalistOptionsName"></datalist>

        function addLine(itemLineId) {

            i = itemLineId;
            $("#dynamicAddRemove").append('<div class="grid gap-6 mb-6 md:grid-cols-8 items-end addField0 hidden" id="gridItemLoading'+i+'"> <div> <label for="code" class="block mb-2 text-sm font-medium text-gray-900 ">Code</label> <div class="relative"> <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none"> <svg aria-hidden="true" class="inline w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/> <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/> </svg> </div> <input type="text" id="email-address-icon"  aria-label="disabled input" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled > </div> </div> <div> <label for="name" class="block mb-2 text-sm font-medium text-gray-900 ">Name</label> <div class="relative"> <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none"> <svg aria-hidden="true" class="inline w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/> <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/> </svg> </div> <input type="text" id="email-address-icon"  aria-label="disabled input" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled > </div> </div> <div> <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 ">Quantity </label> <div class="relative"> <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none"> <svg aria-hidden="true" class="inline w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/> <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/> </svg> </div> <input type="text" id="email-address-icon"  aria-label="disabled input" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled > </div> </div> <div> <label for="company" class="block mb-2 text-sm font-medium text-gray-900 ">Warehouse</label> <div class="relative"> <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none"> <svg aria-hidden="true" class="inline w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/> <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/> </svg> </div> <input type="text" id="email-address-icon"  aria-label="disabled input" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled > </div> </div> <div> <label for="price" class="block mb-2 text-sm font-medium text-gray-900 ">Price</label> <div class="relative"> <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none"> <svg aria-hidden="true" class="inline w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/> <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/> </svg> </div> <input type="text" id="email-address-icon"  aria-label="disabled input" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled > </div> </div> <div> <label for="discount" class="block mb-2 text-sm font-medium text-gray-900 ">Discount</label> <div class="relative"> <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none"> <svg aria-hidden="true" class="inline w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/> <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/> </svg> </div> <input type="text" id="email-address-icon"  aria-label="disabled input" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled > </div> </div> <div> <label for="subtotal" class="block mb-2 text-sm font-medium text-gray-900 ">Subtotal</label> <div class="relative"> <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none"> <svg aria-hidden="true" class="inline w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/> <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/> </svg> </div> <input type="text" id="email-address-icon"  aria-label="disabled input" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled > </div> </div> <div> </div> </div><div class="grid gap-6 mb-6 md:grid-cols-8 items-end addField'+i+' " id="gridItem'+i+'"> <div> <input type="text" name="addMoreInputFields['+i+'][itemId]"  id="id'+i+'"  placeholder=""  aria-valuetext="'+i+'" aria-label="id" hidden/><label for="code" class="block mb-2 text-sm font-medium text-gray-900 ">Code</label> <input type="text" name="addMoreInputFields['+i+'][code]"  id="code'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  aria-valuetext="'+i+'" aria-label="code"/> </div> <div> <label for="name" class="block mb-2 text-sm font-medium text-gray-900 ">Name</label> <input autocomplete="off" list="datalistOptionsName" type="text" name="addMoreInputFields['+i+'][name]"  id="name'+i+'" class="nameList register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  aria-valuetext="'+i+'" aria-label="name" /> <datalist id="datalistOptionsName"></datalist> </div> <div> <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 ">Quantity </label> <input type="text" name="addMoreInputFields['+i+'][quantity]"  id="quantity'+i+'" class="qty register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  aria-valuetext="'+i+'" aria-label="quantity"/> </div> <div> <label for="company" class="block mb-2 text-sm font-medium text-gray-900 ">Warehouse</label> <input type="text" name="addMoreInputFields['+i+'][wh]"   id="wh'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" aria-valuetext="'+i+'" aria-label="warehouse" /> </div> <div> <label for="price" class="block mb-2 text-sm font-medium text-gray-900 ">Price</label> <input type="text" name="addMoreInputFields['+i+'][price]"  id="price'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder=""  aria-valuetext="'+i+'" aria-label="price"/> </div> <div> <label for="discount" class="block mb-2 text-sm font-medium text-gray-900 ">Discount</label> <input type="text" name="addMoreInputFields['+i+'][discount]"   id="discount'+i+'" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" aria-valuetext="'+i+'" aria-label="discount" /> </div> <div> <label for="subtotal" class="block mb-2 text-sm font-medium text-gray-900 ">Subtotal</label> <input type="text" name="addMoreInputFields['+i+'][subtotal]"  id="subtotal'+i+'" class="sto register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" aria-valuetext="'+i+'" aria-label="subtotal" /> </div> <div> <button  onclick="remove('+i+')" type="button" class="text-red-600 inline-flex items-center hover:text-white border border-red-600 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900"> <svg class="mr-1 -ml-1 w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" > <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /> </svg> Remove </button> </div> </div>'
            );
        }



        function remove(val) {
            $('.addField'+val).remove();
        }

        function getItem(itemId,itemLineId,whId){

            document.getElementById("gridItemLoading"+itemLineId).classList.remove("hidden");
            document.getElementById("gridItem"+itemLineId).classList.add("hidden");

            $.ajax({
                url: "{{route('ajax.getitem')}}",
                type: "GET",
                data: {
                    item_id:itemId,
                    wh_id:whId,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (res) {

                    console.log(res);
                    
                

                    $('#id'+itemLineId).val(res.data.id);
                    $('#code'+itemLineId).val(res.data.code);
                    $('#name'+itemLineId).val(res.data.name);
                    $('#price'+itemLineId).val(res.data.price);
                    $('#wh'+itemLineId).val(res.whQty);
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
            var ppn = 0;
            var tbp = 0
           

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

            console.log(total);

            $('#subtotal'+itemLineId).val(total);

            var arraySto = $(".sto").map(function(){return $(this).val();}).get();

            tbc = collect(arraySto).sum()

            ppn = (11/100)*tbc;

            tbp = tbc-ppn;

            $('#tbc').val(tbc)
            $('#total').val(tbc)
            $('#ppn').val(ppn)
            $('#tbppn').val(tbp)
           
            // console.log(arraySto);
            // console.log(data);
        }

        function totalQty(){

            var arrayQty = $(".qty").map(function(){return $(this).val();}).get();
            tqty = collect(arrayQty).sum()
            var cqty;

            if(tqty){
                cqty = tqty;
            }else{
                cqty =0;
            }

            $('#totqty').val(cqty)

            console.log(arrayQty)

        }

       
        $('#disc').keyup(function () {
            var getDiscAll = $('#disc').val()

            getOverAll(adjustment,getDiscAll,tbc)

            
            // var discDesimal = discAll/100;
            // var discGet = tbc*discDesimal;
            // var DiscTotal = tbc-discGet;

            // var newppn = (11/100)*DiscTotal;

            // var newtbp = DiscTotal-newppn;

            // $('#total').val(DiscTotal)
            // $('#ppn').val(newppn)
            // $('#tbppn').val(newtbp)
            
            
        });

       

        $('#adjustment').keyup(function () {
            var adjs = $('#adjustment').val()

            getOverAll(adjs,discAll,tbc)
            
            
            // var adjsTotal = parseFloat(tbc)+parseFloat(adjs);

            // var adjppn = (11/100)*adjsTotal;

            // var adjtbp = adjsTotal-adjppn;

            // $('#total').val(adjsTotal)
            // $('#ppn').val(adjppn)
            // $('#tbppn').val(adjtbp)
            
            
        });

        function getOverAll(adjustmentVal, discAllVal, total)
        {
            adjustment = parseFloat(adjustmentVal);
            discAll = parseFloat(discAllVal);
            totalFl = parseFloat(total);

            var discDesimal = discAll/100;
            var discGet = totalFl*discDesimal;

            var overAllTotal = totalFl+adjustment-discGet;


            var newppn = (11/100)*overAllTotal;

            var newtbp = overAllTotal-newppn;

            $('#total').val(overAllTotal)
            $('#ppn').val(newppn)
            $('#tbppn').val(newtbp)



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