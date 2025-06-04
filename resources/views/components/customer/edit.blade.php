<div>


    <form action="{{route('addrbook.update', ['id' => $cid, 'action' => $action])}}" method="post" enctype="multipart/form-data">

        @csrf

        <section class="bg-gray-50 dark:bg-gray-900 mb-8">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-4">

                    <div class="">

                        <div class="grid grid-cols-2 gap-4 mb-8">

                            <div class="col-span-2">
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                                <input type="text" name="name" id="name" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{old('name',$data->name)}}">

                            </div>

                            @if ($hideProp == 'show')
                            <div class="col-span-2">
                                <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                                <textarea name="address" id="address" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >{{old('address',$data->address)}}</textarea>


                            </div>
                            @endif

                            {{-- <div class="col-span-2">
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                                <input type="text" name="email" id="email" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{old('email')}}">

                            </div>

                            <div class="col-span-2">
                                <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone</label>
                                <input type="text" name="phone" id="phone" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{old('phone')}}">

                            </div>

                            <div class="col-span-2">
                                <label for="phone2" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone 2</label>
                                <input type="text" name="phone2" id="phone2" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{old('phone2')}}">

                            </div>

                            <div class="col-span-2">
                                <label for="fax" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fax</label>
                                <input type="text" name="fax" id="fax" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{old('fax')}}">

                            </div> --}}

                            <div class="col-span-2">
                                <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                                <textarea name="description" id="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >{{old('description',$data->description)}}</textarea>


                            </div>


                            @if ($hideProp == 'show')
                            <div>

                                <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Type</label>
                                <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                                
                                    @foreach (\App\Models\Customer::$typesJSON as $item)
                                        <option {{$item['id'] == $data->type ? 'selected' : ''}}  value="{{$item['id']}}">{{ $item['name']}}</option>
                                    @endforeach
                                   
                                   
                                    
                                    
                                  
                                </select>

                            </div>
                            @endif

                            <div  class="col-span-2">
                               
                                <div class="flex items-center">
                                    <input {{ $data->ppn == 1 ? 'checked' : (old('ppn') == 1 ? 'checked' : '')}}  id="checked-checkbox" name="ppn" type="checkbox" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"> PPN</label>
                                </div>

                                @if ($onlineProp == "show")
                                    
                                <div class="flex items-center mt-2">
                                    <input {{ $data->is_online == 1 ? 'checked' : (old('is_online') == 1 ? 'checked' : '')}}  id="checked-checkbox" name="is_online" type="checkbox" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"> Online</label>
                                </div>

                                @endif
                            </div>
                        </div>

                       
                     
                    


                        
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Submit</button>

                    </div>
                </div>
            </div>
        </section>

    </form>
</div>