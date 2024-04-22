@push('jsBody')


@if (old('customer'))
    

<script>
    $(document).ready(function() {

        var idCust = {{old('customer')}}; // ID default yang ingin Anda pilih
        var $select = $('.customer-select');

        $.ajax({
            url: '{{route("ajax.getCostumerSingle")}}?idCust=' + idCust, // Ganti dengan endpoint untuk mendapatkan data berdasarkan id
            dataType: 'json'
        }).done(function(data) {
            var option = new Option(data.name, data.id, true, true);
            $select.append(option).trigger('change');
        });

    })
    

</script>

@endif

@if (old('warehouse'))

<script>
    $(document).ready(function() {

        var idCust = {{old('warehouse')}}; // ID default yang ingin Anda pilih
        var $select = $('.warehouse-select');

        $.ajax({
            url: '{{route("ajax.getCostumerSingle")}}?idCust=' + idCust, // Ganti dengan endpoint untuk mendapatkan data berdasarkan id
            dataType: 'json'
        }).done(function(data) {
            var option = new Option(data.name, data.id, true, true);
            $select.append(option).trigger('change');
        });

    })
    

</script>

@endif

<script>
        $(document).ready(function() {
            $('#datalistWh').on('input', function() {
                var search = $(this).val();
                $.ajax({
                    url: '{{ route("ajax.getCostumer") }}',
                    data: { search: search, type: "2" },
                    success: function(users) {
                        var options = '';
                        users.forEach(function(user) {
                            options += '<option value="' + user.name + '" data-id="' + user.id + '">';
                        });
                        $('#datalistOptionsWh').html(options);
                    }
                });
            });
            // Jika Anda perlu menggunakan ID pengguna ketika opsi dipilih
            $('#datalistWh').on('change', function() {
                var selectedName = $(this).val();
                var selectedId = $('#datalistOptionsWh option').filter(function() {
                    return $(this).val() === selectedName;
                }).data('id');

                $('#warehouse').val(selectedId);
                console.log('Selected: ' + selectedId); // Lakukan sesuatu dengan ID ini
            });

            $('#datalistCus').on('input', function() {
                var search = $(this).val();
                $.ajax({
                    url: '{{ route("ajax.getCostumer") }}',
                    data: { search: search, type: "1" },
                    success: function(users) {
                        var options = '';
                        users.forEach(function(user) {
                            options += '<option value="' + user.name + '" data-id="' + user.id + '">';
                        });
                        $('#datalistOptionsCus').html(options);
                    }
                });
            });
            // Jika Anda perlu menggunakan ID pengguna ketika opsi dipilih
            $('#datalistCus').on('change', function() {
                var selectedName = $(this).val();
                var selectedId = $('#datalistOptionsCus option').filter(function() {
                    return $(this).val() === selectedName;
                }).data('id');

                $('#customer').val(selectedId);
                console.log('Selected: ' + selectedId); // Lakukan sesuatu dengan ID ini
            });

            $('#datalistSender').on('input', function() {
                var search = $(this).val();
                $.ajax({
                    url: '{{ route("ajax.getCostumer") }}',
                    data: { search: search, type: "2" },
                    success: function(users) {
                        var options = '';
                        users.forEach(function(user) {
                            options += '<option value="' + user.name + '" data-id="' + user.id + '">';
                        });
                        $('#datalistOptionsSender').html(options);
                    }
                });
            });
            // Jika Anda perlu menggunakan ID pengguna ketika opsi dipilih
            $('#datalistSender').on('change', function() {
                var selectedName = $(this).val();
                var selectedId = $('#datalistOptionsSender option').filter(function() {
                    return $(this).val() === selectedName;
                }).data('id');

                $('#sender').val(selectedId);
                console.log('Selected: ' + selectedId); // Lakukan sesuatu dengan ID ini
            });

             $('#datalistRecaiver').on('input', function() {
                var search = $(this).val();
                $.ajax({
                    url: '{{ route("ajax.getCostumer") }}',
                    data: { search: search, type: "2" },
                    success: function(users) {
                        var options = '';
                        users.forEach(function(user) {
                            options += '<option value="' + user.name + '" data-id="' + user.id + '">';
                        });
                        $('#datalistOptionsRecaiver').html(options);
                    }
                });
            });
            // Jika Anda perlu menggunakan ID pengguna ketika opsi dipilih
            $('#datalistRecaiver').on('change', function() {
                var selectedName = $(this).val();
                var selectedId = $('#datalistOptionsRecaiver option').filter(function() {
                    return $(this).val() === selectedName;
                }).data('id');

                $('#recaiver').val(selectedId);
                console.log('Selected: ' + selectedId); // Lakukan sesuatu dengan ID ini
            });

        });

    // $(document).ready(function() {

       
    //     $('.customer-select').select2({
    //         minimumInputLength:2,
    //         placeholder:'Select Customer',
    //         ajax:{
    //             url: '{{route("ajax.getCostumer")}}',
    //             dataType: "json",
    //             data: (params) => {
    //                 console.log(params)
    //                 let query = {
    //                     search: params.term,
    //                     type: "1",
    //                     page: params.page || 1,
    //                 };
    //                 return query;
    //             },
    //             processResults: (data) => {
    //                 return {
    //                     results: data.data.map((customer) => {
    //                         return { text: customer.name, id: customer.id };
    //                     }),
    //                     pagination: {
    //                         more: data.current_page < data.last_page,
    //                     },
    //                 };
    //             },
            
    //         }
    //     });


    // })
    

</script>

{{-- <script>
    $(document).ready(function() {

    

        $('.warehouse-select').select2({
            minimumInputLength:2,
            placeholder:'Select Warehouse',
            ajax:{
                url: '{{route("ajax.getCostumer")}}',
                dataType: "json",
                data: (params) => {
                    console.log(params)
                    let query = {
                        search: params.term,
                        type: "2",
                        page: params.page || 1,
                    };
                    return query;
                },
                processResults: (data) => {
                    return {
                        results: data.data.map((customer) => {
                            return { text: customer.name, id: customer.id };
                        }),
                        pagination: {
                            more: data.current_page < data.last_page,
                        },
                    };
                },
            }
        });
    });
</script> --}}

@endpush