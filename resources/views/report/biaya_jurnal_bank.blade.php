<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">
        <p class="text-2xl font-bold">Data {{ $month }} - {{ $year }}</p>
    </div>

    <div class="mb-8">

        <section class="bg-gray-50 dark:bg-gray-900 ">
            <div class="mx-auto  ">
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    
                    <form action="{{ route('filter.get', ['action' => 'report.laporanBiaya']) }}" method="post">
                        @csrf
                        <div class="flex flex-col md:flex-row items-end justify-between p-4">
                            <div class="w-full md:w-4/6">
                                <div class="grid gap-4 md:grid-cols-5 items-end">
                                    <div>
                                        <label for="month"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Month</label>
                                        <select id="month" name="month"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="">-- All Month --</option>
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option {{ Request('month') == $i ? 'selected' : '' }} value="{{ $i }}">
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div>
                                        <label for="year"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Year</label>
                                        <select id="year" name="year"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            @foreach ($yearList as $y)
                                                <option {{ Request('year', $datesNow->year) == $y ? 'selected' : '' }} value="{{ $y }}">
                                                    {{ $y }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                                <button type="submit"
                                    class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                    Filter
                                </button>

                                <a href="{{ route('report.laporanBiaya') }}"
                                    class="flex items-center justify-center py-2 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                    Clear
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="space-y-10">

                        {{-- ========================= --}}
                        {{-- TABEL JURNAL (ACCOUNT) --}}
                        {{-- ========================= --}}
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3 p-4">Biaya Jurnal (Account)</h3>

                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th class="px-4 py-3">Nama Jurnal</th>
                                            <th class="px-4 py-3">Cash In (Dari Bank)</th>
                                            <th class="px-4 py-3">Cash Out (Ke Bank)</th>
                                            <th class="px-4 py-3">Nett</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($accountList as $item)
                                            @php
                                                $in = $accountReport['cashIn'][$item->id] ?? 0;
                                                $out = $accountReport['cashOut'][$item->id] ?? 0;
                                                $nett = $in + $out; // FIX
                                            @endphp
                                            <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                                                <th class="px-4 py-3 whitespace-nowrap">
                                                    <a href="{{ route('customer.transaction', $item->id) }}"
                                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                                        {{ $item->name }}
                                                    </a>
                                                </th>
                                                <td class="px-4 py-3 text-green-600 font-medium">
                                                    {{ Number::format($in) }}
                                                </td>
                                                <td class="px-4 py-3 text-red-600 font-medium">
                                                    {{ Number::format($out) }}
                                                </td>
                                                <td class="px-4 py-3 font-bold {{ $nett < 0 ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">
                                                    {{ Number::format($nett) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center py-4">Data Empty</td></tr>
                                        @endforelse

                                        @php
                                            $totalAccountIn = array_sum($accountReport['cashIn']);
                                            $totalAccountOut = array_sum($accountReport['cashOut']);
                                            $totalAccountNett = $totalAccountIn + $totalAccountOut; // FIX
                                        @endphp

                                        <tr class="border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-700 font-bold">
                                            <th class="px-4 py-3 text-gray-900 dark:text-white">Total Jurnal</th>
                                            <th class="px-4 py-3 text-green-600">{{ Number::format($totalAccountIn) }}</th>
                                            <th class="px-4 py-3 text-red-600">{{ Number::format($totalAccountOut) }}</th>
                                            <th class="px-4 py-3 text-gray-900 dark:text-white">{{ Number::format($totalAccountNett) }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                        {{-- ========================= --}}
                        {{-- TABEL BANK --}}
                        {{-- ========================= --}}
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3 p-4">Biaya Bank</h3>

                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th class="px-4 py-3">Nama Bank</th>
                                            <th class="px-4 py-3">Cash In (Dari Jurnal)</th>
                                            <th class="px-4 py-3">Cash Out (Ke Jurnal)</th>
                                            <th class="px-4 py-3">Nett</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($bankList as $item)
                                            @php
                                                $in = $bankReport['cashIn'][$item->id] ?? 0;
                                                $out = $bankReport['cashOut'][$item->id] ?? 0;
                                                $nett = $in + $out; // FIX
                                            @endphp
                                            <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                                                <th class="px-4 py-3 whitespace-nowrap">
                                                    <a href="{{ route('customer.transaction', $item->id) }}"
                                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                                        {{ $item->name }}
                                                    </a>
                                                </th>
                                                <td class="px-4 py-3 text-green-600 font-medium">
                                                    {{ Number::format($in) }}
                                                </td>
                                                <td class="px-4 py-3 text-red-600 font-medium">
                                                    {{ Number::format($out) }}
                                                </td>
                                                <td class="px-4 py-3 font-bold {{ $nett < 0 ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">
                                                    {{ Number::format($nett) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center py-4">Data Empty</td></tr>
                                        @endforelse

                                        @php
                                            $totalBankIn = array_sum($bankReport['cashIn']);
                                            $totalBankOut = array_sum($bankReport['cashOut']);
                                            $totalBankNett = $totalBankIn + $totalBankOut; // FIX
                                        @endphp

                                        <tr class="border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-700 font-bold">
                                            <th class="px-4 py-3 text-gray-900 dark:text-white">Total Bank</th>
                                            <th class="px-4 py-3 text-green-600">{{ Number::format($totalBankIn) }}</th>
                                            <th class="px-4 py-3 text-red-600">{{ Number::format($totalBankOut) }}</th>
                                            <th class="px-4 py-3 text-gray-900 dark:text-white">{{ Number::format($totalBankNett) }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- ========================= --}}
                        {{-- GLOBAL SUMMARY (ACCOUNT ONLY) --}}
                        {{-- ========================= --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6 p-4">

                            @php
                                // BANK TIDAK DIHITUNG DI SINI
                                $grandIn = $totalAccountIn;
                                $grandOut = $totalAccountOut;
                                $grandNett = $grandIn + $grandOut;
                            @endphp

                            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 border-l-4 border-green-500">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Total Mutasi Masuk (Cash In)</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">
                                    {{ Number::format($grandIn) }}
                                </p>
                            </div>

                            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 border-l-4 border-red-500">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Total Mutasi Keluar (Cash Out)</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">
                                    {{ Number::format($grandOut) }}
                                </p>
                            </div>

                            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 border-l-4 border-blue-500">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Total Selisih (Nett)</p>
                                <p class="text-xl font-bold text-blue-600 dark:text-blue-400">
                                    {{ Number::format($grandNett) }}
                                </p>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </section>

    </div>

</x-layouts.layout>