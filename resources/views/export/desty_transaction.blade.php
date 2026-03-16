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
                    $qty = '-' . $item->quantity;
                    $idgudang = (string)$item->destySender->gudang_id ?? 'kosong';
                    $namagudang = $item->destySender->warehouse->name ?? 'kosong';
                    $idslot = (string)$item->destySender->slot_id ?? 'kosong';
                } else {
                    $qty = $item->quantity;
                }

            @endphp
            <tr>
                <td>{{ $item->item->name }}</td>
                <td>{{ $item->item->code }}</td>
                <td data-type="string">
                    {{ $idgudang }}
                </td>
                <td>{{ $namagudang }}</td>
                <td data-type="string">
                    {{ $idslot }}
                </td>
                <td></td>
                <td>{{ $qty }}</td>
                <td>Rp {{ number_format($item->total, 0, '', '.') }}</td>
            </tr>
        @empty
        @endforelse

    </tbody>
</table>
