<div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    
                    <th scope="col" class="px-4 py-3">Name</th>
                    <th scope="col" class="px-4 py-3">Balance</th>
                    <th scope="col" class="px-4 py-3">Actions</th>
                    
                </tr>
            </thead>
            <tbody>
                @forelse ( $dataList as $item)
                    
              

                <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                   
                    <th scope="row" class="px-4 py-3  whitespace-nowrap ">
                        
                        <a href="{{route('customer.transaction',$item->id)}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$item->name}}</a>

                    </th>
           
                    <td class="px-4 py-3">{{$item->stat->balance}}</td>
                 
                    <td class="px-4 py-3">
                        <a href="{{route('item.edit',$item->id)}}" class=" items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                            Edit
                        </a>
                    </td>
                    
                    
                </tr>
                    
                @empty

                <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                   
                    <td class="px-4 py-3 text-center" colspan="9">Data Empty</td>
                   
                    
                    
                </tr>
                    
                @endforelse ()
                
              
            
            </tbody>
        </table>
    </div>

    {{$dataList->onEachSide(1)->links()}}
</div>