<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Restock List</p>

        <div class="flex justify-end items-center space-x-2">

            <a href="{{ route('restock.create') }}"
                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                Add Restock
            </a>
        </div>
    </div>

    <div class="mb-8">


        <section class="bg-gray-50 dark:bg-gray-900 ">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">

                    <form action="{{route('filter.get',['action' =>'restock.index'])}}" method="post">
                        @csrf
                        <div class="flex flex-col md:flex-row items-end justify-between p-4">


                            <div class="w-full md:w-4/6">

                                <div class="grid gap-4 md:grid-cols-5">
                                    <div>
                                        <label for="code"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Barcode/SKU</label>
                                        <input type="text" id="code" name="code"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            value="{{ Request('code') }}" />
                                    </div>
                                    <div class="md:col-span-2">
                                        <label for="order_date"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Order</label>
                                        <div class="flex space-x-2">

                                            <div>
                                                <select id="order" name="order"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                    <option value ="">Choose a type</option>
                                                    <option {{ Request('order') == 'desc' ? 'selected' : 'null' }}
                                                        value="desc">Descending</option>

                                                    <option
                                                        {{ Request('order') == 'asc' ? 'selected' : 'null' }}
                                                        value="asc">Ascending</option>

                                                </select>
                                            </div>
                                            <div>

                                                <select id="kolom" name="kolom"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                    <option value ="">Choose a type</option>
                                                    <option {{ Request('kolom') == 'restock' ? 'selected' : 'null' }}
                                                        value="restock">Restock</option>
                                                    <option
                                                        {{ Request('kolom') == 'production' ? 'selected' : 'null' }}
                                                        value="production">Production</option>

                                                    <option
                                                        {{ Request('kolom') == 'shipped' ? 'selected' : 'null' }}
                                                        value="shipped">Shipped</option>

                                                    <option
                                                        {{ Request('kolom') == 'missing' ? 'selected' : 'null' }}
                                                        value="missing">Missing</option>

                                                </select>

                                            </div>

                                        </div>

                                    </div>




                                </div>



                            </div>
                            <div
                                class="mt-4 w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                                <button type="submit"
                                    class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="h-4 w-4 mr-2 "
                                        viewbox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Filter
                                </button>

                                <a href="{{ route('restock.index') }}"
                                    class="flex items-center justify-center py-2 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>


                                    {{-- <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" c viewbox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                                    </svg> --}}

                                    Clear
                                </a>


                            </div>


                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Image</th>
                                    <th scope="col" class="px-4 py-3">Date</th>
                                    <th scope="col" class="px-4 py-3">Barcode</th>
                                    <th scope="col" class="px-4 py-3">Name</th>
                                    <th scope="col" class="px-4 py-3">Code</th>
                                    <th scope="col" class="px-4 py-3">Restock QTY</th>
                                    <th scope="col" class="px-4 py-3">In Prod QTY</th>
                                    <th scope="col" class="px-4 py-3">Shipped QTY</th>
                                    <th scope="col" class="px-4 py-3">Missing QTY</th>

                                    <th scope="col" class="px-4 py-3">Actions</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($restocks as $item)
                                    @php

                                        $url = $item->item->getImageUrl();
                                    @endphp



                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-100">

                                        <th scope="row" id=""
                                            class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">


                                            <div class=" mr-3">
                                                <x-partial.image type="h-20 w-20 print:h-10 print:w-10"
                                                    :url="$url" />
                                            </div>

                                        </th>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $item->date }}</td>
                                        <th scope="row" class="px-4 py-3  whitespace-nowrap ">



                                            <a href="{{ route('restock.history', $item->id) }}"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ $item->item_id }}</a>

                                        </th>

                                        <td class="px-4 py-3">{{ $item->item->name }}</td>
                                        <td class="px-4 py-3">{{ $item->item->code }}</td>
                                        <td class="px-4 py-3">{{ $item->restocked_quantity }}</td>
                                        <td class="px-4 py-3">{{ $item->in_production_quantity }}</td>
                                        <td class="px-4 py-3">{{ $item->shipped_quantity }}</td>
                                        <td class="px-4 py-3">{{ $item->missing_quantity }}</td>

                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">

                                                {{-- UPDATE DATA --}}
                                                <a href="{{ route('restock.update', $item->id) }}"
                                                    title="Update Data Stok"
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-blue-600 bg-blue-100 hover:bg-blue-200 focus:ring-2 focus:ring-blue-300 dark:bg-blue-900 dark:text-blue-300">

                                                    {{-- Pencil --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                    </svg>
                                                </a>


                                                {{-- RECEIVED --}}
                                                <a href="{{ route('restock.received', $item->id) }}"
                                                    title="Terima Barang di Gudang"
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-green-600 bg-green-100 hover:bg-green-200 focus:ring-2 focus:ring-green-300 dark:bg-green-900 dark:text-green-300">

                                                    {{-- Box --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M20 7l-8-4-8 4m16 0v10l-8 4m0-14v14m-8-4V7" />
                                                    </svg>
                                                </a>



                                            </div>



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

                    {{ $restocks->onEachSide(1)->links() }}


                </div>
            </div>
        </section>


    </div>

    @push('jsBody')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var toggleNamaImage = document.getElementById('image-checkbox');
                var namaColumnImage = document.querySelectorAll('.image-col');


                toggleNamaImage.addEventListener('change', function() {
                    if (toggleNamaImage.checked) {
                        namaColumnImage.forEach(function(barcode) {
                            barcode.classList.remove('hidden');
                        });
                    } else {
                        namaColumnImage.forEach(function(image) {
                            image.classList.add('hidden');
                        });
                    }
                });

            });
        </script>
    @endpush

</x-layouts.layout>
