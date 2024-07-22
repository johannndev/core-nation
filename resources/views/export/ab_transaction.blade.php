<table >
    <thead>
        <tr>
            <th >Date</th>
            <th >Type</th>
            <th >Invoice</th>
            <th >Description</th>
            <th >Total</th>
            <th >Items</th>
            <th >Sender</th>
            <th >Balance</th>
            <th >Receiver</th>
            <th >Balance</th>
       

            
        </tr>
    </thead>
    <tbody>
        @forelse ( $dataList as $item)
            
      

        <tr class="">
           
            <th>
                
               {{\App\Helpers\DateHelper::display($item->date)}}

            </th>
   
            <td>{{$item->type_name}}</td>
            <td>{{$item->invoice}}</td>
           
            <td>{{$item->description}}</td>
            <td>{{Number::format($item->total,2)}}</td>
            <td>{{Number::format($item->total_items,2)}}</td>
            <td>
                @isset($item->sender)
                    {{$item->sender->name}}
                @endisset
            </td>
            <td>
                @isset($item->sender)
                    {{Number::format($item->sender->stat->balance,2)}}
                @endisset
            </td>
            <td>
                @isset($item->receiver)
                    {{$item->receiver->name}}
                @endisset
            </td>
            <td>
                @isset($item->receiver)
                    {{Number::format($item->receiver->stat->balance,2)}}
                @endisset
            </td>
          
            
            
            
        </tr>
            
        @empty

        <tr >
           
            <td colspan="9">Data Empty</td>
           
            
            
        </tr>
            
        @endforelse 
        
      
    
    </tbody>
</table>