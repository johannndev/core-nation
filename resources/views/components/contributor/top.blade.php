<div>
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-4 py-3">Name</th>
                <th scope="col" class="px-4 py-3">Brand</th>
                <th scope="col" class="px-4 py-3">Genre</th>
                <th scope="col" class="px-4 py-3">Size</th>
                <th scope="col" class="px-4 py-3">Quantity</th>
                <th scope="col" class="px-4 py-3">Value</th>
                
                
            </tr>
        </thead>
        <tbody>
            @forelse ( $top50 as $data)
                
     

            <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                <th scope="row" class="px-4 py-3  whitespace-nowrap ">
                    
                    <a href="{{route('item.detail',$data->item_id)}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$data->name}} </a>

                </th>
       
                <td class="px-4 py-3">{{\App\Models\Item::$brands[$data->brand]}}</td>
                <td class="px-4 py-3">
                    @if ($data->genre == 0)
                        Accessories
                    @else

                        {{\App\Models\Tag::loadGenres()[$data->genre]}}
                        
                    @endif
                </td>
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