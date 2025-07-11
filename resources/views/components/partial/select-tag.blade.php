<div class="filterSelect">
    <div >
        <label for="{{$dataProp['id']}}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white @error($dataProp['id']) text-red-700 dark:text-red-500  @enderror">{{$dataProp['label']}}</label>

        <div class="@error($dataProp['id']) not-valid @enderror">
            <div class="relative">
                <select class="{{$dataProp['id']}}" name="{{$dataProp['id']}}" id="{{$dataProp['id']}}">
                    <option ></option>
                </select>

                @error('')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror
                
            </div>
            
        </div>
          
    </div>

    @push('jsBody')

    <script>
        $(document).ready(function() {

            $('.{{$dataProp["id"]}}').select2({
                width: '100%',
                placeholder: "Pilih {{$dataProp['label']}}",
                minimumInputLength:1,
                ajax: {
                    url: '{{ route("ajax.getTag") }}',
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

            @isset ($dataProp['default'])

                var blueOption = new Option('{{$default->name}}',{{$default->id}}, true, true);
                $('.{{$dataProp["id"]}}').append(blueOption).trigger('change');

            @endisset

        })
    </script>

    @endpush
</div>