<!DOCTYPE html>
<html lang="en">
<head>
<title>CORENATION</title>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no" />

<link rel="shortcut icon" href="{{ asset('img/ico/fav.ico') }}">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ asset('img/ico/ico144.png') }}">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ asset('img/ico/ico114.png') }}">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ asset('img/ico/ico72.png') }}">
<link rel="apple-touch-icon-precomposed" href="{{ asset('img/ico/ico57.png') }}">
<link rel="stylesheet" href="{{ asset('css/receipt.css') }}"/>
</head>
<body>
<div class="receipt">
<!-- ========= HEADER ========= -->
<div class="center bold title-main">CORENATION</div>
<div class="center title-sub tight">
  CILANDAK TOWN SQUARE no.171<br>
  FX SUDIRMAN lt.4<br>
  BSD MAGGIORE GRANDE G50<br>
</div>
<br>
<div class="center bold invoice-label">Retail Invoice</div>
<!-- ========= META ========= -->
<div class="tight">
  Date : {{\Carbon\Carbon::parse($data->date)->format('d/m/Y')}}<br>
  Bill No: {{ $data->id }}<br>
</div>
<br>
<hr>
@php
	$subtotal =0;
    $subq = 0;
@endphp
<!-- ========= ITEMS ========= -->
<table>
  <thead>
    <tr class="bold">
      <th class="item">Item</th>
      <th class="qty">Qty</th>
      <th class="amt">Amt</th>
    </tr>
  </thead>
  <tbody>
@foreach($data->transactionDetail as $d)
    <tr>
      <td class="item">{{ $d->item->getItemName() }}</td>
      <td class="qty">{{ $d->quantity }}</td>
      <td class="amt">{{ Number::format($d->total) }}</td>
    </tr>
@php
    $subtotal += $d->total;
    $subq += $d->quantity;
@endphp
@endforeach
  </tbody>
  <tfoot>
    <tr class="bold">
      <td class="item">Sub Total</td>
      <td class="qty">{{ $subq }}</td>
      <td class="amt">{{ Number::format($subtotal) }}</td>
    </tr>
  </tfoot>
</table>
<hr>
@php
$discount = abs($data->total) - $subtotal;
@endphp
<!-- ========= TOTALS ========= -->
<table class="totals">
  <tr>
    <td class="label">Discount :</td>
    <td class="value">{{ Number::format($discount); }}</td>
  </tr>
  <tr class="bold">
    <td class="label">TOTAL</td>
    <td class="value">{{ Number::format(abs($data->total)); }}</td>
  </tr>
</table>
<br>
<!-- ========= FOOTER ========= -->
<div class="thankyou">@corenationactive phone: 082244226656</div>
</div>
</body>
</html>



