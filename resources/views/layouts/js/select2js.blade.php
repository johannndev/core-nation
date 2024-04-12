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

       
        $('.customer-select').select2({
            minimumInputLength:2,
            placeholder:'Select Customer',
            ajax:{
                url: '{{route("ajax.getCostumer")}}',
                dataType: "json",
                data: (params) => {
                    console.log(params)
                    let query = {
                        search: params.term,
                        type: "1",
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


    })
    

</script>

<script>
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
</script>

@endpush