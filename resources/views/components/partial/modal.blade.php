@props([
    'id' => 'modal',
    'title' => '',
    'maxWidth' => '2xl'
])

<div
    x-data="{ open: false }"
    x-show="open"
    x-cloak
    x-on:show-{{ $id }}.window="open = true"
    x-on:hide-{{ $id }}.window="open = false"
    id="{{ $id }}-wrapper"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
>
    <div
        @click.away="open = false"
        class="bg-white rounded-xl shadow-lg h-full w-full  p-6"
    >
        <div class="flex justify-between items-center border-b pb-2 mb-4">
            <h2 class="text-xl font-semibold">{{ $title }}</h2>
            <button onclick="hideModal('{{ $id }}')" class="text-gray-500 hover:text-gray-800 text-xl">&times;</button>
        </div>

        <div>
            {{ $slot }}
        </div>
    </div>
</div>