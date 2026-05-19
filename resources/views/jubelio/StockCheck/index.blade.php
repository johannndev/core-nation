<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6 items-center">
        <p class="text-2xl font-bold">Stock Check</p>
        <div class="flex justify-end">
            <a href="{{ route('jubelio-stock-checks.create') }}"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                Buat Pengecekan Baru
            </a>
        </div>

    </div>

    {{-- Alert Error --}}
    @if (session('errorMessage'))
        <div id="alert-border-2"
            class="flex items-center p-4 mb-4 text-red-800 border-t-4 border-red-300 bg-red-50 dark:text-red-400 dark:bg-gray-800 dark:border-red-800"
            role="alert">

            <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
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

                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
    @endif

    {{-- Active Job --}}
    @if ($activeJob)
        <div
            class="flex items-center justify-between rounded-lg border border-blue-500/30 bg-blue-500/10 p-4 text-blue-700 dark:text-blue-400 mb-6">

            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 animate-pulse" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>

                <div>
                    <p class="font-bold">
                        Pengecekan Sedang Aktif (ID: {{ $activeJob->id }})
                    </p>

                    <p class="text-sm opacity-80">
                        Status:
                        <span class="uppercase">
                            {{ $activeJob->status }}
                        </span>
                        |
                        Halaman Terakhir:
                        {{ $activeJob->page_tracking }}
                    </p>
                </div>
            </div>

            <a href="{{ route('jubelio-stock-checks.show', $activeJob->id) }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium border border-blue-500 rounded-lg hover:bg-blue-500/10">
                Pantau Detail
            </a>
        </div>
    @endif

    {{-- Table --}}
    <div
        class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">

                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 uppercase text-xs">

                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Dibuat Pada</th>
                        <th class="px-6 py-4 text-center">Halaman</th>
                        <th class="px-6 py-4 text-center">Ketidakcocokan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                    @forelse ($stockChecks as $job)
                        <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-700/40">

                            {{-- ID --}}
                            <td class="px-6 py-4 font-mono font-bold">
                                #{{ $job->id }}
                            </td>

                            {{-- Created At --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($job->created_at)->translatedFormat('d M Y H:i') }}
                            </td>

                            {{-- Page Tracking --}}
                            <td class="px-6 py-4 text-center">
                                {{ $job->page_tracking }}
                            </td>

                            {{-- Discrepancies --}}
                            <td class="px-6 py-4 text-center">

                                @if ($job->discrepancies_count > 0)
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                        {{ $job->discrepancies_count }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        {{ $job->discrepancies_count }}
                                    </span>
                                @endif

                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4 text-center">

                                @php
                                    $statusColor = match ($job->status) {
                                        'completed'
                                            => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'processing'
                                            => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                        'failed' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                        default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                    };
                                @endphp

                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                                    {{ strtoupper($job->status) }}
                                </span>

                            </td>

                            {{-- Action --}}
                            <td class="px-6 py-4 text-right">

                                <div class="flex justify-end gap-2">

                                    {{-- Detail --}}
                                    <a href="{{ route('jubelio-stock-checks.show', $job->id) }}"
                                        class="inline-flex items-center justify-center w-9 h-9 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                                        title="Lihat Detail">

                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('jubelio-stock-checks.destroy', $job->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus data ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-red-500 hover:bg-red-100 dark:hover:bg-red-900/30"
                                            title="Hapus">

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>


                                        </button>
                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 italic">
                                Belum ada data pengecekan stok.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $stockChecks->links() }}
    </div>

    @push('jsBody')
    @endpush

</x-layouts.layout>
