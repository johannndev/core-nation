<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">
        <p class="text-2xl font-bold">Data {{ $month }} - {{ $year }}</p>
    </div>

    <div class="mb-8">

        <section class="bg-gray-50 dark:bg-gray-900">
            <div class="mx-auto">
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">

                    {{-- FILTER --}}
                    <form action="{{ route('filter.get', ['action' => 'report.laporanBiaya']) }}" method="post">
                        @csrf
                        <div class="flex flex-col md:flex-row items-end justify-between p-4">

                            <div class="w-full md:w-4/6">
                                <div class="grid gap-4 md:grid-cols-5 items-end">

                                    <div>
                                        <label class="block mb-2 text-sm">Month</label>
                                        <select name="month" class="w-full p-2 rounded">
                                            <option value="">All</option>
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option {{ Request('month') == $i ? 'selected' : '' }} value="{{ $i }}">
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block mb-2 text-sm">Year</label>
                                        <select name="year" class="w-full p-2 rounded">
                                            @foreach ($yearList as $y)
                                                <option {{ Request('year', $datesNow->year) == $y ? 'selected' : '' }} value="{{ $y }}">
                                                    {{ $y }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>

                            <div>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                                    Filter
                                </button>
                            </div>

                        </div>
                    </form>

                    <div class="space-y-10">

                        {{-- ================= ACCOUNT ================= --}}
                        <div>
                            <h3 class="font-bold p-4">Biaya Jurnal (Account)</h3>

                            <table class="w-full text-sm">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Cash In</th>
                                        <th>Cash Out</th>
                                        <th>Nett</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($accountList as $item)
                                        @php
                                            $in = $accountReport['cashIn'][$item->id] ?? 0;
                                            $out = $accountReport['cashOut'][$item->id] ?? 0;
                                            $nett = $in + $out; // FIX
                                        @endphp

                                        <tr>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ Number::format($in) }}</td>
                                            <td>{{ Number::format($out) }}</td>
                                            <td>{{ Number::format($nett) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4">Empty</td></tr>
                                    @endforelse

                                    @php
                                        $totalAccountIn = array_sum($accountReport['cashIn']);
                                        $totalAccountOut = array_sum($accountReport['cashOut']);
                                        $totalAccountNett = $totalAccountIn + $totalAccountOut; // FIX
                                    @endphp

                                    <tr class="font-bold bg-gray-100">
                                        <td>Total</td>
                                        <td>{{ Number::format($totalAccountIn) }}</td>
                                        <td>{{ Number::format($totalAccountOut) }}</td>
                                        <td>{{ Number::format($totalAccountNett) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- ================= BANK ================= --}}
                        <div>
                            <h3 class="font-bold p-4">Biaya Bank</h3>

                            <table class="w-full text-sm">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Cash In</th>
                                        <th>Cash Out</th>
                                        <th>Nett</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($bankList as $item)
                                        @php
                                            $in = $bankReport['cashIn'][$item->id] ?? 0;
                                            $out = $bankReport['cashOut'][$item->id] ?? 0;
                                            $nett = $in + $out; // FIX
                                        @endphp

                                        <tr>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ Number::format($in) }}</td>
                                            <td>{{ Number::format($out) }}</td>
                                            <td>{{ Number::format($nett) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4">Empty</td></tr>
                                    @endforelse

                                    @php
                                        $totalBankIn = array_sum($bankReport['cashIn']);
                                        $totalBankOut = array_sum($bankReport['cashOut']);
                                        $totalBankNett = $totalBankIn + $totalBankOut; // FIX
                                    @endphp

                                    <tr class="font-bold bg-gray-100">
                                        <td>Total</td>
                                        <td>{{ Number::format($totalBankIn) }}</td>
                                        <td>{{ Number::format($totalBankOut) }}</td>
                                        <td>{{ Number::format($totalBankNett) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- ================= GLOBAL (ACCOUNT ONLY) ================= --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4">

                            @php
                                // ❗ BANK TIDAK DIPAKAI DI SINI
                                $grandIn = $totalAccountIn;
                                $grandOut = $totalAccountOut;
                                $grandNett = $grandIn + $grandOut;
                            @endphp

                            <div class="p-4 bg-white shadow rounded border-l-4 border-green-500">
                                <p>Total Cash In (Account)</p>
                                <p class="text-xl font-bold">{{ Number::format($grandIn) }}</p>
                            </div>

                            <div class="p-4 bg-white shadow rounded border-l-4 border-red-500">
                                <p>Total Cash Out (Account)</p>
                                <p class="text-xl font-bold">{{ Number::format($grandOut) }}</p>
                            </div>

                            <div class="p-4 bg-white shadow rounded border-l-4 border-blue-500">
                                <p>Nett (Account Only)</p>
                                <p class="text-xl font-bold">{{ Number::format($grandNett) }}</p>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </section>

    </div>

</x-layouts.layout>