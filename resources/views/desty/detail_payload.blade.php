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
                            <p class="font-medium">{{$data->invoice}}</p>
                        </div>

                        {{-- <div>
                            <p class="text-sm text-gray-500">Oder Item ID</p>
                            <p class="font-medium">{{$data->item_order_id}}</p>
                        </div> --}}

                    </div>
                </div>
            </div>
        </section>

        

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