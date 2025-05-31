<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Jubelio cek order</p>

       
    </div>

    <div class="mb-8">

        <div>
        <form class="myForm" id="myForm" action="" method="get" enctype="multipart/form-data">

            <section class="bg-gray-50 dark:bg-gray-900 mb-8">
                <div class="mx-auto  ">
                    <!-- Start coding here -->
                    <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-4">

                        <div class="">

                            <div class="grid grid-cols-2 gap-4 mb-4">

                                <div class="col-span-2">
                                    <label for="order_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Order ID</label>
                                       <input type="text" name="order_id" id="order_id" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ Request('order_id') }}">

                                </div>
                                

                                
                            </div>

                           

                            
                            <x-layout.submit-button />

                        </div>
                    </div>
                </div>
            </section>

        </form>
        </div>

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