<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Aset Lancar</p>

       
    </div>

    <div id="alert-border-2">

    @if ((session('errorMessage')))

    <div  class="flex items-center p-4 mb-4 text-red-800 border-t-4 border-red-300 bg-red-50 dark:text-red-400 dark:bg-gray-800 dark:border-red-800" role="alert">
        <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
          <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <div class="ms-3 text-sm font-medium">
            {{session('errorMessage')}}
        </div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700"  data-dismiss-target="#alert-border-2" aria-label="Close">
          <span class="sr-only">Dismiss</span>
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
          </svg>
        </button>
    </div>
        
    @endif

</div>

    <form class="myForm" id="myForm" action="{{route('asetLancar.postCreate')}}" method="post" enctype="multipart/form-data">

        @csrf

        <input type="text" name="type" value="{{ $type }}" hidden>
        <input type="text" name="tags[2][]" hidden>

        <section class="bg-gray-50 dark:bg-gray-900 mb-8">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-4">

                    <div class="">

                        <div class="grid grid-cols-2 gap-4 mb-8">

                            <div class="col-span-2">
                                <label for="pcode" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Code</label>
                                <input type="text" name="pcode" id="pcode" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{old('pcode')}}">

                            </div>

                            <div class="col-span-2">
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                                <input type="text" name="name" id="name" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{old('name')}}">

                            </div>

                            <div class="col-span-2">
                                <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
                                <input type="text" name="price" id="price" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{old('price')}}">

                            </div>

                            <div class="col-span-2">
                                <label for="cost" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cost</label>
                                <input type="text" name="cost" id="cost" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{old('cost')}}">

                            </div>

                            <div class="col-span-2">
                                <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                                <textarea name="description" id="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >{{old('description')}}</textarea>


                            </div>

                            <div class="col-span-2">
                                <label for="description2" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NB</label>
                                <textarea name="description2" id="description2" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >{{old('description2')}}</textarea>


                            </div>

                            <div class="col-span-2">
                                
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Upload file</label>
                                <input name="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="file_input" type="file">

                            </div>

                            <div class="col-span-2">
                                <div >
                                    <label for="warna" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white ">Warna</label>

                                    <div class="">
                                        <div class="relative mb-4">
                                            <select class="warna" name="tags[20][]" id="warna">
                                                <option ></option>
                                            </select>

                                            @error('')
                                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                            @enderror
                                            
                                        </div>
                                        
                                    </div>
                                    
                                </div>

                            </div>

                            <div class="col-span-2">
                                @foreach ($tags as $type)
                                    <div class="mb-6">
                                        <p class="font-bold text-lg mb-4">{{$type['name']}} </p>
                                        <div class="grid grid-cols-3 md:grid-cols-6 gap-2">

                                            @foreach ($type['data'] as $item)
                                                <div>
                                                    <div class="flex items-start md:items-center  mb-4">
                                                        <input id="default-radio-1" type="{{ $type['type_id'] == 7 ? 'checkbox' : 'radio' }}" value="{{ $item['id'] }}" name="tags[{{ $type['type_id'] }}][]" class="mt-1 md:mt-0 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                        <label for="default-radio-1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{$item['name']}}</label>
                                                    </div>

                                                
                                                </div>
                                            @endforeach

                                        </div>
                                    
                                    </div>
                                    
                                @endforeach
                            </div>
                          
                        </div>


                     
                    


                        
                        <x-layout.submit-button />

                    </div>
                </div>
            </div>
        </section>

    </form>

    @push('jsBody')

        <script>
        $(document).ready(function() {

            $('.warna').select2({
                width: '100%',
                placeholder: "Pilih Warna",
                minimumInputLength:2,
                ajax: {
                    url: '{{ route("ajax.getWarna") }}',
                    dataType: "json",
                    data: (params) => {
                        console.log(params);
                        
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

            // @isset ($dataProp['default'])

            //     var blueOption = new Option('{{$defaultWH->name}}',{{$defaultWH->id}}, true, true);
            //     $('.{{$dataProp["id"]}}').append(blueOption).trigger('change');

            // @endisset

        })
        </script>

    @endpush
   

    
   


</x-layouts.layout>