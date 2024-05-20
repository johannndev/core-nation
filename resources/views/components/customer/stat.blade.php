<div>
  
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-4 py-3">Cash In</th>
                    <th scope="col" class="px-4 py-3">Cash Out</th>

               
      
                    
                </tr>
            </thead>
            <tbody>
            
                    
              

                <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                   
              
                    <td class="px-4 py-3">{{ number_format($dataCashIn->total_cash_in) }}</td>
                    <td class="px-4 py-3">{{ number_format($dataCashOut->total_cash_out) }}</td>
                   
                   
                  
                  
                    
                    
                    
                </tr>
                    
           
              
            
            </tbody>
        </table>
    </div>

</div>