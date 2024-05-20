<div>
  
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-4 py-3">Date</th>
                    <th scope="col" class="px-4 py-3">Type</th>
                    <th scope="col" class="px-4 py-3">Invoice</th>
                    <th scope="col" class="px-4 py-3">Description</th>
                    <th scope="col" class="px-4 py-3">Total</th>
                    <th scope="col" class="px-4 py-3">Items</th>
                    <th scope="col" class="px-4 py-3">Sender</th>
                    <th scope="col" class="px-4 py-3">Receiver</th>
               
      
                    
                </tr>
            </thead>
            <tbody>
                @forelse ( $dataList as $item)
                    
              

                <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                   
                    <th scope="row" class="px-4 py-3  whitespace-nowrap ">
                        
                        <a href="{{route('transaction.getDetail',$item->id)}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{\App\Helpers\DateHelper::display($item->date)}}</a>

                    </th>
           
                    <td class="px-4 py-3">{{$item->type_name}}</td>
                    <td class="px-4 py-3">{{$item->invoice}}</td>
                   
                    <td class="px-4 py-3 w-60">{{$item->description}}</td>
                    <td class="px-4 py-3">{{$item->total}}</td>
                    <td class="px-4 py-3">{{$item->total_items}}</td>
                    <td class="px-4 py-3">
                        @isset($item->sender)
                            {{$item->sender->name}}
                        @endisset
                    </td>
                    <td class="px-4 py-3">
                        @isset($item->receiver)
                            {{$item->receiver->name}}
                        @endisset
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