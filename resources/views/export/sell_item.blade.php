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
            <th >Sub Discount</th>
            <th >Subtotal</th>
            <th>Adjust</th>
            <th>Disc</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
@php
      $currentInvoice = 0;
@endphp
        @forelse ( $dataList as $item)
        <tr>
@if($currentInvoice != $item->transaction->invoice)
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
            <td></td><td></td><td></td><td></td>
@endif
            <td >{{$item->type_name}}</td>
            <td >{{$item->item->id}}</td>
            <td >{{$item->item->code}}</td>
            <td >{{number_format($item->quantity,2)}}</td>
            <td >{{number_format($item->discount,2)}}</td>
            <td >{{number_format($item->total,2)}}</td>
@if($currentInvoice != $item->transaction->invoice)
        <td>{{number_format($item->transaction->discount,2)}}</td>
        <td>{{number_format($item->transaction->adjustment,2)}}</td>
        <td>{{number_format(abs($item->transaction->total),2)}}</td>
@else
        <td></td><td></td><td></td>
@endif
    </tr>
@php
      $currentInvoice = $item->transaction->invoice;
@endphp
        @empty
        @endforelse
    </tbody>
</table>
