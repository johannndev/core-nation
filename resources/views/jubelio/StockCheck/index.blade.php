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

                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.828 10.172a4 4 0 010 5.656m-5.656 0a4 4 0 010-5.656m1.414-1.414l4.242-4.242m0 0H21m-3.172 0v3.172" />
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

                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0h8m-1-2a1 1 0 00-1-1h-4a1 1 0 00-1 1l-1 2h8l-1-2z" />
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
