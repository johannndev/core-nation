<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">
        <p class="text-2xl font-bold">Data {{ $month }} - {{ $year }}</p>
    </div>

    <div class="mb-8">
        <section class="bg-gray-50 dark:bg-gray-900 ">
            <div class="mx-auto">
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">

                    {{-- ================= FILTER ================= --}}
                    <form action="{{ route('filter.get', ['action' => 'report.cash']) }}" method="post">
                        @csrf

                        <div class="flex flex-col md:flex-row items-end justify-between p-4">
                            <div class="w-full md:w-4/6">
                                <div class="grid gap-4 md:grid-cols-5 items-end">

                                    {{-- MONTH --}}
                                    <div>
                                        <label class="block mb-2 text-sm font-medium">Month</label>
                                        <select name="month" class="w-full p-2 rounded-lg">
                                            <option value="">-- All Month --</option>
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option {{ Request('month') == $i ? 'selected' : '' }} value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>

                                    {{-- YEAR --}}
                                    <div>
                                        <label class="block mb-2 text-sm font-medium">Year</label>
                                        <select name="year" class="w-full p-2 rounded-lg">
                                            @foreach ($yearList as $y)
                                                <option {{ Request('year', $datesNow->year) == $y ? 'selected' : '' }} value="{{ $y }}">{{ $y }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>

                            <div class="mt-4 flex gap-2">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                                    Filter
                                </button>

                                <a href="{{ route('report.cash') }}" class="px-4 py-2 border rounded-lg">
                                    Clear
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="space-y-10">

                        {{-- ================= CUSTOMER ================= --}}
                        <div>
                            <h3 class="text-lg font-bold p-4">Customer</h3>

                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-3 text-left">Customer</th>
                                            <th class="px-4 py-3 text-right">Cash In</th>
                                            <th class="px-4 py-3 text-right">Cash Out</th>
                                            <th class="px-4 py-3 text-right">Sell</th>
                                            <th class="px-4 py-3 text-right">Return</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($customerList as $item)
                                            <tr class="border-b">
                                                <td class="px-4 py-3">
                                                    <a href="{{ route('customer.transaction', $item->id) }}" class="text-blue-600">
                                                        {{ $item->name }}
                                                    </a>
                                                </td>
                                                <td class="px-4 py-3 text-right">{{ Number::format($customerReport['cashIn'][$item->id] ?? 0) }}</td>
                                                <td class="px-4 py-3 text-right">{{ Number::format($customerReport['cashOut'][$item->id] ?? 0) }}</td>
                                                <td class="px-4 py-3 text-right">{{ Number::format($customerReport['sell'][$item->id] ?? 0) }}</td>
                                                <td class="px-4 py-3 text-right">{{ Number::format($customerReport['return'][$item->id] ?? 0) }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="text-center py-4">Data Empty</td></tr>
                                        @endforelse

                                        <tr class="font-semibold bg-gray-50">
                                            <td class="px-4 py-3">Total</td>
                                            <td class="px-4 py-3 text-right">{{ Number::format(array_sum($customerReport['cashIn'])) }}</td>
                                            <td class="px-4 py-3 text-right">{{ Number::format(array_sum($customerReport['cashOut'])) }}</td>
                                            <td class="px-4 py-3 text-right">{{ Number::format(array_sum($customerReport['sell'])) }}</td>
                                            <td class="px-4 py-3 text-right">{{ Number::format(array_sum($customerReport['return'])) }}</td>
                                        </tr>

                                        <tr class="font-bold">
                                            <td class="px-4 py-3">Nett</td>
                                            <td class="px-4 py-3 text-right">{{ Number::format($customerReport['nettCash']) }}</td>
                                            <td></td>
                                            <td class="px-4 py-3 text-right">{{ Number::format($customerReport['nettSell']) }}</td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- ================= RESELLER ================= --}}
                        <div>
                            <h3 class="text-lg font-bold p-4">Reseller</h3>

                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-3 text-left">Reseller</th>
                                            <th class="px-4 py-3 text-right">Cash In</th>
                                            <th class="px-4 py-3 text-right">Cash Out</th>
                                            <th class="px-4 py-3 text-right">Sell</th>
                                            <th class="px-4 py-3 text-right">Return</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($resellerList as $item)
                                            <tr class="border-b">
                                                <td class="px-4 py-3">
                                                    <a href="{{ route('reseller.transaction', $item->id) }}" class="text-blue-600">
                                                        {{ $item->name }}
                                                    </a>
                                                </td>
                                                <td class="px-4 py-3 text-right">{{ Number::format($resellerReport['cashIn'][$item->id] ?? 0) }}</td>
                                                <td class="px-4 py-3 text-right">{{ Number::format($resellerReport['cashOut'][$item->id] ?? 0) }}</td>
                                                <td class="px-4 py-3 text-right">{{ Number::format($resellerReport['sell'][$item->id] ?? 0) }}</td>
                                                <td class="px-4 py-3 text-right">{{ Number::format($resellerReport['return'][$item->id] ?? 0) }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="text-center py-4">Data Empty</td></tr>
                                        @endforelse

                                        <tr class="font-semibold bg-gray-50">
                                            <td class="px-4 py-3">Total</td>
                                            <td class="px-4 py-3 text-right">{{ Number::format(array_sum($resellerReport['cashIn'])) }}</td>
                                            <td class="px-4 py-3 text-right">{{ Number::format(array_sum($resellerReport['cashOut'])) }}</td>
                                            <td class="px-4 py-3 text-right">{{ Number::format(array_sum($resellerReport['sell'])) }}</td>
                                            <td class="px-4 py-3 text-right">{{ Number::format(array_sum($resellerReport['return'])) }}</td>
                                        </tr>

                                        <tr class="font-bold">
                                            <td class="px-4 py-3">Nett</td>
                                            <td class="px-4 py-3 text-right">{{ Number::format($resellerReport['nettCash']) }}</td>
                                            <td></td>
                                            <td class="px-4 py-3 text-right">{{ Number::format($resellerReport['nettSell']) }}</td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- ================= BANK ================= --}}
                        <div>
                            <h3 class="text-lg font-bold p-4">Bank</h3>

                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-3 text-left">Bank</th>
                                            <th class="px-4 py-3 text-right">Cash In</th>
                                            <th class="px-4 py-3 text-right">Cash Out</th>
                                            <th class="px-4 py-3 text-right">Sell</th>
                                            <th class="px-4 py-3 text-right">Return</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($bankList as $item)
                                            <tr class="border-b">
                                                <td class="px-4 py-3">{{ $item->name }}</td>
                                                <td class="px-4 py-3 text-right">{{ Number::format($bankReport['cashIn'][$item->id] ?? 0) }}</td>
                                                <td class="px-4 py-3 text-right">{{ Number::format($bankReport['cashOut'][$item->id] ?? 0) }}</td>
                                                <td class="px-4 py-3 text-right">{{ Number::format($bankReport['sell'][$item->id] ?? 0) }}</td>
                                                <td class="px-4 py-3 text-right">{{ Number::format($bankReport['return'][$item->id] ?? 0) }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="text-center py-4">Data Empty</td></tr>
                                        @endforelse

                                        <tr class="font-semibold bg-gray-50">
                                            <td class="px-4 py-3">Total</td>
                                            <td class="px-4 py-3 text-right">{{ Number::format(array_sum($bankReport['cashIn'])) }}</td>
                                            <td class="px-4 py-3 text-right">{{ Number::format(array_sum($bankReport['cashOut'])) }}</td>
                                            <td class="px-4 py-3 text-right">{{ Number::format(array_sum($bankReport['sell'])) }}</td>
                                            <td class="px-4 py-3 text-right">{{ Number::format(array_sum($bankReport['return'])) }}</td>
                                        </tr>

                                        <tr class="font-bold">
                                            <td class="px-4 py-3">Nett</td>
                                            <td class="px-4 py-3 text-right">{{ Number::format($bankReport['nettCash']) }}</td>
                                            <td></td>
                                            <td class="px-4 py-3 text-right">{{ Number::format($bankReport['nettSell']) }}</td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- ================= GLOBAL SUMMARY ================= --}}
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

                            <div class="p-4 bg-white shadow rounded">
                                <p>Cash In</p>
                                <b>
                                    {{ Number::format(
                                        array_sum($customerReport['cashIn']) +
                                        array_sum($resellerReport['cashIn']) 
                                        
                                    ) }}
                                </b>
                            </div>

                            <div class="p-4 bg-white shadow rounded">
                                <p>Cash Out</p>
                                <b>
                                    {{ Number::format(
                                        array_sum($customerReport['cashOut']) +
                                        array_sum($resellerReport['cashOut']) 
                                       
                                    ) }}
                                </b>
                            </div>

                            <div class="p-4 bg-white shadow rounded">
                                <p>Sell</p>
                                <b>
                                    {{ Number::format(
                                        array_sum($customerReport['sell']) +
                                        array_sum($resellerReport['sell']) 
                                       
                                    ) }}
                                </b>
                            </div>

                            <div class="p-4 bg-white shadow rounded">
                                <p>Return</p>
                                <b>
                                    {{ Number::format(
                                        array_sum($customerReport['return']) +
                                        array_sum($resellerReport['return']) 
                                       
                                    ) }}
                                </b>
                            </div>

                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-white shadow rounded">
                                <p>Nett Cash</p>
                                <b>
                                    {{ Number::format(
                                        $customerReport['nettCash'] +
                                        $resellerReport['nettCash'] 
                                        
                                    ) }}
                                </b>
                            </div>

                            <div class="p-4 bg-white shadow rounded">
                                <p>Nett Sell</p>
                                <b>
                                    {{ Number::format(
                                        $customerReport['nettSell'] +
                                        $resellerReport['nettSell'] 
                                        
                                    ) }}
                                </b>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </section>
    </div>

</x-layouts.layout>