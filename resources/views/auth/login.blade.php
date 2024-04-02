<!DOCTYPE html>
<html lang="en" ng-app="rootApp">
<head>
<title>Login Dulu</title>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no" />

<link rel="shortcut icon" href="{{ asset('img/ico/fav.ico') }}">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ asset('img/ico/ico144.png') }}">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ asset('img/ico/ico114.png') }}">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ asset('img/ico/ico72.png') }}">
<link rel="apple-touch-icon-precomposed" href="{{ asset('img/ico/ico57.png') }}">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/materialize.min.css') }}"/>
</head>

<body>

<div class="container">
    <form method="POST" action="{{ route('login') }}">

        @csrf

        <div>
            <label>Username</label>
            <input name="username" type="text" id="username">
        </div>

        <div>
            <label>Password</label>
            <input name="password" type="password" value="" id="password">
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="red-text">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- @if(session('status'))
        <h4 class="red-text">{{session('status')}}</h4>
        @endif --}}

        <div><input class="btn" type="submit" value="Login"></div>
</form>
</div>

<script src="{{ asset('js/jquery-2.1.4.min.js') }}"></script>
<script src="{{ asset('js/materialize.min.js') }}"></script>


</body>
</html>