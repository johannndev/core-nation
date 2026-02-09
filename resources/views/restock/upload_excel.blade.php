<x-layouts.layout>

    <x:partial.scan-modal />

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Import Excel</p>


    </div>

    <div id="alert-border-2">

        <div class="error-wrapper hidden items-center p-4 mb-4 text-red-800 border-t-4 border-red-300 bg-red-50 dark:text-red-400 dark:bg-gray-800 dark:border-red-800"
            role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <div class="ms-3 text-sm font-medium error-text">

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

    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-300 bg-red-50 p-4">
            <div class="mb-2 text-sm font-semibold text-red-700">
                Terjadi kesalahan:
            </div>
            <ul class="list-disc space-y-1 pl-5 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="bg-gray-50 dark:bg-gray-900 mb-8">
       
        <div class="mx-auto  ">
            <!-- Start coding here -->
            <div class="">

                <div class="">

                    <form method="POST" action="{{ route('restock.importExcel') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-4">

                            <p class="text-lg font-semibold mb-6">Update Quantity</p>

                            <div class="grid gap-6 mb-6 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-1 items-end "id="">
                                <div class="mb-3 space-y-2">
                                    @foreach (['restocked', 'production', 'shipped','missing'] as $type)
                                        <label class="flex items-center gap-2 text-sm">
                                            <input type="radio" name="type" value="{{ $type }}" required>
                                            {{ ucfirst($type) }}
                                        </label>
                                    @endforeach
                                </div>

                                <div>
                                    <label for="date"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date</label>
                                    <input type="date" name="date" id="date"
                                        aria-describedby="helper-text-explanation"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        value="{{ date('Y-m-d') }}">

                                </div>

                                <div class="">
                                    <label class="block mb-2.5 text-sm font-medium text-heading" for="file_input">Upload file</label>
                                    <input class="cursor-pointer bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full shadow-xs placeholder:text-body" id="file_input" type="file" name="file">
                                </div>

                              

                            </div>

                            <x-layout.submit-button  />

                        </div>

                    </form>
                </div>

                {{-- <div class="col-span-12 md:col-span-8 lg:col-span-8">

                    <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-4">
                        <p class="text-lg font-bold mb-4">Daftar Restock</p>

                        <div>
                            @foreach ($items as $item)
                                <div class="flex items-start justify-between gap-4 border-b py-2">
                                    <!-- Nama -->
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-700 break-words leading-snug">
                                            {{ $item['name'] }}
                                        </p>
                                    </div>

                                    <!-- Qty + Delete -->
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm font-semibold text-gray-900">
                                            {{ $item['qty'] }}
                                        </span>

                                        <form method="POST" action="{{ route('restock.removeItem', $item['code']) }}">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="text-xs font-medium text-red-600 hover:text-red-700 hover:underline focus:outline-none focus:ring-2 focus:ring-red-300 rounded">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach

                        </div>

                        <form class="myForm" id="myForm" method="POST" action="{{ route('restock.store') }}">

                            @csrf

                            <div class="grid grid-cols-2 gap-4 mb-8 mt-4">
                                <div>
                                    <label for="date"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date</label>
                                    <input type="date" name="date" id="date"
                                        aria-describedby="helper-text-explanation"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        value="{{ date('Y-m-d') }}">

                                </div>

                            </div>
                            <x-layout.submit-button />
                        </form>
                    </div>
                </div> --}}

            </div>
        </div>
    </section>



    @push('jsBody')
        <script>
           
        </script>
    @endpush





</x-layouts.layout>
