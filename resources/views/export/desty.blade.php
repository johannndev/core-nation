<table>
    <thead>
        <tr>
            <th>Nama Produk</th>
            <th>SKU Master</th>
            <th>ID Gudang</th>
            <th>Nama Gudang</th>
            <th>ID Slot</th>
            <th>Nama Slot</th>
            <th>Stok Fisik</th>
            <th>Unit Price</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        @php
            $currentId = 0;

        @endphp
        @forelse ($dataList as $item)

            @php
                
                if ($item->transaction_type == 2 || $item->transaction_type == 17) {
                    $qty = "-".$item->quantity;
                    $idgudang = $item->destySender->gudang_id ?? 'kosong';
                    $namagudang = $item->destySender->warehouse->name ?? 'kosong';
                    $idslot = $item->destySender->slot_id ?? 'kosong';
                } else {
                    $qty = $item->quantity;
                }

            @endphp
            <tr>
                <td>{{ $item->item->name }}</td>
                <td>{{ $item->item->code }}</td>
                <td data-type="string"  data-format="@">{{(string)$idgudang}}</td>
                <td>{{$namagudang}}</td>
                <td  data-type="string" data-format="@">{{(string)$idslot}}</td>
                <td></td>
                <td>{{ $qty}}</td>
                <td>Rp {{ number_format($item->total, 0, '', '.') }}</td>
                {{-- <td>{{ $item->transaction->invoice ?? '' }}</td> --}}
                {{-- @if ($currentId != $item->transaction->id)
                    <td>{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                    <td>
                        @isset($item->sender)
                            {{ $item->sender->name }}
                        @endisset
                    </td>
                    <td>
                        @isset($item->receiver)
                            {{ $item->receiver->name }}
                        @endisset
                    </td>
                @else
                    <td></td>
                    <td></td>
                    <td></td>
                @endif
                <td>{{ $item->type_name }}</td>
                <td>{{ $item->item->id }}</td>
                <td>{{ $item->item->code }}</td>
                <td>{{ number_format($item->quantity, 2) }}</td>
                <td>{{ number_format($item->discount, 2) }}</td>
                <td>{{ number_format($item->total, 2) }}</td>
                @if ($currentId != $item->transaction->id)
                    <td>{{ number_format($item->transaction->discount, 2) }}</td>
                    <td>{{ number_format($item->transaction->adjustment, 2) }}</td>
                    <td>{{ number_format(abs($item->transaction->total), 2) }}</td>
                    <td>{{ $item->transaction->description }}</td>
                @else
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                @endif
            </tr>
            @php
                $currentId = $item->transaction->id;
            @endphp --}}
        @empty
        @endforelse

    </tbody>
</table>
