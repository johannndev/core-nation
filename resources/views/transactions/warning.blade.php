<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Peringatan #{{ $tid }}</p>

       
    </div>

    <div id="alert-border-2">

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
        
    @endif

</div>

    <div id="alert-additional-content-2" class="p-4 mb-4 text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert">
        <div class="flex items-center">
            <svg class="shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">Info</span>
            <h3 class="text-lg font-medium">Peringatan adjust stok</h3>
        </div>
        <div class="mt-2 mb-4 text-sm">
            Kamu sudah pernah submit adjust stok pada transaksi <strong>#{{ $tid }}</strong> sebanyak <strong>1 kali</strong>. Mohon periksa kembali pada dashbord Jubelio.
        </div>
        <div class="flex">

            <div class="me-2">
                <form class="myForm" action="{{ route('transaction.warningKonfirmasiJubelioSync',['id'=>$tid,'side'=>Request('side')]) }}" method="post">

                    @csrf

                    <x-layout.submit-button label="Konfirmasi selesai" type="small" color='red' />
        
                </form>
            </div>

             <div class="me-2">
                <form class="myForm" action="{{ route('transaction.clearWarningJubelioSync',['id'=>$tid,'side'=>Request('side')]) }}" method="post">

                    @csrf

                    <x-layout.submit-button label="Hapus peringatan" type="small" color='red-2' />
        
                </form>
            </div>


            <a href="https://v2.jubelio.com/inventory/stock_transaction/adjustment_qty" class="text-gray-800 bg-transparent border border-gray-800 hover:bg-gray-900 hover:text-white focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center dark:hover:bg-gray-600 dark:border-gray-600 dark:text-gray-500 dark:hover:text-white dark:focus:ring-gray-800" data-dismiss-target="#alert-additional-content-2" aria-label="Close">
               Cek jubelio
            </a>
        </div>
    </div>


   
   


   


</x-layouts.layout>