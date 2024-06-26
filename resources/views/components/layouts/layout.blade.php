<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<title>{{ config('app.name', 'Laravel') }}</title>

		<!-- Fonts -->
		<link rel="preconnect" href="https://fonts.bunny.net">
		<link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

		<!-- Scripts -->
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
		@vite(['resources/css/app.css', 'resources/js/app.js'])

		<style>
			
		</style>

		<script src="{{asset('js/jquery-2.1.4.min.js')}}"></script>

		
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
		{{-- <link rel="stylesheet" href="{{asset('css/select2Custom.css')}}"> --}}

		<style>
			/* Contoh styling */
			.active, .autocomplete-items div:hover {
				background-color: #e9e9e9;
			}

		</style>

	</head>
	<body class="font-sans antialiased">
	  <div class="antialiased bg-gray-50 dark:bg-gray-900">
      

    @include('layouts.nav')
	
		<!-- Sidebar -->
      
    @include('layouts.aside')
		

	
		<main class="p-4 md:ml-64 h-auto pt-20">
		  
        {{ $slot }}
		 
		</main>

		<x-partial.toast-danger-js />

		@if (session('success'))

			<x-partial.toast-success />

		@endif

		@if (session('fail'))

			<x-partial.toast-fail />

		@endif

    @stack('jsBody')

    
	  </div>
	</body>
</html>
