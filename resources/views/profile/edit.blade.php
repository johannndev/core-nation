<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Account settings</p>

       
    </div>

    <div class="mb-8">
        <section class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-4 ">
            @include('profile.partials.update-profile-information-form')
        <section>
    </div>

    <div class="mb-8">
        <section class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-4 ">
            @include('profile.partials.update-password-form')
        <section>
    </div>

    <div class="mb-8">
        <section class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-4 ">
            @include('profile.partials.delete-user-form')
        <section>
    </div>


</x-layouts.layout>