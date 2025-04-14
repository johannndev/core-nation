<table >
    <thead >
        <tr>
           
            <th >ID</th>
            <th >Date</th>
            <th >Type</th>
            <th >Invoice</th>
            <th >Items</th>
            <th >Qty</th>
            <th >Discount</th>
            <th >Total</th>
            <th >Receiver</th>
            <th >Sender</th>
            
        </tr>
    </thead>
    <tbody>
        @forelse ( $dataList as $item)
            
        <tr >
   
            <td >{{$item->id}}</td>
            <td >{{\Carbon\Carbon::parse($item->date)->format('d/m/Y')}}</td>
            <td >{{$item->transaction->invoice ?? ''}}</td>
            <td >{{$item->item->code}}</td>
            <td >{{number_format($item->quantity,2)}}</td>
            <td >{{number_format($item->discount,2)}}</td>
            <td >{{number_format($item->total,2)}}</td>
            <td >
                @isset($item->sender)
                    {{$item->sender->name}}               
                @endisset
            </td>
            <td >
                @isset($item->receiver)
                    {{$item->receiver->name}}               
                @endisset
            </td>
        </tr>
            
        @empty

    
            
        @endforelse 
        
      
    
    </tbody>
</table>