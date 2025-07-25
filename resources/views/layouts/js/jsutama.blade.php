@push('jsBody')

<script src="https://cdnjs.cloudflare.com/ajax/libs/collect.js/4.36.1/collect.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@ericblade/quagga2/dist/quagga.min.js"></script>

<script>
   

    var lineC = 0;
    var adjustment = 0
    var discAll = 0
    var scanId = 0;

    var isMobile = Math.min(window.screen.width, window.screen.height) < 768 || navigator.userAgent.indexOf("Mobi") > -1;

    // console.log(isMobile);

</script>

<script>

    var heightCS;

    if(isMobile){
        heightCS = {min:300};
    }else{

        heightCS =  {min:10, max:300};

    }

    const quaggaConf = {
        inputStream: {
            target: document.querySelector("#camera"),
            type: "LiveStream",
            constraints: {
                width:  {min:300},
                height: heightCS ,
                facingMode: "environment",
                aspectRatio: { min: 1, max: 2 }
            }
        },
        decoder: {
            readers: ['code_128_reader']
        },
    }

    const scanAlert = document.getElementById('alert-scan');

    function startScan(id){

        // console.log(id);
        
        scanId = id;
     

        scanAlert.classList.add('hidden');

        Quagga.init(quaggaConf, function (err) {
        if (err) {
                return console.log(err);
            }

            
            Quagga.start();
        
        
        });

    }

    

    Quagga.onDetected(function (result) {

        

        if ($('#alert-scan').find('#kodebar').length === 0) {
            $('#alert-scan').append('<span id="kodebar"> barcode '+result.codeResult.code+' berhasil di scan.</span>');

            
        }

        handleScan(scanId,result.codeResult.code);

        // $('#kodebar').append(result.codeResult.code);

        scanAlert.classList.remove('hidden');

        closeAll();

        document.getElementById("quantity"+scanId).focus();

        

        // alert("Detected barcode: " + result.codeResult.code);
    });

    function stopScan(){

        Quagga.stop();

    }

    

    function closeAll(){

        console.log('modal akan di tutup')

        setTimeout(function() {
            var button = document.getElementById('closeModalButton');
            if (button) {
                window.stopScanButton();
            }
        }, 2000); // Klik otomatis setelah 2 detik
    }

    
</script>

<script>

   


    $(document).on("keypress", function(e){
        // console.log(e)

        if(e.which == 13){e.preventDefault();}
    })

    

    $(document).ready(function() {
        
        initializeSelect2();


    })

    function initializeSelect2() {

        $('#name'+lineC).select2({
            width: '100%',
            placeholder: "Pilih ",
            minimumInputLength:2,
            ajax: {
                url: '{{ route("ajax.getitemName") }}',
                dataType: "json",
                data: (params) => {
                    let query = {
                        search: params.term,
                        page: params.page || 1,
                    };
                    return query;
                },
                processResults: data => {
                    return {
                        results: data.data.map((row) => {
                            return { text: row.name, id: row.id };
                        }),
                        pagination: {
                            more: data.current_page < data.last_page,
                        },
                    };
                },
            },
        });

        $('#name'+lineC).on('change', function() {
            
            var valCode = $(this).val();
    
            var dataId = $('#name'+lineC).attr('data-customId');
    
            // console.log('data ini '+dataId);
    
            var wh= $('#warehouse').val();
            var se = $('#sender').val();
            var whId = 0;
    
            if(wh){
                whId = wh;
            }else{
                whId = se;
            }
    
            // console.log(whId);
    
            getItem(valCode,dataId,whId)
    
            document.getElementById("quantity"+lineC).focus();
    
            // alert('Selected ID: ' + selectedId);
        });

        

        
    }

    function centangCheckbox(id) {
        $('#myCheckbox' + id).prop('checked', true);
    }

    

    function handleCode(event, id,get=null) {
        // console.log(event.key);

        let lid = lineC;

        if ($('#myCheckbox'+id).is(':checked')) {
            lid = id;
            console.log('Checkbox diceklis');
        }

        // console.log(event);
        // console.log(lineC);

        if(event.key === "Enter" ){

           
            // var price = $('#code'+id).val();
            // alert('Function called without submitting the form! '+price);
           

            var valCode = $('#code'+lid).val()

            // const goBtn = document.getElementById('btncode'+id);

            // goBtn.classList.add('hidden');


            var se = $('#sender').val();


            if( "{{$trType}}" == "move"){
                var wh= $('#sender').val();
            }else{

                var wh= $('#warehouse').val();

            }

            // console.log(wh);

            var whId = 0;

            if(wh){
                whId = wh;
            }else{
                whId = se;
            }

            // console.log(valCode,lid,whId);

            getItem(valCode,lid,whId)

            
           

            document.getElementById("quantity"+lid).focus();

            // event.preventDefault(); // Mencegah form dari submitting
            // codeFunction(id);
            return false; // Mengembalikan false untuk mencegah aksi default

        }

        
    
        return true; // Memastikan bahwa input lainnya tetap berfungsi normal
    }

    function handleScan(scanId,valCode) {
        // console.log('halo');

        let lid = lineC;

        if ($('#myCheckbox'+scanId).is(':checked')) {
            lid = scanId;
            console.log('Checkbox diceklis');
        }
        

        var wh= $('#warehouse').val();
        var se = $('#sender').val();
        var whId = 0;

        if(wh){
            whId = wh;
        }else if(se){
            whId = se;
        }else{
           
        }

        // console.log(valCode+" "+scanId+" "+whId);

        getItem(valCode,lid,whId)

        
        document.getElementById("quantity"+lid).focus();

         
        
    
        return true; // Memastikan bahwa input lainnya tetap berfungsi normal
    }




    function handleQty(event, id,get=null) {

        let lid = lineC;

        if ($('#myCheckbox'+id).is(':checked')) {
            lid = scanId;
            console.log('Checkbox diceklis');
        }
        

        getTotalItem(1,id);

        totalQty()

        if ( event.key === "Enter") {

        // const goBtn = document.getElementById('btnquantity'+id);

        // goBtn.classList.add('hidden');

        // console.log('dienter')

        document.getElementById("price"+lid).focus();

        // event.preventDefault(); // Mencegah form dari submitting
        // codeFunction(id);
        return false; // Mengembalikan false untuk mencegah aksi default

        }



        return true; // Memastikan bahwa input lainnya tetap berfungsi normal
    }

    function handlePrice(event, id,get=null) {

        let lid = lineC;

        


        getTotalItem(1,id,0);



        if ( event.key === "Enter") {

            // console.log('dienter')

           

            // const goBtn = document.getElementById('btnprice'+id);

            // goBtn.classList.add('hidden');

            if( "{{$trType}}" == "move" ||  "{{$trType}}" == "use"){
            
                centangCheckbox(id)

                // if ($('#myCheckbox'+id).is(':checked')) {
                //     lid = lineC;
                //     console.log('Checkbox diceklis');
                // }
                // console.log(lineC)

              

                ++id 
                ++lineC

                console.log(lineC)

               

                newLine2(lineC);

              

                document.getElementById("code"+lineC).focus();
            }else{

                if ($('#myCheckbox'+id).is(':checked')) {
                    lid = lineC;
                    console.log('Checkbox diceklis');
                }
                console.log(lineC)

                document.getElementById("discount"+lid).focus();

            }



            // event.preventDefault(); // Mencegah form dari submitting
            // codeFunction(id);
            return false; // Mengembalikan false untuk mencegah aksi default
        }




        return true; // Memastikan bahwa input lainnya tetap berfungsi normal
    }


    function handleDisc(event, id,get=null) {
        
        let lid = lineC;

        if ($('#myCheckbox'+id).is(':checked')) {
            lid = id;
            console.log('Checkbox diceklis');
        }

        var discVal = $('#discount'+lid).val()

        getTotalItem(2,id,discVal);



        if ( event.key === "Enter") {

            // const goBtn = document.getElementById('btndiscount'+id);

            // goBtn.classList.add('hidden');

            // console.log('dienter')

            ++id
            ++lineC 

            // console.log(id)



            newLine(lineC);

            document.getElementById("code"+lineC).focus();
            // event.preventDefault(); // Mencegah form dari submitting
            // codeFunction(id);
            return false; // Mengembalikan false untuk mencegah aksi default
        }

        

        
        return true; // Memastikan bahwa input lainnya tetap berfungsi normal
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

                // console.log(res);
                var errorCode = res['error'];
            
                if(errorCode === 0 ){

                    
                    $('#id'+itemLineId).val(res.data.data.id);
                    $('#code'+itemLineId).val(res.data.data.code);


                    getNameItem(res.data.data.id,res.data.data.code)
                    // $('#name'+itemLineId).val(res.data.data.name);


                    $('#price'+itemLineId).val(res.data.data.price);
                    $('#wh'+itemLineId).val(res.data.whQty);
                    $('#discount'+itemLineId).val(0);

                    
                    document.getElementById("gridItemLoading"+itemLineId).classList.add("hidden");
                    document.getElementById("gridItem"+itemLineId).classList.remove("hidden");
                    document.getElementById("quantity"+itemLineId).focus();
                    
                    getTotalItem(1,itemLineId,0);

                }else{

                    toastClose(itemLineId);


                }

                

            
            },
        
        });


    }

    function getNameItem(id,name){
        var nameData = new Option(name,id, true, true);
        $('#name'+lineC).append(nameData);

        
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

        // console.log(subtotal);
        // console.log(diskon);

        var total = subtotal-diskon;

        // console.log(total);

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

        // console.log(arrayQty)

    }

    $('#disc').keyup(function () {
        var getDiscAll = $('#disc').val()

        // console.log(getDiscAll);

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

    function remove(val) {

        // console.log(val)

         const totalRows = $('[name^="addMoreInputFields["][name$="[itemId]"]').length;

        if(totalRows <= 1){

            $('#name'+val).val(null).trigger('change.select2');

            $('#id'+val).val(null);
            $('#code'+val).val(null);
            $('#price'+val).val(null);
            $('#wh'+val).val(null);
            $('#discount'+val).val(null);
            $('#quantity'+val).val(null);
            $('#subtotal'+val).val(null);

          

        }else{

            $('.addField'+val).remove();

        }

        
    }

    function newLine2(itemLineId){

        var i = itemLineId;

        const newRow = `

            <div class="grid gap-6 mb-6 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-9 items-end addField`+i+` hidden" id="gridItemLoading`+i+`">
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

            <div class="grid gap-6 mb-6 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-9 items-end addField`+i+` "id="gridItem`+i+`">
                <div class="">
                    <input type="checkbox" id="myCheckbox`+i+`" hidden class="hidden">
                    <input type="text" name="addMoreInputFields[`+i+`][itemId]"  id="id`+i+`"  placeholder=""  aria-valuetext="`+i+`" aria-label="id" hidden/>

                    <label for="code" class="block mb-2 text-sm font-medium text-gray-900 ">Code</label>
                    <div class="flex">

                        <div class="block md:hidden">
                            <button onclick="starScanButton(`+i+`)" id="openModalButton"  type="button" class="focus:outline-none inline-flex items-center text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-3 py-2.5 mr-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            

                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M8,2A1,1,0,0,1,8,4H4V8A1,1,0,0,1,2,8V3A1,1,0,0,1,3,2ZM8,20H4V16a1,1,0,0,0-2,0v5a1,1,0,0,0,1,1H8a1,1,0,0,0,0-2Zm13-5a1,1,0,0,0-1,1v4H16a1,1,0,0,0,0,2h5a1,1,0,0,0,1-1V16A1,1,0,0,0,21,15Zm0-6a1,1,0,0,0,1-1V3a1,1,0,0,0-1-1H16a1,1,0,0,0,0,2h4V8A1,1,0,0,0,21,9Zm1,2H2a1,1,0,0,0,0,2H22a1,1,0,0,0,0-2Z"></path></g></svg>

                                
                            </button>
                        </div>

                        <input type="search" data-input="value" data-name="code" data-id="`+i+`" onkeydown="return handleCode(event,`+i+`)" name="addMoreInputFields[`+i+`][code]"  id="code`+i+`" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" inputmode="search" />

                    </div>
                   
                </div>
                <div class="">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 ">Name</label>
                    
                    <div class="">

                        <div class="relative ">
                            <select class="select2-ajax-item" id="name`+i+`" name="addMoreInputFields[`+i+`][name]" data-customId="`+i+`">
                                
                                <option ></option>
                            </select>

                            @error('')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                            
                        </div>

                    </div>
                

                </div>
                <div class="">
                    <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 ">Quantity </label>
                    <input onkeyup="return handleQty(event,`+i+`)" type="search" inputmode="search" name="addMoreInputFields[`+i+`][quantity]"  id="quantity`+i+`" class="qty register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" />
                </div>  
                <div class="">
                    <label for="wh" class="block mb-2 text-sm font-medium text-gray-900 ">Warehouse</label>
                    <input type="text" name="addMoreInputFields[`+i+`][wh]"   id="wh`+i+`"class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" disabled/>
                </div>  

                <div class="">
                    <label for="price" class="block mb-2 text-sm font-medium text-gray-900 ">Price</label>
                    <input onkeyup="return handlePrice(event,`+i+`)" type="search" inputmode="search" name="addMoreInputFields[`+i+`][price]"  id="price`+i+`" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" />
                </div> 
                
                <div class="hidden ">
                    <label for="discount" class="block mb-2 text-sm font-medium text-gray-900 ">Discount</label>
                    <input hidden onkeyup="return handleDisc(event,`+i+`)" type="search" inputmode="search" name="addMoreInputFields[`+i+`][discount]"   id="discount`+i+`" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" />
                </div> 

                <div class="">
                    <label for="subtotal" class="block mb-2 text-sm font-medium text-gray-900 ">Subtotal</label>
                    <input type="text" name="addMoreInputFields[`+i+`][subtotal]"  id="subtotal`+i+`" class="sto register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" aria-valuetext="0" aria-label="subtotal" />
                </div> 

                <div class="">
                    <button  onclick="remove('`+i+`')" type="button" class="text-red-500 inline-flex items-center hover:text-white border border-red-500 hover:bg-red-500 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-500 dark:focus:ring-red-900">

                        <svg class="mr-1 -ml-1 w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                        Remove
                    </button>

                </div> 

                
            </div> 

        `;

        
        $('#dynamicAddRemove').append(newRow);

        // Inisialisasi Select2 pada elemen yang baru ditambahkan
        initializeSelect2();

    }


    function newLine(itemLineId){

        var i = itemLineId;
   
        
        const newRow = `

                <div class="grid gap-6 mb-6 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-9 items-end addField0 hidden" id="gridItemLoading`+i+`">
                    
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

            
                <div class="grid gap-6 mb-6 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-9 items-end addField`+i+` "id="gridItem`+i+`">
                    <div class="flex items-end w-full ">

                        <div class="w-full">
                            <input type="checkbox" id="myCheckbox`+i+`" hidden class="hidden">

                            <input type="text" name="addMoreInputFields[`+i+`][itemId]"  id="id`+i+`"  placeholder=""  aria-valuetext="0" aria-label="id" hidden/>
                
                            <label for="code" class="block mb-2 text-sm font-medium text-gray-900 ">Code</label>


                            <div class="flex">

                                <div class="block md:hidden">
                                    <button onclick="starScanButton(`+i+`)" id="openModalButton"  type="button" class="focus:outline-none inline-flex items-center text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-3 py-2.5 mr-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                    

                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M8,2A1,1,0,0,1,8,4H4V8A1,1,0,0,1,2,8V3A1,1,0,0,1,3,2ZM8,20H4V16a1,1,0,0,0-2,0v5a1,1,0,0,0,1,1H8a1,1,0,0,0,0-2Zm13-5a1,1,0,0,0-1,1v4H16a1,1,0,0,0,0,2h5a1,1,0,0,0,1-1V16A1,1,0,0,0,21,15Zm0-6a1,1,0,0,0,1-1V3a1,1,0,0,0-1-1H16a1,1,0,0,0,0,2h4V8A1,1,0,0,0,21,9Zm1,2H2a1,1,0,0,0,0,2H22a1,1,0,0,0,0-2Z"></path></g></svg>

                                        
                                    </button>
                                </div>

                                <input type="search" data-input="value" data-name="code" data-id="`+i+`" onkeydown="return handleCode(event,`+i+`)" name="addMoreInputFields[`+i+`][code]"  id="code`+i+`" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" inputmode="search" />

                            </div>

                        </div>

                        

                        
                    </div>
                    <div class="">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 ">Name</label>
                        
                        <div class="flex items-end w-full ">

                        
                            <div class=" w-full">

                                <div class="relative ">
                                    <select class="select2-ajax-item" id="name`+i+`" name="addMoreInputFields[`+i+`][name]" data-customId="`+i+`">
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
                                
                                <input data-name="quantity" data-id="`+i+`"  onkeyup="return handleQty(event,`+i+`)" type="search" name="addMoreInputFields[`+i+`][quantity]"  id="quantity`+i+`" class="qty register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" inputmode="search"/>
                            </div>

                            
                        </div>
                    </div>  
                    <div class="">
                        <label for="wh" class="block mb-2 text-sm font-medium text-gray-900 ">Warehouse</label>
                        <input  type="text" name="addMoreInputFields[`+i+`][wh]"   id="wh`+i+`"class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" disabled/>
                    </div>  
                
                    <div class="">
                        <label for="price" class="block mb-2 text-sm font-medium text-gray-900 ">Price</label>

                        <div class="flex items-end w-full">

                            <div class="w-full">
                                <input data-name="price" data-id="`+i+`"  onkeyup="return handlePrice(event,`+i+`)" type="search" name="addMoreInputFields[`+i+`][price]"  id="price`+i+`" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" inputmode="search"/>
                            </div>

                        </div>
                        
                    </div> 
                    
                    <div class="">
                        <label for="discount" class="block mb-2 text-sm font-medium text-gray-900 ">Discount</label>

                        <div class="flex items-end w-full">

                            <div class="w-full">
                                <input data-name="discount" data-id="`+i+`" onkeyup="return handleDisc(event,`+i+`)" type="search"   name="addMoreInputFields[`+i+`][discount]"   id="discount`+i+`" class="register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" inputmode="search" />
                            </div>

                        </div>
                        

                    </div> 
                
                    <div class="">
                        <label for="subtotal" class="block mb-2 text-sm font-medium text-gray-900 ">Subtotal</label>
                        <input type="text" name="addMoreInputFields[`+i+`][subtotal]"  id="subtotal`+i+`" class="sto register_form bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" aria-valuetext="0" aria-label="subtotal" />
                    </div> 
                
                    <div class="">
                        <button  onclick="remove(`+i+`)" type="button" class="text-red-500 inline-flex items-center hover:text-white border border-red-500 hover:bg-red-500 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-500 dark:focus:ring-red-900">
                
                            <svg class="mr-1 -ml-1 w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                            Remove
                        </button>
                
                    </div> 
                
                    
                </div>
            
            `;

        

        $('#dynamicAddRemove').append(newRow);

        // Inisialisasi Select2 pada elemen yang baru ditambahkan
        initializeSelect2();
                
    
    }

    function toastClose(itemLineId){
        document.getElementById("toast-danger-js").classList.remove("hidden");

                    
        document.getElementById("gridItemLoading"+itemLineId).classList.add("hidden");
        document.getElementById("gridItem"+itemLineId).classList.remove("hidden");
        document.getElementById("code"+itemLineId).focus();

        document.getElementById('dangerText').innerHTML += "Item not found"

        setTimeout(function () { 

            document.getElementById("toast-danger-js").classList.add("hidden");

        }, 6000);
    }
</script>


@endpush
