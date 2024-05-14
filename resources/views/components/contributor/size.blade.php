<div>
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
            <tr>
               
                <th scope="col" class="px-4 py-3">Size</th>
                <th scope="col" class="px-4 py-3">Quantity</th>
                <th scope="col" class="px-4 py-3">Value</th>
                
                
            </tr>
        </thead>
        <tbody>
            @forelse ( $bySize as $data)
                
     

            <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
              
       
                <td class="px-4 py-3">
                    @if ($data->size == 0)
                        Accessories
                    @else

                        {{\App\Models\Tag::loadSizes()[$data->size]}}
                        
                    @endif
                   
                </td>
               
                <td class="px-4 py-3">{{number_format($data->total_quantity,2)}}</td>
                <td class="px-4 py-3">{{number_format($data->total_value,2)}}</td>
              
               
               
               
                
                
            </tr>
                
            @empty

            <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
               
                <td class="px-4 py-3 text-center" colspan="9">Data Empty</td>
               
                
                
            </tr>
                
            @endforelse 
            
          
        
        </tbody>
    </table>
</div>