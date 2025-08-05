@push('jsBody')

<script>

    const loader = document.getElementById('loading-item');

    function showLoader() {
        loader.classList.remove('opacity-0', '-translate-y-4');
        loader.classList.add('opacity-100', 'translate-y-0');
        
    }

    function hideLoader() {
        loader.classList.remove('opacity-100', 'translate-y-0');
        loader.classList.add('opacity-0', '-translate-y-4');
        
    }

    var qtySum = 0;
    var qtyPrice = 0;
    var adjs = 0;

    document.getElementById('csvFile').addEventListener('change', function() {
        let formData = new FormData();
        formData.append('csv_file', this.files[0]);
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        formData.append('whId', $('#warehouse').val()); // Menambahkan whid ke FormData

        showLoader()

        fetch('{{ route("ajax.sellBatch") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(async response => {
            hideLoader();

            // Jika response bukan 200 OK
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                // Tampilkan pesan error dari server jika ada
                let message = errorData.error || 'Terjadi kesalahan saat membaca file CSV.';
                alert(message); // Bisa diganti dengan Swal.fire jika pakai SweetAlert
                throw new Error(message);
            }

            return response.json();
        })
        .then(data => {

            console.log(data.length);
            
            console.log(data.data)
            
           
            displayData(data.data);

            qtySum = data.totalQty;
            qtyPrice = data.totalPrice;

            console.log('total Baris: '+data.total);


            total();
           
        })
        .catch(error => {
            hideLoader();
            console.error('Error:', error);
            alert('Upload gagal: ' + error.message);
        });
    });

    function fetchSell() {

       
        var csvInput = $('#csv').val();

        var warehouse = $('#warehouse').val();

        // console.log(warehouse);

        if (csvInput.length > 0) {
            $.ajax({
                url: '{{ route("ajax.sellBatch") }}',
                method: 'GET',
                data: {
                    csvInput: csvInput,
                    whId:warehouse
                },
                success: function(response) {
                   
                    displayData(response.data);

                    qtySum = response.totalQty;
                    qtyPrice = response.totalPrice;

                    console.log('total Baris: '+response.total);
                    

                    total();
                },
                error: function(xhr) {
                    console.log('An error occurred:', xhr.responseText);
                }
            });
        } else {
            $('#userTable').empty();
        }


        // console.log(csvInput);
    }

    function displayData(dataList) {
        if (dataList.length > 0) {

            document.getElementById("item-warpper").classList.add("hidden");

            var table = '';
            dataList.forEach(function(row, index) {
                table += `

                 <div class="grid gap-6 mb-6 md:grid-cols-5 items-end addField`+index+` "id="gridItem`+index+`">
                                    

                    <div class="">

                        <input type="text" name="addMoreInputFields[`+index+`][itemId]" value="`+row.id+`"  id="id0"  placeholder=""  aria-valuetext="0" aria-label="id" hidden/>

                        <input type="text" name="addMoreInputFields[`+index+`][discount]" value="0"  id="id0"  placeholder=""  aria-valuetext="0" aria-label="id" hidden/>
            
                        <label for="code" class="block mb-2 text-sm font-medium text-gray-900 ">Code</label>
                        <input type="text" name="addMoreInputFields[`+index+`][code]" id="disabled-input" aria-label="disabled input" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" value="`+row.code+`" disabled>


                    </div>

                    <div>
                        <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 ">Quantity</label>
                        <input type="text" name="addMoreInputFields[`+index+`][quantity]" id="disabled-input" aria-label="disabled input" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" value="`+row.quantity+`" readonly>
                    </div>

                    <div>
                        <label for="warehouse" class="block mb-2 text-sm font-medium text-gray-900 ">Warehouse </label>
                        <input type="text" name="addMoreInputFields[`+index+`][warehouse]" id="disabled-input" aria-label="disabled input" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" value="`+row.warehouse+`" disabled>
                    </div>  
                
                    <div>
                        <label for="price" class="block mb-2 text-sm font-medium text-gray-900 ">Price</label>
                        <input type="text" id="price" name="addMoreInputFields[`+index+`][price]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="`+row.price+`" />
                    </div> 

                
                    <div>
                        <button  onclick="remove(`+index+`)" type="button" class="text-red-600 inline-flex items-center hover:text-white border border-red-600 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">
                
                            <svg class="mr-1 -ml-1 w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                            Remove
                        </button>
                
                    </div> 
                
                    
                </div>

                
                `;
            });
           
            $('#itemTable').html(table);
        } else {

            var table = `
                <div class=" border-2 border-dashed rounded-lg border-red-500  p-4" id="item-warpper">

                    <p class="text-center font-medium text-red-500 ">Item Tidak Ditemukan</p>

                </div>
            `

            $('#itemTable').html(table);
        }
    }

    function remove(val) {
        $('.addField'+val).remove();
    }

    function sumQty(data) {
        return data.reduce((total, item) => total + item.quantity, 0);
    }

    function sumPrice(data) {
        return data.reduce((total, item) => total + item.price, 0);
    }

    $('#adjustment').keyup(function () {
        adjs = $('#adjustment').val()

        total()
        
        
        // var adjsTotal = parseFloat(tbc)+parseFloat(adjs);

        // var adjppn = (11/100)*adjsTotal;

        // var adjtbp = adjsTotal-adjppn;

        // $('#total').val(adjsTotal)
        // $('#ppn').val(adjppn)
        // $('#tbppn').val(adjtbp)
        
        
    });

    function total(){
        // console.log(qtySum);
        // console.log(qtyPrice);

        var totalPrice = qtyPrice+ parseFloat(adjs);

        $('#total').val(totalPrice)
        $('#totqty').val(qtySum)
    }
   

</script>

@endpush