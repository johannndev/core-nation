<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Webhook History</p>

       
    </div>

    <div class="mb-8">


        <section class="bg-gray-50 dark:bg-gray-900 ">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="grid grid-cols-2 p-4">

                        <div>
                            <p class="text-sm text-gray-500">Order ID</p>
                            <p class="font-medium">{{$data->jubelio_order_id}}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Invoice</p>
                            <p class="font-medium">{{$data->invoice}}</p>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        @if ($data->error)

        <div id=" alert-additional-content-2" class="p-4 mt-6 text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert">
            <div class="flex items-center">
                <svg class="shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="sr-only">Info</span>
                <h3 class="text-lg font-medium">Error alert</h3>
            </div>
            <div class="mt-2 mb-4 text-sm">
               {{$data->error}}
            </div>
            
        </div>

        @endif

        <div class="mt-6" id="jsoneditor"></div>
       

    </div>

    @push('jsHead')

    <link href="https://cdn.jsdelivr.net/npm/jsoneditor@9.10.0/dist/jsoneditor.min.css" rel="stylesheet" type="text/css">
        
    @endpush

    @push('jsBody')

    <script src="https://cdn.jsdelivr.net/npm/jsoneditor@9.10.0/dist/jsoneditor.min.js"></script>

    <script>
           // Data dari PHP (konversi array ke JSON)
        const container = document.getElementById("jsoneditor");

        const options = {
            mode: "view", // atau "view"
            mainMenuBar: true
        };

        const editor = new JSONEditor(container, options);

        // Kirim data JSON dari Laravel ke editor
        const json = @json($jsonData); // Laravel helper agar JS dapat objek asli, bukan string

        editor.set(json);
    </script>

        
    @endpush

</x-layouts.layout>