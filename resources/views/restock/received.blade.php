<x-layouts.layout>

    <x:partial.scan-modal />

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Receive Restock</p>


    </div>

    <div id="alert-border-2">

        <div class="error-wrapper hidden items-center p-4 mb-4 text-red-800 border-t-4 border-red-300 bg-red-50 dark:text-red-400 dark:bg-gray-800 dark:border-red-800"
            role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <div class="ms-3 text-sm font-medium error-text">

            </div>
            <button type="button"
                class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700"
                data-dismiss-target="#alert-border-2" aria-label="Close">
                <span class="sr-only">Dismiss</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>

    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-300 bg-red-50 p-4">
            <div class="mb-2 text-sm font-semibold text-red-700">
                Terjadi kesalahan:
            </div>
            <ul class="list-disc space-y-1 pl-5 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg p-6">


        <form method="POST" action="{{ route('restock.postReceived') }}">
            @csrf
            {{-- HEADER --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

                {{-- DATE --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Tanggal Penerimaan
                    </label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5
                           focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                {{-- INVOICE --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        No Invoice / Surat Jalan
                    </label>
                    <input type="text" name="invoice"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5
                           focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="INV-2026-0001">
                </div>

                {{-- TOTAL INFO --}}
                <div class="col-span-1 md:col-span-2 flex items-end">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg w-full">
                        <p class="text-sm text-gray-500">Total Item</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ count($items) }} SKU |
                            {{ collect($items)->sum('quantity') }} pcs
                        </p>
                    </div>
                </div>
            </div>

            {{-- ITEM LIST --}}
            <div class="border rounded-lg divide-y dark:border-gray-700">

                <div
                    class="bg-gray-100 dark:bg-gray-700 px-4 py-2 font-semibold text-sm text-gray-700 dark:text-gray-200">
                    Daftar Barang Diterima
                </div>

                @forelse($items as $index => $item)
                    <div class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700">

                        {{-- ITEM INFO --}}
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $item['name'] }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $item['code'] }}
                            </p>
                        </div>

                        {{-- QTY --}}
                        <div class="text-right mr-6">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $item['quantity'] }} pcs
                            </p>
                            <p class="text-xs text-gray-500">
                                Rp {{ number_format($item['subtotal']) }}
                            </p>
                        </div>

                        {{-- DELETE BUTTON --}}
                        <button type="button" onclick="deleteItem('{{ $item['code'] }}')"
                            class="text-red-600 hover:text-red-700 text-sm font-medium px-2 py-1 rounded hover:bg-red-50">
                            Hapus
                        </button>



                    </div>
                @empty
                    <div class="p-4 text-gray-500 text-sm text-center">
                        Tidak ada item di cart gudang
                    </div>
                @endforelse
            </div>

            {{-- FOOTER --}}
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('restock.index') }}"
                    class="px-4 py-2 text-sm rounded-lg border border-gray-300 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700">
                    Kembali
                </a>



                <button type="submit"
                    class="px-5 py-2 text-sm font-medium text-white bg-green-700 hover:bg-green-800 rounded-lg focus:ring-4 focus:ring-green-300">
                    Simpan ke Gudang
                </button>


            </div>

        </form>

        <form id="delete-form" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>


    </section>

    @push('jsBody')
        <script>
            function deleteItem(code) {
                if (!confirm('Hapus item ini?')) return;

                let form = document.getElementById('delete-form');
                form.action = `/restock/remove-cart/${code}`; // sesuaikan route
                form.submit();
            }
        </script>
    @endpush





</x-layouts.layout>
