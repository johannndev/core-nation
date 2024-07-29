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
                    <th scope="col" class="px-4 py-3">Balance</th>
                    <th scope="col" class="px-4 py-3">Receiver</th>
                    <th scope="col" class="px-4 py-3">Balance</th>
               
      
                    
                </tr>
            </thead>
            <tbody>
                @forelse ( $dataList as $item)
                    
              

                <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                   
                    <th scope="row" class="px-4 py-3  whitespace-nowrap ">
                        
                        <a href="{{route('transaction.getDetail',$item->id)}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{\App\Helpers\DateHelper::display($item->date)}}</a>

                    </th>
           
                    <td class="px-4 py-3">
                        <span class="text-nowrap bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">{{$item->type_name}}</span>
                    </td>
                    <td class="px-4 py-3">{{$item->invoice}}</td>
                   
                    <td class="px-4 py-3 w-60 des-link">{!! $item->description !!}</td>
                    <td class="px-4 py-3">{{Number::format($item->total,2)}}</td>
                    <td class="px-4 py-3">{{Number::format($item->total_items,2)}}</td>
                    <td class="px-4 py-3">
                        @isset($item->sender)
                            {{$item->sender->name}}
                        @endisset
                    </td>
                    <td class="px-4 py-3">
                        @isset($item->sender)
                            {{Number::format($item->sender_balance,2)}}
                        @endisset
                    </td>
                    <td class="px-4 py-3">
                        @isset($item->receiver)
                            {{$item->receiver->name}}
                        @endisset
                    </td>
                    <td class="px-4 py-3">
                        @isset($item->receiver)
                            {{Number::format($item->receiver_balance,2)}}
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