<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">{{ $restock->item->name }}</p>

        <div class="flex justify-end items-center space-x-2">

            <a href="{{ route('restock.index') }}"
                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                Restock
            </a>
        </div>
    </div>

    <div class="mb-8">


        <section class="bg-gray-50 dark:bg-gray-900 ">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">

                    <div class="mx-auto p-6">

                        <h2 class="text-lg font-semibold mb-4">
                            History Perubahan Stok
                        </h2>

                        @if ($histories->isEmpty())
                            <div class="text-sm text-gray-500">
                                Belum ada history.
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach ($histories as $history)
                                    <div class="border rounded-lg p-4 bg-white shadow-sm">

                                        <div class="flex items-start justify-between gap-4">
                                            <div class="flex-1">
                                                {{-- PESAN HUMAN READABLE --}}
                                                <p class="text-sm text-gray-800 leading-relaxed">
                                                    <span class="font-medium">
                                                        {{ $history->user->username ?? 'System' }}
                                                    </span>

                                                    @if ($history->action === 'receive')
                                                        menerima barang
                                                    @else
                                                        mengubah
                                                        <span class="font-medium">
                                                            {{ ucfirst($history->step) }}
                                                        </span>
                                                    @endif

                                                    dari
                                                    <span class="font-semibold">
                                                        {{ $history->qty_before }}
                                                    </span>
                                                    ke
                                                    <span class="font-semibold">
                                                        {{ $history->qty_after }}
                                                    </span>
                                                </p>

                                                {{-- TANGGAL --}}
                                                <p class="text-xs text-gray-500 mt-1">
                                                    {{ \Carbon\Carbon::parse($history->date)->format('d M Y') }}
                                                </p>
                                            </div>

                                            {{-- QTY --}}
                                            <div class="text-right">
                                                <span class="inline-block text-sm font-semibold text-blue-600">
                                                    +{{ $history->qty_changed }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- INVOICE --}}
                                        @if ($history->invoice)
                                            <div class="mt-2 text-xs">
                                                <span class="text-gray-500">Invoice:</span>
                                                <a href="{{ route('transaction.getDetail', $history->invoice) }}"
                                                    class="text-blue-600 hover:underline">
                                                    #{{ $history->invoice }}
                                                </a>
                                            </div>
                                        @endif

                                    </div>
                                @endforeach
                            </div>
                        @endif

                    </div>

                     {{ $histories->onEachSide(1)->links() }}

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
