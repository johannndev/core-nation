<div id="modalManual" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 bottom-0 z-50 hidden h-full w-full overflow-y-auto overflow-x-hidden  md:inset-0">
    <div class="relative h-full w-full ">
        <!-- Modal content -->
        <div class="relative rounded-lg bg-white shadow dark:bg-gray-700 h-full w-full flex justify-center">

            <div class="w-full md:w-1/3 ">
                <!-- Modal header -->
                <div
                    class="flex items-start justify-between rounded-t border-b p-5 dark:border-gray-600"
                    >
                    <h3
                        class="text-xl font-semibold text-gray-900 dark:text-white "
                    >
                        Scan barcode
                    </h3>
                    <button
                        id="closeModalButton"
                        type="button"
                        class="ms-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white"
                    >
                        <svg
                            class="h-3 w-3"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 14 14"
                        >
                            <path
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"
                            />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="space-y-6 p-6">
                    
                    <p class="text-center mb-4">Scan barcode kamu di sini</p>

                    <div id="alert-scan" class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                        
                    </div>

                    <div id="camera" style="width:100%; height:200px"></div>


                    <div class="flex justify-center mt-6 ">

                        <button
                            type="button"
                            id="closeModalButton"
                            class="rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        >
                            Tutup
                        </button>

                    </div>
                    


                </div>
             

            </div>
        </div>
    </div>
</div>