<div>
  
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-4 py-3">Cash In</th>
                    <th scope="col" class="px-4 py-3">Cash Out</th>
                    <th scope="col" class="px-4 py-3">Sell</th>
                    <th scope="col" class="px-4 py-3">Return</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                    <td class="px-4 py-3">{{ number_format($dataCashIn->total_cash_in) }}</td>
                    <td class="px-4 py-3">{{ number_format($dataCashOut->total_cash_out) }}</td>
                    <td class="px-4 py-3">{{ number_format($dataSell->total_sell) }}</td>
                    <td class="px-4 py-3">{{ number_format($dataReturn->total_return) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
