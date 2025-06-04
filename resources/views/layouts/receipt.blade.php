<!DOCTYPE html>
<html lang="en">
<head>
<title>CORENATION</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
@page { 
    size: 57mm auto; 
    margin: 0;
}

* { 
    box-sizing: border-box; 
    margin: 0; 
    padding: 0;
}

body {
    margin: 0;
    padding: 0;
    background: #fff;
}

.receipt {
    width: 57mm;
    padding: 2mm 2mm;
    font-family: Arial Black, Gadget, sans-serif; /* Thickest font */
    font-size: 10px;
    line-height: 1.3;
    color: #000;
    font-weight: 900;
    
    /* Multiple text shadows for thickness */
    text-shadow: 
        0 0 0.5px #000,
        0 0 0.5px #000,
        0 0 0.5px #000,
        0.3px 0 0 #000,
        -0.3px 0 0 #000,
        0 0.3px 0 #000,
        0 -0.3px 0 #000;
}

/* Force webkit to render thicker */
* {
    -webkit-text-stroke: 0.3px #000;
    text-stroke: 0.3px #000;
    -webkit-font-smoothing: none;
    font-smoothing: none;
}

/* Typography */
.center { text-align: center; }
.bold { 
    font-weight: 900;
    -webkit-text-stroke: 0.5px #000;
}

/* Headers with extra thickness */
.title-main { 
    font-size: 14px;
    font-weight: 900;
    margin-bottom: 3px;
    letter-spacing: 1px;
    text-transform: uppercase;
    -webkit-text-stroke: 0.8px #000;
    text-shadow: 
        0 0 1px #000,
        0 0 1px #000,
        0.5px 0 0 #000,
        -0.5px 0 0 #000,
        0 0.5px 0 #000,
        0 -0.5px 0 #000;
}

.title-sub { 
    font-size: 9px;
    line-height: 1.3;
    margin-bottom: 3px;
    font-weight: 900;
    -webkit-text-stroke: 0.4px #000;
}

.invoice-label {
    font-size: 11px;
    margin: 4px 0;
    font-weight: 900;
    -webkit-text-stroke: 0.5px #000;
}

/* Make HR much thicker */
hr {
    border: none;
    height: 2px;
    background-color: #000;
    margin: 4px 0;
}

/* Table with thicker text */
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 10px;
    margin: 3px 0;
    font-weight: 900;
}

th, td {
    padding: 2px 0;
    vertical-align: top;
    -webkit-text-stroke: 0.3px #000;
}

th {
    font-weight: 900;
    padding-bottom: 3px;
    -webkit-text-stroke: 0.5px #000;
}

/* Column widths */
.item { width: 50%; text-align: left; }
.qty { width: 15%; text-align: center; }
.amt { width: 35%; text-align: right; }

/* Footer row extra bold */
tfoot td {
    padding-top: 4px;
    font-weight: 900;
    -webkit-text-stroke: 0.5px #000;
}

/* Totals section */
.totals {
    width: 100%;
    margin: 4px 0;
    font-size: 10px;
}

.totals td {
    padding: 2px 0;
    font-weight: 900;
    -webkit-text-stroke: 0.3px #000;
}

.totals .label {
    text-align: left;
    width: 60%;
}

.totals .value {
    text-align: right;
    width: 40%;
}

/* Total row extra thick */
.totals tr.bold td {
    font-size: 12px;
    font-weight: 900;
    -webkit-text-stroke: 0.6px #000;
    text-shadow: 
        0 0 0.5px #000,
        0.3px 0 0 #000,
        -0.3px 0 0 #000;
}

/* Footer */
.thankyou {
    text-align: center;
    font-size: 9px;
    margin-top: 5px;
    padding-bottom: 3mm;
    font-weight: 900;
    -webkit-text-stroke: 0.3px #000;
}

/* Aggressive print styles for low-res thermal */
@media print {
    * {
        color: #000 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    /* Force maximum thickness */
    .receipt * {
        text-rendering: geometricPrecision !important;
        filter: contrast(500%) brightness(0.6) !important;
        -webkit-filter: contrast(500%) brightness(0.6) !important;
    }
    
    /* Double the strokes for print */
    .title-main {
        -webkit-text-stroke: 1px #000 !important;
    }
    
    .bold, th, tfoot td, .totals tr.bold td {
        -webkit-text-stroke: 0.8px #000 !important;
    }
    
    body {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}

/* Alternative: Use pseudo elements for extra thickness */
.title-main::before {
    content: attr(data-text);
    position: absolute;
    left: 0.2px;
    top: 0.2px;
    color: #000;
    z-index: -1;
}

</style>
</head>
<body>
<div class="receipt">
    <!-- Header -->
    <div class="center title-main">CORENATION</div>
    <div class="center title-sub">
        CILANDAK TOWN SQUARE no.171<br>
        FX SUDIRMAN lt.4<br>
        BSD MAGGIORE GRANDE G50
    </div>
    
    <div class="center invoice-label">Retail Invoice</div>
    
    <!-- Meta -->
    <div class="meta">
  Date : {{\Carbon\Carbon::parse($data->date)->format('d/m/Y')}}<br>
  Bill No: {{ $data->id }}<br>
    </div>
    
    <hr>
    
    <!-- Items -->
    <table>
        <thead>
            <tr>
                <th class="item">Item</th>
                <th class="qty">Qty</th>
                <th class="amt">Amt</th>
            </tr>
        </thead>
        <tbody>
@php
	$subtotal =0;
    $subq = 0;
@endphp
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
            <tr>
                <td class="item">Sub Total</td>
                <td class="qty">{{ $subq }}</td>
                <td class="amt">{{ Number::format($subtotal) }}</td>
            </tr>
        </tfoot>
    </table>
    
    <hr>
@php
$discount = abs($data->total) - $subtotal;
@endphp    
    <!-- Totals -->
    <table class="totals">
        <tr>
            <td class="label">Discount :</td>
            <td class="value">{{ Number::format($discount); }}</td>
        </tr>
        <tr class="total-row">
            <td class="label">TOTAL</td>
            <td class="value">{{ Number::format(abs($data->total)); }}</td>
        </tr>
    </table>
    
    <!-- Footer -->
    <div class="thankyou">
        @corenationactive phone: 082244226656
    </div>
</div>
</body>
</html>
