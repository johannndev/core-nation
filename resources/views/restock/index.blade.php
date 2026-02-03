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

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Image</th>
                                    <th scope="col" class="px-4 py-3">Barcode</th>
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
