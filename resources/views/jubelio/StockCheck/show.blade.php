<x-layouts.layout>

    <div class="flex items-center gap-4 mb-6">

        {{-- Back Button --}}
        <a href="{{ route('jubelio-stock-checks.index') }}"
            class="inline-flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">

            <svg class="w-5 h-5"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 19l-7-7 7-7" />
            </svg>
        </a>

        <div>
            <h1 class="text-2xl font-bold">
                Pengecekan Stok #{{ $stockCheck->id }}
            </h1>

            <p class="text-sm text-gray-500 dark:text-gray-400">
                Status:
                <span class="font-bold uppercase">
                    {{ $stockCheck->status }}
                </span>
                |
                Dibuat:
                {{ \Carbon\Carbon::parse($stockCheck->created_at)->translatedFormat('d M Y H:i') }}
            </p>
        </div>

    </div>

    {{-- Summary Card --}}
    <div class="grid gap-6 md:grid-cols-3 mb-6">

        {{-- Page Tracking --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-5">

            <div class="flex items-center justify-between mb-2">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Halaman Terakhir
                </p>

                <svg class="w-4 h-4 text-gray-400"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 12h14" />
                </svg>
            </div>

            <div class="text-2xl font-bold">
                {{ $stockCheck->page_tracking }}
            </div>

        </div>

        {{-- Total Discrepancies --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-5">

            <div class="flex items-center justify-between mb-2">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Total Ketidakcocokan
                </p>

                <svg class="w-4 h-4 text-red-500"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z" />
                </svg>
            </div>

            <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                {{ $stockCheck->discrepancies->count() }}
            </div>

        </div>

        {{-- Limit --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-5">

            <div class="flex items-center justify-between mb-2">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Batas Ketidakcocokan
                </p>

                <svg class="w-4 h-4 text-gray-400"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M20 13V7a2 2 0 00-2-2h-4m-4 0H6a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2v-2" />
                </svg>
            </div>

            <div class="text-2xl font-bold">
                200
            </div>

        </div>

    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="font-bold text-lg">
                Daftar Ketidakcocokan
            </h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-left text-sm">

                <thead class="bg-gray-100 dark:bg-gray-700 uppercase text-xs text-gray-700 dark:text-gray-300">

                    <tr>
                        <th class="px-4 py-3">Item (Aria)</th>
                        <th class="px-4 py-3">Jubelio Item ID</th>
                        <th class="px-4 py-3">Warehouse (Aria)</th>
                        <th class="px-4 py-3">Location (Jubelio)</th>
                        <th class="px-4 py-3 text-center">Qty Aria</th>
                        <th class="px-4 py-3 text-center">Qty Jubelio</th>
                        <th class="px-4 py-3 text-center">Selisih</th>
                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                    @forelse ($stockCheck->discrepancies as $item)

                        @php
                            $diff = $item->aria_qty - $item->jubelio_qty;
                        @endphp

                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">

                            {{-- Item --}}
                            <td class="px-4 py-3">

                                @if ($item->item)

                                    <div class="flex flex-col">
                                        <span class="font-bold">
                                            {{ $item->item->name }}
                                        </span>

                                        <span class="text-xs text-gray-500 font-mono">
                                            {{ $item->item->code }}
                                        </span>
                                    </div>

                                @else

                                    <span class="italic text-gray-500">
                                        Item tidak ditemukan
                                    </span>

                                @endif

                            </td>

                            {{-- Jubelio Item ID --}}
                            <td class="px-4 py-3 font-mono">
                                {{ $item->jubelio_item_id }}
                            </td>

                            {{-- Warehouse --}}
                            <td class="px-4 py-3">
                                {{ $item->warehouse->name ?? 'ID: ' . $item->warehouse_id }}
                            </td>

                            {{-- Jubelio Location --}}
                            <td class="px-4 py-3">

                                <div class="flex flex-col">

                                    <span>
                                        {{ $item->jubelio_location_name ?? '-' }}
                                    </span>

                                    <span class="text-xs text-gray-500 font-mono">
                                        ID: {{ $item->jubelio_location_id }}
                                    </span>

                                </div>

                            </td>

                            {{-- Qty Aria --}}
                            <td class="px-4 py-3 text-center font-bold">
                                {{ $item->aria_qty }}
                            </td>

                            {{-- Qty Jubelio --}}
                            <td class="px-4 py-3 text-center font-bold text-blue-600 dark:text-blue-400">
                                {{ $item->jubelio_qty }}
                            </td>

                            {{-- Difference --}}
                            <td class="px-4 py-3 text-center">

                                @if ($diff < 0)

                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">

                                        {{ number_format($diff, 2) }}

                                    </span>

                                @elseif ($diff > 0)

                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-500 text-white">

                                        +{{ number_format($diff, 2) }}

                                    </span>

                                @else

                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">

                                        0.00

                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7"
                                class="px-4 py-8 text-center italic text-gray-500">

                                Tidak ada ketidakcocokan ditemukan pada pengecekan ini.

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    @push('jsBody')
    @endpush

</x-layouts.layout>