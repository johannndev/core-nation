<!DOCTYPE html>
<html lang="en">
<head>
<title>CORENATION</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
    @page { 
        /* ISO C8 is 57mm × 81mm */
        size: 57mm auto; 
        margin: 0;
    }

    * { 
        box-sizing: border-box; 
        margin: 0; 
        padding: 0;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: 'Courier New', Courier, monospace;
        font-size: 11px;
        line-height: 1.1;
        color: #000;
        background: #fff;
    }

    .receipt {
        /* Adjusted for ISO C8: 57mm total width */
        width: 57mm;
        /* Reduced padding to maximize usable width: 2mm each side = 53mm content */
        padding: 2mm 2mm;
        background: #fff;
    }

    /* Typography */
    .center { text-align: center; }
    .bold { font-weight: bold; }
    
    .title-main { 
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 2px;
    }
    
    .title-sub { 
        font-size: 10px;
        line-height: 1.2;
        margin-bottom: 4px;
    }
    
    .invoice-label {
        font-size: 12px;
        margin: 4px 0;
    }

    /* Horizontal rule */
    hr {
        border: none;
        border-top: 1px dashed #000;
        margin: 3px 0;
    }

    /* Meta info */
    .meta {
        font-size: 11px;
        line-height: 1.3;
        margin: 3px 0;
    }

    /* Table */
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
        margin: 2px 0;
    }

    th, td {
        padding: 1px 0;
        vertical-align: top;
    }

    th {
        font-weight: bold;
        text-align: left;
        padding-bottom: 2px;
    }

    .item { width: 50%; text-align: left; }
    .qty { width: 15%; text-align: center; }
    .amt { width: 35%; text-align: right; }

    /* Footer row */
    tfoot td {
        padding-top: 3px;
        font-weight: bold;
    }

    /* Totals section */
    .totals {
        margin: 3px 0;
        font-size: 11px;
    }

    .totals tr {
        line-height: 1.3;
    }

    .totals .label {
        text-align: left;
        width: 60%;
    }

    .totals .value {
        text-align: right;
        width: 40%;
    }

    .totals .total-row {
        font-weight: bold;
        font-size: 12px;
    }

    /* Footer */
    .thankyou {
        text-align: center;
        font-size: 10px;
        margin-top: 5px;
        padding-bottom: 3mm;
    }

    /* Print specific */
    @media print {
        body {
            margin: 0;
            padding: 0;
        }
        
        .receipt {
            page-break-after: always;
        }
    }

    /* Hide screen elements */
    @media screen {
        body {
            background: #f0f0f0;
            display: flex;
            justify-content: center;
            padding: 20px;
        }
        
        .receipt {
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin: 0 auto;
        }
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


