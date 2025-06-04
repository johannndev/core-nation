<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Dashboard</p>

       
    </div>

    {{-- <div id="alert-border-2">

    @if ((session('errorMessage')))

    <div  class="flex items-center p-4 mb-4 text-red-800 border-t-4 border-red-300 bg-red-50 dark:text-red-400 dark:bg-gray-800 dark:border-red-800" role="alert">
        <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
          <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <div class="ms-3 text-sm font-medium">
            {{session('errorMessage')}}
        </div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700"  data-dismiss-target="#alert-border-2" aria-label="Close">
          <span class="sr-only">Dismiss</span>
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
          </svg>
        </button>
    </div>
        
    @endif --}}

   

   



    <div class="mb-8">

        <div class="grid md:grid-cols-3 gap-4">


          <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-4">

            <p class="font-medium">Online</p>
            <p class=" font-bold text-2xl">{{$onlineStat['online_total']}}</p>

            <div class="grid grid-cols-2 gap-4 text-sm mt-2 text-gray-500">

              <div class="flex">
                <p>Customer:</p>
                <p class="ml-2 font-medium">{{$onlineStat['online_customer']}}</p>
              </div>

              <div class="flex">
                <p>Warehouse:</p>
                <p class="ml-2 font-medium">{{$onlineStat['online_warehouse']}}</p>
              </div>

            </div>

          </div>

           <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-4">

            <p class="font-medium">Offline</p>
            <p class=" font-bold text-2xl">{{$onlineStat['offline_total']}}</p>

            <div class="grid grid-cols-2 gap-4 text-sm mt-2 text-gray-500">

              <div class="flex">
                <p>Customer:</p>
                <p class="ml-2 font-medium">{{$onlineStat['offline_customer']}}</p>
              </div>

              <div class="flex">
                <p>Warehouse:</p>
                <p class="ml-2 font-medium">{{$onlineStat['offline_warehouse']}}</p>
              </div>

            </div>

          </div>

        </div>

    </div>

</x-layouts.layout>