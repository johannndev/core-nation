<!DOCTYPE html>
<html lang="en">
<head>
<title>Amazing Crystal</title>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no" />

<link rel="shortcut icon" href="{{ asset('img/ico/fav.ico') }}">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ asset('img/ico/ico144.png') }}">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ asset('img/ico/ico114.png') }}">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ asset('img/ico/ico72.png') }}">
<link rel="apple-touch-icon-precomposed" href="{{ asset('img/ico/ico57.png') }}">
<link rel="stylesheet" href="{{ asset('css/normalize.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('css/receipt.css') }}"/>
</head>
<body>

<h3 class="title center"><img src="{{ asset('img/logo20px.jpg') }}" width="15px" />&nbsp;CORENATION</h3>
<div class="center">@corenationactive</div>
<hr/>
<div class="float-left">Bill No: {{ $transaction->invoice }}</div>
<div class="float-right">Date: {{ $transaction->printDate() }}</div>
<div class="clear"></div>
<hr/>
<? $subtotal = 0; ?>
<div class="content">
@foreach($details as $d)
<div class="row">
	<div>{{ $d['quantity'] }} x {{ $d['name'] }}
		@if($d['discount'] > 0)
		( {{ $d['discount'] }} % )
		@endif
	</div>
	<div class="right">{{ nf($d['total']) }}</div>
</div>
<? $subtotal += $d['total']; ?>
@endforeach
</div>
<hr>
@if($transaction->discount > 0)
<div class="right">Subtotal: {{ nf($subtotal) }}</div>
<div class="right">Discount: {{ nf($transaction->discount) }}%</div>
@endif
<div class="right">Total: {{ nf(0 - $transaction->total) }}</div>
<hr>
<p class="center">Thank you for shopping with CORENATION</p>
</body>
</html>