<div>
    <div class="mb-5">
        <label for="select-item" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white @error('select-item') text-red-700 dark:text-red-500  @enderror">Costomer</label>

        <div class="@error('select-item') not-valid @enderror">
            <div class="relative mb-4">
                <select class="select-item" name="select-item">
                    <option ></option>
                </select>

                @error('select-item')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror
                
            </div>
            
        </div>
          
    </div>

    @push('jsBody')

    <script>
        $(document).ready(function() {

            $('.select-item').select2({
                width: '100%',
                placeholder: "Pilih costomer",
                minimumInputLength:2,
                ajax: {
                    url: '{{ route("ajax.getCostumer") }}',
                    dataType: "json",
                    data: (params) => {
                        let query = {
                            search: params.term,
                            type: '{{$dataProp["type"]}}',
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

        })
    </script>

    @endpush

</div>