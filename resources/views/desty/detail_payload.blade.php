<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Webhook History</p>


    </div>

    <div id="alert-border-2">

        @if (session('errorMessage'))
            <div class="flex items-center p-4 mb-4 text-red-800 border-t-4 border-red-300 bg-red-50 dark:text-red-400 dark:bg-gray-800 dark:border-red-800"
                role="alert">
                <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                </svg>
                <div class="ms-3 text-sm font-medium">
                    {{ session('errorMessage') }}
                </div>
                <button type="button"
                    class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700"
                    data-dismiss-target="#alert-border-2" aria-label="Close">
                    <span class="sr-only">Dismiss</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
        @endif

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
            @endif

            @if ($data->status == 'error' || $data->status == 'failed')
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
