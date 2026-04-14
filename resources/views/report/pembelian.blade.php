<x-layouts.layout>

    {{-- HEADER --}}
    <div class="mb-6">
        <p class="text-2xl font-bold">Data {{ $month }} - {{ $year }}</p>
    </div>

    <div class="mb-8">
        <section class="bg-gray-50 dark:bg-gray-900">
            <div class="mx-auto">
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">

                    {{-- FILTER --}}
                    <form action="{{ route('filter.get', ['action' => 'report.pembelian']) }}" method="post">
                        @csrf

                        <div class="flex flex-col md:flex-row items-end justify-between p-4">

                            <div class="grid gap-4 md:grid-cols-5">
                                <div>
                                    <label class="text-sm">Month</label>
                                    <select name="month" class="w-full p-2 rounded-lg">
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option {{ Request('month', $datesNow->month) == $i ? 'selected' : '' }} value="{{ $i }}">
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
                    </form>

                    <div class="p-4 space-y-10">

                        {{-- INIT TOTAL --}}
                        @php
                            $totalBuy = array_sum($supplierReport['buy']);
                            $totalReturn = array_sum($supplierReport['returnSupplier']);

                            $totalCashInSupplier = array_sum($supplierReport['cashInSupplier']);
                            $totalCashOutSupplier = array_sum($supplierReport['cashOutSupplier']);

                            $totalCashInAccount = array_sum($supplierReport['cashInAccount']);
                            $totalCashOutAccount = array_sum($supplierReport['cashOutAccount']);

                            $totalCashIn = $totalCashInSupplier + $totalCashInAccount;
                            $totalCashOut = $totalCashOutSupplier + $totalCashOutAccount;

                            $nettSupplierBuy = $totalBuy - $totalReturn;
                            $nettCash = $totalCashOut - $totalCashIn;
                        @endphp

                        {{-- ========================= --}}
                        {{-- 1. SUPPLIER BUY --}}
                        {{-- ========================= --}}
                        <div>
                            <h3 class="text-lg font-bold mb-3">Supplier Buy</h3>

                            <table class="w-full text-sm">
                                <thead class="bg-gray-100 text-xs uppercase">
                                    <tr>
                                        <th class="px-4 py-3 text-left">Supplier</th>
                                        <th class="px-4 py-3 text-right">Buy</th>
                                        <th class="px-4 py-3 text-right">Return</th>
                                        <th class="px-4 py-3 text-right">Nett</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($supplierList as $item)
                                        @php
                                            $buy = $supplierReport['buy'][$item->id] ?? 0;
                                            $ret = $supplierReport['returnSupplier'][$item->id] ?? 0;
                                            $nett = $buy - $ret;
                                        @endphp

                                        <tr class="border-b">
                                            <td class="px-4 py-3 text-left">
                                                <a href="{{ route('supplier.transaction', $item->id) }}" class="text-blue-600 hover:underline">
                                                    {{ $item->name }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-3 text-right tabular-nums">{{ Number::format($buy) }}</td>
                                            <td class="px-4 py-3 text-right tabular-nums">{{ Number::format($ret) }}</td>
                                            <td class="px-4 py-3 text-right font-bold tabular-nums">{{ Number::format($nett) }}</td>
                                        </tr>
                                    @endforeach

                                    <tr class="bg-gray-100 font-semibold">
                                        <td class="px-4 py-3 text-left">Total</td>
                                        <td class="text-right">{{ Number::format($totalBuy) }}</td>
                                        <td class="text-right">{{ Number::format($totalReturn) }}</td>
                                        <td class="text-right">{{ Number::format($nettSupplierBuy) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- ========================= --}}
                        {{-- 2. SUPPLIER CASH --}}
                        {{-- ========================= --}}
                        <div>
                            <h3 class="text-lg font-bold mb-3">Supplier Cash</h3>

                            <table class="w-full text-sm">
                                <thead class="bg-gray-100 text-xs uppercase">
                                    <tr>
                                        <th class="px-4 py-3 text-left">Supplier</th>
                                        <th class="px-4 py-3 text-right">Cash In</th>
                                        <th class="px-4 py-3 text-right">Cash Out</th>
                                        <th class="px-4 py-3 text-right">Nett</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($supplierList as $item)
                                        @php
                                            $in = $supplierReport['cashInSupplier'][$item->id] ?? 0;
                                            $out = $supplierReport['cashOutSupplier'][$item->id] ?? 0;
                                            $nett = $out - $in;
                                        @endphp

                                        <tr class="border-b">
                                            <td class="px-4 py-3 text-left">
                                                <a href="{{ route('supplier.transaction', $item->id) }}" class="text-blue-600 hover:underline">
                                                    {{ $item->name }}
                                                </a></td>
                                            <td class="px-4 py-3 text-right text-blue-600 tabular-nums">{{ Number::format($in) }}</td>
                                            <td class="px-4 py-3 text-right text-red-600 tabular-nums">{{ Number::format($out) }}</td>
                                            <td class="px-4 py-3 text-right font-bold tabular-nums">{{ Number::format($nett) }}</td>
                                        </tr>
                                    @endforeach

                                    <tr class="bg-gray-100 font-semibold">
                                        <td class="text-left px-4 py-3">Total</td>
                                        <td class="text-right">{{ Number::format($totalCashInSupplier) }}</td>
                                        <td class="text-right">{{ Number::format($totalCashOutSupplier) }}</td>
                                        <td class="text-right">{{ Number::format($totalCashOutSupplier - $totalCashInSupplier) }}</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>

                        {{-- ========================= --}}
                        {{-- 3. JOURNAL CASH --}}
                        {{-- ========================= --}}
                        <div>
                            <h3 class="text-lg font-bold mb-3">Journal Cash (Account)</h3>

                            <table class="w-full text-sm">
                                <thead class="bg-gray-100 text-xs uppercase">
                                    <tr>
                                        <th class="px-4 py-3 text-left">Account</th>
                                        <th class="px-4 py-3 text-right">Cash In</th>
                                        <th class="px-4 py-3 text-right">Cash Out</th>
                                        <th class="px-4 py-3 text-right">Nett</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($accountList as $acc)
                                        @php
                                            $in = $supplierReport['cashInAccount'][$acc->id] ?? 0;
                                            $out = $supplierReport['cashOutAccount'][$acc->id] ?? 0;
                                            $nett = $out - $in;
                                        @endphp

                                        <tr class="border-b">
                                            <td class="px-4 py-3 text-left">
                                                <a href="{{ route('operation.account', $acc->id) }}" class="text-blue-600 hover:underline">
                                                    {{ $acc->name }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-3 text-right text-blue-600 tabular-nums">{{ Number::format($in) }}</td>
                                            <td class="px-4 py-3 text-right text-red-600 tabular-nums">{{ Number::format($out) }}</td>
                                            <td class="px-4 py-3 text-right font-bold tabular-nums">{{ Number::format($nett) }}</td>
                                        </tr>
                                    @endforeach

                                    <tr class="bg-gray-100 font-semibold">
                                        <td class="text-left px-4 py-3">Total</td>
                                        <td class="text-right">{{ Number::format($totalCashInAccount) }}</td>
                                        <td class="text-right">{{ Number::format($totalCashOutAccount) }}</td>
                                        <td class="text-right">{{ Number::format($totalCashOutAccount - $totalCashInAccount) }}</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>

                        {{-- ========================= --}}
                        {{-- GLOBAL SUMMARY --}}
                        {{-- ========================= --}}
                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">

                            <div class="p-4 bg-white shadow rounded">
                                <p class="text-sm">Total Buy</p>
                                <p class="font-bold text-right">{{ Number::format($totalBuy) }}</p>
                            </div>

                            <div class="p-4 bg-white shadow rounded">
                                <p class="text-sm">Total Return</p>
                                <p class="font-bold text-right">{{ Number::format($totalReturn) }}</p>
                            </div>

                            <div class="p-4 bg-white shadow rounded">
                                <p class="text-sm">Nett Supplier Buy</p>
                                <p class="font-bold text-right text-red-500">{{ Number::format($nettSupplierBuy) }}</p>
                            </div>

                            <div class="p-4 bg-white shadow rounded">
                                <p class="text-sm">Total Cash In</p>
                                <p class="font-bold text-right text-blue-600">{{ Number::format($totalCashIn) }}</p>
                            </div>

                            <div class="p-4 bg-white shadow rounded">
                                <p class="text-sm">Total Cash Out</p>
                                <p class="font-bold text-right text-red-600">{{ Number::format($totalCashOut) }}</p>
                            </div>

                            <div class="p-4 bg-white shadow rounded">
                                <p class="text-sm">Nett Cash</p>
                                <p class="font-bold text-right text-green-600">{{ Number::format($nettCash) }}</p>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </section>
    </div>

</x-layouts.layout>