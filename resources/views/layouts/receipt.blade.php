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

<h3 class="title center">CORENATION</h3>
<div class="center">@corenationactive</div>
<hr/>
<div class="float-left">Bill No: {{ $data->id }}</div>
<div class="float-right">Date: {{\Carbon\Carbon::parse($data->date)->format('d/m/Y')}}</div>
<div class="clear"></div>
<hr/>
@php

	$subtotal =0;
		
	@endphp

<div class="content">
@foreach($data->transactionDetail as $d)
<div class="row">
	<div>{{ $d->quantity }} x {{ $d->item->getItemName() }}
		@if($d->discount > 0)
		( {{ $d->discount }} % )
		@endif
	</div>
	<div class="right">{{ Number::format($d->total) }}</div>
</div>

	@php

	$subtotal += $d->total;
		
	@endphp

@endforeach
</div>
<hr>
@if($data->discount > 0)
<div class="right">Subtotal: {{ Number::format($subtotal) }}</div>
<div class="right">Discount: {{ Number::format($data->discount) }}%</div>
@endif
<div class="right">Total: {{ Number::format($data->total) }}</div>
<hr>
<p class="center">Thank you for shopping with CORENATION</p>
</body>
</html>
