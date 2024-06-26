<div class="vbtn-wrapper fixed bottom-0 z-10" >

    <button type="button"  id="virtualEnterKay" data-name="halo" class=" text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M20 7V8.2C20 9.88016 20 10.7202 19.673 11.362C19.3854 11.9265 18.9265 12.3854 18.362 12.673C17.7202 13 16.8802 13 15.2 13H4M4 13L8 9M4 13L8 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>

        {{-- <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
        </svg> --}}
        <span class="sr-only">Icon description</span>
    </button>

    @push('jsBody')

    <script>
        // const VBtn = document.querySelector('.vbtn-wrapper');

        // if(window.visualViewport){
        //     const vv = window.visualViewport;

        //     function fixPosition(){
        //         // VBtn.style.bottom = `${vv.height}px`
        //     }

        //     vv.addEventListener('resaize',fixPosition);
        //     fixPosition();
        // }
    </script>

    @endpush


</div>