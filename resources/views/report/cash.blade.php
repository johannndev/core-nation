<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Data {{ $month }} - {{ $year }}</p>


    </div>

    <div class="mb-8">


        <section class="bg-gray-50 dark:bg-gray-900 ">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <form action="{{ route('filter.get', ['action' => 'report.cash']) }}" method="post">
                        @csrf

                        <div class="flex flex-col md:flex-row items-end justify-between p-4">


                            <div class="w-full md:w-4/6">

                                <div class="grid gap-4 md:grid-cols-5 items-end">
                                    <div>
                                        <label for="month"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Month</label>
                                        <select id="month" name="month"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">


                                            @for ($i = 1; $i <= 12; $i++)
                                                <option {{ Request('month', $datesNow->month) == $i ? 'selected' : '' }}
                                                    value="{{ $i }}">{{ $i }}</option>
                                            @endfor

                                        </select>
                                    </div>

                                    <div>
                                        <label for="year"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Year</label>
                                        <select id="year" name="year"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">


                                            @foreach ($yearList as $y)
                                                <option {{ Request('year', $datesNow->year) == $y ? 'selected' : '' }}
                                                    value="{{ $y }}">{{ $y }}</option>
                                            @endforeach



                                        </select>
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

                                <a href="{{ route('report.cash') }}"
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

                    <div class="space-y-10">

                        {{-- ========================= --}}
                        {{-- CUSTOMER --}}
                        {{-- ========================= --}}
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Customer</h3>

                            {{-- SUMMARY --}}
                            <div class="mb-4 text-sm text-gray-700 dark:text-gray-300">
                                <span class="mr-6">Total Cash:
                                    <b>{{ Number::format($customerReport['nettCash']) }}</b></span>
                                <span>Total Sell: <b>{{ Number::format($customerReport['nettSell']) }}</b></span>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead
                                        class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th class="px-4 py-3">Customer</th>
                                            <th class="px-4 py-3">Cash In</th>
                                            <th class="px-4 py-3">Cash Out</th>
                                            <th class="px-4 py-3">Sell</th>
                                            <th class="px-4 py-3">Return</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($customerList as $item)
                                            <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                                                <th class="px-4 py-3 whitespace-nowrap">
                                                    <span
                                                        class="font-medium text-blue-600 dark:text-blue-500">{{ $item->name }}</span>
                                                </th>
                                                <td class="px-4 py-3">
                                                    {{ Number::format($customerReport['cashIn'][$item->id] ?? 0) }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    {{ Number::format($customerReport['cashOut'][$item->id] ?? 0) }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    {{ Number::format($customerReport['sell'][$item->id] ?? 0) }}</td>
                                                <td class="px-4 py-3">
                                                    {{ Number::format($customerReport['return'][$item->id] ?? 0) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">Data Empty</td>
                                            </tr>
                                        @endforelse

                                        {{-- TOTAL --}}
                                        <tr
                                            class="border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-700 font-semibold">
                                            <th class="px-4 py-3">Total</th>
                                            <th class="px-4 py-3">
                                                {{ Number::format(array_sum($customerReport['cashIn'])) }}</th>
                                            <th class="px-4 py-3">
                                                {{ Number::format(array_sum($customerReport['cashOut'])) }}</th>
                                            <th class="px-4 py-3">
                                                {{ Number::format(array_sum($customerReport['sell'])) }}</th>
                                            <th class="px-4 py-3">
                                                {{ Number::format(array_sum($customerReport['return'])) }}</th>
                                        </tr>

                                        {{-- NETT --}}
                                        <tr class="border-b dark:border-gray-700 font-bold">
                                            <th class="px-4 py-3">Nett</th>
                                            <th class="px-4 py-3">{{ Number::format($customerReport['nettCash']) }}
                                            </th>
                                            <th></th>
                                            <th class="px-4 py-3">{{ Number::format($customerReport['nettSell']) }}
                                            </th>
                                            <th></th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                        {{-- ========================= --}}
                        {{-- RESELLER --}}
                        {{-- ========================= --}}
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Reseller</h3>

                            {{-- SUMMARY --}}
                            <div class="mb-4 text-sm text-gray-700 dark:text-gray-300">
                                <span class="mr-6">Total Cash:
                                    <b>{{ Number::format($resellerReport['nettCash']) }}</b></span>
                                <span>Total Sell: <b>{{ Number::format($resellerReport['nettSell']) }}</b></span>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead
                                        class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th class="px-4 py-3">Reseller</th>
                                            <th class="px-4 py-3">Cash In</th>
                                            <th class="px-4 py-3">Cash Out</th>
                                            <th class="px-4 py-3">Sell</th>
                                            <th class="px-4 py-3">Return</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($resellerList as $item)
                                            <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                                                <th class="px-4 py-3 whitespace-nowrap">
                                                    <span
                                                        class="font-medium text-blue-600 dark:text-blue-500">{{ $item->name }}</span>
                                                </th>
                                                <td class="px-4 py-3">
                                                    {{ Number::format($resellerReport['cashIn'][$item->id] ?? 0) }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    {{ Number::format($resellerReport['cashOut'][$item->id] ?? 0) }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    {{ Number::format($resellerReport['sell'][$item->id] ?? 0) }}</td>
                                                <td class="px-4 py-3">
                                                    {{ Number::format($resellerReport['return'][$item->id] ?? 0) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">Data Empty</td>
                                            </tr>
                                        @endforelse

                                        {{-- TOTAL --}}
                                        <tr
                                            class="border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-700 font-semibold">
                                            <th class="px-4 py-3">Total</th>
                                            <th class="px-4 py-3">
                                                {{ Number::format(array_sum($resellerReport['cashIn'])) }}</th>
                                            <th class="px-4 py-3">
                                                {{ Number::format(array_sum($resellerReport['cashOut'])) }}</th>
                                            <th class="px-4 py-3">
                                                {{ Number::format(array_sum($resellerReport['sell'])) }}</th>
                                            <th class="px-4 py-3">
                                                {{ Number::format(array_sum($resellerReport['return'])) }}</th>
                                        </tr>

                                        {{-- NETT --}}
                                        <tr class="border-b dark:border-gray-700 font-bold">
                                            <th class="px-4 py-3">Nett</th>
                                            <th class="px-4 py-3">{{ Number::format($resellerReport['nettCash']) }}
                                            </th>
                                            <th></th>
                                            <th class="px-4 py-3">{{ Number::format($resellerReport['nettSell']) }}
                                            </th>
                                            <th></th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>



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
