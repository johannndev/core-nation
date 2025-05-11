<table >
    <thead >
        <tr>
            <th >Date</th>
            <th >Invoice</th>
            <th >Sender</th>
            <th >Receiver</th>
            <th >Type</th>
            <th >Barcode</th>
            <th >Items</th>
            <th >Qty</th>
            <th >Discount</th>
            <th >Subtotal</th>
        </tr>
    </thead>
    <tbody>
@php
      $currentInvoice = 0;
      $previous = false;
@endphp
        @forelse ( $dataList as $item)
@if($currentInvoice != $item->transaction->invoice)
@php
      $currentInvoice = $item->transaction->invoice;
@endphp
@if($previous)
      <tr>
        <td></td><td></td><td></td><td></td>
        <td>Discount</td><td>{{number_format($previous->transaction->discount,2)}}</td>
        <td>Adjust</td><td>{{number_format($previous->transaction->adjustment,2)}}</td>
        <td>Total</td><td>{{number_format($previous->transaction->total,2)}}</td>
      </tr>
@endif
      <tr >
            <td >{{\Carbon\Carbon::parse($item->date)->format('d/m/Y')}}</td>
            <td >{{$item->transaction->invoice ?? ''}}</td>
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
@else
            <tr ><td></td><td></td><td></td><td></td>
@endif
            <td >{{$item->type_name}}</td>
            <td >{{$item->item->id}}</td>
            <td >{{$item->item->code}}</td>
            <td >{{number_format($item->quantity,2)}}</td>
            <td >{{number_format($item->discount,2)}}</td>
            <td >{{number_format($item->total,2)}}</td>
        </tr>
@php
    $previous = $item;
@endphp
        @empty
        @endforelse
    </tbody>
</table>
