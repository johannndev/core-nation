<table >
    <thead >
        <tr>
           
            <th >ID</th>
            <th >SKU</th>
            <th >Code</th>
            <th >Name</th>
            <th >Description</th>
            <th >Size</th>
            <th >Price</th>
            <th >Quantity</th>
            <th >Disc</th>
            <th >Action</th>
            
        </tr>
    </thead>
    <tbody>
        @forelse ( $dataList as $item)
            
        @php
            $url = $item->item->getImageUrl();
            
        @endphp

        <tr >
           
            
            <th >
                
               

                <a href="{{$item->item->getDetailUrl($item->item_id,$item->item->type)}}" >{{$item->item_id}}</a>

            </th>
   
            <td >{{$item->item->code}}</td>
            <td >{{$item->item->getItemCode()}}</td>
            <td >{{$item->item->getItemName()}}</td>
            <td >{{$item->item->getOnlineName()}}</td>
            <td >{{$item->item->group ? $item->item->group->description : $item->item->description}}</td>
            <td >
                @if ($item->item->getSize())

                <span > {{$item->item->getSize()}}</span>
                    
                @endif
               
            </td>
            <td >{{Number::format($item->item->price,2)}}</td>
            <td >{{$item->quantity}}</td>
            <td >{{$item->item->getDisc()}}</td>
         
          
            
            
        </tr>
            
        @empty

    
            
        @endforelse 
        
      
    
    </tbody>
</table>