@php
        $status = Auth::user()->getRoleNames();

       

        if(count($status) == 0){

            $heading = "Access Denied";
            $caption = "You do not have permission to access this resource. Please contact support if you believe this is an error.";

        }else{

            if($status[0] == "ban"){
                $heading = "Account Banned";
                $caption = "Your account has been banned and you no longer have access to this service. Please contact support if you believe this is a mistake.";
            }else{
                $heading = "Access Denied";
                $caption = "You do not have permission to access this resource. Please contact support if you believe this is an error.";
            }

        }
        
        
    @endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$heading}}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body>

    
 
    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
            <div class="mx-auto max-w-screen-sm text-center">
                <h1 class="mb-4 text-7xl tracking-tight font-extrabold lg:text-9xl text-primary-600 dark:text-primary-500">403</h1>
                <p class="mb-4 text-3xl tracking-tight font-bold text-gray-900 md:text-4xl dark:text-white">{{$heading}}</p>
                <p class="mb-4 text-lg font-light text-gray-500 dark:text-gray-400">{{$caption}}</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
    
                    <a href="{{route('logout')}}" onclick="event.preventDefault(); this.closest('form').submit();"  class="inline-flex text-white bg-primary-600 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:focus:ring-primary-900 my-4"
                    >Try another account</a>
    
                  
                </form>
                
            </div>   
        </div>
    </section>
</body>
</html>
