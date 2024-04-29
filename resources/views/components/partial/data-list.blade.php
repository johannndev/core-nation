
<div class="col-span-2 ">

   
    <label for="{{$dataProp['id']}}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{$dataProp['label']}}</label>
    <input id="{{$dataProp['id']}}" name="{{$dataProp['id']}}" hidden  value="{{$defaultWH ? $defaultWH->id : ''}}">
    <input type="text" id="{{$dataProp['idList']}}" list="{{$dataProp['idOption']}}" value="{{$defaultWH ? $defaultWH->name : ''}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" autocomplete="off">
    <datalist id="{{$dataProp['idOption']}}">
        <!-- Options akan diisi oleh jQuery AJAX -->
    </datalist>


    @push('jsBody')

    <script>
         $(document).ready(function() {

            $('#{{$dataProp["idList"]}}').on('input', function() {
                var search = $(this).val();
                $.ajax({
                    url: '{{ route("ajax.getCostumer") }}',
                    data: { search: search, type: '{{$dataProp["type"]}}' },
                    success: function(users) {
                        var options = '';
                        users.forEach(function(user) {
                            options += '<option value="' + user.name + '" data-id="' + user.id + '">';
                        });
                        $('#{{$dataProp["idOption"]}}').html(options);
                    }
                });
            });
            // Jika Anda perlu menggunakan ID pengguna ketika opsi dipilih
            $('#{{$dataProp["idList"]}}').on('change', function() {
                var selectedName = $(this).val();
                var selectedId = $('#{{$dataProp["idOption"]}} option').filter(function() {
                    return $(this).val() === selectedName;
                }).data('id');

                $('#{{$dataProp["id"]}}').val(selectedId);
                console.log('Selected: ' + selectedId); // Lakukan sesuatu dengan ID ini
            });

         })
    </script>

    @endpush

</div>

