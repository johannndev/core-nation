<!DOCTYPE html>
<html lang="en">
<head>
<title>CORENATION</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
@page {
    size: 58mm auto;
    margin: 0;
}

body {
    margin: 0;
    padding: 2mm;
    width: 58mm;
    font-family: monospace;
    font-size: 12px;
    line-height: 1.4;
}

.center {
    text-align: center;
    margin: 0 auto;
}

hr {
    border: 0;
    border-top: 1px solid #000;
    margin: 2px 0;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    font-family: monospace;
    font-size: 12px;
    padding: 0;
}

.item-table td {
    padding: 1px 0;
}

.item-name {
    width: 50%;
    text-align: left;
    white-space: nowrap;
    overflow: hidden;
}

.item-qty {
    width: 20%;
    text-align: right;
}

.item-amt {
    width: 30%;
    text-align: right;
}

.total-label {
    text-align: left;
    font-weight: bold;
}

.total-value {
    text-align: right;
    font-weight: bold;
}

@media print {
    * {
        font-family: monospace !important;
    }
}
</style>
</head>
<body>
<div class="receipt">
    <div class="center">CORENATION</div>
    <div class="center">CILANDAK TOWN SQUARE no.171</div>
    <div class="center">FX SUDIRMAN lt.4</div>
    <div class="center">BSD MAGGIORE GRANDE G50</div>
    <br>
    <div class="center">Retail Invoice</div>
    <br>
    <div>Date : {{ \Carbon\Carbon::parse($data->date)->format('d/m/Y') }}</div>
    <div>Bill No: {{ $data->id }}</div>
    <hr>
    
    <table class="item-table">
        <thead>
            <tr>
                <th class="item-name">Item</th>
                <th class="item-qty">Qty</th>
                <th class="item-amt">Amt</th>
            </tr>
        </thead>
        <tbody>
            @php
            $subtotal = 0;
            $subq = 0;
            @endphp
            @foreach($data->transactionDetail as $d)
            @php
                $subtotal += $d->total;
                $subq += $d->quantity;
            @endphp
            <tr>
                <td class="item-name">{{ Str::limit($d->item->getItemName(), 18, '') }}</td>
                <td class="item-qty">{{ $d->quantity }}</td>
                <td class="item-amt">{{ number_format($d->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    <table>
        <tr>
            <td class="total-label">SubTotal ({{ $subq }} items)</td>
            <td class="total-value">{{ number_format($subtotal, 0, ',', '.') }}</td>
        </tr>
        @php
            $discount = abs($data->total) - $subtotal;
        @endphp
        <tr>
            <td class="total-label">Discount</td>
            <td class="total-value">{{ number_format($discount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="total-label">TOTAL</td>
            <td class="total-value">{{ number_format(abs($data->total), 0, ',', '.') }}</td>
        </tr>
    </table>

    <br>
    <div class="center">@corenationactive 082244226656</div>
</div>
</body>
</html>
