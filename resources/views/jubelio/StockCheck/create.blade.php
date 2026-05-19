<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">
        <p class="text-2xl font-bold">Create Stock Check</p>
    </div>

    {{-- Alert Error --}}
    @if (session('errorMessage'))
        <div id="alert-border-2"
            class="flex items-center p-4 mb-4 text-red-800 border-t-4 border-red-300 bg-red-50 dark:text-red-400 dark:bg-gray-800 dark:border-red-800"
            role="alert">

            <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 20 20">
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

                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
    @endif

    {{-- Active Job --}}
    @if ($activeJob)
        <div
            class="rounded-lg bg-yellow-50 p-4 mb-6 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">

            <p class="font-medium">
                Pengecekan Sedang Berjalan
            </p>

            <p class="mt-1 text-sm">
                Terdapat pengecekan (ID: {{ $activeJob->id }})
                yang sedang berstatus
                "{{ $activeJob->status }}".
                Harap tunggu hingga pengecekan tersebut selesai
                sebelum membuat pengecekan baru.
            </p>

            <div class="mt-4">
                <a href="{{ route('jubelio-stock-checks.index') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700">
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    @else

        <form action="{{ route('jubelio-stock-checks.store') }}" method="POST">
            @csrf

            <section class="bg-gray-50 dark:bg-gray-900 mb-8">
                <div class="mx-auto">
                    <div
                        class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-4">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                            {{-- Page Tracking --}}
                            <div>
                                <label for="page_tracking"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Mulai dari Halaman
                                </label>

                                <input type="number"
                                    name="page_tracking"
                                    id="page_tracking"
                                    min="1"
                                    value="{{ old('page_tracking', 1) }}"
                                    placeholder="Contoh: 1"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                           focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                                           dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                           dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Pengecekan akan dimulai dari halaman Jubelio ini
                                    (200 item per halaman).
                                </p>

                                @error('page_tracking')
                                    <p class="mt-1 text-sm text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror

                                @error('active_job')
                                    <p class="mt-1 text-sm text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                        </div>

                        {{-- Button --}}
                        <div class="flex justify-end gap-3 pt-6">
                            <a href="{{ route('jubelio-stock-checks.index') }}"
                                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700">
                                Batal
                            </a>

                            <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4
                                       focus:ring-blue-300 font-medium rounded-lg text-sm
                                       px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700
                                       focus:outline-none dark:focus:ring-blue-800">
                                Submit
                            </button>
                        </div>

                    </div>
                </div>
            </section>
        </form>

    @endif

    @push('jsBody')
    @endpush

</x-layouts.layout>