<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Restock List</p>

        <div class="flex justify-end items-center space-x-2">

            <a href="{{ route('restock.create') }}"
                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                Add Restock
            </a>
            
            <a href="{{ route('restock.uploadExcel') }}"
                class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-purple-600 dark:hover:bg-purple-700 focus:outline-none dark:focus:ring-purple-800">
                Import Excel
            </a>

            <a href="{{ route('restock.received') }}"
                class="relative inline-flex items-center gap-2 text-white bg-green-700 hover:bg-green-800 
          focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 
          dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">

                
                To Gudang

                {{-- Badge Count --}}
                @if ($cartCount > 0)
                    <span
                        class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold 
                     rounded-full w-6 h-6 flex items-center justify-center">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>
        </div>
    </div>

    <div class="mb-8">


        <section class="bg-gray-50 dark:bg-gray-900 ">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">

                    <form action="{{ route('filter.get', ['action' => 'restock.index']) }}" method="post">
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

                                                    <option {{ Request('order') == 'asc' ? 'selected' : 'null' }}
                                                        value="asc">Ascending</option>

                                                </select>
                                            </div>
                                            <div>

                                                <select id="kolom" name="kolom"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                    <option value ="">Choose a type</option>
                                                    <option {{ Request('kolom') == 'restock' ? 'selected' : 'null' }}
                                                        value="restock">Restock</option>
                                                    <option {{ Request('kolom') == 'production' ? 'selected' : 'null' }}
                                                        value="production">Production</option>

                                                    <option {{ Request('kolom') == 'shipped' ? 'selected' : 'null' }}
                                                        value="shipped">Shipped</option>

                                                    <option {{ Request('kolom') == 'missing' ? 'selected' : 'null' }}
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
                                    <th scope="col" class="px-4 py-3">Warehouse</th>
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

                                        $warehouseItems = $item->item?->whItem;

                                        if (!($warehouseItems instanceof \Illuminate\Support\Collection)) {
                                            $warehouseItems = collect();
                                        }
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



                                            <a href="{{ route('asetLancar.detail', $item->item_id) }}"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ $item->item_id }}</a>

                                        </th>

                                        <td class="px-4 py-3">{{ $item->item->name }}</td>
                                        <td class="px-4 py-3">{{ $item->item->code }}</td>
                                        <td>
                                            @forelse ($warehouseItems as $wh)
                                                <div>
                                                    <strong>{{ $wh->warehouse?->name ?? '-' }}</strong> :
                                                    {{ $wh->quantity ?? 0 }}
                                                </div>
                                            @empty
                                                <span class="text-gray-400 italic">No warehouse stock</span>
                                            @endforelse
                                        </td>
                                        <td class="px-4 py-3">{{ $item->restocked_quantity }}</td>
                                        <td class="px-4 py-3 ">
                                            <div class="flex items-center gap-2">
                                                {{ $item->in_production_quantity }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 ">
                                            <div class="flex items-center gap-2">
                                                {{ $item->shipped_quantity }}
                                               
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">{{ $item->missing_quantity }}</td>

                                        <td class="px-4 py-3 ">
                                            <div class="flex items-center gap-2">

                                                {{-- history --}}
                                                <a href="{{ route('restock.history', $item->id) }}"
                                                    title="Lihat History Perubahan Stok"
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-yellow-600 bg-yellow-100 hover:bg-yellow-200 focus:ring-2 focus:ring-yellow-300 dark:bg-yellow-900 dark:text-yellow-300">

                                                    {{-- Clock --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </a>

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

                                                <div>
                                                    <!-- Modal toggle -->
                                                    <button data-modal-target="invoice-model-{{ $item->id }}"
                                                        data-modal-toggle="invoice-model-{{ $item->id }}"
                                                        class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-purple-600 bg-purple-100 hover:bg-purple-200 focus:ring-2 focus:ring-purple-300 dark:bg-purple-900 dark:text-purple-300"
                                                        type="button">
                                                        {{-- Box --}}
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M3 9l9-6 9 6v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9zM9 22V12h6v10" />
                                                        </svg>
                                                    </button>

                                                    <!-- Main modal -->
                                                    <div id="invoice-model-{{ $item->id }}" tabindex="-1"
                                                        aria-hidden="true"
                                                        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                                        <div class="relative p-4 w-full max-w-md max-h-full">
                                                            <!-- Modal content -->
                                                            <div
                                                                class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                                <!-- Modal header -->
                                                                <div
                                                                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                                                    <h3
                                                                        class="text-xl font-semibold text-gray-900 dark:text-white">
                                                                        Masukkan ke Daftar Penerimaan
                                                                    </h3>
                                                                    <button type="button"
                                                                        class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                                                        data-modal-hide="invoice-model-{{ $item->id }}">
                                                                        <svg class="w-3 h-3" aria-hidden="true"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            fill="none" viewBox="0 0 14 14">
                                                                            <path stroke="currentColor"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                                        </svg>
                                                                        <span class="sr-only">Close modal</span>
                                                                    </button>
                                                                </div>

                                                                <form
                                                                    action="{{ route('restock.toGudangCart', $item->id) }}"
                                                                    method="post" class="space-y-4">
                                                                    <!-- Modal body -->
                                                                    @csrf

                                                                    <div class="p-4 md:p-5">

                                                                        <div>
                                                                            <label for="password"
                                                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Quantity</label>
                                                                            <input type="number" name="quantity"
                                                                                min="1"
                                                                                max="{{ $item->shipped_quantity }}"
                                                                                value="{{ $item->shipped_quantity }}"
                                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg  focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                                                required>
                                                                        </div>

                                                                    </div>

                                                                    <div
                                                                        class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                                                        <button type="submit"
                                                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                                                                        <button
                                                                            data-modal-hide="invoice-model-{{ $item->id }}"
                                                                            type="button"
                                                                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Decline</button>
                                                                    </div>

                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

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
