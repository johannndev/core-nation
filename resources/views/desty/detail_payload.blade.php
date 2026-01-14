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
                            <p class="font-medium">{{ $data->invoice }}</p>
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

        <div class="mt-6">

            @if ($data->status != 'pending')
                @php
                    $alertClass = 'bg-gray-50 text-gray-800 dark:bg-gray-800 dark:text-gray-400';
                    if ($data->status == 'processed') {
                        $alertClass = 'bg-green-50 text-green-800 dark:bg-green-900 dark:text-green-300';
                    } elseif ($data->status == 'error') {
                        $alertClass = 'bg-orange-50 text-orange-800 dark:bg-orange-900 dark:text-orange-300';
                    } elseif ($data->status == 'failed') {
                        $alertClass = 'bg-red-50 text-red-800 dark:bg-red-900 dark:text-red-300';
                    }
                @endphp
                <div class="p-4 mb-4 text-sm rounded-lg {{ $alertClass }}" role="alert">
                    {{ $data->info }}
                </div>
           

            <div class="mt-2">

                <form action="{{ route('desty.createManual', $data->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        Proses Ulang Manual
                    </button>

                </form>

            </div>

            @endif

        </div>


    </div>

    @push('jsHead')
        <link href="https://cdn.jsdelivr.net/npm/jsoneditor@9.10.0/dist/jsoneditor.min.css" rel="stylesheet"
            type="text/css">
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
