<script>
    
    $(document).on("keypress", function(e){
        if(e.which == 13){e.preventDefault();}
    })

    function handleRecaiver(event) {
       
       // Menggunakan debounce teknik untuk menunda eksekusi
       clearTimeout(window.inputTimeout);

       window.inputTimeout = setTimeout(() => {
           const query = $('#receiver').val();

           if (query.length > 0) {
               $.ajax({
                   url: '{{ route("ajax.getCostumerCash") }}',
                   type: 'GET',
                   data: { q: query },
                   success: function (data) {
                       $('#name-list-recaiver').empty();
                       data.forEach(function (data) {
                           $('#name-list-recaiver').append( '<option value="' + data.name + '" data-id="' + data.id + '" data-balance="' + data.stat.balance + '">');
                       });
                   }
               });
           }
       }, 300); // Delay 300 milidetik

     

       if (event.key === "Enter") {

           console.log('masuk')

          
           recaiverGet();
           
   
           return false; // Mengembalikan false untuk mencegah aksi default
       }
       return true; // Memastikan bahwa input lainnya tetap berfungsi normal
       

       
   }

   
   function handleSender(event) {
       
       // Menggunakan debounce teknik untuk menunda eksekusi
       clearTimeout(window.inputTimeout);

       window.inputTimeout = setTimeout(() => {
           const query = $('#sender').val();

           if (query.length > 0) {
               $.ajax({
                   url: '{{ route("ajax.getCostumerCash") }}',
                   type: 'GET',
                   data: { q: query },
                   success: function (data) {
                       $('#name-list-sender').empty();
                       data.forEach(function (data) {
                           $('#name-list-sender').append( '<option value="' + data.name + '" data-id="' + data.id + '" data-balance="' + data.stat.balance + '">');
                       });
                   }
               });
           }
       }, 300); // Delay 300 milidetik

   

       if (event.key === "Enter") {

           console.log('masuk')

          
           recaiverGet();
           
   
           return false; // Mengembalikan false untuk mencegah aksi default
       }
       return true; // Memastikan bahwa input lainnya tetap berfungsi normal
       

       
   }

</script>

<script>
    // Ambil elemen input dan daftar buah
    const inputR = document.getElementById('receiver');
    const datalistR = document.getElementById('name-list-recaiver');

    const inputS = document.getElementById('sender');
    const datalistS = document.getElementById('name-list-sender');

    // Tambahkan event listener untuk peristiwa input
    inputR.addEventListener('input', function() {
        // Cek apakah nilai input ada dalam daftar
        const selectedOption = [...datalistR.options].find(option => option.value === inputR.value);
        if (selectedOption) {
            // Panggil fungsi jika nilai ada dalam daftar
            recaiverGet();
        }
    });

     // Tambahkan event listener untuk peristiwa input
    inputS.addEventListener('input', function() {
        // Cek apakah nilai input ada dalam daftar
        const selectedOption = [...datalistS.options].find(option => option.value === inputS.value);
        if (selectedOption) {
            // Panggil fungsi jika nilai ada dalam daftar
            senderGet();
        }
    });

    // Fungsi yang dipanggil saat nilai dipilih dari datalist
    function recaiverGet() {
        var selectedName =  $('#receiver').val();
           var selectedId = $('#name-list-recaiver option').filter(function() {
               return $(this).val() === selectedName;
           }).data('id');

           var selectedBalance = $('#name-list-recaiver option').filter(function() {
               return $(this).val() === selectedName;
           }).data('balance');

           console.log(selectedBalance);

           $('#receiverId').val(selectedId);
           $('#value-receiver').val(selectedBalance);
           console.log('Selected: ' + selectedId); // Lakukan sesuatu dengan ID ini
    }

    function senderGet() {
        var selectedName =  $('#sender').val();
           var selectedId = $('#name-list-sender option').filter(function() {
               return $(this).val() === selectedName;
           }).data('id');

           var selectedBalance = $('#name-list-sender option').filter(function() {
               return $(this).val() === selectedName;
           }).data('balance');

           console.log(selectedBalance);

           $('#senderId').val(selectedId);
           $('#value-sender').val(selectedBalance);
           console.log('Selected: ' + selectedId); // Lakukan sesuatu dengan ID ini
    }
</script>