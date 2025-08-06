<div>
  
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-4 py-3">Type</th>
                    <th scope="col" class="px-4 py-3">Cash In</th>
                    <th scope="col" class="px-4 py-3">Cash Out</th>
                    <th scope="col" class="px-4 py-3">Sell</th>
                    <th scope="col" class="px-4 py-3">Return</th>
                </tr>
            </thead>
            <tbody>
                 <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                    <td class="px-4 py-3">Customer</td>
                    <td class="px-4 py-3">{{ number_format($dataStat['cash_in']['customer']) }}</td>
                    <td class="px-4 py-3">{{ number_format($dataStat['cash_out']['customer']) }}</td>
                    <td class="px-4 py-3">-</td>
                    <td class="px-4 py-3">-</td>
                  
                </tr>
                <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                    <td class="px-4 py-3">Reseller</td>
                    <td class="px-4 py-3">{{ number_format($dataStat['cash_in']['reseller']) }}</td>
                    <td class="px-4 py-3">{{ number_format($dataStat['cash_out']['reseller']) }}</td>
                    <td class="px-4 py-3">-</td>
                    <td class="px-4 py-3">-</td>
                </tr>

                <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">{{ number_format($dataStat['cash_in']['total']) }}</th>
                    <th class="px-4 py-3">{{ number_format($dataStat['cash_out']['total']) }}</th>
                    <th class="px-4 py-3">{{ number_format($dataStat['sell']['total']) }}</th>
                    <th class="px-4 py-3">{{ number_format($dataStat['return']['total']) }}</th>
                </tr>
            </tbody>
        </table>
    </div>

</div>
