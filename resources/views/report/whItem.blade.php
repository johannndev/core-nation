<x-layouts.layout>

    {{-- HEADER --}}
    <div class="mb-6">
        <p class="text-2xl font-bold">
            Item Gudang
            {{-- Data {{ $month ? "Bulan $month" : 'Tahun' }} {{ $year }} --}}
        </p>
    </div>

    <div class="mb-8">
        <section class="bg-gray-50 dark:bg-gray-900">
            <div class="mx-auto">
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">

                    {{-- FILTER --}}
                    {{-- <form action="{{ route('filter.get', ['action' => 'report.pembelian']) }}" method="post">
                        @csrf

                        <div class="flex flex-col md:flex-row items-end justify-between p-4">
                            <div class="grid gap-4 md:grid-cols-5">

                                <div>
                                    <label class="text-sm">Month</label>
                                    <select name="month" class="w-full p-2 rounded-lg">
                                        <option value="">-- All Month --</option>
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option {{ Request('month') == $i ? 'selected' : '' }} value="{{ $i }}">
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <div>
                                    <label class="text-sm">Year</label>
                                    <select name="year" class="w-full p-2 rounded-lg">
                                        @foreach ($yearList as $y)
                                            <option {{ Request('year', $datesNow->year) == $y ? 'selected' : '' }} value="{{ $y }}">
                                                {{ $y }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                                Filter
                            </button>
                        </div>
                    </form> --}}

                    {{-- CONTENT --}}
                    <div class="p-4 space-y-6">

                        {{-- GLOBAL SUMMARY --}}
                        @php
                            $grandTotalCost = $data->sum('total_cost');
                            $grandTotalItem = $data->sum('total_item');
                            $grandTotalQty  = $data->sum('total_qty');
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="p-4 bg-white shadow rounded">
                                <p class="text-sm">Total Item (SKU)</p>
                                <p class="font-bold text-right">
                                    {{ Number::format($grandTotalItem) }}
                                </p>
                            </div>

                            <div class="p-4 bg-white shadow rounded">
                                <p class="text-sm">Total Qty</p>
                                <p class="font-bold text-right">
                                    {{ Number::format($grandTotalQty) }}
                                </p>
                            </div>

                            <div class="p-4 bg-white shadow rounded">
                                <p class="text-sm">Total Asset (Cost)</p>
                                <p class="font-bold text-right text-green-600">
                                    {{ Number::format($grandTotalCost) }}
                                </p>
                            </div>
                        </div>

                        {{-- WAREHOUSE LIST --}}
                        <div>
                            <h3 class="text-lg font-bold mb-3">Warehouse Stock</h3>

                            <table class="w-full text-sm">
                                <thead class="bg-gray-100 text-xs uppercase">
                                    <tr>
                                        <th class="px-4 py-3 text-left">Gudang</th>
                                        <th class="px-4 py-3 text-right">Item</th>
                                        <th class="px-4 py-3 text-right">Qty</th>
                                        <th class="px-4 py-3 text-right">Cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $row)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="px-4 py-3 text-left">
                                               <a href="{{ route('warehouse.items', $row->id) }}"
                                                    class="text-blue-600 hover:underline">
                                                    {{ $row->nama_gudang }}
                                                </a>
                                               
                                            </td>

                                            <td class="px-4 py-3 text-right tabular-nums">
                                                {{ Number::format($row->total_item) }}
                                            </td>

                                            <td class="px-4 py-3 text-right tabular-nums">
                                                {{ Number::format($row->total_qty) }}
                                            </td>

                                            <td class="px-4 py-3 text-right font-semibold tabular-nums">
                                                {{ Number::format($row->total_cost) }}
                                            </td>
                                        </tr>
                                    @endforeach

                                    {{-- TOTAL --}}
                                    <tr class="bg-gray-100 font-semibold">
                                        <td class="px-4 py-3 text-left">TOTAL</td>
                                        <td class="text-right">{{ Number::format($grandTotalItem) }}</td>
                                        <td class="text-right">{{ Number::format($grandTotalQty) }}</td>
                                        <td class="text-right">{{ Number::format($grandTotalCost) }}</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </section>
    </div>

</x-layouts.layout>