<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Barang di Produksi</p>

       
    </div>



    <div class="mb-8">


        <section class="bg-gray-50 dark:bg-gray-900 ">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="flex justify-end p-4">
                        <button type="submit" class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800" data-modal-target="filterModal" data-modal-toggle="filterModal">
                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="h-4 w-4 mr-2 " viewbox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                            </svg>
                            Filter
                        </button>

                    </div>

                    <form action="{{route('filter.get',['action' =>'produksi.index'])}}" method="post" id="filterModal" tabindex="-1" aria-hidden="true"
                        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">

                        @csrf

                        <div class="relative w-full h-full max-w-xl md:h-auto">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                                <!-- Modal header -->
                                <div class="mt-20 flex items-start justify-between px-6 py-4 rounded-t">
                                    <h3 class="text-lg font-normal text-gray-500 dark:text-gray-400">
                                        Filter barang di produksi
                                    </h3>
                                    <button type="button"
                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                        data-modal-toggle="filterModal">
                                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <div class="px-4 md:px-6 mb-6">
                                    <div class="col-span-12">
                                        <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date</label>
                                    </div>

                                    <div class="grid grid-cols-12 gap-2  ">
                                    
                                        <div class="col-span-5">
                                            <div>
                                               
                                                <input type="date" id="from" name="from" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('from')}}"/>
                                            </div>
                                        </div>
    
                                        <div class="col-span-2">
                                            <div class="flex items-center justify-center h-full">
                                                <p class="font-medium text-gray-500">to</p>
                                            </div>
                                        </div>
    
                                        <div class="col-span-5">
                                            <div>
                                                
                                                <input type="date" id="to" name="to" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('to')}}"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              
                                <div class="grid grid-cols-2 gap-2 px-4 md:px-6 md:grid-cols-3">

                                    <div>
                                        <label for="potong" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Potong Worker</label>
                                        <select id="potong" name="potong_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="" >Choose</option>
                                           
                                            @foreach ($potongList as $item)
                                                <option {{Request('potong_id') == $item->id ? "selected" : ""}} value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                           
                                          </select>
                                    </div>

                                    <div>
                                        <label for="jahit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jahit Worker</label>
                                        <select id="jahit" name="jahit_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="">Choose</option>
                                           
                                            @foreach ($jahitList as $item)
                                                <option {{Request('jahit_id') == $item->id ? "selected" : ""}} value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                           
                                          </select>
                                    </div>

                                    

                                   
                                    <div>
                                        <label for="serial" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kitir</label>
                                        <input type="text" id="serial" name="serial" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('serial')}}"/>
                                    </div>

                                    <div>
                                        <label for="kode" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode</label>
                                        <input type="text" id="kode" name="kode" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('kode')}}"/>
                                    </div>

                                    <div>
                                        <label for="surat_jalan_potong" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">SJP</label>
                                        <input type="text" id="surat_jalan_potong" name="surat_jalan_potong" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('surat_jalan_potong')}}"/>
                                    </div>

                                    <div>
                                        <label for="warna" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Warna</label>
                                        <input type="text" id="warna" name="warna" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('warna')}}"/>
                                    </div>

                                    <div>
                                        <label for="customer" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Customer</label>
                                        <input type="text" id="customer" name="customer" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('customer')}}"/>
                                    </div>

                                   
                                    
                                </div>
                                <!-- Modal footer -->
                                <div class="flex items-center p-6 space-x-4 rounded-b dark:border-gray-600">
                                    <button type="submit"
                                        class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-700 dark:hover:bg-primary-800 dark:focus:ring-primary-800">
                                        Apply
                                    </button>
                                    <a href="{{route('produksi.index')}}"
                                        class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Kitir</th>
                                    <th scope="col" class="px-4 py-3">Kode</th>
                                    <th scope="col" class="px-4 py-3">Jumlah</th>
                                    <th scope="col" class="px-4 py-3">SJP</th>
                                    <th scope="col" class="px-4 py-3">Potong</th>
                                    <th scope="col" class="px-4 py-3">Size</th>
                                    <th scope="col" class="px-4 py-3">Warna</th>
                                    <th scope="col" class="px-4 py-3">Costumer</th>
                                    <th scope="col" class="px-4 py-3 ">Jahit</th>
                                    <th scope="col" class="px-4 py-3">Action</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ( $produksi as $row)
                                    
                                

                                <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                                    <th scope="row" class="px-4 py-3  whitespace-nowrap ">
                                        
                                        <a href="{{route('produksi.detail',$row->id)}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$row->serial()}}</a>

                                        </th>
                                    <td class="px-4 py-3">

                                      

                                        @if ($row->item_id > 0)

                                            {{$row->item->getItemCode()}}
                                        @else

                                            {{$row->temp_name}}
                                            
                                        @endif

                                        
                                    </td>
                                  
                                    <td class="px-4 py-3">{{$row->quantity}}</td>
                                    <td class="px-4 py-3">{{$row->surat_jalan_potong}}</td>
                                    <td class="px-4 py-3 text-center">
                                        <p>{{$row->potong_date}}</p>
                                        @isset($row->potong)
                                        <hr class="h-px my-1 bg-gray-400 border-0 dark:bg-gray-700">
                                        <p>{{$row->potong->name}}</p>
                                        @endisset
                                    </td>
                                    <td class="px-4 py-3">
                                        {{$row->size->name}}
                                    </td>
                                    <td class="px-4 py-3">{{$row->warna}}</td>
                                    <td class="px-4 py-3">{{$row->customer}}</td>
                                    <td class="px-4 py-3 text-center ">
                                        @if ($row->jahit_date)

                                            <p>{{$row->jahit_date}}</p>
                                            @isset($row->jahit)
                                                <hr class="h-px my-1 bg-gray-400 border-0 dark:bg-gray-700">
                                                <p>{{$row->jahit->name}}</p>
                                            @endisset

                                        @else

                                        <form action="{{route('produksi.postSaveRow',$row->id)}}" method="post" id="form-id">

                                            @csrf

                                            @method('PATCH')



                                            <select id="jahitUpdate" name="jahitUpdate" class="w-28 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block  p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <option value="">Choose</option>
                                                @foreach ($jahitList as $item)
                                                    <option {{Request('jahit_id') == $item->id ? "selected" : ""}} value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                              </select>
                                        </form>
                                            
                                        @endif

                                       
                                        
                                    </td>
                                    
                                    
                                    <td class="px-4 py-3">
                                        @if ($row->jahit_date)

                                            <form action="{{route('produksi.postSetor',$row->id)}}" method="post">

                                                @csrf

                                                @method('PATCH')

                                                <button type="submit" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5  dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Setor</button>

                                                
                                            </form>


                                        @else

                                            <button onclick="document.getElementById('form-id').submit();" type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Update</button>

                                        @endif
                                    </td>
                                   
                                    
                                    
                                </tr>
                                    
                                @empty

                                <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                                   
                                    <td class="px-4 py-3 text-center" colspan="9">Data Empty</td>
                                   
                                    
                                    
                                </tr>
                                    
                                @endforelse ()
                                
                              
                            
                            </tbody>
                        </table>
                    </div>

                    {{$produksi->onEachSide(1)->links()}}

                   
                </div>
            </div>
        </section>
       

    </div>

</x-layouts.layout>